<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Jadwal Seleksi') }}

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

                            <x-primary-button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'create-modal')" class="mb-4">
                                Tambah Jadwal (T.A: {{ $tahunAktif->nama_tahun_pelajaran }})
                            </x-primary-button>

                            <form action="{{ route('jadwal-seleksi.index') }}" method="GET" class="mb-4">
                                <x-input-label for="search" value="Cari Data" class="sr-only" />
                                <div class="flex">
                                    <x-text-input id="search" name="search" type="text" class="mt-1 block w-full"
                                        placeholder="Cari judul, lokasi, atau no. surat..."
                                        value="{{ $search ?? '' }}" />
                                    <x-primary-button class="ml-3 mt-1">Cari</x-primary-button>
                                </div>
                            </form>

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
                                                Judul Kegiatan</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Waktu Pelaksanaan</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Lokasi</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Penandatangan</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($jadwalSeleksis as $jadwal)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->judul_kegiatan }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    Mulai:
                                                    {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai_pelaksanaan)->format('d-m-Y H:i') }}
                                                    <br>
                                                    Selesai:
                                                    {{ \Carbon\Carbon::parse($jadwal->tanggal_akhir_pelaksanaan)->format('d-m-Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->lokasi_kegiatan }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $jadwal->penandatangan->name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($jadwal->status == 'menunggu_nst')
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                            Menunggu NST
                                                        </span>
                                                    @elseif ($jadwal->status == 'menunggu_acc')
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                            Menunggu ACC
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                            Diterbitkan
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                                    <x-secondary-button x-data=""
                                                        x-on:click.prevent="
                                                            $dispatch('open-modal', 'edit-modal');
                                                            editData = {
                                                                ...{{ $jadwal }},
                                                                tanggal_mulai_pelaksanaan: '{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai_pelaksanaan)->format('Y-m-d\TH:i') }}',
                                                                tanggal_akhir_pelaksanaan: '{{ \Carbon\Carbon::parse($jadwal->tanggal_akhir_pelaksanaan)->format('Y-m-d\TH:i') }}',
                                                                tanggal_surat: '{{ \Carbon\Carbon::parse($jadwal->tanggal_surat)->format('Y-m-d') }}'
                                                            };
                                                            editFormAction = '{{ route('jadwal-seleksi.update', $jadwal->id) }}';
                                                        ">
                                                        Edit
                                                    </x-secondary-button>

                                                    <x-danger-button x-data=""
                                                        x-on:click.prevent="
                                                            $dispatch('open-modal', 'delete-modal');
                                                            deleteId = {{ $jadwal->id }};
                                                        "
                                                        class="ml-2">
                                                        Delete
                                                    </x-danger-button>
                                                    @if ($jadwal->status == 'diterbitkan')
                                                        <a href="{{ route('penugasan.index', $jadwal->id) }}"
                                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                            Petugas
                                                        </a>

                                                        <a href="{{ route('peserta.index', $jadwal->id) }}"
                                                            class="ml-2 inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                            Peserta
                                                        </a>

                                                        <div class="inline-block ml-2">
                                                            <x-dropdown align="right" width="48">
                                                                <x-slot name="trigger">
                                                                    <button
                                                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white dark:bg-gray-200 dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                                        <div>Unduh</div>
                                                                        <div class="ms-1">
                                                                            <svg class="fill-current h-4 w-4"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                        </div>
                                                                    </button>
                                                                </x-slot>

                                                                <x-slot name="content">
                                                                    <x-dropdown-link :href="route(
                                                                        'jadwal.download-kartu',
                                                                        $jadwal->id,
                                                                    )" target="_blank">
                                                                        Download Kartu Login
                                                                    </x-dropdown-link>
                                                                    <x-dropdown-link :href="route(
                                                                        'jadwal.download-daftar-hadir',
                                                                        $jadwal->id,
                                                                    )" target="_blank">
                                                                        Download Daftar Hadir Petugas
                                                                    </x-dropdown-link>
                                                                    <x-dropdown-link :href="route(
                                                                        'jadwal.download-daftar-hadir-peserta',
                                                                        $jadwal->id,
                                                                    )" target="_blank">
                                                                        Download Daftar Hadir Peserta
                                                                    </x-dropdown-link>
                                                                    <x-dropdown-link :href="route(
                                                                        'jadwal.download-berita-acara',
                                                                        $jadwal->id,
                                                                    )" target="_blank">
                                                                        Download Berita Acara
                                                                    </x-dropdown-link>
                                                                    <x-dropdown-link :href="route(
                                                                        'jadwal.download-spt',
                                                                        $jadwal->id,
                                                                    )" target="_blank">
                                                                        Download Surat Tugas
                                                                    </x-dropdown-link>
                                                                </x-slot>
                                                            </x-dropdown>
                                                        </div>

                                                        <div class="inline-block ml-2">
                                                            <x-dropdown align="right" width="48">
                                                                <x-slot name="trigger">
                                                                    <button
                                                                        class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 active:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                                        <div>Absensi</div>
                                                                        <div class="ms-1">
                                                                            <svg class="fill-current h-4 w-4"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                        </div>
                                                                    </button>
                                                                </x-slot>

                                                                <x-slot name="content">
                                                                    <x-dropdown-link :href="route('absensi.index', $jadwal->id)">
                                                                        Input Kehadiran (By Sistem)
                                                                    </x-dropdown-link>

                                                                    <div
                                                                        class="border-t border-gray-200 dark:border-gray-600">
                                                                    </div>

                                                                    <x-dropdown-link :href="route(
                                                                        'absensi.download.peserta',
                                                                        $jadwal->id,
                                                                    )" target="_blank">
                                                                        Unduh Laporan Peserta
                                                                    </x-dropdown-link>

                                                                    <x-dropdown-link :href="route(
                                                                        'absensi.download.petugas',
                                                                        $jadwal->id,
                                                                    )" target="_blank">
                                                                        Unduh Laporan Petugas
                                                                    </x-dropdown-link>

                                                                </x-slot>
                                                            </x-dropdown>
                                                        </div>

                                                        <div class="inline-block ml-2">
                                                            <x-dropdown align="right" width="48">
                                                                <x-slot name="trigger">
                                                                    <button
                                                                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                                        <div>Eviden</div>
                                                                        <div class="ms-1">
                                                                            <svg class="fill-current h-4 w-4"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                        </div>
                                                                    </button>
                                                                </x-slot>

                                                                <x-slot name="content">
                                                                    <x-dropdown-link :href="route('eviden.index', $jadwal->id)">
                                                                        Upload Eviden Manual (Scan)
                                                                    </x-dropdown-link>
                                                                </x-slot>
                                                            </x-dropdown>
                                                        </div>
                                                    @else
                                                        <span class="text-xs text-gray-500 italic">Menunggu NST</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if ($search)
                                                        Data tidak ditemukan untuk pencarian "{{ $search }}".
                                                    @else
                                                        Data masih kosong untuk tahun pelajaran ini.
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $jadwalSeleksis->links() }}
                            </div>


                            <x-modal name="create-modal" :show="$errors->any() && old('form_type') === 'create'" focusable>
                                <form method="POST" action="{{ route('jadwal-seleksi.store') }}" class="p-6">
                                    @csrf
                                    <input type="hidden" name="form_type" value="create">
                                    <h2 class="text-lg font-medium text-gray-900">Tambah Jadwal (T.A:
                                        {{ $tahunAktif->nama_tahun_pelajaran }})</h2>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                                        <div>
                                            <x-input-label for="judul_kegiatan" value="Judul Kegiatan" />
                                            <x-text-input id="judul_kegiatan" name="judul_kegiatan" type="text"
                                                class="mt-1 block w-full" :value="old('judul_kegiatan')" required />
                                            <x-input-error :messages="$errors->get('judul_kegiatan')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="lokasi_kegiatan" value="Lokasi Kegiatan" />
                                            <x-text-input id="lokasi_kegiatan" name="lokasi_kegiatan" type="text"
                                                class="mt-1 block w-full" :value="old('lokasi_kegiatan')" required />
                                            <x-input-error :messages="$errors->get('lokasi_kegiatan')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="tanggal_mulai_pelaksanaan"
                                                value="Mulai Pelaksanaan" />
                                            <x-text-input id="tanggal_mulai_pelaksanaan"
                                                name="tanggal_mulai_pelaksanaan" type="datetime-local"
                                                class="mt-1 block w-full" :value="old('tanggal_mulai_pelaksanaan')" required />
                                            <x-input-error :messages="$errors->get('tanggal_mulai_pelaksanaan')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="tanggal_akhir_pelaksanaan"
                                                value="Selesai Pelaksanaan" />
                                            <x-text-input id="tanggal_akhir_pelaksanaan"
                                                name="tanggal_akhir_pelaksanaan" type="datetime-local"
                                                class="mt-1 block w-full" :value="old('tanggal_akhir_pelaksanaan')" required />
                                            <x-input-error :messages="$errors->get('tanggal_akhir_pelaksanaan')" class="mt-2" />
                                        </div>
                                        {{-- <div>
                                            <x-input-label for="nomor_surat_tugas" value="Nomor Surat Tugas" />
                                            <x-text-input id="nomor_surat_tugas" name="nomor_surat_tugas"
                                                type="text" class="mt-1 block w-full" :value="old('nomor_surat_tugas')"
                                                required />
                                            <x-input-error :messages="$errors->get('nomor_surat_tugas')" class="mt-2" />
                                        </div> --}}
                                        <div>
                                            <x-input-label for="kota_surat" value="Kota Surat" />
                                            <x-text-input id="kota_surat" name="kota_surat" type="text"
                                                class="mt-1 block w-full" :value="old('kota_surat')" required />
                                            <x-input-error :messages="$errors->get('kota_surat')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="tanggal_surat" value="Tanggal Surat" />
                                            <x-text-input id="tanggal_surat" name="tanggal_surat" type="date"
                                                class="mt-1 block w-full" :value="old('tanggal_surat')" required />
                                            <x-input-error :messages="$errors->get('tanggal_surat')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="id_penandatangan" value="Penandatangan" />
                                            <select id="id_penandatangan" name="id_penandatangan"
                                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                                <option value="">Pilih Penandatangan</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ old('id_penandatangan') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('id_penandatangan')" class="mt-2" />
                                        </div>
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
                                    <h2 class="text-lg font-medium text-gray-900">Edit Jadwal</h2>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                                        <div>
                                            <x-input-label for="edit_judul_kegiatan" value="Judul Kegiatan" />
                                            <x-text-input id="edit_judul_kegiatan" name="judul_kegiatan"
                                                type="text" class="mt-1 block w-full"
                                                x-model="editData.judul_kegiatan" required />
                                            <x-input-error :messages="$errors->get('judul_kegiatan')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="edit_lokasi_kegiatan" value="Lokasi Kegiatan" />
                                            <x-text-input id="edit_lokasi_kegiatan" name="lokasi_kegiatan"
                                                type="text" class="mt-1 block w-full"
                                                x-model="editData.lokasi_kegiatan" required />
                                            <x-input-error :messages="$errors->get('lokasi_kegiatan')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="edit_tanggal_mulai_pelaksanaan"
                                                value="Mulai Pelaksanaan" />
                                            <x-text-input id="edit_tanggal_mulai_pelaksanaan"
                                                name="tanggal_mulai_pelaksanaan" type="datetime-local"
                                                class="mt-1 block w-full" x-model="editData.tanggal_mulai_pelaksanaan"
                                                required />
                                            <x-input-error :messages="$errors->get('tanggal_mulai_pelaksanaan')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="edit_tanggal_akhir_pelaksanaan"
                                                value="Selesai Pelaksanaan" />
                                            <x-text-input id="edit_tanggal_akhir_pelaksanaan"
                                                name="tanggal_akhir_pelaksanaan" type="datetime-local"
                                                class="mt-1 block w-full" x-model="editData.tanggal_akhir_pelaksanaan"
                                                required />
                                            <x-input-error :messages="$errors->get('tanggal_akhir_pelaksanaan')" class="mt-2" />
                                        </div>
                                        {{-- <div>
                                            <x-input-label for="edit_nomor_surat_tugas" value="Nomor Surat Tugas" />
                                            <x-text-input id="edit_nomor_surat_tugas" name="nomor_surat_tugas"
                                                type="text" class="mt-1 block w-full"
                                                x-model="editData.nomor_surat_tugas" required />
                                            <x-input-error :messages="$errors->get('nomor_surat_tugas')" class="mt-2" />
                                        </div> --}}
                                        <div>
                                            <x-input-label for="edit_kota_surat" value="Kota Surat" />
                                            <x-text-input id="edit_kota_surat" name="kota_surat" type="text"
                                                class="mt-1 block w-full" x-model="editData.kota_surat" required />
                                            <x-input-error :messages="$errors->get('kota_surat')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="edit_tanggal_surat" value="Tanggal Surat" />
                                            <x-text-input id="edit_tanggal_surat" name="tanggal_surat" type="date"
                                                class="mt-1 block w-full" x-model="editData.tanggal_surat" required />
                                            <x-input-error :messages="$errors->get('tanggal_surat')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="edit_id_penandatangan" value="Penandatangan" />
                                            <select id="edit_id_penandatangan" name="id_penandatangan"
                                                x-model="editData.id_penandatangan"
                                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('id_penandatangan')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="mt-6 flex justify-end">
                                        <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                        <x-primary-button class="ml-3">Update</x-primary-button>
                                    </div>
                                </form>
                            </x-modal>

                            <x-modal name="delete-modal" focusable max-width="md">
                                <form method="POST" :action="`/jadwal-seleksi/${deleteId}`" class="p-6">
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
                    @else
                        <div classid="alert-warning" class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50"
                            role="alert">
                            <span class="font-medium">Perhatian!</span> Silahkan aktifkan tahun pelajaran terlebih
                            dahulu.
                            <p class="mt-2">Anda tidak dapat melihat atau menambah jadwal seleksi jika tidak ada
                                tahun pelajaran yang disetel sebagai "Aktif".</p>
                            <a href="{{ route('tahun-pelajaran.index') }}"
                                class="font-bold underline hover:text-yellow-900">Klik di sini untuk pergi ke halaman
                                Tahun Pelajaran</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
