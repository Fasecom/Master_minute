<div id="masters-panel" class="shift-column sticky top-4 pt-0" style="width: 100%;">
    <div class="sc-header flex items-center gap-2 pr-2" style="padding-top:0; flex-direction: row; height: 40px; align-items: stretch;">
        <span class="name text-xl" style="font-size: 25px;">Мастера</span>
        @php $totalPages = max(1, ceil($masters->count() / $mastersPerPage)); @endphp
        @if($totalPages > 1)
            <div class="flex items-center"> 
                <button type="button" wire:click="goPrevPage" class="flipper-arrow" style="width: 20px; height: 20px;" {{ $currentPage <= 1 ? 'disabled' : '' }}>
                    <img src="/img/icon/angle-left.svg" alt="Prev" width="18" height="18">
                </button>
                <span class="flipper-number text-center" style="font-size: 28px; width: 20px;">{{ $currentPage }}</span>
                <button type="button" wire:click="goNextPage" class="flipper-arrow" style="width: 20px; height: 20px;" {{ $currentPage >= $totalPages ? 'disabled' : '' }}>
                    <img src="/img/icon/angle-right.svg" alt="Next" width="18" height="18">
                </button>
            </div>
        @endif
    </div>

    @foreach($this->pagedMasters as $master)
        @php
            $parts = explode(' ', $master->full_name);
            $fio = count($parts) >= 2 ? ($parts[0].' '.$parts[1]) : $master->full_name;
            $color = $master->color ?? '#e5e7eb';
        @endphp
        <!-- Шаблон для клонирования -->
        <template id="master-card-template-{{ $master->id }}">
            <div class="shifts-card cursor-move w-full h-[34px] flex items-center pl-3 pr-8 relative" draggable="true" data-user-id="{{ $master->id }}" style="background-color: {{ $color }};">
                <div class="master-name text-left w-full truncate">{{ $fio }}</div>
            </div>
        </template>
        <!-- Карточка -->
        <div class="shifts flex items-center relative" style="height:34px;">
            <div class="shifts-card cursor-move w-full h-full flex items-center pl-3 pr-8 relative" draggable="true" data-user-id="{{ $master->id }}" style="background-color: {{ $color }};">
                <div class="master-name text-left w-full truncate">{{ $fio }}</div>
                <!-- Инпут цвета -->
                <input type="color" class="absolute right-1 bottom-1 w-5 h-5 opacity-0 cursor-pointer" wire:change="updateColor({{ $master->id }}, $event.target.value)" value="{{ $color }}">
                <img src="/img/icon/paint-brush.svg" alt="color" class="absolute right-1 bottom-1 w-5 h-5 pointer-events-none">
            </div>
        </div>
    @endforeach
</div> 