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
        $shift = $master?->workingShifts()->orderByDesc('date')->first();
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
    Route::view('/shops', 'shops.index')->name('shops');
    Route::view('/schedule', 'schedule.index')->name('schedule');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/masters/skills/edit', [\App\Http\Controllers\MasterController::class, 'skillsEdit'])->name('masters.skills.edit');
    Route::post('/masters/skills/edit', [\App\Http\Controllers\MasterController::class, 'skillsUpdate'])->name('masters.skills.update');
});

require __DIR__.'/auth.php';
