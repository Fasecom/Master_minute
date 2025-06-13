@php
    $placeholder = 'Мастер...';
    $display = $placeholder;
    if(count($selected)){
        $first = collect($options)->firstWhere('id',(int)$selected[0]);
        if($first){ $display = $first->name.(count($selected)>1 ? ' ...' : ''); }
    }
@endphp
<div class="relative">
    <button type="button" class="input-header flex items-center gap-2 w-[300px] pl-4 pr-3 py-2 relative" wire:click="toggleOpen">
        <span>{{ $display }}</span>
        <svg class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>
    @if($open)
    <div class="absolute z-50 mt-2 bg-white rounded-md shadow-lg p-4 w-[300px] max-h-60 overflow-y-auto" wire:click.away="close">
        @foreach($options as $option)
            <label class="flex items-center gap-2 py-1 cursor-pointer hover:bg-gray-50">
                <input type="checkbox" value="{{ $option->id }}" @if(in_array($option->id, $selected)) checked @endif wire:click="toggleMaster({{ $option->id }})" class="rounded border-gray-300">
                <span class="select-none">{{ $option->name }}</span>
            </label>
        @endforeach
        <button type="button" class="mt-2 btn-leval4 btn-red" style="max-width: 250px; width: 100%;" wire:click="resetMasters">Сбросить</button>
    </div>
    @endif
</div> 