<x-peserta-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sedang Mengerjakan Tes Buta Warna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('tes-buta-warna.submit') }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin mengumpulkan jawaban? Tes tidak dapat diulang.');">
                    @csrf

                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-8">

                        @foreach ($soals as $soal)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <p class="font-semibold mb-4">Soal Nomor {{ $loop->iteration }}: Silahkan pilih
                                    angka/jawaban yang benar:</p>

                                <div class="flex justify-center mb-4">
                                    <img src="{{ Storage::url($soal->gambar_soal) }}" alt="Soal {{ $soal->id }}"
                                        class="max-w-xs w-full h-auto rounded-lg shadow-md">
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                    <label
                                        class="border dark:border-gray-600 rounded-lg p-4 text-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <input type="radio" name="answers[{{ $soal->id }}]" value="A"
                                            class="mb-2" required>
                                        <span class="text-2xl font-bold">A. {{ $soal->pilihan_a }}</span>
                                    </label>
                                    <label
                                        class="border dark:border-gray-600 rounded-lg p-4 text-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <input type="radio" name="answers[{{ $soal->id }}]" value="B"
                                            class="mb-2" required>
                                        <span class="text-2xl font-bold">B. {{ $soal->pilihan_b }}</span>
                                    </label>
                                    <label
                                        class="border dark:border-gray-600 rounded-lg p-4 text-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <input type="radio" name="answers[{{ $soal->id }}]" value="C"
                                            class="mb-2" required>
                                        <span class="text-2xl font-bold">C. {{ $soal->pilihan_c }}</span>
                                    </label>
                                    @if ($soal->pilihan_d)
                                        <label
                                            class="border dark:border-gray-600 rounded-lg p-4 text-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <input type="radio" name="answers[{{ $soal->id }}]" value="D"
                                                class="mb-2" required>
                                            <span class="text-2xl font-bold">D. {{ $soal->pilihan_d }}</span>
                                        </label>
                                    @endif
                                    @if ($soal->pilihan_e)
                                        <label
                                            class="border dark:border-gray-600 rounded-lg p-4 text-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <input type="radio" name="answers[{{ $soal->id }}]" value="E"
                                                class="mb-2" required>
                                            <span class="text-2xl font-bold">E. {{ $soal->pilihan_e }}</span>
                                        </label>
                                    @endif
                                </div>
                                <x-input-error :messages="$errors->get('answers.' . $soal->id)" class="mt-2" />
                            </div>
                        @endforeach

                    </div>

                    <div
                        class="p-6 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 text-right">
                        <x-primary-button class="px-10 py-4 text-lg bg-green-600 hover:bg-green-700">
                            Selesai & Kumpulkan Jawaban
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-peserta-layout>
