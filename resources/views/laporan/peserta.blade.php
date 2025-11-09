<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rekapitulasi Laporan Peserta') }}
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

                    <form action="{{ route('laporan.peserta.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <x-input-label for="tanggal_mulai" value="Tanggal Mulai Kegiatan" />
                                <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date"
                                    class="mt-1 block w-full" :value="$tanggal_mulai ?? old('tanggal_mulai')" required />
                            </div>
                            <div>
                                <x-input-label for="tanggal_akhir" value="Tanggal Akhir Kegiatan" />
                                <x-text-input id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    class="mt-1 block w-full" :value="$tanggal_akhir ?? old('tanggal_akhir')" required />
                            </div>
                            <div class="pt-6">
                                <x-primary-button name="preview" value="true">
                                    Tampilkan Preview
                                </x-primary-button>
                            </div>
                        </div>
                    </form>

                    <hr class="my-6 dark:border-gray-700">

                    <h3 class="text-lg font-medium mb-4">Unduh Laporan</h3>
                    <div class="flex space-x-4">
                        <form action="{{ route('laporan.peserta.download') }}" method="GET" target="_blank">
                            <input type="hidden" name="tanggal_mulai" value="{{ $tanggal_mulai ?? '' }}">
                            <input type="hidden" name="tanggal_akhir" value="{{ $tanggal_akhir ?? '' }}">

                            <x-primary-button type="submit" class="bg-green-600 hover:bg-green-700" :disabled="!$tanggal_mulai || !$tanggal_akhir">
                                Download (Sesuai Filter)
                            </x-primary-button>
                        </form>

                        <a href="{{ route('laporan.peserta.download') }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white dark:bg-gray-200 dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 ...">
                            Download Semua (Tanpa Filter)
                        </a>
                    </div>
                </div>
            </div>

            @if (isset($peserta))
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Preview Data ({{ $stats['total'] }} Peserta Ditemukan)</h3>
                        <div class="mb-4 flex space-x-4">
                            <span
                                class="px-2 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Hadir:
                                {{ $stats['hadir'] }}</span>
                            <span class="px-2 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Tidak
                                Hadir: {{ $stats['tidak_hadir'] }}</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 ... uppercase">No</th>
                                        <th class="px-6 py-3 ... uppercase">Kegiatan</th>
                                        <th class="px-6 py-3 ... uppercase">Tahun</th>
                                        <th class="px-6 py-3 ... uppercase">No. Pendaftaran</th>
                                        <th class="px-6 py-3 ... uppercase">Nama Peserta</th>
                                        <th class="px-6 py-3 ... uppercase">Status Hadir</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 ...">
                                    @forelse ($peserta as $p)
                                        <tr>
                                            <td class="px-6 py-4 ...">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4 ...">{{ $p->jadwalSeleksi->judul_kegiatan ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 ...">
                                                {{ $p->jadwalSeleksi->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 ...">{{ $p->nomor_pendaftaran }}</td>
                                            <td class="px-6 py-4 ...">{{ $p->nama_pendaftar }}</td>
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
                                            <td colspan="6" class="px-6 py-4 text-center">Tidak ada data peserta
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
