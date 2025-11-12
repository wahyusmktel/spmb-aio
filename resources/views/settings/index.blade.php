<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Setting Aplikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('settings.update-logo') }}" method="POST" enctype="multipart/form-data"
                    class="p-6">
                    @csrf
                    <h3 class="text-lg font-medium text-gray-900">Upload Logo Aplikasi</h3>
                    <p class="text-sm text-gray-600 mt-1">Gunakan file .png transparan dengan tinggi 36px (h-9).</p>

                    @if ($logoSetting && $logoSetting->value)
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-700">Logo Saat Ini:</p>
                            <img src="{{ Storage::url($logoSetting->value) }}" alt="Logo"
                                class="h-9 w-auto mt-2 bg-gray-200 p-1 rounded">
                        </div>
                    @endif

                    <div class="mt-4">
                        <x-input-label for="logo_upload" value="Pilih Logo Baru (Max 1MB)" />
                        <input id="logo_upload" name="logo_upload" type="file" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                            required />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-primary-button>Simpan Logo</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
