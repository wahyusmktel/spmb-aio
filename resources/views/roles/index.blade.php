<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg ...">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg ...">{{ session('error') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <form action="{{ route('roles.store') }}" method="POST" class="p-6">
                    @csrf
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tambah Role Baru</h3>
                    <div class="mt-4 flex items-center space-x-4">
                        <div class="flex-grow">
                            <x-input-label for="name" value="Nama Role (Case sensitive)" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <x-primary-button class="mt-5">Simpan</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 ...">Nama Role</th>
                                <th class="px-6 py-3 ...">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white ...">
                            @foreach ($roles as $role)
                                <tr>
                                    <td class="px-6 py-4 ...">{{ $role->name }}</td>
                                    <td class="px-6 py-4 ... text-right">
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus role ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button type="submit">Hapus</x-danger-button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
