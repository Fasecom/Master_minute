@section('title', 'ММ: Мастера')

<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col gap-4">
            <div class="flex flex-wrap items-center justify-between">
                <h1 class="h1-header">Аналитика мастеров</h1>
                <div class="flex items-center gap-4 ml-auto">
                    <livewire:month-year-filter/>
                    <a href="{{ route('masters') }}" class="btn-leval3 btn-red">
                        <img src="/img/icon/undo.svg" alt="Назад" width="24" height="24">
                    </a>
                </div>
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col gap-5 bg-white rounded-[21px] shadow-[0_5px_27px_rgba(46,69,85,0.25)] p-5 md:p-[20px] min-h-[340px] w-full mb-5">

                <div>
                    <ul class="flex flex-col gap-2">
                        <li class="flex items-center gap-2">
                            <img src="/img/icon/chart-histogram.svg" alt="place" width="23" height="23">
                            <h3 class="h3-point">Изменение выручки за месяц</h3>
                        </li>
                    </ul>
                    <livewire:master-monthly-revenue-change-chart />
                </div>

                <div class="flex-1 min-w-[300px]">
                    <ul class="flex flex-col gap-2">
                        <li class="flex items-center gap-2">
                            <img src="/img/icon/chart-histogram.svg" alt="place" width="23" height="23">
                            <h3 class="h3-point">Рейтинг по выручки за месяц</h3>
                        </li>
                    </ul>
                    <livewire:master-revenue-rating-chart mode="month" />
                </div>
                <div class="flex-1 min-w-[300px]">
                    <ul class="flex flex-col gap-2">
                        <li class="flex items-center gap-2">
                            <img src="/img/icon/chart-histogram.svg" alt="place" width="23" height="23">
                            <h3 class="h3-point">Рейтинг по выручки за год</h3>
                        </li>
                    </ul>
                    <livewire:master-revenue-rating-chart mode="year" />
                </div>

                <div>
                    <ul class="flex flex-col gap-2">
                        <li class="flex items-center gap-2">
                            <img src="/img/icon/chart-histogram.svg" alt="place" width="23" height="23">
                            <h3 class="h3-point">Рейтинг по количества смен за год</h3>
                        </li>
                    </ul>
                    <livewire:master-shift-count-rating-chart />
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 