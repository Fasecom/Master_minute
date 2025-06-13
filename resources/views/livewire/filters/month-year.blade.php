@php $monthNames = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь']; @endphp
<div class="relative">
    <button type="button" wire:click="toggleOpen" class="input-header flex items-center gap-2 w-[240px] pl-4 pr-10 py-2 relative">
        <img src="/img/icon/calendar.svg" alt="Календарь" class="w-5 h-5 flex-shrink-0">
        <span class="ml-1">{{ $monthNames[$month-1] }}</span>
        <span class="ml-auto mr-4">{{ $year }}</span>
        <svg class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>
    @if($open)
    <div class="absolute z-50 mt-2 bg-white rounded-md shadow-lg p-4 w-[307px]" wire:click.away="close">
        <div class="flex items-center justify-between mb-3">
            <button type="button" wire:click="prevYear" class="year-arrow"><img src="/img/icon/angle-left.svg" alt="Prev" width="20" height="20"></button>
            <span class="text-lg font-semibold">{{ $year }}</span>
            <button type="button" wire:click="nextYear" class="year-arrow"><img src="/img/icon/angle-right.svg" alt="Next" width="20" height="20"></button>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($monthNames as $idx=>$m)
                <button type="button" wire:click="selectMonth({{ $idx+1 }})" class="schedule-month-btn {{ $idx+1==$month ? 'selected' : '' }}">{{ $m }}</button>
            @endforeach
        </div>
    </div>
    @endif
</div> 