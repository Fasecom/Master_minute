<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WorkingShift;
use App\Models\Workshop;
use App\Models\User;
use Carbon\Carbon;

class ScheduleTable extends Component
{
    public string $monthYear;

    public array $selectedMasters = [];
    public array $selectedShops = [];

    public int $currentPage = 1;

    // Списки для фильтров
    public $masters;
    public $shops;

    private int $workshopsPerPage = 7;

    protected $listeners = [
        'monthYearUpdated' => 'onMonthYear',
        'mastersUpdated' => 'onMasters',
        'shopsUpdated' => 'onShops',
    ];

    public function mount(): void
    {
        // Берём из сессии (для перезагрузки F5)
        $this->monthYear = session('schedule.monthYear', now()->format('Y-m'));
        $this->selectedMasters = session('schedule.selectedMasters', []);
        $this->selectedShops = session('schedule.selectedShops', []);
        $this->currentPage = session('schedule.currentPage', 1);

        $this->loadFilterLists();
    }

    public function updated($propertyName): void
    {
        // Когда изменяется элемент массива (например selectedMasters.0) – сохраняем весь массив
        $baseProperty = explode('.', $propertyName)[0];
        if (property_exists($this, $baseProperty)) {
            session(['schedule.' . $baseProperty => $this->{$baseProperty}]);
        }
    }

    private function loadFilterLists(): void
    {
        $this->masters = User::where('role_id', 3)
            ->whereNull('work_end_date')
            ->get(['id', 'full_name'])
            ->map(function ($master) {
                $master->name = $this->formatFullName($master->full_name);
                return $master;
            });

        $this->shops = Workshop::whereNull('close_date')
            ->get(['id', 'name']);
    }

    private function formatFullName(string $fullName): string
    {
        $parts = explode(' ', $fullName);
        if (count($parts) >= 3) {
            return $parts[0] . ' ' . mb_substr($parts[1], 0, 1) . '. ' . mb_substr($parts[2], 0, 1) . '.';
        }
        return $fullName;
    }

    public function onMonthYear(string $monthYear): void
    {
        $this->monthYear = $monthYear;
        $this->currentPage = 1;
    }

    public function onMasters(array $masterIds): void
    {
        $this->selectedMasters = $masterIds;
        $this->currentPage = 1;
    }

    public function onShops(array $shopIds): void
    {
        $this->selectedShops = $shopIds;
        $this->currentPage = 1;
    }

    public function goPrevPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function goNextPage(): void
    {
        if ($this->currentPage < $this->getTotalPages()) {
            $this->currentPage++;
        }
    }

    private function getTotalPages(): int
    {
        [$totalWorkshops] = $this->calculateWorkshops();
        return max(1, (int)ceil($totalWorkshops / $this->workshopsPerPage));
    }

    private function calculateWorkshops(): array
    {
        // Период месяца
        $startDate = Carbon::parse($this->monthYear)->startOfMonth();
        $endDate = Carbon::parse($this->monthYear)->endOfMonth();

        // Фильтр мастерских
        $workshopsQuery = Workshop::whereNull('close_date');

        if (!empty($this->selectedShops)) {
            $workshopsQuery->whereIn('id', $this->selectedShops);
        }

        if (!empty($this->selectedMasters)) {
            $workshopsQuery->whereIn('id', function ($query) use ($startDate, $endDate) {
                $query->select('workshop_id')
                    ->from('working_shifts')
                    ->whereIn('user_id', $this->selectedMasters)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->distinct();
            });
        }

        $totalWorkshops = $workshopsQuery->count();
        $offset = ($this->currentPage - 1) * $this->workshopsPerPage;

        $workshops = $workshopsQuery->skip($offset)->take($this->workshopsPerPage)->get();

        return [$totalWorkshops, $workshops];
    }

    private function getShifts($displayedWorkshopIds, Carbon $startDate, Carbon $endDate)
    {
        $shiftsQuery = WorkingShift::with(['user', 'workshop'])
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('workshop_id', $displayedWorkshopIds);

        if (!empty($this->selectedMasters)) {
            $shiftsQuery->whereIn('user_id', $this->selectedMasters);
        }

        return $shiftsQuery->get()
            ->map(function ($shift) {
                $shift->user->formatted_name = $this->formatFullName($shift->user->full_name);
                return $shift;
            })
            ->groupBy('workshop_id');
    }

    private function getDays(Carbon $startDate, Carbon $endDate): array
    {
        $days = [];
        $currentDate = $startDate->copy();
        $weekDays = ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'];

        while ($currentDate <= $endDate) {
            $days[] = [
                'day' => $currentDate->day,
                'short' => $weekDays[$currentDate->dayOfWeek],
                'isWeekend' => $currentDate->isWeekend(),
                'date' => $currentDate->format('Y-m-d'),
            ];
            $currentDate->addDay();
        }

        return $days;
    }

    public function render()
    {
        $startDate = Carbon::parse($this->monthYear)->startOfMonth();
        $endDate = Carbon::parse($this->monthYear)->endOfMonth();

        [$totalWorkshops, $workshops] = $this->calculateWorkshops();
        $totalPages = max(1, (int)ceil($totalWorkshops / $this->workshopsPerPage));
        $this->currentPage = min($this->currentPage, $totalPages);

        $displayedWorkshopIds = $workshops->pluck('id')->toArray();
        $shifts = $this->getShifts($displayedWorkshopIds, $startDate, $endDate);

        $days = $this->getDays($startDate, $endDate);

        return view('livewire.schedule-table', [
            'workshops' => $workshops,
            'shifts' => $shifts,
            'days' => $days,
            'totalPages' => $totalPages,
            'currentPage' => $this->currentPage,
            'monthYear' => $this->monthYear,
            'selectedMasters' => $this->selectedMasters,
            'selectedShops' => $this->selectedShops,
        ]);
    }
}
