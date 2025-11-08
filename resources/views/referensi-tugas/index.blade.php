<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Referensi Tugas') }}
        </h2>
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
                            Tambah Referensi Tugas
                        </x-primary-button>

                        <form action="{{ route('referensi-tugas.index') }}" method="GET" class="mb-4">
                            <x-input-label for="search" value="Cari Data" class="sr-only" />
                            <div class="flex">
                                <x-text-input id="search" name="search" type="text" class="mt-1 block w-full"
                                    placeholder="Cari deskripsi tugas..." value="{{ $search ?? '' }}" />
                                <x-primary-button class="ml-3 mt-1">Cari</x-primary-button>
                            </div>
                        </form>

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
                                            Deskripsi Tugas</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($referensiTugas as $tugas)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-pre-wrap">{{ $tugas->deskripsi_tugas }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($tugas->status)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Tidak Aktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                                <x-secondary-button x-data=""
                                                    x-on:click.prevent="
                                                        $dispatch('open-modal', 'edit-modal');
                                                        editData = {{ $tugas }};
                                                        editFormAction = '{{ route('referensi-tugas.update', $tugas->id) }}';
                                                    ">
                                                    Edit
                                                </x-secondary-button>

                                                <x-danger-button x-data=""
                                                    x-on:click.prevent="
                                                        $dispatch('open-modal', 'delete-modal');
                                                        deleteId = {{ $tugas->id }};
                                                    "
                                                    class="ml-2">
                                                    Delete
                                                </x-danger-button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center">
                                                @if ($search)
                                                    Data tidak ditemukan untuk pencarian "{{ $search }}".
                                                @else
                                                    Data masih kosong.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $referensiTugas->links() }}
                        </div>


                        <x-modal name="create-modal" :show="$errors->any() && old('form_type') === 'create'" focusable>
                            <form method="POST" action="{{ route('referensi-tugas.store') }}" class="p-6">
                                @csrf
                                <input type="hidden" name="form_type" value="create">

                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tambah Referensi Tugas
                                    Baru</h2>
                                <div class="mt-6">
                                    <x-input-label for="deskripsi_tugas" value="Deskripsi Tugas" />
                                    <textarea id="deskripsi_tugas" name="deskripsi_tugas" rows="3"
                                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
                                        required>{{ old('deskripsi_tugas') }}</textarea>
                                    <x-input-error :messages="$errors->get('deskripsi_tugas')" class="mt-2" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="status" value="Status" />
                                    <select id="status" name="status"
                                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="0" {{ old('status', '0') == '0' ? 'selected' : '' }}>Tidak
                                            Aktif</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
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

                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit Data Referensi
                                    Tugas</h2>
                                <div class="mt-6">
                                    <x-input-label for="edit_deskripsi_tugas" value="Deskripsi Tugas" />
                                    <textarea id="edit_deskripsi_tugas" name="deskripsi_tugas" rows="3" x-model="editData.deskripsi_tugas"
                                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
                                        required></textarea>
                                    <x-input-error :messages="$errors->get('deskripsi_tugas')" class="mt-2" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="edit_status" value="Status" />
                                    <select id="edit_status" name="status" x-model="editData.status"
                                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">
                                        <option value="1">Aktif</an>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                    <x-primary-button class="ml-3">Update</x-primary-button>
                                </div>
                            </form>
                        </x-modal>

                        <x-modal name="delete-modal" focusable max-width="md">
                            <form method="POST" :action="`/referensi-tugas/${deleteId}`" class="p-6">
                                @csrf
                                @method('DELETE')
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Apakah Anda yakin ingin menghapus data ini?
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Data yang sudah dihapus tidak dapat dikembalikan.
                                </p>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                    <x-danger-button class="ml-3">Hapus Data</x-danger-button>
                                </div>
                            </form>
                        </x-modal>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
