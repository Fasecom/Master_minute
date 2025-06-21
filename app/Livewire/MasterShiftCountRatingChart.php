<?php

namespace App\Livewire;

use App\Models\WorkingShift;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class MasterShiftCountRatingChart extends Component
{
    public string $monthYear;

    /** @var array<int,array{category:string,value:int}> */
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
        [$year] = array_map('intval', explode('-', $this->monthYear));
        $start = Carbon::create($year, 1, 1)->startOfYear();
        $end = (clone $start)->endOfYear();

        $shiftCounts = WorkingShift::query()
            ->join('users', 'working_shifts.user_id', '=', 'users.id')
            ->where('users.role_id', 3)
            ->whereBetween('date', [$start, $end])
            ->groupBy('users.id', 'users.full_name')
            ->select('users.full_name as master_name', DB::raw('COUNT(*) as cnt'))
            ->orderByDesc('cnt')
            ->get();

        $this->chartData = $shiftCounts->map(function ($row) {
            return [
                'category' => $this->formatShortName($row->master_name),
                'value'    => intval($row->cnt),
            ];
        })->toArray();
    }

    private function formatShortName(string $fullName): string
    {
        $parts = preg_split('/\s+/', trim($fullName));
        $last = $parts[0] ?? '';
        $firstInitial = isset($parts[1]) ? mb_substr($parts[1],0,1).'.' : '';
        $middleInitial = isset($parts[2]) ? mb_substr($parts[2],0,1).'.' : '';
        return trim(sprintf('%s %s %s', $last, $firstInitial, $middleInitial));
    }

    public function render()
    {
        return view('livewire.charts.company-shop-rating-chart');
    }
} 