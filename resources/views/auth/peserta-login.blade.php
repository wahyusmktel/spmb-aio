<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('peserta.login') }}">
        @csrf
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-200">Login Peserta Seleksi</h2>
        <p class="text-center text-gray-600 dark:text-gray-400 mb-4">Gunakan akun CBT yang Anda dapatkan.</p>

        <x-input-error :messages="$errors->get('username')" class="mt-2" />

        <div>
            <x-input-label for="username" value="Username" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')"
                required autofocus />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded ..." name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Log In') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
