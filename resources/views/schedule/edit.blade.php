<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col gap-4">
            <!-- Первая строка -->
            <div class="flex flex-wrap items-center gap-4">
                <h1 class="h1-header">График смен – редактирование</h1>
            </div>
            <!-- Вторая строка: фильтр слева, кнопки справа -->
            <div class="flex flex-wrap items-center justify-between gap-4 w-full">
                <!-- Фильтр по дате -->
                <livewire:month-year-filter />
                <!-- Кнопки -->
                <div class="flex items-center gap-2 ml-auto">
                    <a href="{{ route('schedule') }}" class="btn-leval3 btn-red flex items-center justify-center w-[60px] h-[44px] rounded-full p-0" title="Назад">
                        <img src="/img/icon/undo.svg" alt="Назад" width="24" height="24">
                    </a>
                    <button id="save-shifts-btn" type="button" class="btn-leval3 btn-blue flex items-center justify-center w-[60px] h-[44px] rounded-full p-0" title="Сохранить">
                        <img src="/img/icon/check.svg" alt="Сохранить" width="24" height="24">
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Контент: таблица + панель мастеров -->
    <div class="flex max-w-screen-2xl mx-auto py-8 ">
        <!-- Таблица -->
        <div class="flex-1 w-full">
            <livewire:schedule-table-edit />
        </div>
        <div class="bg-white overflow-auto shadow-xl sm:rounded-lg p-4 w-[210px] mr-8">
            <livewire:masters-panel />
        </div>
    </div>

    <!-- Скрипт перенесён в resources/js/schedule-edit.js и подключается через Vite -->
</x-app-layout> 