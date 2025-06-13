<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class MastersFilter extends Component
{
    public array $selectedMasters = [];

    public $masters;
    public bool $open = false;

    public function mount(): void
    {
        $this->selectedMasters = session('schedule.selectedMasters', []);
        $this->masters = User::where('role_id', 3)
            ->whereNull('work_end_date')
            ->get(['id', 'full_name'])
            ->map(function ($master) {
                $parts = explode(' ', $master->full_name);
                $short = $master->full_name;
                if (count($parts) >= 3) {
                    $short = $parts[0].' '.mb_substr($parts[1],0,1).'. '.mb_substr($parts[2],0,1).'.';
                }
                return (object)['id'=>$master->id,'name'=>$short];
            })->values();
    }

    public function updatedSelectedMasters($value): void
    {
        session(['schedule.selectedMasters' => $this->selectedMasters]);
        $this->dispatch('mastersUpdated', $this->selectedMasters);
    }

    public function toggleOpen(): void { $this->open = !$this->open; }
    public function close(): void { $this->open = false; }

    public function toggleMaster($id): void
    {
        $id = (int)$id;
        if (($key = array_search($id, $this->selectedMasters)) !== false) {
            unset($this->selectedMasters[$key]);
        } else {
            $this->selectedMasters[] = $id;
        }
        $this->selectedMasters = array_values($this->selectedMasters);
        session(['schedule.selectedMasters' => $this->selectedMasters]);
        $this->dispatch('mastersUpdated', $this->selectedMasters);
    }

    public function resetMasters(): void
    {
        $this->selectedMasters = [];
        session(['schedule.selectedMasters' => $this->selectedMasters]);
        $this->dispatch('mastersUpdated', $this->selectedMasters);
    }

    public function render()
    {
        return view('livewire.filters.masters', [
            'options' => $this->masters,
            'selected' => $this->selectedMasters,
        ]);
    }
}
