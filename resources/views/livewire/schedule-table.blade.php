<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-auto shadow-xl sm:rounded-lg p-4">
            <div class="base flex" name="WorkingShift">
                <div class="horizontal-lines">
                    <table>
                        @for($i = 0; $i < count($days) + 2; $i++)
                            <tr>
                                @for($j = 0; $j < count($workshops) + 1; $j++)
                                    <td class="{{ $j === count($workshops) ? '' : 'border-r border-dashed border-gray-300' }}"></td>
                                @endfor
                            </tr>
                        @endfor
                    </table>
                </div>
                <div class="calendar">
                    <div class="page-flipper">
                        <button class="flipper-arrow" type="button" wire:click="goPrevPage" {{ $currentPage <= 1 ? 'disabled' : '' }}>
                            <img src="/img/icon/angle-left.svg" alt="Назад" width="20" height="20">
                        </button>
                        <span class="flipper-number">{{ $currentPage }}</span>
                        <button class="flipper-arrow" type="button" wire:click="goNextPage" {{ $currentPage >= $totalPages ? 'disabled' : '' }}>
                            <img src="/img/icon/angle-right.svg" alt="Вперёд" width="20" height="20">
                        </button>
                    </div>

                    @foreach($days as $day)
                        <div class="date {{ $day['isWeekend'] ? 'red' : '' }}">
                            <span class="day">{{ $day['day'] }}</span>
                            <span class="weekday">{{ $day['short'] }}</span>
                        </div>
                    @endforeach
                    <div class="total">
                        <div class="total-revenue"></div>
                        <div class="total-revenue">Выручка:</div>
                    </div>
                </div>

                @foreach($workshops as $workshop)
                    <div class="shift-column">
                        <div class="sc-header">
                            <div class="name">{{ $workshop->name }}</div>
                            <div class="work-time">
                                <img src="/img/icon/clocktime.svg" alt="Время работы" width="14" height="14">
                                <h3 class="time-text">
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $workshop->open_time)->format('H:i') }} – {{ \Carbon\Carbon::createFromFormat('H:i:s', $workshop->close_time)->format('H:i') }}
                                </h3>
                            </div>
                        </div>

                        @php
                            $workshopShifts = $shifts[$workshop->id] ?? collect();
                            $shiftsByDate = $workshopShifts->groupBy(function($shift) { return Carbon\Carbon::parse($shift->date)->format('Y-m-d'); });
                        @endphp

                        @foreach($days as $i => $day)
                            @php
                                $dayShifts = $shiftsByDate[$day['date']] ?? collect();
                            @endphp

                            @if($dayShifts->isNotEmpty())
                                @foreach($dayShifts as $shift)
                                    @php
                                        $prevDay = $days[$i-1]['date'] ?? null;
                                        $nextDay = $days[$i+1]['date'] ?? null;
                                        $hasPrev = $prevDay && ($shiftsByDate[$prevDay] ?? collect())->where('user_id', $shift->user_id)->isNotEmpty();
                                        $hasNext = $nextDay && ($shiftsByDate[$nextDay] ?? collect())->where('user_id', $shift->user_id)->isNotEmpty();
                                        $shiftClass = 'shifts';
                                        if ($hasPrev && $hasNext) {
                                            $shiftClass = 'shifts-between';
                                        } elseif ($hasPrev) {
                                            $shiftClass = 'shifts-lower';
                                        } elseif ($hasNext) {
                                            $shiftClass = 'shifts-upper';
                                        }
                                    @endphp
                                    <div class="{{ $shiftClass }}">
                                        <div class="shifts-card bg-green-100">
                                            <div class="master-name">{{ $shift->user->formatted_name }}</div>
                                            <div class="revenue">
                                                <div class="flex justify-between">
                                                    <div>Нал:</div>
                                                    <div>{{ number_format($shift->cash_revenue, 0, ',', ' ') }} ₽</div>
                                                </div>
                                                <div class="flex justify-between">
                                                    <div>Без нал:</div>
                                                    <div>{{ number_format($shift->cashless_revenue, 0, ',', ' ') }} ₽</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="shifts"></div>
                            @endif
                        @endforeach

                        <div class="total">
                            <div class="name">{{ $workshop->name }}</div>
                            <div class="revenue">
                                {{ number_format($workshopShifts->sum('cash_revenue') + $workshopShifts->sum('cashless_revenue'), 0, ',', ' ') }} ₽
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="result flex">
                <div class="result-total">Итого:</div>
                <div class="result-total-revenue">
                    {{ number_format($shifts->flatten()->sum('cash_revenue') + $shifts->flatten()->sum('cashless_revenue'), 0, ',', ' ') }} ₽
                </div>
            </div>
        </div>
    </div>
</div> 