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
                <x-multiselect-dropdown name="masters"/>
                <x-multiselect-dropdown name="shops"/>
                <button class="btn-leval1 w-[150px] sm:w-[300px] ml-auto">Внести выручку</button>
            </form>
        </div>
        <!-- Подключение кастомных стилей и скриптов -->
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-auto shadow-xl sm:rounded-lg p-4">
                <div class="base flex">
                    <div class="horizontal-lines">
                        <table>
                            @for($i = 0; $i < 9; $i++)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endfor
                        </table>
                    </div>
                    <div class="calendar">
                        <div class="page-flipper">
                            <button class="flipper-arrow" type="button"><img src="/img/icon/angle-left.svg" alt="Назад" width="20" height="20"></button>
                            <span class="flipper-number">1</span>
                            <button class="flipper-arrow" type="button"><img src="/img/icon/angle-right.svg" alt="Вперёд" width="20" height="20"></button>
                        </div>

                        @php
                            $week = [
                                ['day' => 1, 'short' => 'пн'],
                                ['day' => 2, 'short' => 'вт'],
                                ['day' => 3, 'short' => 'ср'],
                                ['day' => 4, 'short' => 'чт'],
                                ['day' => 5, 'short' => 'пт'],
                                ['day' => 6, 'short' => 'сб'],
                                ['day' => 7, 'short' => 'вс'],
                            ];
                        @endphp

                        @foreach($week as $d)
                            <div class="date {{ in_array($d['short'], ['сб','вс']) ? 'red' : '' }}">
                                <span class="day">{{ $d['day'] }}</span>
                                <span class="weekday">{{ $d['short'] }}</span>
                            </div>
                        @endforeach
                        <div class="total">
                            <div class="total-revenue"></div>
                            <div class="total-revenue">Выручка:</div>
                        </div>
                    </div>
                    <div class="shift-column">
                        <div class="sc-header">
                            <div class="name">ТРЦ Горки</div>
                            <div class="work-time">
                                <img src="/img/icon/clocktime.svg" alt="Время работы" width="14" height="14">
                                <h3 class="time-text">10:00 – 22:00</h3>
                            </div>
                        </div>
                        <div class="shifts-upper">
                            <div class="shifts-card bg-green-100">
                                <div class="master-name">Смирнова Е.П.</div>
                                <div class="revenue">
                                    <div class="flex justify-between">
                                        <div>Нал:</div>
                                        <div>1150 ₽</div>
                                    </div>
                                    <div class="flex justify-between">
                                        <div>Без нал:</div>
                                        <div>4050 ₽</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="shifts-between">
                            <div class="shifts-card bg-green-100">
                                <div class="master-name">Смирнова Е.П.</div>
                                <div class="revenue">
                                    <div class="flex justify-between">
                                        <div>Нал:</div>
                                        <div>1150 ₽</div>
                                    </div>
                                    <div class="flex justify-between">
                                        <div>Без нал:</div>
                                        <div>4050 ₽</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="shifts-lower">
                            <div class="shifts-card bg-green-100">
                                <div class="master-name">Смирнова Е.П.</div>
                                <div class="revenue">
                                    <div class="flex justify-between">
                                        <div>Нал:</div>
                                        <div>1150 ₽</div>
                                    </div>
                                    <div class="flex justify-between">
                                        <div>Без нал:</div>
                                        <div>4050 ₽</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="shifts">
                            
                        </div>
                        <div class="shifts">
                            
                        </div>
                        <div class="shifts-upper">
                            <div class="shifts-card bg-green-100">
                                <div class="master-name">Смирнова Е.П.</div>
                                <div class="revenue">
                                    <div class="flex justify-between">
                                        <div>Нал:</div>
                                        <div>1150 ₽</div>
                                    </div>
                                    <div class="flex justify-between">
                                        <div>Без нал:</div>
                                        <div>4050 ₽</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="shifts-lower">
                            <div class="shifts-card bg-green-100">
                                <div class="master-name">Смирнова Е.П.</div>
                                <div class="revenue">
                                    <div class="flex justify-between">
                                        <div>Нал:</div>
                                        <div>1150 ₽</div>
                                    </div>
                                    <div class="flex justify-between">
                                        <div>Без нал:</div>
                                        <div>4050 ₽</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="total">
                            <div class="name">ТРЦ Горки</div>
                            <div class="revenue">111 350 ₽</div>
                        </div>
                    </div>
                    <div class="shift-column">
                        <div class="sc-header">
                            <div class="name">ТРЦ Фиеста</div>
                            <div class="work-time">
                                <img src="/img/icon/clocktime.svg" alt="Время работы" width="14" height="14">
                                <h3 class="time-text">10:00 – 22:00</h3>
                            </div>
                        </div>

                        @foreach($week as $d)
                            <div class="shifts">
                                <div class="shifts-card bg-green-100">
                                    <div class="master-name">Козлов Д.И.</div>
                                    <div class="revenue">
                                        <div class="flex justify-between">
                                            <div>Нал:</div>
                                            <div>1150 ₽</div>
                                        </div>
                                        <div class="flex justify-between">
                                            <div>Без нал:</div>
                                            <div>4050 ₽</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="total">
                            <div class="name">ТРЦ Фиеста</div>
                            <div class="revenue">36 400 ₽</div>
                        </div>
                    </div>
                    <div class="shift-column">
                        <div class="sc-header">
                            <div class="name">ТРЦ Фокус</div>
                            <div class="work-time">
                                <img src="/img/icon/clocktime.svg" alt="Время работы" width="14" height="14">
                                <h3 class="time-text">10:00 – 22:00</h3>
                            </div>
                        </div>

                        @foreach($week as $d)
                            <div class="shifts">
                                <div class="shifts-card bg-red-100">
                                    <div class="master-name">Василий И.С.</div>
                                    <div class="revenue">
                                        <div class="flex justify-between">
                                            <div>Нал:</div>
                                            <div>1150 ₽</div>
                                        </div>
                                        <div class="flex justify-between">
                                            <div>Без нал:</div>
                                            <div>4050 ₽</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="total">
                            <div class="name">ТРЦ Фокус</div>
                            <div class="revenue">36 400 ₽</div>
                        </div>
                    </div>
                    <div class="shift-column">
                        <div class="sc-header">
                            <div class="name">ТРЦ Родник</div>
                            <div class="work-time">
                                <img src="/img/icon/clocktime.svg" alt="Время работы" width="14" height="14">
                                <h3 class="time-text">10:00 – 22:00</h3>
                            </div>
                        </div>

                        @foreach($week as $d)
                            <div class="shifts">
                                <div class="shifts-card bg-purple-100">
                                    <div class="master-name">Иванов С.П.</div>
                                    <div class="revenue">
                                        <div class="flex justify-between">
                                            <div>Нал:</div>
                                            <div>1150 ₽</div>
                                        </div>
                                        <div class="flex justify-between">
                                            <div>Без нал:</div>
                                            <div>4050 ₽</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="total">
                            <div class="name">ТРЦ Родник</div>
                            <div class="revenue">36 400 ₽</div>
                        </div>
                    </div>
                    <div class="shift-column">
                        <div class="sc-header">
                            <div class="name">ГМ Молния</div>
                            <div class="work-time">
                                <img src="/img/icon/clocktime.svg" alt="Время работы" width="14" height="14">
                                <h3 class="time-text">10:00 – 22:00</h3>
                            </div>
                        </div>

                        @foreach($week as $d)
                            <div class="shifts">
                                <div class="shifts-card bg-blue-100">
                                    <div class="master-name">Смирнова Е.П.</div>
                                    <div class="revenue">
                                        <div class="flex justify-between">
                                            <div>Нал:</div>
                                            <div>1150 ₽</div>
                                        </div>
                                        <div class="flex justify-between">
                                            <div>Без нал:</div>
                                            <div>4050 ₽</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="total">
                            <div class="name">ГМ Молния</div>
                            <div class="revenue">36 400 ₽</div>
                        </div>
                    </div>
                    <div class="shift-column">
                        <div class="sc-header">
                            <div class="name">ТРЦ Кольцо</div>
                            <div class="work-time">
                                <img src="/img/icon/clocktime.svg" alt="Время работы" width="14" height="14">
                                <h3 class="time-text">10:00 – 22:00</h3>
                            </div>
                        </div>

                        @foreach($week as $d)
                            <div class="shifts">
                                <div class="shifts-card bg-indigo-100">
                                    <div class="master-name">Новикова А.С.</div>
                                    <div class="revenue">
                                        <div class="flex justify-between">
                                            <div>Нал:</div>
                                            <div>1150 ₽</div>
                                        </div>
                                        <div class="flex justify-between">
                                            <div>Без нал:</div>
                                            <div>4050 ₽</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="total">
                            <div class="name">ТРЦ Кольцо</div>
                            <div class="revenue">36 400 ₽</div>
                        </div>
                    </div>
                    <div class="shift-column">
                        <div class="sc-header">
                            <div class="name">ГМ Молния (ул.Труда)</div>
                            <div class="work-time">
                                <img src="/img/icon/clocktime.svg" alt="Время работы" width="14" height="14">
                                <h3 class="time-text">10:00 – 22:00</h3>
                            </div>
                        </div>

                        @foreach($week as $d)
                            <div class="shifts">
                                <div class="shifts-card bg-green-100">
                                    <div class="master-name">Василий И.С.</div>
                                    <div class="revenue">
                                        <div class="flex justify-between">
                                            <div>Нал:</div>
                                            <div>1150 ₽</div>
                                        </div>
                                        <div class="flex justify-between">
                                            <div>Без нал:</div>
                                            <div>4050 ₽</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="total">
                            <div class="name">ГМ Молния (ул.Труда)</div>
                            <div class="revenue">36 400 ₽</div>
                        </div>
                    </div>
                </div>
                <div class="result flex">
                    <div class="result-total">Итого:</div>
                    <div class="result-total-revenue">2 456 000 ₽</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 