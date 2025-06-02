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
            <form method="POST" action="{{ route('shops.services.update') }}" id="services-form" class="flex flex-row min-h-[340px] w-full bg-white rounded-[21px] shadow-[0_5px_27px_rgba(46,69,85,0.25)]">
                @csrf
                <input type="hidden" name="back" value="{{ request('back', route('shops')) }}">
                <!-- Левая часть -->
                <div class="flex flex-col gap-5 w-1/2 p-10" style="padding:30px;">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <img src="/img/icon/letter-case.svg" alt="fio" width="23" height="23">
                            <h3 class="h3-point">Добавление услуги</h3>
                        </div>
                        <div class="flex flex-row mb-4 w-full gap-2">
                            <input type="text" id="new-service-input" class="input-header flex-1 rounded-l-full rounded-r-none" placeholder="Добавить услугу..." autocomplete="off">
                            <button type="button" class="btn-leval1 rounded-r-full rounded-l-none h-[40px] w-[70px] flex items-center justify-center" onclick="addService()">
                                <img src="/img/icon/plus.svg" alt="Добавить" width="24" height="24">
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            <img src="/img/icon/key.svg" alt="services" width="23" height="23">
                            <h3 class="h3-point">Список услуг</h3>
                        </div>
                        <ul id="services-list" class="flex flex-col gap-2">
                            @foreach($services as $service)
                                <li class="flex items-center gap-2 service-item">
                                    <input type="hidden" name="services[{{ $loop->index }}][id]" value="{{ $service->id }}">
                                    <input type="text" name="services[{{ $loop->index }}][name]" value="{{ $service->name }}" class="input-header flex-1" autocomplete="off">
                                    <button type="button" class="btn-leval4" onclick="removeService(this)"><img src="/img/icon/trash.svg" alt="Удалить" width="16" height="16" style="filter: invert(32%) sepia(99%) saturate(7492%) hue-rotate(352deg) brightness(97%) contrast(101%); opacity:0.85; transition:opacity .2s, filter .2s;" onmouseover="this.style.opacity=1;this.style.filter='invert(32%) sepia(99%) saturate(7492%) hue-rotate(352deg) brightness(80%) contrast(120%)';" onmouseout="this.style.opacity=0.85;this.style.filter='invert(32%) sepia(99%) saturate(7492%) hue-rotate(352deg) brightness(97%) contrast(101%)';"></button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Правая часть -->
                <div class="flex flex-col justify-between bg-[#234E9B] rounded-[16px] p-10 flex-1" style="padding:30px;">
                    <div class="flex justify-between items-center mb-4">
                        <span class="h1-header" style="color:#fff;">Редактирование услуг</span>
                        <div></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-auto">
                        <button type="button" class="btn-leval4 btn-red" onclick="window.history.back()"><img src="/img/icon/undo.svg" alt="Назад" width="20" height="20"></button>
                        <button type="button" class="btn-leval4 btn-blue" onclick="showSaveModal()"><img src="/img/icon/check.svg" alt="Сохранить" width="20" height="20"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Модалка подтверждения сохранения -->
    <div id="modal-save" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-8 min-w-[320px] flex flex-col items-center">
            <div class="mb-4 text-xl font-semibold text-[#234E9B]">Сохранить изменения?</div>
            <div class="flex gap-4 mt-2">
                <button class="btn-leval4 btn-blue" onclick="submitServicesForm()">Да</button>
                <button class="btn-leval4 btn-red" onclick="closeSaveModal()">Нет</button>
            </div>
        </div>
    </div>
    <script>
        function addService() {
            const input = document.getElementById('new-service-input');
            const value = input.value.trim();
            if (!value) return;
            const list = document.getElementById('services-list');
            const index = list.children.length;
            const li = document.createElement('li');
            li.className = 'flex items-center gap-2 service-item';
            li.innerHTML = `<input type="hidden" name="services[${index}][id]" value="">
                <input type="text" name="services[${index}][name]" value="${value}" class="input-header flex-1" autocomplete="off">
                <button type="button" class="btn-leval4" onclick="removeService(this)"><img src="/img/icon/trash.svg" alt="Удалить" width="16" height="16" style="filter: invert(32%) sepia(99%) saturate(7492%) hue-rotate(352deg) brightness(97%) contrast(101%); opacity:0.85; transition:opacity .2s, filter .2s;" onmouseover="this.style.opacity=1;this.style.filter='invert(32%) sepia(99%) saturate(7492%) hue-rotate(352deg) brightness(80%) contrast(120%)';" onmouseout="this.style.opacity=0.85;this.style.filter='invert(32%) sepia(99%) saturate(7492%) hue-rotate(352deg) brightness(97%) contrast(101%)';"></button>`;
            list.appendChild(li);
            input.value = '';
        }
        function removeService(btn) {
            btn.closest('li').remove();
        }
        function showSaveModal() {
            document.getElementById('modal-save').classList.remove('hidden');
        }
        function closeSaveModal() {
            document.getElementById('modal-save').classList.add('hidden');
        }
        function submitServicesForm() {
            document.getElementById('services-form').submit();
        }
    </script>
</x-app-layout> 