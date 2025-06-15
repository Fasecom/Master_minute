<div id="masters-panel" class="shift-column" style="width: 100%;">
    <div class="sc-header">
        <div class="name">Мастера</div>
    </div>

    @foreach($masters as $master)
        @php
            $parts = explode(' ', $master->full_name);
            $fio = count($parts) >= 2 ? ($parts[0].' '.$parts[1]) : $master->full_name;
        @endphp
        <!-- Шаблон для клонирования -->
        <template id="master-card-template-{{ $master->id }}">
            <div class="shifts-card bg-blue-100 cursor-move w-full h-[40px] flex items-center pl-4 justify-start" draggable="true" data-user-id="{{ $master->id }}">
                <div class="master-name text-left w-full">{{ $fio }}</div>
            </div>
        </template>
        <!-- Ячейка с сеткой -->
        <div class="shifts h-[40px] flex items-center">
            <div class="shifts-card bg-blue-100 cursor-move w-full h-full flex items-center pl-4 justify-start" draggable="true" data-user-id="{{ $master->id }}">
                <div class="master-name text-left w-full">{{ $fio }}</div>
            </div>
        </div>
    @endforeach
</div> 