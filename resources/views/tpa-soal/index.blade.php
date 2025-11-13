<x-app-layout>
    <x-slot name="header">
        <!-- 'dark:text-gray-200' dihapus -->
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Soal TPA') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <!-- 'dark:...' classes dihapus -->
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <!-- 'dark:...' classes dihapus -->
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}</div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('tpa-soal.create') }}">
                    <x-primary-button>Tambah Soal TPA Baru</x-primary-button>
                </a>

                <form action="{{ route('tpa-soal.index') }}" method="GET" class="flex items-center space-x-2">
                    <x-input-label for="filter_grup_id" value="Filter Grup:" class="whitespace-nowrap" />
                    <!-- 'dark:...' classes dan '...' dihapus -->
                    <select id="filter_grup_id" name="filter_grup_id" onchange="this.form.submit()"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                        <option value="">-- Tampilkan Semua --</option>
                        @foreach ($grups as $grup)
                            <option value="{{ $grup->id }}" {{ $filter_grup_id == $grup->id ? 'selected' : '' }}>
                                {{ $grup->nama_grup }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- 'dark:bg-gray-800' dihapus -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- 'dark:text-gray-100' diganti 'text-gray-900' -->
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <!-- 'dark:divide-gray-700' dihapus -->
                        <table class="min-w-full divide-y divide-gray-200">
                            <!-- 'dark:bg-gray-700' dihapus -->
                            <thead class="bg-gray-50">
                                <tr>
                                    <!-- '...' dan 'dark:...' dihapus dari semua <th> -->
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Grup Soal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pertanyaan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Gambar</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kunci</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <!-- 'dark:bg-gray-800' dan 'dark:...' dihapus dari <tbody> -->
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($soals as $soal)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap align-top">{{ $soal->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap align-top">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $soal->tpaGrupSoal->nama_grup ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm align-top">
                                            {{ Str::limit($soal->pertanyaan_teks, 100) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-top">
                                            @if ($soal->gambar_soal)
                                                <img src="{{ Storage::url($soal->gambar_soal) }}"
                                                    alt="Soal {{ $soal->id }}"
                                                    class="w-16 h-16 object-cover rounded">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-bold text-center align-top">
                                            {{ $soal->jawaban_benar }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium align-top">
                                            <div
                                                class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2 justify-end">
                                                <a href="{{ route('tpa-soal.edit', $soal->id) }}">
                                                    <x-secondary-button>Edit</x-secondary-button>
                                                </a>
                                                <form action="{{ route('tpa-soal.destroy', $soal->id) }}"
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
                                        <td colspan="6" class="text-center py-4">
                                            @if ($filter_grup_id)
                                                Tidak ada soal untuk grup ini.
                                            @else
                                                Belum ada soal TPA. Silahkan tambahkan.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $soals->appends(request()->query())->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
