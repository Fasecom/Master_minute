@props(['initialMonth' => date('n'), 'initialYear' => date('Y')])
@php
    $monthNames = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
@endphp
<div class="relative"
     x-data="{
        open: false,
        month: {{ $initialMonth }},
        year: {{ $initialYear }},
        months: @js($monthNames),
        monthName(i) { return this.months[i - 1]; },
        display() { return this.monthName(this.month) + ' ' + this.year; }
     }"
>
    <!-- Main button -->
    <button type="button" class="input-header flex items-center gap-2 w-[240px] pl-4 pr-10 py-2 relative" @click="open = !open">
        <img src="/img/icon/calendar.svg" alt="Календарь" class="w-5 h-5 flex-shrink-0">
        <span class="ml-1" x-text="monthName(month)"></span>
        <span class="ml-auto mr-4" x-text="year"></span>
        <svg class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>
    <!-- Dropdown -->
    <div x-show="open" @click.outside="open = false" class="absolute z-50 mt-2 bg-white rounded-md shadow-lg p-4 w-[307px]" style="display: none;">
        <!-- Year navigation -->
        <div class="flex items-center justify-between mb-3">
            <button type="button" @click="year--" class="year-arrow">
                <img src="/img/icon/angle-left.svg" alt="Предыдущий год" width="20" height="20">
            </button>
            <span x-text="year" class="text-lg font-semibold"></span>
            <button type="button" @click="year++" class="year-arrow">
                <img src="/img/icon/angle-right.svg" alt="Следующий год" width="20" height="20">
            </button>
        </div>
        <!-- Months grid -->
        <div class="flex flex-wrap gap-2">
            <template x-for="(m, idx) in months" :key="idx">
                <button type="button"
                    @click="month = idx + 1;"
                    :class="{'selected': idx + 1 === month}"
                    class="schedule-month-btn" x-text="m"></button>
            </template>
        </div>
    </div>
</div> 