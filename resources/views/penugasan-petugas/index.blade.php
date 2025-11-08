<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tetapkan Petugas untuk:') }} <span class="font-bold">{{ $jadwal->judul_kegiatan }}</span>
        </h2>
        <a href="{{ route('jadwal-seleksi.index') }}" class="text-sm text-blue-500 hover:underline">&larr; Kembali ke
            Jadwal Seleksi</a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div x-data="{
                        editData: {},
                        deleteId: null,
                        editFormAction: ''
                    }">

                        <x-primary-button x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'create-modal')" class="mb-4">
                            Tambah Petugas
                        </x-primary-button>

                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                                {{ session('success') }}
                            </div>
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

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nama Guru</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Peran Tugas</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($penugasan as $tugas)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $tugas->guru->nama_guru ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-pre-wrap">
                                                {{ $tugas->referensiTugas->deskripsi_tugas ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                                <x-secondary-button x-data=""
                                                    x-on:click.prevent="
                                                        $dispatch('open-modal', 'edit-modal');
                                                        editData = {
                                                            id: {{ $tugas->id }},
                                                            id_referensi_tugas: {{ $tugas->id_referensi_tugas }}
                                                        };
                                                        editFormAction = '{{ route('penugasan.update', $tugas->id) }}';
                                                    ">
                                                    Edit Tugas
                                                </x-secondary-button>

                                                <x-danger-button x-data=""
                                                    x-on:click.prevent="
                                                        $dispatch('open-modal', 'delete-modal');
                                                        deleteId = {{ $tugas->id }};
                                                    "
                                                    class="ml-2">
                                                    Hapus
                                                </x-danger-button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center">
                                                Belum ada petugas yang ditugaskan untuk jadwal ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                        <x-modal name="create-modal" :show="$errors->any() && old('form_type') === 'create'" focusable>
                            <form method="POST" action="{{ route('penugasan.store', $jadwal->id) }}" class="p-6">
                                @csrf
                                <input type="hidden" name="form_type" value="create">

                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tambah Petugas Baru
                                </h2>
                                <div class="mt-6">
                                    <x-input-label for="id_guru" value="Pilih Guru" />
                                    <select id="id_guru" name="id_guru"
                                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
                                        required>
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach ($gurus as $guru)
                                            <option value="{{ $guru->id }}"
                                                {{ old('id_guru') == $guru->id ? 'selected' : '' }}>
                                                {{ $guru->nama_guru }} (NIP: {{ $guru->nip }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('id_guru')" class="mt-2" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="id_referensi_tugas" value="Pilih Peran Tugas (Aktif)" />
                                    <select id="id_referensi_tugas" name="id_referensi_tugas"
                                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
                                        required>
                                        <option value="">-- Pilih Tugas --</option>
                                        @foreach ($referensiTugas as $tugas)
                                            <option value="{{ $tugas->id }}"
                                                {{ old('id_referensi_tugas') == $tugas->id ? 'selected' : '' }}>
                                                {{ $tugas->deskripsi_tugas }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('id_referensi_tugas')" class="mt-2" />
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

                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit Peran Tugas</h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Anda hanya dapat mengubah peran tugas untuk petugas ini.
                                </p>
                                <div class="mt-6">
                                    <x-input-label for="edit_id_referensi_tugas" value="Pilih Peran Tugas (Aktif)" />
                                    <select id="edit_id_referensi_tugas" name="id_referensi_tugas"
                                        x-model="editData.id_referensi_tugas"
                                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
                                        required>
                                        <option value="">-- Pilih Tugas --</option>
                                        @foreach ($referensiTugas as $tugas)
                                            <option value="{{ $tugas->id }}">{{ $tugas->deskripsi_tugas }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('id_referensi_tugas')" class="mt-2" />
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                    <x-primary-button class="ml-3">Update</x-primary-button>
                                </div>
                            </form>
                        </x-modal>

                        <x-modal name="delete-modal" focusable max-width="md">
                            <form method="POST" :action="`/penugasan-petugas/${deleteId}`" class="p-6">
                                @csrf
                                @method('DELETE')
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Apakah Anda yakin ingin menghapus petugas ini?
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Penugasan petugas ini akan dibatalkan dari jadwal seleksi.
                                </p>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                    <x-danger-button class="ml-3">Hapus Penugasan</x-danger-button>
                                </div>
                            </form>
                        </x-modal>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
