<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<x-peserta-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengerjaan Tes Potensi Akademik (TPA)') }}
        </h2>
    </x-slot>

    <div x-data="{
        allGrupSoals: {{ json_encode($grupSoals) }},
        currentGrupIndex: 0,
        currentSoalIndex: 0,
        answers: {},

        // Computed: Grup yang sedang aktif
        get currentGrup() {
            return this.allGrupSoals[this.currentGrupIndex];
        },

        // Computed: Soal yang sedang aktif
        get currentSoal() {
            return this.currentGrup.tpa_soals[this.currentSoalIndex];
        },

        // Pindah soal
        changeSoal(index) {
            this.currentSoalIndex = index;
        },

        // Pindah grup (dan reset soal ke 0)
        changeGrup(index) {
            this.currentGrupIndex = index;
            this.currentSoalIndex = 0;
        },

        // Navigasi soal
        nextSoal() {
            if (this.currentSoalIndex < this.currentGrup.tpa_soals.length - 1) {
                this.currentSoalIndex++;
            }
        },
        prevSoal() {
            if (this.currentSoalIndex > 0) {
                this.currentSoalIndex--;
            }
        },

        // Cek progres
        isSoalAnswered(soalId) {
            return this.answers.hasOwnProperty(soalId);
        },
        get totalAnswered() {
            return Object.keys(this.answers).length;
        },
        get totalSoal() {
            let total = 0;
            this.allGrupSoals.forEach(grup => total += grup.tpa_soals.length);
            return total;
        }
    }" x-cloak>

        <form action="{{ route('tes-tpa.submit') }}" method="POST"
            onsubmit="return confirm('Anda yakin ingin mengumpulkan SEMUA jawaban? Tes tidak dapat diulang.');">
            @csrf

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">

                <div class="flex flex-col md:flex-row gap-6">

                    <div class="w-full md:w-1/4">
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Grup Soal</h4>
                            <div class="mt-4 space-y-2">
                                <template x-for="(grup, index) in allGrupSoals" :key="grup.id">
                                    <button type="button" @click="changeGrup(index)"
                                        :class="{
                                            'bg-indigo-600 text-white': index == currentGrupIndex,
                                            'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300': index !=
                                                currentGrupIndex
                                        }"
                                        class="w-full text-left px-3 py-2 rounded-md font-medium text-sm">
                                        <span x-text="grup.nama_grup"></span>
                                        (<span x-text="grup.tpa_soals.length"></span> Soal)
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-1/2">
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <h3 class="text-lg font-bold" x-text="currentGrup.nama_grup"></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Soal Nomor <span x-text="currentSoalIndex + 1"></span> dari <span
                                        x-text="currentGrup.tpa_soals.length"></span>
                                </p>

                                <hr class="my-4 dark:border-gray-700">

                                <div class="space-y-4">
                                    <template x-if="currentSoal.gambar_soal">
                                        <img :src="'/storage/' + currentSoal.gambar_soal" alt="Gambar Soal"
                                            class="w-full max-w-md mx-auto rounded-lg shadow">
                                    </template>

                                    <div class="text-lg" x-text="currentSoal.pertanyaan_teks"></div>

                                    <div class="space-y-3">
                                        <label
                                            class="flex items-start p-3 border rounded-lg cursor-pointer dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="radio" :name="'answers[' + currentSoal.id + ']'"
                                                value="A" x-model="answers[currentSoal.id]" class="mt-1">
                                            <span class="ml-3"><strong class="mr-1">A.</strong> <span
                                                    x-text="currentSoal.pilihan_a"></span></span>
                                        </label>
                                        <label
                                            class="flex items-start p-3 border rounded-lg cursor-pointer dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="radio" :name="'answers[' + currentSoal.id + ']'"
                                                value="B" x-model="answers[currentSoal.id]" class="mt-1">
                                            <span class="ml-3"><strong class="mr-1">B.</strong> <span
                                                    x-text="currentSoal.pilihan_b"></span></span>
                                        </label>
                                        <label
                                            class="flex items-start p-3 border rounded-lg cursor-pointer dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="radio" :name="'answers[' + currentSoal.id + ']'"
                                                value="C" x-model="answers[currentSoal.id]" class="mt-1">
                                            <span class="ml-3"><strong class="mr-1">C.</strong> <span
                                                    x-text="currentSoal.pilihan_c"></span></span>
                                        </label>
                                        <label
                                            class="flex items-start p-3 border rounded-lg cursor-pointer dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="radio" :name="'answers[' + currentSoal.id + ']'"
                                                value="D" x-model="answers[currentSoal.id]" class="mt-1">
                                            <span class="ml-3"><strong class="mr-1">D.</strong> <span
                                                    x-text="currentSoal.pilihan_d"></span></span>
                                        </label>
                                        <label
                                            class="flex items-start p-3 border rounded-lg cursor-pointer dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="radio" :name="'answers[' + currentSoal.id + ']'"
                                                value="E" x-model="answers[currentSoal.id]" class="mt-1">
                                            <span class="ml-3"><strong class="mr-1">E.</strong> <span
                                                    x-text="currentSoal.pilihan_e"></span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="p-6 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center">

                                <button type="button" @click="prevSoal()" :disabled="currentSoalIndex == 0"
                                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                    &larr; Sebelumnya
                                </button>

                                <button type="button" @click="nextSoal()"
                                    :disabled="currentSoalIndex == currentGrup.tpa_soals.length - 1"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                    Selanjutnya &rarr;
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-1/4">
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100" x-text="currentGrup.nama_grup">
                            </h4>
                            <div class="grid grid-cols-5 gap-2 mt-4">
                                <template x-for="(soal, index) in currentGrup.tpa_soals" :key="soal.id">
                                    <button type="button" @click="changeSoal(index)"
                                        :class="{
                                            'bg-indigo-600 text-white': index == currentSoalIndex,
                                            'bg-green-600 text-white': index != currentSoalIndex && isSoalAnswered(soal
                                                .id),
                                            'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300': index !=
                                                currentSoalIndex && !isSoalAnswered(soal.id)
                                        }"
                                        class="w-10 h-10 flex items-center justify-center rounded font-bold">
                                        <span x-text="index + 1"></span>
                                    </button>
                                </template>
                            </div>

                            <hr class="my-4 dark:border-gray-700">

                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Total Jawaban: <span x-text="totalAnswered"></span> / <span x-text="totalSoal"></span>
                            </div>

                            <x-primary-button type="submit"
                                class="w-full mt-4 bg-green-600 hover:bg-green-700 text-center justify-center">
                                SELESAI & KUMPULKAN
                            </x-primary-button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</x-peserta-layout>
