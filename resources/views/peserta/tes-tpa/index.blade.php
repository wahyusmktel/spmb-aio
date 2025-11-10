<x-peserta-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tes Potensi Akademik (TPA)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg ...">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg ...">{{ session('error') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($jadwal->tpa_grup_soals_count == 0)
                        <h3 class="text-2xl font-bold text-center text-yellow-600">TES TPA BELUM TERSEDIA</h3>
                        <p class="text-center text-gray-600 dark:text-gray-400 mt-4">
                            Admin belum mengatur soal TPA untuk jadwal seleksi Anda. Silakan kembali lagi nanti.
                        </p>
                    @elseif ($peserta->status_tes_tpa)
                        <h3 class="text-2xl font-bold text-center text-green-600">ANDA SUDAH MENYELESAIKAN TES TPA</h3>
                        <p class="text-center text-gray-600 dark:text-gray-400">Tes hanya dapat dikerjakan satu kali.
                        </p>

                        <div class="text-center my-8">
                            <div class="text-lg">Nilai Akhir TPA Anda:</div>
                            <div class="text-8xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $peserta->nilai_tes_tpa }}</div>
                        </div>

                        <h4 class="text-lg font-semibold mt-10">Rincian Jawaban:</h4>
                        <div class="mt-4 space-y-6">
                            @foreach ($hasilPerGrup as $namaGrup => $hasilGrup)
                                <div class="border rounded-lg dark:border-gray-700">
                                    <h5 class="text-md font-bold p-3 bg-gray-100 dark:bg-gray-700 rounded-t-lg">
                                        {{ $namaGrup }}
                                        (Benar: {{ $hasilGrup->where('is_benar', true)->count() }} /
                                        {{ $hasilGrup->count() }})
                                    </h5>
                                    <div class="p-3">
                                        @foreach ($hasilGrup as $hasil)
                                            <div
                                                class="text-sm {{ $hasil->is_benar ? 'text-green-600' : 'text-red-500' }}">
                                                Soal ID {{ $hasil->tpa_soal_id }}: Jawaban Anda
                                                <strong>{{ $hasil->jawaban_peserta }}</strong>
                                                @if (!$hasil->is_benar)
                                                    (Kunci: {{ $hasil->tpaSoal->jawaban_benar }})
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <h3 class="text-2xl font-bold">Instruksi Tes Potensi Akademik</h3>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">
                            Selamat datang di Tes Potensi Akademik (TPA).
                        </p>
                        <ul class="list-disc list-inside mt-4 space-y-2">
                            <li>Tes ini terdiri dari <strong>{{ $jadwal->tpa_grup_soals_count }} grup soal</strong>.
                            </li>
                            <li>Anda harus menyelesaikan semua grup soal dalam satu kali pengerjaan.</li>
                            <li>Tes hanya dapat dikerjakan <strong>SATU KALI</strong>.</li>
                        </ul>
                        <div class="mt-6 text-center">
                            <a href="{{ route('tes-tpa.kerjakan') }}">
                                <x-primary-button class="px-10 py-4 text-lg bg-green-600 hover:bg-green-700">
                                    Mulai Kerjakan Tes TPA
                                </x-primary-button>
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-peserta-layout>
