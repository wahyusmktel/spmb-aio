<x-peserta-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tes Buta Warna') }}
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

                    @if ($peserta->status_tes_buta_warna)
                        <h3 class="text-2xl font-bold text-center text-green-600">ANDA SUDAH MENYELESAIKAN TES</h3>
                        <p class="text-center text-gray-600 dark:text-gray-400">Tes hanya dapat dikerjakan satu kali.</p>

                        <div class="text-center my-8">
                            <div class="text-lg">Nilai Anda:</div>
                            <div class="text-8xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $peserta->nilai_tes_buta_warna }}</div>
                        </div>

                        <h4 class="text-lg font-semibold mt-10">Rincian Jawaban:</h4>
                        <div class="mt-4 space-y-4">
                            @foreach ($peserta->hasilButaWarna as $hasil)
                                <div
                                    class="flex items-start space-x-4 p-4 border rounded-lg {{ $hasil->is_benar ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                                    <img src="{{ Storage::url($hasil->soalButaWarna->gambar_soal) }}"
                                        class="w-20 h-20 rounded object-cover">
                                    <div>
                                        <p class="font-semibold">Jawaban Anda: {{ $hasil->jawaban_peserta }}</p>
                                        @if ($hasil->is_benar)
                                            <p class="text-sm text-green-700 font-bold">Benar</p>
                                        @else
                                            <p class="text-sm text-red-700 font-bold">Salah (Kunci:
                                                {{ $hasil->soalButaWarna->jawaban_benar }})</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <h3 class="text-2xl font-bold">Instruksi Tes Buta Warna</h3>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">
                            Selamat datang di tes buta warna. Tes ini bertujuan untuk menguji persepsi warna Anda.
                        </p>
                        <ul class="list-disc list-inside mt-4 space-y-2">
                            <li>Tes terdiri dari 10 soal (atau lebih).</li>
                            <li>Anda akan diperlihatkan gambar lingkaran (plat Ishihara) dan diminta menebak angka di
                                dalamnya.</li>
                            <li>Pilih salah satu jawaban yang menurut Anda paling benar.</li>
                            <li>Tes hanya dapat dikerjakan <strong>SATU KALI</strong>. Pastikan Anda fokus.</li>
                            <li>Tidak ada batasan waktu, namun selesaikan sesegera mungkin.</li>
                        </ul>
                        <div class="mt-6 text-center">
                            <a href="{{ route('tes-buta-warna.kerjakan') }}">
                                <x-primary-button class="px-10 py-4 text-lg bg-green-600 hover:bg-green-700">
                                    Mulai Kerjakan Tes
                                </x-primary-button>
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-peserta-layout>
