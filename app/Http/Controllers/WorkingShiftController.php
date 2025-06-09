<?php

namespace App\Http\Controllers;

use App\Models\WorkingShift;
use App\Models\Workshop;
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
        
        // Пагинация для торговых точек
        $workshopsPerPage = 7;
        $totalWorkshops = Workshop::count();
        $totalPages = ceil($totalWorkshops / $workshopsPerPage);
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $workshopsPerPage;

        $workshops = Workshop::skip($offset)->take($workshopsPerPage)->get();
        
        $shifts = WorkingShift::with(['user', 'workshop'])
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
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

        return view('schedule.index', compact('workshops', 'shifts', 'monthYear', 'days', 'currentPage', 'totalPages'));
    }
} 