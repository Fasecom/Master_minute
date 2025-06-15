<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class MastersPanel extends Component
{
    public $masters;

    public function mount(): void
    {
        $this->masters = User::where('role_id', 3)
            ->whereNull('work_end_date')
            ->get();
    }

    public function render()
    {
        return view('livewire.masters-panel');
    }
} 