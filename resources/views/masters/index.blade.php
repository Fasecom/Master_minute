<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col gap-4 mb-8">
            <div class="flex items-center justify-between">
                <h1 class="h1-header">Мастера</h1>
                <div class="flex items-center gap-4">
                    <button class="btn-header btn-header--wide">Аналитика</button>
                    <button class="btn-header btn-header--plus">+</button>
                </div>
            </div>
            <form method="GET" class="flex gap-4 items-center">
                <input type="text" name="search" placeholder="поиск" class="input-header w-full" value="{{ request('search') }}">
                <button class="btn-header btn-header--search" type="submit">Найти</button>
            </form>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-wrap gap-5 justify-center">
                @foreach ($masters as $master)
                    @php
                        $fio = collect(explode(' ', $master->full_name))->take(2)->implode(' ');
                        $shift = $master->workingShifts()->orderByDesc('date')->first();
                        $workshopName = $shift && $shift->workshop ? $shift->workshop->name : 'Нет смены';
                    @endphp
                    <div class="master-card flex flex-col justify-between rounded-[21px] shadow-[0_5px_27px_rgba(46,69,85,0.25)] p-5 bg-white min-w-[400px] max-w-none min-[1271px]:max-w-[400px] flex-1 basis-[400px] h-[140px]">
                        <h3 class="h3-main mb-2">{{ $fio }}</h3>
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-2">
                                <img src="/img/icon/geo-marker.svg" alt="place" width="20" height="20">
                                <h4 class="h4-main">{{ $workshopName }}</h4>
                            </div>
                            <button class="btn-main">Подробнее</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    @vite(['resources/js/search.js'])
@endpush 