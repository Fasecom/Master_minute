<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\WorkingShift;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MasterRevenueChart extends Component
{
    public User $master;

    /**
     * Год-месяц в формате YYYY-MM, выбранный в фильтре
     */
    public string $monthYear;

    /** @var array<string,mixed> */
    public array $data = [];

    protected $listeners = ['monthYearUpdated' => 'onMonthYearUpdated'];

    public function mount(User $master): void
    {
        $this->master = $master;
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
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        // Текущая выручка мастера за выбранный месяц
        $currentRevenue = WorkingShift::query()
            ->where('user_id', $this->master->id)
            ->whereBetween('date', [$start, $end])
            ->select(DB::raw('COALESCE(SUM(cash_revenue + cashless_revenue),0) as sum'))
            ->value('sum');

        // Общая выручка компании за выбранный месяц
        $companyRevenue = WorkingShift::query()
            ->whereBetween('date', [$start, $end])
            ->select(DB::raw('COALESCE(SUM(cash_revenue + cashless_revenue),0) as sum'))
            ->value('sum');

        // Выручка мастера за предыдущий месяц
        $prevStart = (clone $start)->subMonth()->startOfMonth();
        $prevEnd = (clone $prevStart)->endOfMonth();

        $prevRevenue = WorkingShift::query()
            ->where('user_id', $this->master->id)
            ->whereBetween('date', [$prevStart, $prevEnd])
            ->select(DB::raw('COALESCE(SUM(cash_revenue + cashless_revenue),0) as sum'))
            ->value('sum');

        // Если выручки у мастера не было, берем среднюю выручку всех мастеров за предыдущий месяц
        if (floatval($prevRevenue) <= 0.0) {
            $prevRevenue = WorkingShift::query()
                ->fromSub(
                    WorkingShift::query()
                        ->select('user_id', DB::raw('SUM(cash_revenue + cashless_revenue) as rev'))
                        ->whereBetween('date', [$prevStart, $prevEnd])
                        ->groupBy('user_id'),
                    't'
                )
                ->select(DB::raw('AVG(rev) as avg_rev'))
                ->value('avg_rev');
        }

        $prevRevenue = floatval($prevRevenue);
        $currentRevenue = floatval($currentRevenue);
        $companyRevenue = floatval($companyRevenue);

        // Процент заполнения относительно прошлой выручки
        $percent = $prevRevenue > 0 ? ($currentRevenue / $prevRevenue) * 100.0 : 0.0;

        // Минимальная и максимальная выручка мастера за день в выбранном месяце
        $dailySums = WorkingShift::query()
            ->select(DB::raw('SUM(cash_revenue + cashless_revenue) as rev'))
            ->where('user_id', $this->master->id)
            ->whereBetween('date', [$start, $end])
            ->groupBy(DB::raw('DATE(date)'))
            ->pluck('rev');

        // Минимальная ненулевая выручка за день (ноль, если выручки не было вовсе)
        $nonZeroDaily = $dailySums->filter(fn ($rev) => floatval($rev) > 0);
        $minDay = $nonZeroDaily->min() ?? 0;
        $maxDay = $dailySums->max() ?? 0;

        // Процент доли мастера от общей выручки
        $companyShare = $companyRevenue > 0 ? ($currentRevenue / $companyRevenue) * 100.0 : 0.0;

        $this->data = [
            'currentRevenue'  => $currentRevenue,
            'prevRevenue'     => $prevRevenue,
            'companyRevenue'  => $companyRevenue,
            'percentFill'     => round($percent, 1),
            'prevPercent'     => round($percent, 1),
            'overflow'        => $percent > 100,
            'companyShare'    => round($companyShare, 1),
            'minDay'          => $minDay,
            'maxDay'          => $maxDay,
        ];
    }

    public function render()
    {
        return view('livewire.charts.master-revenue-chart');
    }
} 