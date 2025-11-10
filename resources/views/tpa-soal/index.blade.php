<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Soal TPA') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg dark:bg-green-900 dark:text-green-300">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg dark:bg-red-900 dark:text-red-300">
                    {{ session('error') }}</div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('tpa-soal.create') }}">
                    <x-primary-button>Tambah Soal TPA Baru</x-primary-button>
                </a>

                <form action="{{ route('tpa-soal.index') }}" method="GET" class="flex items-center space-x-2">
                    <x-input-label for="filter_grup_id" value="Filter Grup:" class="whitespace-nowrap" />
                    <select id="filter_grup_id" name="filter_grup_id" onchange="this.form.submit()"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 ... rounded-md shadow-sm block w-full">
                        <option value="">-- Tampilkan Semua --</option>
                        @foreach ($grups as $grup)
                            <option value="{{ $grup->id }}" {{ $filter_grup_id == $grup->id ? 'selected' : '' }}>
                                {{ $grup->nama_grup }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left ... uppercase">ID</th>
                                    <th class="px-6 py-3 text-left ... uppercase">Grup Soal</th>
                                    <th class="px-6 py-3 text-left ... uppercase">Pertanyaan</th>
                                    <th class="px-6 py-3 text-left ... uppercase">Gambar</th>
                                    <th class="px-6 py-3 text-center ... uppercase">Kunci</th>
                                    <th class="px-6 py-3 text-right ... uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y ...">
                                @forelse ($soals as $soal)
                                    <tr>
                                        <td class="px-6 py-4 ...">{{ $soal->id }}</td>
                                        <td class="px-6 py-4 ...">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $soal->tpaGrupSoal->nama_grup ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 ... text-sm">
                                            {{ Str::limit($soal->pertanyaan_teks, 100) }}
                                        </td>
                                        <td class="px-6 py-4 ...">
                                            @if ($soal->gambar_soal)
                                                <img src="{{ Storage::url($soal->gambar_soal) }}"
                                                    alt="Soal {{ $soal->id }}"
                                                    class="w-16 h-16 object-cover rounded">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 ... font-bold text-center">{{ $soal->jawaban_benar }}</td>
                                        <td class="px-6 py-4 ... text-right flex space-x-2">
                                            <a href="{{ route('tpa-soal.edit', $soal->id) }}">
                                                <x-secondary-button>Edit</x-secondary-button>
                                            </a>
                                            <form action="{{ route('tpa-soal.destroy', $soal->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus soal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button type="submit">Hapus</x-danger-button>
                                            </form>
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
