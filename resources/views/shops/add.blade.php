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
            <form method="POST" action="{{ route('shops.store') }}" class="bg-white rounded-[21px] shadow-[0_5px_27px_rgba(46,69,85,0.25)] flex flex-row min-h-[340px] w-full" id="add-shop-form">
                @csrf
                <!-- Левая часть -->
                <div class="flex flex-col gap-5 w-1/2 p-10" style="padding:30px;">
                    <div class="flex flex-col gap-2">
                        <!-- Название -->
                        <div class="flex items-center gap-2">
                            <img src="/img/icon/letter-case.svg" alt="name" width="23" height="23">
                            <h3 class="h3-point">Название</h3>
                        </div>
                        <input type="text" name="name" class="input-header w-full mb-1" placeholder="ТРЦ Фокус" value="{{ old('name') }}" required autocomplete="off">
                        @error('name')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                        <!-- Дата открытия -->
                        <div class="flex items-center gap-2">
                            <img src="/img/icon/calendar.svg" alt="date" width="23" height="23">
                            <h3 class="h3-point">Дата открытия</h3>
                        </div>
                        <input type="date" name="open_date" class="input-header w-full mb-1" value="{{ old('open_date', date('Y-m-d')) }}" required autocomplete="off">
                        @error('open_date')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                        <!-- График работы -->
                        <div class="flex items-center gap-2">
                            <img src="/img/icon/clock.svg" alt="worktime" width="23" height="23">
                            <h3 class="h3-point">График работы</h3>
                        </div>
                        <div class="flex gap-5 mb-1">
                            <input type="time" name="open_time" class="input-header w-full" value="{{ old('open_time') }}" required autocomplete="off">
                            <input type="time" name="close_time" class="input-header w-full" value="{{ old('close_time') }}" required autocomplete="off">
                        </div>
                        @error('open_time')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                        @error('close_time')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                        <!-- Адрес -->
                        <div class="flex items-center gap-2">
                            <img src="/img/icon/geo-marker.svg" alt="address" width="23" height="23">
                            <h3 class="h3-point">Адрес</h3>
                        </div>
                        <input type="text" name="address" class="input-header w-full mb-1" placeholder="Молдавская улица, 16" value="{{ old('address') }}" required autocomplete="off">
                        @error('address')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                        <!-- Почта -->
                        <div class="flex items-center gap-2">
                            <img src="/img/icon/mail.svg" alt="email" width="23" height="23">
                            <h3 class="h3-point">Почта</h3>
                        </div>
                        <input type="email" name="email" class="input-header w-full mb-1" placeholder="ivanivanovich@mail.ru" value="{{ old('email') }}" autocomplete="off">
                        @error('email')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                        <!-- Телефон -->
                        <div class="flex items-center gap-2">
                            <img src="/img/icon/call.svg" alt="phone" width="23" height="23">
                            <h3 class="h3-point">Телефон</h3>
                        </div>
                        <input type="text" name="phone" class="input-header w-full mb-1" placeholder="+7 000 000 00 00" value="{{ old('phone') }}" id="phone-input" autocomplete="off">
                        @error('phone')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                        <!-- Услуги -->
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2 mb-1">
                                <img src="/img/icon/key.svg" alt="services" width="23" height="23">
                                <h3 class="h3-point">Услуги</h3>
                                <a href="{{ route('shops.services.edit', ['back' => url()->current()]) }}" class="group" style="display:inline-flex;align-items:center;">
                                    <img src="/img/icon/pencil.svg" alt="Редактировать услуги" width="16" height="16" style="filter: invert(74%) sepia(0%) saturate(0%) hue-rotate(180deg) brightness(93%) contrast(90%); opacity:0.7; transition:opacity .2s, filter .2s;" onmouseover="this.style.opacity=1;this.style.filter='invert(74%) sepia(0%) saturate(0%) hue-rotate(180deg) brightness(60%) contrast(120%)';" onmouseout="this.style.opacity=0.7;this.style.filter='invert(74%) sepia(0%) saturate(0%) hue-rotate(180deg) brightness(93%) contrast(90%)';">
                                </a>
                            </div>
                            <div class="flex flex-wrap gap-x-4">
                                @foreach($services->chunk(3) as $chunk)
                                    <div class="flex flex-col">
                                        @foreach($chunk as $service)
                                            <label class="flex items-center gap-2 mb-1">
                                                <input type="checkbox" name="services[]" value="{{ $service->id }}" {{ (is_array(old('services')) && in_array($service->id, old('services', []))) ? 'checked' : '' }}>
                                                {{ $service->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            @error('services')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <!-- Правая часть -->
                <div class="flex flex-col justify-between bg-[#234E9B] rounded-[16px] p-10 flex-1" style="padding:30px;">
                    <div class="flex justify-between items-center mb-4">
                        <span class="h1-header" style="color:#fff;">Добавление</span>
                        <div></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-auto">
                        <button type="button" class="btn-leval4 btn-red" onclick="window.history.back()"><img src="/img/icon/undo.svg" alt="Назад" width="20" height="20"></button>
                        <button type="submit" class="btn-leval4 btn-blue"><img src="/img/icon/check.svg" alt="Сохранить" width="20" height="20"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="/js/phone-mask.js"></script>
</x-app-layout> 