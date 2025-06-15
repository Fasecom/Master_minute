<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col gap-4">
            <div class="flex flex-wrap items-center justify-between">
                <h1 class="h1-header">Внести выручку</h1>
                <div class="flex items-center gap-4 ml-auto">
                    <a href="{{ route('schedule') }}" class="btn-leval3 btn-red">
                        <img src="/img/icon/undo.svg" alt="Назад" width="24" height="24">
                    </a>
                </div>
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <form method="POST" action="{{ route('schedule.revenue.store') }}" class="bg-white rounded-[21px] shadow-[0_5px_27px_rgba(46,69,85,0.25)] flex flex-row min-h-[500px] w-full" id="add-revenue-form">
                @csrf
                <!-- Левая часть -->
                <div class="flex flex-col justify-between w-3/5 p-10" style="padding:30px;">
                    <div class="flex flex-col h-full justify-between">
                        <div class="flex flex-col gap-2">
                            <!-- Мастер -->
                            @if(auth()->user()->role_id != 3)
                            <div class="flex items-center gap-2">
                                <img src="/img/icon/user.svg" alt="master" width="23" height="23">
                                <h3 class="h3-point">Мастер</h3>
                            </div>
                            <select name="user_id" class="input-header w-full mb-1" required>
                                <option value="">Выберите мастера</option>
                                @foreach($masters as $master)
                                    @php
                                        $parts = explode(' ', $master->full_name);
                                        $surname = $parts[0] ?? '';
                                        $name = $parts[1] ?? '';
                                    @endphp
                                    <option value="{{ $master->id }}">{{ $surname }} {{ $name }}</option>
                                @endforeach
                            </select>
                            @endif
                            <!-- Дата смены -->
                            <div class="flex items-center gap-2">
                                <img src="/img/icon/calendar.svg" alt="date" width="23" height="23">
                                <h3 class="h3-point">Дата смены</h3>
                            </div>
                            <input type="date" name="date" class="input-header w-full mb-1" value="{{ old('date', date('Y-m-d')) }}" required autocomplete="off">
                            @error('date')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                            <!-- Выручка -->
                            <div class="flex items-center gap-2">
                                <img src="/img/icon/calculator.svg" alt="revenue" width="23" height="23">
                                <h3 class="h3-point">Выручка</h3>
                            </div>
                            <div class="flex gap-5 mb-1">
                                <input type="number" step="0.01" min="0" name="cash_revenue" class="input-header w-full" placeholder="Наличными" value="{{ old('cash_revenue') }}" required autocomplete="off">
                                <input type="number" step="0.01" min="0" name="cashless_revenue" class="input-header w-full" placeholder="Безналичными" value="{{ old('cashless_revenue') }}" required autocomplete="off">
                            </div>
                            @error('cash_revenue')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                            @error('cashless_revenue')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex justify-end mt-8">
                            <button type="submit" class="btn-leval1 btn-blue w-full text-lg py-2">Добавить</button>
                        </div>
                    </div>
                </div>
                <!-- Правая часть -->
                <div class="flex flex-col justify-between bg-[#234E9B] rounded-[16px] p-10 flex-1 min-w-[180px] max-w-[100%]" style="padding:30px;">
                    <div class="flex justify-between items-center mb-4">
                        <span class="h1-header" style="color:#fff;">Выручка</span>
                        <div></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 