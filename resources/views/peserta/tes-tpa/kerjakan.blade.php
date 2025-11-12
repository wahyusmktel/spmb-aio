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
    
        sisaDetik: {{ $sisaDetik }},
        timerDisplay: '',
        timerInterval: null,
    
        initTimer() {
            // Panggil updateTimer sekali agar tidak delay 1 detik
            this.updateTimerDisplay();
    
            this.timerInterval = setInterval(() => {
                this.sisaDetik--;
                this.updateTimerDisplay();
    
                if (this.sisaDetik <= 0) {
                    clearInterval(this.timerInterval);
                    this.timerDisplay = 'WAKTU HABIS';
    
                    // Auto-submit
                    this.$refs.cbtForm.submit();
                }
            }, 1000);
        },
    
        updateTimerDisplay() {
            if (this.sisaDetik < 0) return;
    
            const jam = Math.floor(this.sisaDetik / 3600);
            const menit = Math.floor((this.sisaDetik % 3600) / 60);
            // === FIX: Tambahkan Math.floor() ===
            const detik = Math.floor(this.sisaDetik % 60);
    
            // Format HH:MM:SS
            this.timerDisplay =
                jam.toString().padStart(2, '0') + ':' +
                menit.toString().padStart(2, '0') + ':' +
                detik.toString().padStart(2, '0'); // <-- SEKARANG SUDAH BENAR
        },
    
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
        },
        nextGrup() {
            if (this.currentGrupIndex < this.allGrupSoals.length - 1) {
                this.currentGrupIndex++;
                this.currentSoalIndex = 0;
            }
        },
        submitTest(event) {
            if (this.totalAnswered < this.totalSoal) {
                event.preventDefault();
                alert('PERINGATAN: Masih ada soal yang belum Anda jawab.\n\nSilakan periksa kembali semua grup soal.');
                return; // Hentikan fungsi
            }
            if (!confirm('Anda yakin ingin mengumpulkan SEMUA jawaban? Tes tidak dapat diulang.')) {
                event.preventDefault();
            }
        },
        get isCurrentGrupComplete() {
            // Loop semua soal di grup yang aktif
            for (const soal of this.currentGrup.tpa_soals) {
                // Jika satu saja soal BELUM ada di 'answers'
                if (!this.answers.hasOwnProperty(soal.id)) {
                    return false; // Grup belum selesai
                }
            }
            return true; // Semua soal di grup ini sudah dijawab
        },
        validateAndNextGrup() {
            if (this.isCurrentGrupComplete) {
                this.nextGrup(); // Panggil fungsi pindah grup (jika lolos)
            } else {
                // Tampilkan peringatan jika belum selesai
                alert('PERINGATAN: Anda harus menjawab semua soal di grup ini sebelum melanjutkan ke grup berikutnya.');
            }
        }
    }" x-init="initTimer()" x-cloak>

        <form x-ref="cbtForm" action="{{ route('tes-tpa.submit') }}" method="POST" x-on:submit="submitTest($event)">
            @csrf

            <div class="sticky top-16 z-10 bg-indigo-800 text-white shadow-lg">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-2 px-4 flex justify-center items-center">
                    <span class="font-medium mr-2">Sisa Waktu:</span>
                    <span class="text-2xl font-bold font-mono" x-text="timerDisplay">00:00:00</span>
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">

                <div class="flex flex-col md:flex-row gap-6">

                    <div class="w-full md:w-1/4">
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Grup Soal</h4>
                            <div class="mt-4 space-y-2">
                                <template x-for="(grup, index) in allGrupSoals" :key="grup.id">
                                    <div :class="{
                                        'bg-indigo-600 text-white': index == currentGrupIndex, // Sedang dikerjakan
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': index <
                                            currentGrupIndex, // Selesai
                                        'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 opacity-60': index >
                                            currentGrupIndex // Terkunci
                                    }"
                                        class="w-full text-left px-3 py-2 rounded-md font-medium text-sm">
                                        <span x-text="grup.nama_grup"></span>
                                        (<span x-text="grup.tpa_soals.length"></span> Soal)

                                        <span x-show="index < currentGrupIndex">(Selesai)</span>
                                        <span x-show="index == currentGrupIndex">(Mengerjakan)</span>
                                    </div>
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

                            <div class="p-6 bg-gray-50 dark:bg-gray-700 border-t ... flex justify-between items-center">

                                <button type="button" @click="prevSoal()" :disabled="currentSoalIndex == 0"
                                    class="inline-flex items-center ... disabled:opacity-25 ...">
                                    &larr; Sebelumnya
                                </button>

                                <button type="button" @click="nextSoal()"
                                    x-show="currentSoalIndex < currentGrup.tpa_soals.length - 1"
                                    class="inline-flex items-center ...">
                                    Selanjutnya &rarr;
                                </button>

                                <button type="button" @click="validateAndNextGrup()"
                                    x-show="currentSoalIndex == currentGrup.tpa_soals.length - 1 && currentGrupIndex < allGrupSoals.length - 1"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border ... text-white ...">
                                    Lanjut ke Grup Berikutnya &rarr;
                                </button>

                                <span
                                    x-show="currentSoalIndex == currentGrup.tpa_soals.length - 1 && currentGrupIndex == allGrupSoals.length - 1"
                                    class="text-sm font-medium text-green-600 dark:text-green-400">
                                    Grup Terakhir Selesai.
                                </span>
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

                            <div x-show="currentGrupIndex == allGrupSoals.length - 1">
                                <x-primary-button type="submit"
                                    class="w-full mt-4 bg-green-600 hover:bg-green-700 text-center justify-center">
                                    SELESAI & KUMPULKAN
                                </x-primary-button>
                            </div>

                            <div x-show="currentGrupIndex != allGrupSoals.length - 1"
                                class="w-full mt-4 p-2 text-center text-sm bg-gray-100 dark:bg-gray-700 text-gray-500 rounded-md">
                                Selesaikan grup ini untuk lanjut...
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</x-peserta-layout>
