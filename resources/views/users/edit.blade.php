<x-app-layout>
    <x-slot name="header">
        <!-- 'dark:text-gray-200' dihapus -->
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <!-- 'x-data' dipindah ke div pembungkus utama -->
    <div class="py-12" x-data="{ selectedRoles: {{ $user->roles->pluck('name') }} }">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- 'dark:bg-gray-800' dihapus -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="name" value="Nama" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            :value="old('name', $user->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                            :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" value="Password Baru (Opsional)" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                            placeholder="Kosongkan jika tidak diubah" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                            class="mt-1 block w-full" placeholder="Kosongkan jika tidak diubah" />
                    </div>

                    <!-- 'dark:border-gray-700' dihapus -->
                    <hr class="border-gray-200">

                    <div>
                        <x-input-label value="Assign Roles" />
                        <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($roles as $role)
                                <div>
                                    <label class="flex items-center">
                                        <!-- 'dark:bg-gray-900' dan '...' dihapus -->
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                            x-model="selectedRoles"
                                            class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                        <!-- 'dark:text-gray-400' dihapus -->
                                        <span class="ml-2 text-sm text-gray-600">{{ $role->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                    </div>

                    <!-- x-show dan style="display: none;" (untuk jaga-jaga) -->
                    <div x-show="selectedRoles.includes('Guru')" style="display: none;">
                        <!-- 'dark:border-gray-700' dihapus -->
                        <hr class="border-gray-200">
                        <x-input-label for="id_guru" value="Mapping Akun ke Data Guru" class="mt-4" />
                        <!-- 'dark:text-gray-400' dihapus -->
                        <p class="text-sm text-gray-600">Pilih data guru untuk di-link ke akun
                            user ini.</p>
                        <!-- '...' dan 'dark:...' dihapus dari <select> -->
                        <select id="id_guru" name="id_guru"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option value="">-- Tidak di-link --</option>
                            @foreach ($availableGurus as $guru)
                                <option value="{{ $guru->id }}"
                                    {{ old('id_guru', $user->guru->id ?? null) == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama_guru }} (NIP: {{ $guru->nip }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('id_guru')" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('users.index') }}"><x-secondary-button
                                type="button">Batal</x-secondary-button></a>
                        <x-primary-button>Update User</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
