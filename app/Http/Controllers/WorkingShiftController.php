<?php

namespace App\Http\Controllers;

use App\Models\WorkingShift;
use App\Models\Workshop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkingShiftController extends Controller
{
    private function formatFullName($fullName)
    {
        $parts = explode(' ', $fullName);
        if (count($parts) >= 3) {
            return $parts[0] . ' ' . mb_substr($parts[1], 0, 1) . '. ' . mb_substr($parts[2], 0, 1) . '.';
        }
        return $fullName;
    }

    public function index(Request $request)
    {
        $monthYear = $request->input('month_year', '2025-06');
        $startDate = Carbon::parse($monthYear)->startOfMonth();
        $endDate = Carbon::parse($monthYear)->endOfMonth();
        
        // Получаем и валидируем параметры фильтрации
        $selectedMasters = $request->input('masters', []);
        $selectedMasters = is_array($selectedMasters) ? $selectedMasters : [];
        
        $selectedShops = $request->input('shops', []);
        $selectedShops = is_array($selectedShops) ? $selectedShops : [];
        
        // Пагинация для торговых точек
        $workshopsPerPage = 7;
        
        // Получаем список мастеров и мастерских для фильтров
        $masters = User::where('role_id', 3)
            ->whereNull('work_end_date')
            ->get(['id', 'full_name'])
            ->map(function($master) {
                $master->name = $this->formatFullName($master->full_name);
                return $master;
            });
            
        $shops = Workshop::whereNull('close_date')
            ->get(['id', 'name']);
            
        // Применяем фильтры к мастерским
        $workshopsQuery = Workshop::whereNull('close_date');
        
        // Если выбраны конкретные мастерские
        if (!empty($selectedShops)) {
            $workshopsQuery->whereIn('id', $selectedShops);
        }
        
        // Если выбраны конкретные мастера, показываем только мастерские, где они работают
        if (!empty($selectedMasters)) {
            $workshopsQuery->whereIn('id', function($query) use ($selectedMasters, $startDate, $endDate) {
                $query->select('workshop_id')
                    ->from('working_shifts')
                    ->whereIn('user_id', $selectedMasters)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->distinct();
            });
        }
        
        // Получаем общее количество отфильтрованных мастерских
        $totalWorkshops = $workshopsQuery->count();
        $totalPages = max(1, ceil($totalWorkshops / $workshopsPerPage));
        $currentPage = min($request->input('page', 1), $totalPages);
        $offset = ($currentPage - 1) * $workshopsPerPage;
        
        // Получаем отфильтрованные мастерские с пагинацией
        $workshops = $workshopsQuery->skip($offset)->take($workshopsPerPage)->get();
        
        // Получаем ID всех отображаемых мастерских
        $displayedWorkshopIds = $workshops->pluck('id')->toArray();
        
        // Применяем фильтры к сменам
        $shiftsQuery = WorkingShift::with(['user', 'workshop'])
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('workshop_id', $displayedWorkshopIds);
            
        if (!empty($selectedMasters)) {
            $shiftsQuery->whereIn('user_id', $selectedMasters);
        }
        
        $shifts = $shiftsQuery->get()
            ->map(function ($shift) {
                $shift->user->formatted_name = $this->formatFullName($shift->user->full_name);
                return $shift;
            })
            ->groupBy('workshop_id');

        // Формируем массив дней месяца
        $days = [];
        $currentDate = $startDate->copy();
        $weekDays = ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'];
        
        while ($currentDate <= $endDate) {
            $days[] = [
                'day' => $currentDate->day,
                'short' => $weekDays[$currentDate->dayOfWeek],
                'isWeekend' => $currentDate->isWeekend(),
                'date' => $currentDate->format('Y-m-d')
            ];
            $currentDate->addDay();
        }

        return view('schedule.index', compact(
            'workshops', 
            'shifts', 
            'monthYear', 
            'days', 
            'currentPage', 
            'totalPages',
            'masters',
            'shops',
            'selectedMasters',
            'selectedShops'
        ));
    }

    public function addRevenueForm()
    {
        $masters = User::where('role_id', 3)
            ->whereNull('work_end_date')
            ->get(['id', 'full_name']);
        return view('schedule.add', compact('masters'));
    }

    public function storeRevenue(Request $request)
    {
        $user = auth()->user();
        $isMaster = $user->role_id == 3;
        $validated = $request->validate([
            'date' => 'required|date',
            'cash_revenue' => 'required|numeric|min:0',
            'cashless_revenue' => 'required|numeric|min:0',
            'user_id' => $isMaster ? '' : 'required|exists:users,id',
        ]);
        $userId = $isMaster ? $user->id : $request->input('user_id');
        $date = $request->input('date');
        $shift = WorkingShift::where('user_id', $userId)
            ->whereDate('date', $date)
            ->first();
        if (!$shift) {
            return back()->withErrors(['date' => 'Смена не найдена для выбранного мастера и даты.'])->withInput();
        }
        $shift->cash_revenue = $request->input('cash_revenue');
        $shift->cashless_revenue = $request->input('cashless_revenue');
        $shift->save();
        return redirect()->route('schedule')->with('success', 'Выручка успешно внесена!');
    }
} 