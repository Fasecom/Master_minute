<?php

namespace App\Livewire;

use Livewire\Component;

class MonthYearFilter extends Component
{
    public string $monthYear;
    public int $month;
    public int $year;
    public bool $open = false;

    private array $monthNames = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];

    public function mount(): void
    {
        $this->monthYear = session('schedule.monthYear', now()->format('Y-m'));
        [$y,$m] = array_map('intval', explode('-',$this->monthYear));
        $this->year = $y;
        $this->month = $m;
    }

    public function toggleOpen(): void { $this->open = !$this->open; }

    private function commit(): void
    {
        $this->monthYear = sprintf('%04d-%02d', $this->year, $this->month);
        session(['schedule.monthYear' => $this->monthYear]);
        $this->dispatch('monthYearUpdated', $this->monthYear);
    }

    public function selectMonth(int $m): void
    {
        $this->month = $m;
        $this->commit();
    }

    public function prevYear(): void
    {
        $this->year--;
        $this->commit();
    }

    public function nextYear(): void
    {
        $this->year++;
        $this->commit();
    }

    public function close(): void
    {
        $this->open = false;
        $this->commit();
    }

    public function getDisplayProperty(): string
    {
        return $this->monthNames[$this->month-1].' '.$this->year;
    }

    public function render()
    {
        return view('livewire.filters.month-year');
    }
}
