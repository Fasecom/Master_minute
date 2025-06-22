@php
    $percent = $data['percentFill'];
    $cycle = intdiv((int)$percent, 100);
    $fillDeg = fmod($percent, 100.0);

    if($cycle === 0){
        $bg = '#234E9B';
        $fill = '#E31E24';
    } else {
        $bg = '#E31E24';
        $fill = '#8B0000';
    }

    $gradient = "conic-gradient({$fill} {$fillDeg}%, {$bg} 0)";
@endphp

<div class="flex w-full items-end justify-between">
    <!-- Левый блок: мин/макс -->
    <div class="flex flex-col gap-2 ">
        <div class="flex items-center gap-2">
            <img src="/img/icon/chat-arrow-grow.svg" alt="max" width="23" height="23">
            <span class="h4-point-analitics">Макс выручка: <span class="h4-point-analitics">{{ number_format($data['maxDay'], 0, ',', ' ') }}₽</span></span>
        </div>
        <div class="flex items-center gap-2">
            <img src="/img/icon/chat-arrow-down.svg" alt="min" width="23" height="23">
            <span class="h4-point-analitics">Мин выручка: <span class="h4-point-analitics">{{ number_format($data['minDay'], 0, ',', ' ') }}₽</span></span>
        </div>
    </div>

    <!-- Правый блок: круг -->
    <div class="relative max-w-[340px] aspect-square w-full" style="background: {{ $gradient }}; border-radius: 50%;">
        <div class="absolute inset-[5.5%] rounded-full bg-white flex items-center justify-center text-center text-[#2E4555]">
            <div class="flex flex-col items-center">
                <span class="h1-header leading-tight">{{ number_format($data['currentRevenue'], 0, ',', ' ') }} руб</span>
                <span class="text-sm text-gray-500">{{ $data['prevPercent'] }}% от выручки за прошлый месяц</span>
            </div>
        </div>
    </div>
</div> 