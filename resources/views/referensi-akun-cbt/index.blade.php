<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Referensi Akun CBT') }}
            @if ($tahunAktif)
                <span class="ml-2 px-2 py-1 text-sm font-medium bg-green-100 text-green-800 rounded">
                    T.A: {{ $tahunAktif->nama_tahun_pelajaran }}
                </span>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($tahunAktif)
                        <div x-data="{
                            editData: {},
                            deleteId: null,
                            editFormAction: ''
                        }">

                            <div class="flex justify-between items-center mb-4">
                                <x-primary-button x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'create-modal')">
                                    Tambah Akun
                                </x-primary-button>

                                <x-secondary-button x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'import-modal')"
                                    class="bg-green-500 hover:bg-green-600 text-white">
                                    Import Excel
                                </x-secondary-button>
                            </div>
                            <form action="{{ route('referensi-akun-cbt.index') }}" method="GET" class="mb-4">
                                <div class="flex">
                                    <x-text-input id="search" name="search" type="text" class="mt-1 block w-full"
                                        placeholder="Cari username..." value="{{ $search ?? '' }}" />
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

                            @if (session('error'))
                                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                                    {{ session('error') }}
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
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Username</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status (Login)</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Keterangan</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($referensiAkunCbt as $akun)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $akun->username }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($akun->status)
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Diblokir
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($akun->pesertaSeleksi)
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            Terpakai
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            Tersedia
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <x-secondary-button x-data=""
                                                        x-on:click.prevent="
                                                            $dispatch('open-modal', 'edit-modal');
                                                            editData = {{ $akun }};
                                                            editFormAction = '{{ route('referensi-akun-cbt.update', $akun->id) }}';
                                                        ">
                                                        Edit
                                                    </x-secondary-button>
                                                    <x-danger-button x-data=""
                                                        x-on:click.prevent="
                                                            $dispatch('open-modal', 'delete-modal');
                                                            deleteId = {{ $akun->id }};
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
                                                        Akun tidak ditemukan.
                                                    @else
                                                        Data akun CBT masih kosong.
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $referensiAkunCbt->links() }}
                            </div>

                            <x-modal name="import-modal" :show="$errors->has('file_import')" focusable>
                                <form method="POST" action="{{ route('referensi-akun-cbt.import') }}" class="p-6"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <h2 class="text-lg font-medium text-gray-900">
                                        Import Akun CBT (T.A: {{ $tahunAktif->nama_tahun_pelajaran }})
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Upload file Excel (xlsx, xls, csv) dengan header: <strong>username,
                                            password</strong>. Kolom <strong>status</strong> (0/1) opsional.
                                    </p>
                                    <div class="mt-6">
                                        <x-input-label for="file_import" value="File Excel" />
                                        <input id="file_import" name="file_import" type="file"
                                            class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
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
                                <form method="POST" action="{{ route('referensi-akun-cbt.store') }}" class="p-6">
                                    @csrf
                                    <input type="hidden" name="form_type" value="create">
                                    <h2 class="text-lg font-medium text-gray-900">Tambah Akun</h2>
                                    <div class="mt-6">
                                        <x-input-label for="username" value="Username" />
                                        <x-text-input id="username" name="username" type="text"
                                            class="mt-1 block w-full" :value="old('username')" required />
                                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                    </div>
                                    <div class="mt-4">
                                        <x-input-label for="password" value="Password" />
                                        <x-text-input id="password" name="password" type="password"
                                            class="mt-1 block w-full" required />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>
                                    <div class="mt-4">
                                        <x-input-label for="status" value="Status" />
                                        <select id="status" name="status"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                                Aktif</option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak
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
                                    <h2 class="text-lg font-medium text-gray-900">Edit Akun</h2>
                                    <div class="mt-6">
                                        <x-input-label for="edit_username" value="Username" />
                                        <x-text-input id="edit_username" name="username" type="text"
                                            class="mt-1 block w-full" x-model="editData.username" required />
                                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                    </div>
                                    <div class="mt-4">
                                        <x-input-label for="edit_password" value="Password (Baru)" />
                                        <x-text-input id="edit_password" name="password" type="password"
                                            class="mt-1 block w-full" placeholder="Kosongkan jika tidak diubah" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>
                                    <div class="mt-4">
                                        <x-input-label for="edit_status" value="Status" />
                                        <select id="edit_status" name="status" x-model="editData.status"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                            <option value="1">Aktif</option>
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
                                <form method="POST" :action="`/referensi-akun-cbt/${deleteId}`" class="p-6">
                                    @csrf
                                    @method('DELETE')
                                    <h2 class="text-lg font-medium text-gray-900">
                                        Apakah Anda yakin ingin menghapus akun ini?
                                    </h2>
                                    <div class="mt-6 flex justify-end">
                                        <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                        <x-danger-button class="ml-3">Hapus Akun</x-danger-button>
                                    </div>
                                </form>
                            </x-modal>

                        </div>
                    @else
                        <div classid="alert-warning" class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50"
                            role="alert">
                            <span class="font-medium">Perhatian!</span> Silahkan aktifkan tahun pelajaran terlebih
                            dahulu.
                            <p class="mt-2">Anda tidak dapat melihat atau menambah akun CBT jika tidak ada tahun
                                pelajaran yang disetel sebagai "Aktif".</p>
                            <a href="{{ route('tahun-pelajaran.index') }}"
                                class="font-bold underline hover:text-yellow-900">Klik di
                                sini untuk pergi ke halaman Tahun Pelajaran</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
