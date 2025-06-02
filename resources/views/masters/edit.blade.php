<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col gap-4">
            <div class="flex flex-wrap items-center justify-between">
                <h1 class="h1-header">Мастера</h1>
                <div class="flex items-center gap-4 ml-auto">
                    <a href="{{ route('masters') }}" class="btn-leval3 btn-red">
                        <img src="/img/icon/undo.svg" alt="Назад" width="24" height="24">
                    </a>
                </div>
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <form method="POST" action="{{ route('masters.update', $master->id) }}" class="flex flex-col gap-5 bg-white rounded-[21px] shadow-[0_5px_27px_rgba(46,69,85,0.25)] min-h-[340px] w-full" id="edit-master-form">
                @csrf
                <!-- Первый див: левая и правая часть -->
                <div class="flex flex-row gap-8">
                    <!-- Левая часть -->
                    <div class="flex flex-col gap-5 w-1/2 p-10" style="padding:30px;">
                        <div class="flex flex-col gap-2">
                            <!-- ФИО -->
                            <div class="flex items-center gap-2">
                                <img src="/img/icon/letter-case.svg" alt="fio" width="23" height="23">
                                <h3 class="h3-point">ФИО</h3>
                            </div>
                            <input type="text" name="full_name" class="input-header w-full mb-1" placeholder="Иван Иванов" value="{{ old('full_name', $master->full_name) }}" required autocomplete="off">
                            @error('full_name')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                            <!-- Дата начала работы -->
                            <div class="flex items-center gap-2">
                                <img src="/img/icon/calendar.svg" alt="date" width="23" height="23">
                                <h3 class="h3-point">Дата начала работы</h3>
                            </div>
                            <input type="date" name="work_start_date" class="input-header w-full mb-1" value="{{ old('work_start_date', $master->work_start_date ? $master->work_start_date->format('Y-m-d') : date('Y-m-d')) }}" required autocomplete="off">
                            @error('work_start_date')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                            <!-- Телефон -->
                            <div class="flex items-center gap-2">
                                <img src="/img/icon/call.svg" alt="phone" width="23" height="23">
                                <h3 class="h3-point">Номер телефона</h3>
                            </div>
                            <input type="text" name="phone" class="input-header w-full mb-1" placeholder="+7 000 000 00 00" value="{{ old('phone', '+7 '.substr($master->phone, 1, 3).' '.substr($master->phone, 4, 3).' '.substr($master->phone, 7, 2).' '.substr($master->phone, 9, 2)) }}" required id="phone-input" autocomplete="off">
                            @error('phone')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                            <!-- Пароль -->
                            <div class="flex items-center gap-2">
                                <img src="/img/icon/lock.svg" alt="password" width="23" height="23">
                                <h3 class="h3-point">Пароль</h3>
                            </div>
                            <div class="relative">
                                <input type="password" name="password" class="input-header w-full mb-1 pr-10" placeholder="*********" id="password-input" autocomplete="new-password">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2" onclick="togglePassword()">
                                    <img src="/img/icon/eye.svg" alt="Показать" id="eye-icon" width="24" height="24" class="eye-icon">
                                </span>
                            </div>
                            @error('password')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                            <!-- Навыки -->
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <img src="/img/icon/key.svg" alt="skills" width="23" height="23">
                                    <h3 class="h3-point">Навыки</h3>
                                    <a href="{{ route('masters.skills.edit', ['back' => url()->current()]) }}" class="group" style="display:inline-flex;align-items:center;">
                                        <img src="/img/icon/pencil.svg" alt="Редактировать навыки" width="14" height="14" style="filter: invert(14%) sepia(0%) saturate(0%) hue-rotate(180deg) brightness(93%) contrast(90%); opacity:0.7; transition:opacity .2s, filter .2s;" onmouseover="this.style.opacity=1;this.style.filter='invert(14%) sepia(0%) saturate(0%) hue-rotate(180deg) brightness(60%) contrast(120%)';" onmouseout="this.style.opacity=0.7;this.style.filter='invert(14%) sepia(0%) saturate(0%) hue-rotate(180deg) brightness(93%) contrast(90%)';">
                                    </a>
                                </div>
                                <div class="flex flex-wrap gap-x-4">
                                    @foreach($skills->chunk(3) as $chunk)
                                        <div class="flex flex-col">
                                            @foreach($chunk as $skill)
                                                <label class="flex items-center gap-2 mb-1">
                                                    <input type="checkbox" name="skills[]" value="{{ $skill->id }}" {{ (is_array(old('skills', $userSkills)) && in_array($skill->id, old('skills', $userSkills))) ? 'checked' : '' }}>
                                                    {{ $skill->name }}
                                                </label>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                                @error('skills')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <!-- Правая часть -->
                    <div class="flex flex-col justify-between bg-[#234E9B] rounded-[16px] p-10 flex-1" style="padding:30px;">
                        <div class="flex justify-between items-center mb-4">
                            <span class="h1-header" style="color:#fff;">Редактирование</span>
                            <div></div>
                        </div>
                        <div class="flex justify-end gap-2 mt-auto">
                            <button type="button" class="btn-leval4 btn-red" onclick="showModal('delete')"><img src="/img/icon/trash.svg" alt="Удалить" width="20" height="20"></button>
                            <button type="button" class="btn-leval4 btn-blue" onclick="showModal('save')"><img src="/img/icon/check.svg" alt="Сохранить" width="20" height="20"></button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Модалка подтверждения сохранения -->
            <div id="modal-save" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-xl p-8 min-w-[320px] flex flex-col items-center">
                    <div class="mb-4 text-xl font-semibold text-[#234E9B]">Сохранить изменения?</div>
                    <div class="flex gap-4 mt-2">
                        <button class="btn-leval4 btn-blue" onclick="submitEditForm()">Да</button>
                        <button class="btn-leval4 btn-red" onclick="closeModal('save')">Нет</button>
                    </div>
                </div>
            </div>
            <!-- Модалка подтверждения удаления -->
            <div id="modal-delete" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-xl p-8 min-w-[320px] flex flex-col items-center">
                    <div class="mb-4 text-xl font-semibold text-[#DE5B5F]">Удалить пользователя?</div>
                    <div class="flex gap-4 mt-2">
                        <button class="btn-leval4 btn-blue" onclick="submitDeleteForm()">Да</button>
                        <button class="btn-leval4 btn-red" onclick="closeModal('delete')">Нет</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/phone-mask.js"></script>
    <script>
    function togglePassword() {
        const input = document.getElementById('password-input');
        const icon = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.src = '/img/icon/eye-crossed.svg';
            icon.classList.add('active');
        } else {
            input.type = 'password';
            icon.src = '/img/icon/eye.svg';
            icon.classList.remove('active');
        }
    }
    function showModal(type) {
        document.getElementById('modal-' + type).classList.remove('hidden');
    }
    function closeModal(type) {
        document.getElementById('modal-' + type).classList.add('hidden');
    }
    function submitEditForm() {
        document.getElementById('edit-master-form').submit();
    }
    function submitDeleteForm() {
        // Создаём и отправляем форму для удаления
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('masters.delete', $master->id) }}';
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
    </script>
</x-app-layout> 