<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Workshop;

class ShopsFilter extends Component
{
    public array $selectedShops = [];
    public $shops;
    public bool $open = false;

    public function mount(): void
    {
        $this->selectedShops = session('schedule.selectedShops', []);
        $this->shops = Workshop::whereNull('close_date')->get(['id','name']);
    }

    public function updatedSelectedShops($value): void
    {
        session(['schedule.selectedShops' => $this->selectedShops]);
        $this->dispatch('shopsUpdated', $this->selectedShops);
    }

    public function toggleOpen(): void { $this->open = !$this->open; }
    public function close(): void { $this->open = false; }

    public function toggleShop($id): void
    {
        $id = (int)$id;
        if (($key = array_search($id, $this->selectedShops)) !== false) {
            unset($this->selectedShops[$key]);
        } else {
            $this->selectedShops[] = $id;
        }
        $this->selectedShops = array_values($this->selectedShops);
        session(['schedule.selectedShops' => $this->selectedShops]);
        $this->dispatch('shopsUpdated', $this->selectedShops);
    }

    public function resetShops(): void
    {
        $this->selectedShops = [];
        session(['schedule.selectedShops' => $this->selectedShops]);
        $this->dispatch('shopsUpdated', $this->selectedShops);
    }

    public function render()
    {
        return view('livewire.filters.shops', [
            'options' => $this->shops,
            'selected' => $this->selectedShops,
        ]);
    }
}
