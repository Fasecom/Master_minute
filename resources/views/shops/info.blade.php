<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col gap-4">
            <div class="flex flex-wrap items-center justify-between">
                <h1 class="h1-header">Торговые точки</h1>
                <div class="flex items-center gap-4 ml-auto">
                    <a href="{{ route('shops') }}" class="btn-leval3 btn-red">
                        <img src="/img/icon/undo.svg" alt="Назад" width="24" height="24">
                    </a>
                </div>
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col gap-5 bg-white rounded-[21px] shadow-[0_5px_27px_rgba(46,69,85,0.25)] p-5 md:p-[20px] min-h-[340px] w-full mb-5">
                <div class="flex gap-8">
                    <!-- Левая часть -->
                    <div class="flex flex-col gap-5 pl-2">
                        <h1 class="h1-header">{{ $shop->name }}</h1>
                        <ul class="flex flex-col gap-2">
                            <li class="flex items-center gap-2">
                                <img src="/img/icon/calendar.svg" alt="date" width="23" height="23">
                                <h3 class="h3-point">Дата открытия:</h3>
                                <h3 class="h3-point-info">{{ $openDate }}</h3>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="/img/icon/clock.svg" alt="worktime" width="23" height="23">
                                <h3 class="h3-point">График работы:</h3>
                                <h3 class="h3-point-info">{{ $workTime }}</h3>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="/img/icon/geo-marker.svg" alt="address" width="23" height="23">
                                <h3 class="h3-point">Адрес:</h3>
                                <h3 class="h3-point-info">{{ $shop->address }}</h3>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="/img/icon/user.svg" alt="master" width="23" height="23">
                                <h3 class="h3-point">Мастер:</h3>
                                <h3 class="h3-point-info">
                                    {{ $currentEmployee ? collect(explode(' ', $currentEmployee->full_name))->take(2)->implode(' ') : 'Нет сотрудника' }}
                                </h3>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="/img/icon/call.svg" alt="phone" width="23" height="23">
                                <h3 class="h3-point">Телефон:</h3>
                                <h3 class="h3-point-info">
                                    @if($shop->phone)
                                        {{ '+7 ' . substr($shop->phone, 1, 3) . ' ' . substr($shop->phone, 4, 3) . ' ' . substr($shop->phone, 7, 2) . ' ' . substr($shop->phone, 9, 2) }}
                                    @else
                                        Нету
                                    @endif
                                </h3>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="/img/icon/mail.svg" alt="email" width="23" height="23">
                                <h3 class="h3-point">Почта:</h3>
                                <h3 class="h3-point-info">{{ $shop->email ?? 'Нету' }}</h3>
                            </li>
                        </ul>
                        <!-- Услуги -->
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <img src="/img/icon/key.svg" alt="services" width="23" height="23">
                                <h3 class="h3-point">Услуги:</h3>
                            </div>
                            <ul class="flex flex-wrap gap-x-4 pl-8">
                                @foreach($services->chunk(3) as $chunk)
                                    <li class="flex flex-col">
                                        @foreach($chunk as $service)
                                            <span class="h3-point-skills whitespace-nowrap"><span class="text-xl">•</span> {{ $service }}</span>
                                        @endforeach
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- Правая часть (график не нужен) -->
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('shops.edit', $shop->id) }}" class="btn-leval4 btn-blue flex items-center gap-2">
                        <img src="/img/icon/pencil.svg" alt="Редактировать" width="20" height="20">
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 