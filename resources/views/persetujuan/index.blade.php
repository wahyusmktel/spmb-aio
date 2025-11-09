<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Persetujuan Surat Perintah Tugas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div
                            class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg dark:bg-green-900 dark:text-green-300">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg dark:bg-red-900 dark:text-red-300">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 ... uppercase">Judul Kegiatan</th>
                                    <th class="px-6 py-3 ... uppercase">Nomor Surat</th>
                                    <th class="px-6 py-3 ... uppercase">Tgl. Surat</th>
                                    <th class="px-6 py-3 ... uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white ...">
                                @forelse ($persetujuan as $jadwal)
                                    <tr>
                                        <td class="px-6 py-4 ...">{{ $jadwal->judul_kegiatan }}</td>
                                        <td class="px-6 py-4 ...">{{ $jadwal->nomor_surat_tugas }}</td>
                                        <td class="px-6 py-4 ...">
                                            {{ \Carbon\Carbon::parse($jadwal->tanggal_surat)->isoFormat('D MMM YYYY') }}
                                        </td>
                                        <td class="px-6 py-4 ... text-right text-sm font-medium">
                                            <form action="{{ route('persetujuan.approve', $jadwal->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Anda yakin ingin menyetujui surat tugas ini?');">
                                                @csrf
                                                <x-primary-button class="bg-green-600 hover:bg-green-700">
                                                    Setujui (Tandatangan)
                                                </x-primary-button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center">Tidak ada surat yang menunggu
                                            persetujuan Anda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $persetujuan->links() }}</div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
