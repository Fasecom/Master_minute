<x-guest-layout>
    <div class="flex justify-center items-center min-h-screen w-full bg-white">
        <div class="flex flex-row w-[800px] h-[380px] bg-transparent rounded-[20px] overflow-hidden"
             style="box-shadow: 0px 5px 27.2px 0px rgba(46, 69, 85, 0.25);">
            <!-- Левая панель -->
            <form method="POST" action="{{ route('login') }}" class="flex flex-col bg-white w-1/2 p-8 gap-4" style="min-width:320px;">
                @csrf
                <div class="flex flex-col gap-2">
                    <label for="phone" class="h3-point">Телефон</label>
                    <input id="phone" name="phone" type="tel" placeholder="login" class="input-header w-full" value="{{ old('phone') }}" required autofocus autocomplete="tel">
                    @error('phone')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label for="password" class="h3-point">Пароль</label>
                    <input id="password" name="password" type="password" placeholder="****************" class="input-header w-full" required autocomplete="current-password">
                    @error('password')<div class="text-red-500 text-sm mt-[-10px] mb-2">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn-leval1 w-full mt-[70px]">Войти</button>
            </form>
            <!-- Правая панель -->
            <div class="flex flex-col bg-[#234E9B] w-1/2 h-full p-8" style="min-width:320px; border-radius: 20px;">
                <span class="h1-header text-white">Вход</span>
            </div>
        </div>
    </div>
</x-guest-layout>
