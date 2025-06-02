<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\WorkingShift;
use App\Models\Workshop;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('schedule');
});

Route::middleware('auth')->group(function () {
    Route::get('/masters', function () {
        $masters = User::where('role_id', 3)
            ->whereNull('work_end_date')
            ->get();
        return view('masters.index', compact('masters'));
    })->name('masters');
    Route::get('/masters/info/{id}', function ($id) {
        $master = User::find($id);
        $shift = $master?->workingShifts()->whereDate('date', date('Y-m-d'))->first();
        $workshop = $shift?->workshop;
        $workStart = $master?->work_start_date;
        $experience = '-';
        if ($workStart) {
            $start = \Carbon\Carbon::parse($workStart);
            $now = \Carbon\Carbon::now();
            $years = $now->diffInYears($start);
            $months = $now->copy()->subYears($years)->diffInMonths($start);
            $days = $now->diffInDays($start);
            if ($years === 0 && $months === 0 && $days > 0) {
                $experience = 'Меньше месяца';
            } elseif ($years === 0 && $months === 0 && $days === 0) {
                $experience = 'Нет стажа';
            } else {
                $experience = trim(
                    ($years ? $years.' '.trans_choice('год|года|лет', $years) : '').
                    ($years && $months ? ', ' : '').
                    ($months ? $months.' '.trans_choice('месяц|месяца|месяцев', $months) : '')
                );
            }
        } else {
            $experience = 'Нет стажа';
        }
        $workStartDate = $workStart ? \Carbon\Carbon::parse($workStart)->format('d.m.Y') : '-';
        $skills = $master ? $master->skills()->pluck('name') : collect();
        return view('masters.info', compact('master', 'workshop', 'experience', 'workStartDate', 'skills'));
    })->name('masters.info');
    Route::get('/masters/add', [\App\Http\Controllers\MasterController::class, 'create'])->name('masters.add');
    Route::post('/masters/add', [\App\Http\Controllers\MasterController::class, 'store'])->name('masters.store');
    Route::get('/masters/edit/{id}', [\App\Http\Controllers\MasterController::class, 'edit'])->name('masters.edit');
    Route::post('/masters/edit/{id}', [\App\Http\Controllers\MasterController::class, 'update'])->name('masters.update');
    Route::post('/masters/delete/{id}', [\App\Http\Controllers\MasterController::class, 'delete'])->name('masters.delete');
    Route::get('/shops', [\App\Http\Controllers\ShopController::class, 'index'])->name('shops');
    Route::get('/shops/add', [\App\Http\Controllers\ShopController::class, 'add'])->name('shops.add');
    Route::get('/shops/info/{id}', [\App\Http\Controllers\ShopController::class, 'info'])->name('shops.info');
    Route::post('/shops/add', [\App\Http\Controllers\ShopController::class, 'store'])->name('shops.store');
    Route::get('/shops/edit/{id}', [\App\Http\Controllers\ShopController::class, 'edit'])->name('shops.edit');
    Route::post('/shops/edit/{id}', [\App\Http\Controllers\ShopController::class, 'update'])->name('shops.update');
    Route::post('/shops/delete/{id}', [\App\Http\Controllers\ShopController::class, 'delete'])->name('shops.delete');
    Route::get('/shops/services/edit', [\App\Http\Controllers\ShopController::class, 'servicesEdit'])->name('shops.services.edit');
    Route::post('/shops/services/edit', [\App\Http\Controllers\ShopController::class, 'servicesUpdate'])->name('shops.services.update');
    Route::get('/schedule', function (\Illuminate\Http\Request $request) {
        $monthYear = $request->input('month_year', now()->format('Y-m'));
        $page = max(1, (int)$request->input('page', 1));
        $perPage = 5; // Количество точек на страницу

        // Получаем год и месяц
        [$year, $month] = explode('-', $monthYear);
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates = collect(range(1, $daysInMonth))->map(function($day) use ($year, $month) {
            $date = \Carbon\Carbon::create($year, $month, $day);
            return [
                'date' => $date->toDateString(),
                'day' => $day,
                'weekday' => mb_substr(__('custom.weekdays_short.'.$date->dayOfWeek), 0, 2),
                'is_weekend' => in_array($date->dayOfWeek, [0, 6]), // 0 - вс, 6 - сб
            ];
        });

        // Все точки
        $allWorkshops = \App\Models\Workshop::whereNull('close_date')->get();
        $totalPages = ceil($allWorkshops->count() / $perPage);
        $workshops = $allWorkshops->slice(($page-1)*$perPage, $perPage)->values();

        // Получаем смены за месяц для выбранных точек
        $shiftQuery = \App\Models\WorkingShift::with(['user', 'workshop'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->whereIn('workshop_id', $workshops->pluck('id'));
        $shifts = $shiftQuery->get();

        // Группируем смены по точке и дате
        $shiftsByWorkshopDate = [];
        foreach ($shifts as $shift) {
            $shiftsByWorkshopDate[$shift->workshop_id][$shift->date->format('Y-m-d')] = $shift;
        }

        // Итоги по точкам и общий итог
        $totals = [];
        $grandTotal = 0;
        foreach ($workshops as $workshop) {
            $total = $shifts->where('workshop_id', $workshop->id)->sum(function($s) {
                return $s->cash_revenue + $s->cashless_revenue;
            });
            $totals[$workshop->id] = $total;
            $grandTotal += $total;
        }

        // Для фильтров (оставляем как было)
        $mastersOptions = \App\Models\User::where('role_id', 3)
            ->whereNull('work_end_date')
            ->get(['id', 'full_name'])
            ->map(fn($m) => ['id' => $m->id, 'name' => $m->full_name])->toArray();
        $shopsOptions = \App\Models\Workshop::all(['id', 'name'])
            ->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->toArray();

        return view('schedule.index', compact(
            'mastersOptions', 'shopsOptions',
            'workshops', 'dates', 'shiftsByWorkshopDate', 'totals', 'grandTotal', 'page', 'totalPages', 'monthYear', 'year', 'month'
        ));
    })->name('schedule');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/masters/skills/edit', [\App\Http\Controllers\MasterController::class, 'skillsEdit'])->name('masters.skills.edit');
    Route::post('/masters/skills/edit', [\App\Http\Controllers\MasterController::class, 'skillsUpdate'])->name('masters.skills.update');
});

require __DIR__.'/auth.php';
