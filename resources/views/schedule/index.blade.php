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
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <img src="/img/Tabel34.svg" alt="">
            </div>
        </div>
    </div>
</x-app-layout> 