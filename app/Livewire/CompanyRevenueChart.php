<?php

namespace App\Livewire;

use App\Models\WorkingShift;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CompanyRevenueChart extends Component
{
    /** @var string 'month'|'year' */
    public string $mode = 'month';
    public string $monthYear;
    public array $data = [];

    protected $listeners = ['monthYearUpdated' => 'onMonthYearUpdated'];

    public function mount(string $mode = 'month'): void
    {
        $this->mode = in_array($mode, ['year', 'month']) ? $mode : 'month';
        $this->monthYear = session('schedule.monthYear', now()->format('Y-m'));
        $this->generateData();
    }

    public function onMonthYearUpdated(string $monthYear): void
    {
        $this->monthYear = $monthYear;
        $this->generateData();
    }

    private function generateData(): void
    {
        [$year, $month] = array_map('intval', explode('-', $this->monthYear));

        if ($this->mode === 'year') {
            // Годовая агрегированная выручка
            $start = Carbon::create($year, 1, 1)->startOfYear();
            $end   = (clone $start)->endOfYear();

            $prevStart = (clone $start)->subYear()->startOfYear();
            $prevEnd   = (clone $prevStart)->endOfYear();

            // Ежемесячные суммы в текущем году для min/max
            $monthlySums = WorkingShift::query()
                ->select(DB::raw('SUM(cash_revenue + cashless_revenue) as rev'))
                ->whereBetween('date', [$start, $end])
                ->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m")'))
                ->pluck('rev');
        } else {
            // Месячная агрегированная выручка
            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end   = (clone $start)->endOfMonth();

            $prevStart = (clone $start)->subMonth()->startOfMonth();
            $prevEnd   = (clone $prevStart)->endOfMonth();

            // Ежедневные суммы в текущем месяце для min/max
            $monthlySums = WorkingShift::query()
                ->select(DB::raw('SUM(cash_revenue + cashless_revenue) as rev'))
                ->whereBetween('date', [$start, $end])
                ->groupBy(DB::raw('DATE(date)'))
                ->pluck('rev');
        }

        $currentRevenue = WorkingShift::query()
            ->whereBetween('date', [$start, $end])
            ->select(DB::raw('COALESCE(SUM(cash_revenue + cashless_revenue),0) as sum'))
            ->value('sum');

        $prevRevenue = WorkingShift::query()
            ->whereBetween('date', [$prevStart, $prevEnd])
            ->select(DB::raw('COALESCE(SUM(cash_revenue + cashless_revenue),0) as sum'))
            ->value('sum');

        $prevRevenue = floatval($prevRevenue);
        $currentRevenue = floatval($currentRevenue);

        // процент по отношению к предыдущему периоду
        $percent = $prevRevenue > 0 ? ($currentRevenue / $prevRevenue) * 100.0 : 0.0;

        $minVal = $monthlySums->min() ?? 0;
        $maxVal = $monthlySums->max() ?? 0;

        $this->data = [
            'currentRevenue' => $currentRevenue,
            'prevRevenue'    => $prevRevenue,
            'percentFill'    => round($percent, 1),
            'prevPercent'    => round($percent, 1),
            'overflow'       => $percent > 100,
            'minDay'         => $minVal,
            'maxDay'         => $maxVal,
        ];
    }

    public function render()
    {
        // Используем тот же шаблон, что и для отдельной точки — без изменений
        return view('livewire.charts.shop-revenue-chart');
    }
} 