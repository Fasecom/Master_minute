<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col gap-4">
            <div class="flex flex-wrap items-center justify-between">
                <h1 class="h1-header">Мастера</h1>
                <div class="flex items-center gap-4 ml-auto">
                    <!-- <button class="btn-leval1 w-[150px] sm:w-[300px]">Аналитика</button> -->
                    <a href="{{ route('masters.add') }}" class="btn-leval1 flex items-center justify-center w-[65px] h-[44px] rounded-full p-0" style="min-width:65px;min-height:44px;">
                        <img src="/img/icon/plus.svg" alt="Добавить" width="24" height="24">
                    </a>
                </div>
            </div>
            <form method="GET" class="flex gap-4 items-center">
                <input type="text" name="search" placeholder="Поиск" class="input-header w-full" value="{{ request('search') }}" autocomplete="off">
            </form>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-wrap gap-5 justify-center">
                @foreach ($masters as $master)
                    @php
                        $fio = collect(explode(' ', $master->full_name))->take(2)->implode(' ');
                        $shift = $master->workingShifts()->whereDate('date', date('Y-m-d'))->first();
                        $workshopName = $shift && $shift->workshop ? $shift->workshop->name : 'Нет смены';
                    @endphp
                    <div class="flex flex-col justify-between rounded-[21px] shadow-[0_5px_27px_rgba(46,69,85,0.25)] p-5 bg-white min-w-[400px] max-w-none min-[1271px]:max-w-[400px] flex-1 basis-[400px] h-[140px]">
                        <h3 class="h3-main mb-2" name="name">{{ $fio }}</h3>
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-2">
                                <img src="/img/icon/geo-marker.svg" alt="place" width="20" height="20">
                                <h4 class="h4-main">{{ $workshopName }}</h4>
                            </div>
                            <a href="{{ route('masters.info', ['id' => $master->id]) }}" class="btn-leval2 btn-blue">Подробнее</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout> 