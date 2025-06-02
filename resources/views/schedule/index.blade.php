<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col gap-4">
            <!-- Верхняя полоса -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="h1-header">График смен</h1>
                <div class="flex items-center gap-2 ml-auto">
                    <x-month-year-picker name="month_year" :value="request('month_year')" />
                    <a href="{{ route('masters.add') }}" class="btn-leval1 flex items-center justify-center w-[44px] h-[44px] rounded-full p-0 ml-2" style="min-width:44px;min-height:44px;">
                        <img src="/img/icon/pencil.svg" alt="Редактировать" width="24" height="24">
                    </a>
                </div>
            </div>
            <!-- Нижняя полоса -->
            <form method="GET" class="flex flex-wrap gap-4 items-center">
                <x-multiselect-dropdown name="masters" :options="$mastersOptions" :selected="request('masters', [])" />
                <x-multiselect-dropdown name="shops" :options="$shopsOptions" :selected="request('shops', [])" />
                <button class="btn-leval1 w-[150px] sm:w-[300px] ml-auto">Внести выручку</button>
            </form>
        </div>
        <!-- Подключение кастомных стилей и скриптов -->
        <link rel="stylesheet" href="/css/schedule.css">
        <script src="/js/schedule.js" defer></script>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <!-- Переключатель страниц точек -->
                <div class="flex items-center mb-4">
                    <form method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="month_year" value="{{ $monthYear }}">
                        <button name="page" value="{{ $page-1 }}" class="arrow-btn" @if($page<=1) disabled @endif>&lt;</button>
                        <span class="font-bold text-lg mx-2">{{ $page }}</span>
                        <button name="page" value="{{ $page+1 }}" class="arrow-btn" @if($page>=$totalPages) disabled @endif>&gt;</button>
                    </form>
                    <span class="ml-4 text-gray-500">Страница {{ $page }} из {{ $totalPages }}</span>
                </div>
                <!-- Таблица смен -->
                <div class="overflow-x-auto">
                    <table class="schedule-table w-full border-collapse">
                        <thead>
                        <tr>
                            <th class="date-col"></th>
                            @foreach($workshops as $workshop)
                                <th class="workshop-col">
                                    <div class="workshop-header">
                                        <div class="font-bold">{{ $workshop->name }}</div>
                                        <div class="text-xs text-gray-500">{{ substr($workshop->open_time,0,5) }}-{{ substr($workshop->close_time,0,5) }}</div>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dates as $date)
                            <tr>
                                <!-- Дата -->
                                <td class="date-col text-center align-middle">
                                    <div class="date-box">
                                        <div class="date-num">{{ $date['day'] }}</div>
                                        <div class="date-week @if($date['is_weekend']) text-red-500 @endif">{{ $date['weekday'] }}</div>
                                    </div>
                                </td>
                                <!-- Смены по точкам -->
                                @foreach($workshops as $workshop)
                                    <td class="shift-cell">
                                        @php
                                            $shift = $shiftsByWorkshopDate[$workshop->id][$date['date']] ?? null;
                                        @endphp
                                        @if($shift)
                                            <div class="shift-card shift-master-{{ $shift->user_id }}">
                                                <div class="shift-fio">{{ $shift->user->full_name }}</div>
                                                <div class="shift-revenue">
                                                    <div class="shift-cash">Нал: <span>{{ (int)$shift->cash_revenue }}₽</span></div>
                                                    <div class="shift-cashless">Без нал: <span>{{ (int)$shift->cashless_revenue }}₽</span></div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        <!-- Итоговая строка -->
                        <tr class="total-row">
                            <td class="date-col font-bold text-right pr-2">Итого:</td>
                            @foreach($workshops as $workshop)
                                <td class="shift-cell font-bold">{{ (int)($totals[$workshop->id] ?? 0) }}₽</td>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Общий итог -->
                <div class="flex justify-end mt-4">
                    <div class="grand-total-box font-bold text-lg">Общий итог: {{ (int)$grandTotal }}₽</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 