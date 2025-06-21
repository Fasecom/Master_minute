<?php

namespace App\Livewire;

use App\Models\WorkingShift;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class MasterMonthlyRevenueChangeChart extends Component
{
    public string $monthYear;

    /** @var array<int,array{category:string,value:float,percent:float}> */
    public array $chartData = [];

    public string $chartId;

    protected $listeners = ['monthYearUpdated' => 'onMonthYearUpdated'];

    public function mount(): void
    {
        $this->monthYear = session('schedule.monthYear', now()->format('Y-m'));
        $this->chartId = 'chart_' . Str::uuid();
        $this->generateData();
    }

    public function onMonthYearUpdated(string $monthYear): void
    {
        $this->monthYear = $monthYear;
        $this->generateData();
        $this->dispatch('updateChart', chartId: $this->chartId, data: $this->chartData);
    }

    private function generateData(): void
    {
        [$year, $month] = array_map('intval', explode('-', $this->monthYear));
        $currentStart = Carbon::create($year, $month, 1)->startOfMonth();
        $currentEnd   = (clone $currentStart)->endOfMonth();

        $prevStart = (clone $currentStart)->subMonth()->startOfMonth();
        $prevEnd   = (clone $prevStart)->endOfMonth();

        // Суммы за текущий месяц
        $currentRevenues = WorkingShift::query()
            ->join('users','working_shifts.user_id','=','users.id')
            ->where('users.role_id',3)
            ->whereBetween('date',[$currentStart,$currentEnd])
            ->groupBy('users.id','users.full_name')
            ->select('users.id','users.full_name', DB::raw('SUM(cash_revenue + cashless_revenue) as sum'))
            ->pluck('sum','id');

        // Суммы за предыдущий месяц
        $prevRevenues = WorkingShift::query()
            ->join('users','working_shifts.user_id','=','users.id')
            ->where('users.role_id',3)
            ->whereBetween('date',[$prevStart,$prevEnd])
            ->groupBy('users.id','users.full_name')
            ->select('users.id','users.full_name', DB::raw('SUM(cash_revenue + cashless_revenue) as sum'))
            ->pluck('sum','id');

        // Получаем список всех мастеров участвовавших в обеих месяцах
        $masterIds = $currentRevenues->keys()->merge($prevRevenues->keys())->unique();

        // Получаем имена одним запросом для отображения на графике
        $names = User::whereIn('id',$masterIds)->pluck('full_name','id');

        $data = [];
        foreach($masterIds as $id){
            $current = floatval($currentRevenues[$id] ?? 0);
            $prev    = floatval($prevRevenues[$id] ?? 0);
            $diff    = $current - $prev;
            $percent = $prev > 0 ? ($diff / $prev) * 100.0 : 0.0;
            $data[] = [
                'category' => $this->formatShortName($names[$id] ?? ('#'.$id)),
                'value'    => $diff,
                'percent'  => round($percent,1),
            ];
        }

        // Сортируем по убыванию разницы
        usort($data, fn($a,$b)=>$b['value'] <=> $a['value']);

        $this->chartData = $data;
    }

    public function render()
    {
        return view('livewire.charts.master-monthly-revenue-change-chart');
    }

    private function formatShortName(string $fullName): string
    {
        $parts = preg_split('/\s+/', trim($fullName));
        $last = $parts[0] ?? '';
        $firstInitial = isset($parts[1]) ? mb_substr($parts[1], 0, 1) . '.' : '';
        $middleInitial = isset($parts[2]) ? mb_substr($parts[2], 0, 1) . '.' : '';
        return trim(sprintf('%s %s %s', $last, $firstInitial, $middleInitial));
    }
} 