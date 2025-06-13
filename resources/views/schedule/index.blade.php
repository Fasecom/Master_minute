<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col gap-4">
            <!-- Верхняя полоса -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="h1-header">График смен</h1>
                <!-- Кнопка «Внести выручку» (пока без логики) -->
                <button type="button" class="btn-leval1 flex items-center justify-center w-[200px] h-[44px] ml-auto">
                    Внести выручку
                </button>
            </div>
            <!-- Нижняя полоса с фильтрами -->
            <div class="flex flex-wrap gap-4 items-center">
                <livewire:masters-filter />
                <livewire:shops-filter />
                <div class="ml-auto">
                    <livewire:month-year-filter />
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Таблица -->
    <livewire:schedule-table />
</x-app-layout> 