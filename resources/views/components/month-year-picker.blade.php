@props(['name' => 'month_year', 'value' => null])
@php
    $months = [
        1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь',
        7 => 'Июль', 8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
    ];
    $currentYear = date('Y');
    $selected = $value ? explode('-', $value) : [date('Y'), date('m')];
@endphp
<style>
    .month-year-select { appearance: none; -webkit-appearance: none; -moz-appearance: none; background: none; }
    .month-year-select option { padding-right: 24px; }
    /* Больше не скрываем стрелки у input[type=number].year-input */
</style>
<div class="relative">
    <div class="flex items-center bg-[#f3f3f3] rounded-[65px] px-6 py-2 min-w-[220px] gap-2 input-header" style="height:45px;">
        <img src="/img/icon/calendar.svg" alt="Календарь" class="w-5 h-5">
        <select name="month" class="month-year-select bg-transparent outline-none border-none focus:ring-0 p-0 m-0 text-[#2E4555] font-semibold" style="min-width:90px;" x-data x-init="$el.value='{{ (int)($selected[1] ?? date('m')) }}'">
            @foreach($months as $num => $name)
                <option value="{{ $num }}" @if((int)($selected[1] ?? date('m')) === $num) selected @endif>{{ $name }}</option>
            @endforeach
        </select>
        <input type="number" name="year" min="2000" max="2100" maxlength="4" class="year-input bg-transparent outline-none border-none focus:ring-0 w-[80px] text-[#2E4555] font-semibold" value="{{ $selected[0] ?? date('Y') }}" oninput="if(this.value.length>4)this.value=this.value.slice(0,4);this.value=this.value.replace(/[^0-9]/g,'');">
        <span class="text-[#2E4555]">г</span>
    </div>
    <input type="hidden" name="{{ $name }}" :value="`${$el.querySelector('select').value}-${$el.querySelector('input[type=number]').value}`">
</div> 