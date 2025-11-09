<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Referensi Guru') }}
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

                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <x-primary-button x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'create-modal')">
                                    Tambah Guru
                                </x-primary-button>

                                <x-secondary-button x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'import-modal')"
                                    class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white dark:text-gray-100">
                                    Import Excel
                                </x-secondary-button>
                            </div>
                            <form action="{{ route('guru.bulk-create') }}" method="POST"
                                onsubmit="return confirm('Anda akan membuat akun untuk SEMUA guru yang belum memiliki akun (dan sudah memiliki email). Lanjutkan?');">
                                @csrf
                                <x-primary-button type="submit" class="bg-blue-600 hover:bg-blue-700">
                                    Bulk Create Account
                                </x-primary-button>
                            </form>
                        </div>

                        <form action="{{ route('guru.index') }}" method="GET" class="mb-4">
                            <x-input-label for="search" value="Cari Data Guru" class="sr-only" />
                            <div class="flex">
                                <x-text-input id="search" name="search" type="text" class="mt-1 block w-full"
                                    placeholder="Cari berdasarkan NIP, Nama, atau Mapel..."
                                    value="{{ $search ?? '' }}" />
                                <x-primary-button class="ml-3 mt-1">Cari</x-primary-button>
                            </div>
                        </form>


                        @if (session('import_errors'))
                            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                                <strong>Gagal mengimpor beberapa data:</strong>
                                <ul class="list-disc list-inside mt-2">
                                    @foreach (session('import_errors') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any() && !session('import_errors'))
                            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        @if (!Str::contains($error, 'file import'))
                                            <li>{{ $error }}</li>
                                        @endif
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
                                            NIP</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nama</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Email (Akun)</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Mata Pelajaran</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($gurus as $guru)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $guru->nip }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $guru->nama_guru }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($guru->user)
                                                    <span
                                                        class="font-bold text-green-600 dark:text-green-400">{{ $guru->user->email }}</span>
                                                    (Terhubung)
                                                @elseif ($guru->email)
                                                    <span
                                                        class="text-yellow-600 dark:text-yellow-400">{{ $guru->email }}</span>
                                                    (Belum Dibuat Akun)
                                                @else
                                                    <span class="text-red-500 italic">(Email Kosong)</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $guru->mata_pelajaran }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <x-secondary-button x-data=""
                                                    x-on:click.prevent="
                                                        $dispatch('open-modal', 'edit-modal');
                                                        editData = {{ $guru }};
                                                        editFormAction = '{{ route('guru.update', $guru->id) }}';
                                                    ">
                                                    Edit
                                                </x-secondary-button>
                                                <x-danger-button x-data=""
                                                    x-on:click.prevent="
                                                        $dispatch('open-modal', 'delete-modal');
                                                        deleteId = {{ $guru->id }};
                                                    "
                                                    class="ml-2">
                                                    Delete
                                                </x-danger-button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center">
                                                @if ($search)
                                                    Data guru tidak ditemukan untuk pencarian "{{ $search }}".
                                                @else
                                                    Data guru masih kosong.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $gurus->links() }}
                        </div>


                        <x-modal name="import-modal" :show="$errors->has('file_import')" focusable>
                            <form method="POST" action="{{ route('guru.import') }}" class="p-6"
                                enctype="multipart/form-data">
                                @csrf
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Import Data Guru</h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Upload file Excel (xlsx, xls, csv) dengan header: <strong>nip, nama_guru,
                                        mata_pelajaran</strong>.
                                </p>
                                <div class="mt-6">
                                    <x-input-label for="file_import" value="File Excel" />
                                    <input id="file_import" name="file_import" type="file"
                                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        required />
                                    <x-input-error :messages="$errors->get('file_import')" class="mt-2" />
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                    <x-primary-button
                                        class="ml-3 bg-green-500 hover:bg-green-600">Import</x-primary-button>
                                </div>
                            </form>
                        </x-modal>
                        <x-modal name="create-modal" :show="$errors->any() && old('form_type') === 'create'" focusable>
                            <form method="POST" action="{{ route('guru.store') }}" class="p-6">
                                @csrf
                                <input type="hidden" name="form_type" value="create">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tambah Guru Baru</h2>
                                <div class="mt-6">
                                    <x-input-label for="nip" value="NIP" />
                                    <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full"
                                        :value="old('nip')" required />
                                    <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="nama_guru" value="Nama Guru" />
                                    <x-text-input id="nama_guru" name="nama_guru" type="text"
                                        class="mt-1 block w-full" :value="old('nama_guru')" required />
                                    <x-input-error :messages="$errors->get('nama_guru')" class="mt-2" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="email" value="Email (Untuk Akun Login)" />
                                    <x-text-input id="email" name="email" type="email"
                                        class="mt-1 block w-full" :value="old('email')" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="mata_pelajaran" value="Mata Pelajaran" />
                                    <x-text-input id="mata_pelajaran" name="mata_pelajaran" type="text"
                                        class="mt-1 block w-full" :value="old('mata_pelajaran')" required />
                                    <x-input-error :messages="$errors->get('mata_pelajaran')" class="mt-2" />
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
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit Data Guru</h2>
                                <div class="mt-6">
                                    <x-input-label for="edit_nip" value="NIP" />
                                    <x-text-input id="edit_nip" name="nip" type="text"
                                        class="mt-1 block w-full" x-model="editData.nip" required />
                                    <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="edit_nama_guru" value="Nama Guru" />
                                    <x-text-input id="edit_nama_guru" name="nama_guru" type="text"
                                        class="mt-1 block w-full" x-model="editData.nama_guru" required />
                                    <x-input-error :messages="$errors->get('nama_guru')" class="mt-2" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="edit_email" value="Email (Untuk Akun Login)" />
                                    <x-text-input id="edit_email" name="email" type="email"
                                        class="mt-1 block w-full" x-model="editData.email" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="edit_mata_pelajaran" value="Mata Pelajaran" />
                                    <x-text-input id="edit_mata_pelajaran" name="mata_pelajaran" type="text"
                                        class="mt-1 block w-full" x-model="editData.mata_pelajaran" required />
                                    <x-input-error :messages="$errors->get('mata_pelajaran')" class="mt-2" />
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                    <x-primary-button class="ml-3">Update</x-primary-button>
                                </div>
                            </form>
                        </x-modal>

                        <x-modal name="delete-modal" focusable max-width="md">
                            <form method="POST" :action="`/guru/${deleteId}`" class="p-6">
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
