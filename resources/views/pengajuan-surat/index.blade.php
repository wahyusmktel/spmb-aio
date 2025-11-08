<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengajuan Surat Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div x-data="{
                        selectedJadwalId: null,
                        terbitkanFormAction: ''
                    }">

                        @if (session('success'))
                            <div
                                class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg dark:bg-green-900 dark:text-green-300">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->has('nomor_surat_tugas'))
                            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg dark:bg-red-900 dark:text-red-300">
                                Gagal menerbitkan: {{ $errors->first('nomor_surat_tugas') }}
                            </div>
                        @endif

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Judul Kegiatan</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Tahun Pelajaran</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Tgl. Surat</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($pengajuan as $jadwal)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->judul_kegiatan }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $jadwal->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal_surat)->isoFormat('D MMM YYYY') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                                <x-primary-button x-data=""
                                                    x-on:click.prevent="
                                                        $dispatch('open-modal', 'terbitkan-modal');
                                                        selectedJadwalId = {{ $jadwal->id }};
                                                        terbitkanFormAction = '{{ route('pengajuan.terbitkan', $jadwal->id) }}';
                                                    ">
                                                    Terbitkan No. Surat
                                                </x-primary-button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center">Tidak ada pengajuan surat
                                                masuk.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $pengajuan->links() }}</div>

                        <x-modal name="terbitkan-modal" :show="$errors->has('nomor_surat_tugas') && old('form_type') === 'terbitkan'" focusable>
                            <form method="POST" :action="terbitkanFormAction" class="p-6">
                                @csrf
                                <input type="hidden" name="form_type" value="terbitkan">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Terbitkan Nomor Surat
                                    Tugas</h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Masukkan nomor surat tugas yang sesuai untuk kegiatan ini.
                                </p>
                                <div class="mt-6">
                                    <x-input-label for="nomor_surat_tugas" value="Nomor Surat Tugas" />
                                    <x-text-input id="nomor_surat_tugas" name="nomor_surat_tugas" type="text"
                                        class="mt-1 block w-full" placeholder="Contoh: 123/SPT/SMK-TL/XI/2025"
                                        :value="old('nomor_surat_tugas')" required />
                                    <x-input-error :messages="$errors->get('nomor_surat_tugas')" class="mt-2" />
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                    <x-primary-button class="ml-3">Simpan & Terbitkan</x-primary-button>
                                </div>
                            </form>
                        </x-modal>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
