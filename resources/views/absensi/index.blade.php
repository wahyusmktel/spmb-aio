<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Input Absensi:') }} <span class="font-bold">{{ $jadwal->judul_kegiatan }}</span>
        </h2>
        <a href="{{ route('jadwal-seleksi.index') }}" class="text-sm text-blue-500 hover:underline">&larr; Kembali ke
            Jadwal Seleksi</a>
    </x-slot>

    <div x-data="{ tab: 'peserta' }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg dark:bg-green-900 dark:text-green-300">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg dark:bg-red-900 dark:text-red-300">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('absensi.store', $jadwal->id) }}" method="POST">
                    @csrf

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                        <div class="flex border-b border-gray-200 dark:border-gray-700">
                            <button type="button" @click="tab = 'peserta'"
                                :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': tab === 'peserta', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': tab !== 'peserta' }"
                                class="py-4 px-6 font-medium text-sm border-b-2 focus:outline-none">
                                Peserta ({{ $peserta->count() }})
                            </button>
                            <button type="button" @click="tab = 'petugas'"
                                :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': tab === 'petugas', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': tab !== 'petugas' }"
                                class="py-4 px-6 font-medium text-sm border-b-2 focus:outline-none">
                                Petugas ({{ $petugas->count() }})
                            </button>
                        </div>

                        <div x-show="tab === 'peserta'" class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-medium mb-4">Absensi Peserta</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                Nama Peserta</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                No. Pendaftaran</th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                Kehadiran (Ceklis)</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse ($peserta as $p)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $p->nama_pendaftar }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $p->nomor_pendaftaran }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="hadir_peserta[]"
                                                        value="{{ $p->id }}"
                                                        {{ $p->kehadiran ? 'checked' : '' }}
                                                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-4">Belum ada peserta.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div x-show="tab === 'petugas'" class="p-6 text-gray-900 dark:text-gray-100"
                            style="display: none;">
                            <h3 class="text-lg font-medium mb-4">Absensi Petugas</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                Nama Petugas</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                Peran Tugas</th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                Kehadiran (Ceklis)</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse ($petugas as $p)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $p->guru->nama_guru ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-pre-wrap">
                                                    {{ $p->referensiTugas->deskripsi_tugas ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" name="hadir_petugas[]"
                                                        value="{{ $p->id }}"
                                                        {{ $p->kehadiran ? 'checked' : '' }}
                                                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-4">Belum ada petugas.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div
                            class="p-6 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 text-right">
                            <x-primary-button type="submit">
                                Simpan Laporan Absensi
                            </x-primary-button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
