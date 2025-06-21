<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\WorkingShift;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class MasterYearRevenueChart extends Component
{
    public User $master;
    public string $monthYear;
    public array $chartData = [];
    public string $chartId;

    protected $listeners = ['monthYearUpdated' => 'onMonthYearUpdated'];

    public function mount(User $master): void
    {
        $this->master = $master;
        $this->monthYear = session('schedule.monthYear', now()->format('Y-m'));
        $this->chartId = 'chart_'.Str::uuid();
        $this->generateData();
    }

    public function onMonthYearUpdated(string $monthYear): void
    {
        $this->monthYear = $monthYear;
        $this->generateData();
    }

    private function generateData(): void
    {
        [$year,$month] = array_map('intval', explode('-', $this->monthYear));
        $data = [];

        // Берём 12 месяцев, заканчивая выбранным monthYear (включительно)
        $current = Carbon::create($year, $month, 1)->startOfMonth();
        for ($i = 11; $i >= 0; $i--) {
            $start = (clone $current)->subMonths($i)->startOfMonth();
            $end   = (clone $start)->endOfMonth();

            $sum = WorkingShift::query()
                ->where('user_id', $this->master->id)
                ->whereBetween('date', [$start, $end])
                ->select(DB::raw('COALESCE(SUM(cash_revenue + cashless_revenue),0) as sum'))
                ->value('sum');

            $data[] = [
                'date'  => $start->timestamp * 1000, // JS expects ms timestamp
                'value' => floatval($sum),
            ];
        }

        $this->chartData = $data;
    }

    public function render()
    {
        return view('livewire.charts.master-year-revenue-chart');
    }
} 