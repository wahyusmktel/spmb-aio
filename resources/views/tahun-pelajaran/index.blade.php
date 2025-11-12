<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Tahun Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div x-data="{
                        editData: {},
                        deleteId: null,
                        editFormAction: ''
                    }">

                        <x-primary-button x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'create-modal')" class="mb-4">
                            Tambah Tahun Pelajaran
                        </x-primary-button>

                        <form action="{{ route('tahun-pelajaran.index') }}" method="GET" class="mb-4">
                            <x-input-label for="search" value="Cari Data" class="sr-only" />
                            <div class="flex">
                                <x-text-input id="search" name="search" type="text" class="mt-1 block w-full"
                                    placeholder="Cari nama tahun pelajaran..." value="{{ $search ?? '' }}" />
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
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Tahun Pelajaran</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($tahunPelajarans as $tahunPelajaran)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $tahunPelajaran->nama_tahun_pelajaran }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($tahunPelajaran->status)
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
                                                        editData = {{ $tahunPelajaran }};
                                                        editFormAction = '{{ route('tahun-pelajaran.update', $tahunPelajaran->id) }}';
                                                    ">
                                                    Edit
                                                </x-secondary-button>

                                                <x-danger-button x-data=""
                                                    x-on:click.prevent="
                                                        $dispatch('open-modal', 'delete-modal');
                                                        deleteId = {{ $tahunPelajaran->id }};
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
                            {{ $tahunPelajarans->links() }}
                        </div>


                        <x-modal name="create-modal" :show="$errors->any() && old('form_type') === 'create'" focusable>
                            <form method="POST" action="{{ route('tahun-pelajaran.store') }}" class="p-6">
                                @csrf
                                <input type="hidden" name="form_type" value="create">

                                <h2 class="text-lg font-medium text-gray-900">Tambah Tahun Pelajaran
                                    Baru</h2>
                                <div class="mt-6">
                                    <x-input-label for="nama_tahun_pelajaran" value="Nama Tahun Pelajaran" />
                                    <x-text-input id="nama_tahun_pelajaran" name="nama_tahun_pelajaran" type="text"
                                        class="mt-1 block w-full" :value="old('nama_tahun_pelajaran')"
                                        placeholder="Contoh: 2024/2025 Ganjil" required />
                                    <x-input-error :messages="$errors->get('nama_tahun_pelajaran')" class="mt-2" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="status" value="Status" />
                                    <select id="status" name="status"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif
                                        </option>
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

                                <h2 class="text-lg font-medium text-gray-900">Edit Data Tahun
                                    Pelajaran</h2>
                                <div class="mt-6">
                                    <x-input-label for="edit_nama_tahun_pelajaran" value="Nama Tahun Pelajaran" />
                                    <x-text-input id="edit_nama_tahun_pelajaran" name="nama_tahun_pelajaran"
                                        type="text" class="mt-1 block w-full" x-model="editData.nama_tahun_pelajaran"
                                        required />
                                    <x-input-error :messages="$errors->get('nama_tahun_pelajaran')" class="mt-2" />
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
                            <form method="POST" :action="`/tahun-pelajaran/${deleteId}`" class="p-6">
                                @csrf
                                @method('DELETE')
                                <h2 class="text-lg font-medium text-gray-900">
                                    Apakah Anda yakin ingin menghapus data ini?
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
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
