<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class MastersPanel extends Component
{
    public $masters;
    public $currentPage = 1;
    public int $mastersPerPage = 26;

    // Несохранённые изменения цвета – [userId=>color]
    public array $colorChanges = [];

    protected $listeners = [
        'saveShifts' => 'resetColorChanges',
    ];

    public function mount(): void
    {
        $this->loadMasters();
        $this->colorChanges = session('schedule.edit.masterColors', []);
        $this->currentPage = session('schedule.edit.mastersPage', 1);
    }

    private function loadMasters(): void
    {
        $this->masters = User::where('role_id', 3)
            ->whereNull('work_end_date')
            ->orderBy('id')
            ->get();

        // Назначаем временный цвет для тех, у кого color не задан
        $colors = config('master_colors');
        if($colors){
            $this->masters = $this->masters->values(); // переиндексируем
            foreach($this->masters as $idx => $m){
                if(!$m->color){
                    $m->color = $colors[$idx % count($colors)];
                }
            }
        }
    }

    public function getPagedMastersProperty()
    {
        $chunks = $this->masters->chunk($this->mastersPerPage);
        return $chunks[$this->currentPage - 1] ?? collect();
    }

    public function goPrevPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            session(['schedule.edit.mastersPage' => $this->currentPage]);
        }
    }

    public function goNextPage(): void
    {
        $totalPages = max(1, (int)ceil($this->masters->count() / $this->mastersPerPage));
        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
            session(['schedule.edit.mastersPage' => $this->currentPage]);
        }
    }

    public function updateColor(int $userId, string $color): void
    {
        // Сохраняем локально, но не сохраняем в БД – это сделает ScheduleTableEdit::saveShifts()
        $this->colorChanges[$userId] = $color;
        session(['schedule.edit.masterColors' => $this->colorChanges]);

        // Обновляем мастеров коллекцию для немедленного отображения
        $this->masters = $this->masters->map(function ($m) use ($userId, $color) {
            if ($m->id == $userId) { $m->color = $color; }
            return $m;
        });

        // Обновить карточки на фронте (browser event)
        $this->dispatch('masterColorChanged', userId: $userId, color: $color);
    }

    public function resetColorChanges(): void
    {
        // После сохранения графика сбрасываем локальные изменения цвета
        $this->colorChanges = [];
        session()->forget(['schedule.edit.masterColors']);
    }

    public function render()
    {
        return view('livewire.masters-panel');
    }
} 