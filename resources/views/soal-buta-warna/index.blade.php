<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Soal Tes Buta Warna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
            @endif

            <div class="mb-4 flex justify-end">
                <a href="{{ route('soal-buta-warna.create') }}">
                    <x-primary-button>Tambah Soal Baru</x-primary-button>
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Gambar Soal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pilihan Jawaban</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kunci</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($soals as $soal)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $soal->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="{{ Storage::url($soal->gambar_soal) }}"
                                                alt="Soal {{ $soal->id }}" class="w-20 h-20 object-cover rounded">
                                        </td>
                                        <td class="px-6 py-4 text-sm align-top">
                                            <span
                                                class="{{ $soal->jawaban_benar == 'A' ? 'font-bold text-green-600' : '' }}">A.
                                                {{ $soal->pilihan_a }}</span><br>
                                            <span
                                                class="{{ $soal->jawaban_benar == 'B' ? 'font-bold text-green-600' : '' }}">B.
                                                {{ $soal->pilihan_b }}</span><br>
                                            <span
                                                class="{{ $soal->jawaban_benar == 'C' ? 'font-bold text-green-600' : '' }}">C.
                                                {{ $soal->pilihan_c }}</span><br>
                                            @if ($soal->pilihan_d)
                                                <span
                                                    class="{{ $soal->jawaban_benar == 'D' ? 'font-bold text-green-600' : '' }}">D.
                                                    {{ $soal->pilihan_d }}</span><br>
                                            @endif
                                            @if ($soal->pilihan_e)
                                                <span
                                                    class="{{ $soal->jawaban_benar == 'E' ? 'font-bold text-green-600' : '' }}">E.
                                                    {{ $soal->pilihan_e }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-bold text-center align-top">
                                            {{ $soal->jawaban_benar }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium align-top">
                                            <div
                                                class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2 justify-end">
                                                <a href="{{ route('soal-buta-warna.edit', $soal->id) }}">
                                                    <x-secondary-button>Edit</x-secondary-button>
                                                </a>
                                                <form action="{{ route('soal-buta-warna.destroy', $soal->id) }}"
                                                    method="POST" onsubmit="return confirm('Yakin hapus soal ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button type="submit">Hapus</x-danger-button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Belum ada soal. Silahkan tambahkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $soals->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
