<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Grup Soal TPA (Bank Soal)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div x-data="{ editData: {}, deleteId: null, editFormAction: '' }">
                        <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-modal')" class="mb-4">
                            Tambah Grup Soal Baru
                        </x-primary-button>

                        @if (session('success')) <div class="mb-4 p-4 bg-green-100 ...">{{ session('success') }}</div> @endif
                        @if ($errors->any()) <div class="mb-4 p-4 bg-red-100 ...">... (Loop errors) ...</div> @endif

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y ...">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 ...">Nama Grup</th>
                                        <th class="px-6 py-3 ...">Deskripsi</th>
                                        <th class="px-6 py-3 ...">Jumlah Soal</th>
                                        <th class="px-6 py-3 ...">Status</th>
                                        <th class="px-6 py-3 ...">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white ...">
                                    @forelse ($grups as $grup)
                                        <tr>
                                            <td class="px-6 py-4 ...">{{ $grup->nama_grup }}</td>
                                            <td class="px-6 py-4 ...">{{ Str::limit($grup->deskripsi, 50) }}</td>
                                            <td class="px-6 py-4 ...">{{ $grup->tpa_soals_count }} Soal</td>
                                            <td class="px-6 py-4 ...">
                                                @if ($grup->status_aktif) <span class="... bg-green-100 text-green-800">Aktif</span>
                                                @else <span class="... bg-red-100 text-red-800">Non-Aktif</span> @endif
                                            </td>
                                            <td class="px-6 py-4 ... text-right text-sm">
                                                <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-modal'); editData = {{ $grup }}; editFormAction = '{{ route('tpa-grup-soal.update', $grup->id) }}';">
                                                    Edit
                                                </x-secondary-button>
                                                <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'delete-modal'); deleteId = {{ $grup->id }};" class="ml-2">
                                                    Hapus
                                                </x-danger-button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-6 py-4 text-center">Data Grup Soal masih kosong.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $grups->links() }}</div>

                        <x-modal name="create-modal" :show="$errors->any() && old('form_type') === 'create'" focusable>
                            <form method="POST" action="{{ route('tpa-grup-soal.store') }}" class="p-6">
                                @csrf
                                <input type="hidden" name="form_type" value="create">
                                <h2 class="text-lg ...">Tambah Grup Soal</h2>
                                <div class="mt-6">
                                    <x-input-label for="nama_grup" value="Nama Grup" />
                                    <x-text-input id="nama_grup" name="nama_grup" type="text" class="mt-1 block w-full" :value="old('nama_grup')" required />
                                    <x-input-error :messages="$errors->get('nama_grup')" class="mt-2" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="deskripsi" value="Deskripsi (Opsional)" />
                                    <textarea id="deskripsi" name="deskripsi" rows="3" class="border-gray-300 ... mt-1 block w-full">{{ old('deskripsi') }}</textarea>
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="status_aktif" value="Status" />
                                    <select id="status_aktif" name="status_aktif" class="border-gray-300 ... mt-1 block w-full">
                                        <option value="1" {{ old('status_aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                                    </select>
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                    <x-primary-button class="ml-3">Simpan</x-primary-button>
                                </div>
                            </form>
                        </x-modal>

                        <x-modal name="edit-modal" :show="$errors->any() && old('form_type') === 'edit'" focusable>
                            <form method="POST" :action="editFormAction" class="p-6">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="form_type" value="edit">
                                <h2 class="text-lg ...">Edit Grup Soal</h2>
                                <div class="mt-6">
                                    <x-input-label for="edit_nama_grup" value="Nama Grup" />
                                    <x-text-input id="edit_nama_grup" name="nama_grup" type="text" x-model="editData.nama_grup" class="mt-1 block w-full" required />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="edit_deskripsi" value="Deskripsi (Opsional)" />
                                    <textarea id="edit_deskripsi" name="deskripsi" rows="3" x-model="editData.deskripsi" class="border-gray-300 ... mt-1 block w-full"></textarea>
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="edit_status_aktif" value="Status" />
                                    <select id="edit_status_aktif" name="status_aktif" x-model="editData.status_aktif" class="border-gray-300 ... mt-1 block w-full">
                                        <option value="1">Aktif</option>
                                        <option value="0">Non-Aktif</option>
                                    </select>
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                    <x-primary-button class="ml-3">Update</x-primary-button>
                                </div>
                            </form>
                        </x-modal>

                        <x-modal name="delete-modal" focusable max-width="md">
                            <form method="POST" :action="`/tpa-grup-soal/${deleteId}`" class="p-6">
                                @csrf
                                @method('DELETE')
                                <h2 class="text-lg ...">Hapus Grup Soal?</h2>
                                <p class="mt-1 ...">Semua soal di dalam grup ini akan ikut terhapus permanen.</p>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                    <x-danger-button class="ml-3">Hapus</x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
