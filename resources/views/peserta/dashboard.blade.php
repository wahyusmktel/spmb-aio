<x-peserta-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Peserta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($peserta)
                        <h3 class="text-2xl font-bold">
                            Selamat Datang, {{ $peserta->nama_pendaftar }}!
                        </h3>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">
                            Anda terdaftar pada seleksi berikut:
                        </p>

                        <div class="mt-6 p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <h4 class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">
                                {{ $jadwal->judul_kegiatan ?? 'Nama Kegiatan Tidak Ditemukan' }}
                            </h4>
                            <p class="mt-2">
                                <strong>Tahun Pelajaran:</strong>
                                {{ $jadwal->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}
                            </p>
                            <p>
                                <strong>Waktu Pelaksanaan:</strong>
                                {{ $jadwal->tanggal_mulai_pelaksanaan ? \Carbon\Carbon::parse($jadwal->tanggal_mulai_pelaksanaan)->isoFormat('dddd, D MMMM YYYY') : 'N/A' }}
                            </p>
                            <p>
                                <strong>Lokasi:</strong> {{ $jadwal->lokasi_kegiatan ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="mt-6 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="text-lg font-semibold">Tes Buta Warna</h4>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                Tes ini wajib diikuti sebagai salah satu syarat seleksi. Tes terdiri dari 10 soal (atau
                                sesuai input admin) dan hanya dapat dikerjakan satu kali.
                            </p>
                            <div class="mt-4">
                                <x-primary-button class="bg-green-600 hover:bg-green-700">
                                    Mulai Kerjakan Tes
                                </x-primary-button>
                            </div>
                        </div>
                    @else
                        <h3 class="text-2xl font-bold text-red-600">Akun Belum Aktif</h3>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">
                            Akun Anda ({{ $akun->username }}) terdeteksi, namun belum ter-mapping ke data peserta
                            seleksi.
                            <br>
                            Silakan hubungi panitia (Admin) untuk melakukan mapping data.
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-peserta-layout>
