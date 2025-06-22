<?php

namespace App\Livewire;

use App\Models\Workshop;
use App\Models\WorkingShift;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class CompanyShopRatingChart extends Component
{
    /** @var string 'month'|'year' */
    public string $mode = 'month';
    public string $monthYear;
    public array $chartData = [];
    public string $chartId;

    protected $listeners = ['monthYearUpdated' => 'onMonthYearUpdated'];

    public function mount(string $mode = 'month'): void
    {
        $this->mode = in_array($mode, ['year', 'month']) ? $mode : 'month';
        $this->monthYear = session('schedule.monthYear', now()->format('Y-m'));
        $this->chartId = 'chart_'.Str::uuid();
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
        [$year,$month] = array_map('intval', explode('-', $this->monthYear));

        if($this->mode === 'year') {
            $start = Carbon::create($year,1,1)->startOfYear();
            $end   = (clone $start)->endOfYear();
        } else {
            $start = Carbon::create($year,$month,1)->startOfMonth();
            $end   = (clone $start)->endOfMonth();
        }

        $shopRevenues = WorkingShift::query()
            ->join('workshops','working_shifts.workshop_id','=','workshops.id')
            ->whereBetween('date', [$start, $end])
            ->groupBy('workshops.id','workshops.name')
            ->select('workshops.name as shop_name', DB::raw('SUM(cash_revenue + cashless_revenue) as sum'))
            ->orderByDesc('sum')
            ->get();

        $data = $shopRevenues->map(function($row){
            return [
                'category' => $row->shop_name,
                'value'    => floatval($row->sum),
            ];
        })->toArray();

        $this->chartData = $data;
    }

    public function render()
    {
        return view('livewire.charts.company-shop-rating-chart', ['unit' => '₽']);
    }
} 