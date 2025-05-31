<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col gap-4">
            <div class="flex flex-wrap items-center justify-between">
                <h1 class="h1-header">Мастера</h1>
                <div class="flex items-center gap-4 ml-auto">
                    <a href="{{ route('masters') }}" class="btn-leval3-red">
                        <img src="/img/icon/undo.svg" alt="Назад" width="24" height="24">
                    </a>
                </div>
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-[21px] shadow-[0_5px_27px_rgba(46,69,85,0.25)] p-5 md:p-[20px] flex flex-row gap-8 min-h-[340px] w-full">
                <!-- Левая часть -->
                <div class="flex flex-col gap-5 w-1/2">
                    <h1 class="h1-header">{{ $master->full_name }}</h1>
                    <div class="flex flex-col gap-2">
                        <!-- Пункты -->
                        <div class="flex items-center gap-2 mb-2">
                            <img src="/img/icon/geo-marker.svg" alt="place" width="23" height="23">
                            <h3 class="h3-point">Точка:</h3>
                            <h3 class="h3-point-info">{{ $workshop->name ?? 'Нет точки' }}</h3>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <img src="/img/icon/clock.svg" alt="exp" width="23" height="23">
                            <h3 class="h3-point">Стаж:</h3>
                            <h3 class="h3-point-info">{{ $experience }}</h3>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <img src="/img/icon/calendar.svg" alt="date" width="23" height="23">
                            <h3 class="h3-point">Дата начала работы:</h3>
                            <h3 class="h3-point-info">{{ $workStartDate }}</h3>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <img src="/img/icon/call.svg" alt="phone" width="23" height="23">
                            <h3 class="h3-point">Номер телефона:</h3>
                            <h3 class="h3-point-info">@formatPhone($master->phone ?? '-')</h3>
                        </div>
                        <!-- Навыки -->
                        <div class="flex flex-col gap-1 mb-2">
                            <div class="flex items-center gap-2 mb-1">
                                <img src="/img/icon/key.svg" alt="skills" width="23" height="23">
                                <h3 class="h3-point">Навыки:</h3>
                            </div>
                            <div class="flex flex-wrap gap-x-4 pl-8">
                                @foreach($skills->chunk(3) as $chunk)
                                    <div class="flex flex-col">
                                        @foreach($chunk as $skill)
                                            <span class="h3-point-skills whitespace-nowrap"><span class="text-xl">•</span> {{ $skill }}</span>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Правая часть (место под график) -->
                <div class="min-w-[260px] max-w-[320px] flex-1 flex items-center justify-center">
                    <!-- Здесь будет график -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 