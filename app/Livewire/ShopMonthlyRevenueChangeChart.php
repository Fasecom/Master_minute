<?php

namespace App\Livewire;

use App\Models\Workshop;
use App\Models\WorkingShift;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class ShopMonthlyRevenueChangeChart extends Component
{
    public string $monthYear;

    /** @var array<int,array{category:string,value:float,percent:float}> */
    public array $chartData = [];

    public string $chartId;

    protected $listeners = ['monthYearUpdated' => 'onMonthYearUpdated'];

    public function mount(): void
    {
        $this->monthYear = session('schedule.monthYear', now()->format('Y-m'));
        $this->chartId   = 'chart_' . Str::uuid();
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

        // Суммы за текущий месяц по торговым точкам
        $currentRevenues = WorkingShift::query()
            ->join('workshops', 'working_shifts.workshop_id', '=', 'workshops.id')
            ->whereBetween('date', [$currentStart, $currentEnd])
            ->groupBy('workshops.id', 'workshops.name')
            ->select('workshops.id as id', 'workshops.name', DB::raw('SUM(cash_revenue + cashless_revenue) as sum'))
            ->pluck('sum', 'id');

        // Суммы за предыдущий месяц по торговым точкам
        $prevRevenues = WorkingShift::query()
            ->join('workshops', 'working_shifts.workshop_id', '=', 'workshops.id')
            ->whereBetween('date', [$prevStart, $prevEnd])
            ->groupBy('workshops.id', 'workshops.name')
            ->select('workshops.id as id', 'workshops.name', DB::raw('SUM(cash_revenue + cashless_revenue) as sum'))
            ->pluck('sum', 'id');

        // объединённый список id торговых точек
        $shopIds = $currentRevenues->keys()->merge($prevRevenues->keys())->unique();

        // названия для категорий
        $names = Workshop::whereIn('id', $shopIds)->pluck('name', 'id');

        $data = [];
        foreach ($shopIds as $id) {
            $current = floatval($currentRevenues[$id] ?? 0);
            $prev    = floatval($prevRevenues[$id] ?? 0);
            $diff    = $current - $prev;
            $percent = $prev > 0 ? ($diff / $prev) * 100.0 : 0.0;
            $data[] = [
                'category' => $names[$id] ?? ('#' . $id),
                'value'    => $diff,
                'percent'  => round($percent, 1),
            ];
        }

        usort($data, fn($a, $b) => $b['value'] <=> $a['value']);

        $this->chartData = $data;
    }

    public function render()
    {
        // Используем тот же шаблон, что и для мастеров (график с положительными/отрицательными столбцами)
        return view('livewire.charts.shop-monthly-revenue-change-chart');
    }
} 