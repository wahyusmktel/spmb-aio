<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Tugas Saya (Absensi Mandiri)') }}
        </h2>
    </x-slot>

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
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg dark:bg-red-900 dark:text-red-300">
                    <strong>Gagal mengunggah:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 ... uppercase">Kegiatan</th>
                                    <th class="px-6 py-3 ... uppercase">Peran Tugas</th>
                                    <th class="px-6 py-3 ... uppercase">Status Absensi Mandiri</th>
                                    <th class="px-6 py-3 ... uppercase">Aksi (Upload Bukti)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white ...">
                                @forelse ($tugas as $t)
                                    <tr>
                                        <td class="px-6 py-4 ...">
                                            <div class="font-bold">{{ $t->jadwalSeleksi->judul_kegiatan }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($t->jadwalSeleksi->tanggal_mulai_pelaksanaan)->isoFormat('D MMM YYYY') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 ...">{{ $t->referensiTugas->deskripsi_tugas ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 ...">
                                            @if ($t->absensi_mandiri_at)
                                                <div class="text-green-600 dark:text-green-400 font-bold">
                                                    Sudah Absen
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ({{ $t->absensi_mandiri_at->isoFormat('D MMM Y, H:mm') }})
                                                </div>
                                                <a href="{{ Storage::url($t->file_bukti_mandiri) }}" target="_blank"
                                                    class="text-xs text-blue-500 hover:underline">
                                                    Lihat Bukti
                                                </a>
                                            @else
                                                <span class="text-red-600 dark:text-red-400 font-bold">Belum
                                                    Absen</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 ...">
                                            @if (!$t->absensi_mandiri_at)
                                                <form action="{{ route('absensi-mandiri.store', $t->id) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="file" name="file_bukti" accept=".jpg,.jpeg,.png"
                                                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                        required>
                                                    <x-primary-button class="mt-2">
                                                        Submit Absen
                                                    </x-primary-button>
                                                </form>
                                            @else
                                                <span class="text-sm text-gray-500 italic">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center">
                                            Anda belum memiliki tugas, atau akun Anda belum terhubung ke data Guru.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $tugas->links() }}</div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
