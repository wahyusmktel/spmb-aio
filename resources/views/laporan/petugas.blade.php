<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rekapitulasi Laporan Petugas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg dark:bg-red-900 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Filter Laporan</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Pilih filter berdasarkan **Kegiatan** (prioritas) ATAU berdasarkan **Rentang Waktu**.
                    </p>

                    <form action="{{ route('laporan.petugas.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                            <div class="md:col-span-2">
                                <x-input-label for="id_jadwal_seleksi" value="Berdasarkan Kegiatan" />
                                <select id="id_jadwal_seleksi" name="id_jadwal_seleksi"
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">
                                    <option value="">-- Pilih Kegiatan --</option>
                                    @foreach ($jadwals as $jadwal)
                                        <option value="{{ $jadwal->id }}"
                                            {{ $id_jadwal_seleksi == $jadwal->id ? 'selected' : '' }}>
                                            {{ $jadwal->judul_kegiatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="tanggal_mulai" value="Atau Tgl Mulai Kegiatan" />
                                <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date"
                                    class="mt-1 block w-full" :value="$tanggal_mulai ?? old('tanggal_mulai')" />
                            </div>
                            <div>
                                <x-input-label for="tanggal_akhir" value="Atau Tgl Akhir Kegiatan" />
                                <x-text-input id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    class="mt-1 block w-full" :value="$tanggal_akhir ?? old('tanggal_akhir')" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-primary-button name="preview" value="true">
                                Tampilkan Preview
                            </x-primary-button>
                        </div>
                    </form>

                    <hr class="my-6 dark:border-gray-700">

                    <h3 class="text-lg font-medium mb-4">Unduh Laporan</h3>
                    <div class="flex space-x-4">
                        <form action="{{ route('laporan.petugas.download') }}" method="GET" target="_blank">
                            <input type="hidden" name="id_jadwal_seleksi" value="{{ $id_jadwal_seleksi ?? '' }}">
                            <input type="hidden" name="tanggal_mulai" value="{{ $tanggal_mulai ?? '' }}">
                            <input type="hidden" name="tanggal_akhir" value="{{ $tanggal_akhir ?? '' }}">

                            <x-primary-button type="submit" class="bg-green-600 hover:bg-green-700" :disabled="!$id_jadwal_seleksi && (!$tanggal_mulai || !$tanggal_akhir)">
                                Download (Sesuai Filter)
                            </x-primary-button>
                        </form>

                        <a href="{{ route('laporan.petugas.download') }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 ...">
                            Download Semua (Tanpa Filter)
                        </a>
                    </div>
                </div>
            </div>

            @if (isset($petugas))
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Preview Data ({{ $stats['total'] }} Petugas Ditemukan)
                        </h3>
                        <div class="mb-4 flex space-x-4">
                            <span class="px-2 py-1 text-sm ...">Hadir: {{ $stats['hadir'] }}</span>
                            <span class="px-2 py-1 text-sm ...">Tidak Hadir: {{ $stats['tidak_hadir'] }}</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 ... uppercase">No</th>
                                        <th class="px-6 py-3 ... uppercase">Kegiatan</th>
                                        <th class="px-6 py-3 ... uppercase">Nama Petugas</th>
                                        <th class="px-6 py-3 ... uppercase">Jabatan</th>
                                        <th class="px-6 py-3 ... uppercase">Peran Tugas</th>
                                        <th class="px-6 py-3 ... uppercase">Status Hadir</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 ...">
                                    @forelse ($petugas as $p)
                                        <tr>
                                            <td class="px-6 py-4 ...">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4 ...">{{ $p->jadwalSeleksi->judul_kegiatan ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 ...">{{ $p->guru->nama_guru ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 ...">{{ $p->guru->mata_pelajaran ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 ...">
                                                {{ $p->referensiTugas->deskripsi_tugas ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 ...">
                                                @if ($p->kehadiran)
                                                    <span
                                                        class="px-2 ... rounded-full bg-green-100 text-green-800">Hadir</span>
                                                @else
                                                    <span class="px-2 ... rounded-full bg-red-100 text-red-800">Tidak
                                                        Hadir</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center">Tidak ada data petugas
                                                untuk kriteria yang dipilih.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
