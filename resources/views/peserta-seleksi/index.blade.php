<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peserta Seleksi:') }} <span class="font-bold">{{ $jadwal->judul_kegiatan }}</span>
        </h2>
        <a href="{{ route('jadwal-seleksi.index') }}" class="text-sm text-blue-500 hover:underline">&larr; Kembali ke
            Jadwal Seleksi</a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div x-data="{
                rows: [{ id: Date.now(), nomor_pendaftaran: '', nama_pendaftar: '', nomor_telepon: '', id_akun_cbt: '' }],
                availableAkunCbt: {{ $availableAkunCbt }},
            
                addRow() {
                    this.rows.push({
                        id: Date.now(),
                        nomor_pendaftaran: '',
                        nama_pendaftar: '',
                        nomor_telepon: '',
                        id_akun_cbt: ''
                    });
                },
            
                removeRow(id) {
                    this.rows = this.rows.filter(row => row.id !== id);
                }
            }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">

                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Tambah Peserta Baru (Inline)</h3>

                    @if ($errors->has('peserta.*') || $errors->has('peserta'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            <strong>Oops! Ada kesalahan pada input baris:</strong>
                            <ul class="list-disc list-inside mt-2">
                                @foreach ($errors->all() as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('peserta.store', $jadwal->id) }}" method="POST">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            No. Pendaftaran</th>
                                        <th class="py-2 px-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Nama Pendaftar</th>
                                        <th class="py-2 px-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            No. Telepon</th>
                                        <th class="py-2 px-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Akun CBT (Tersedia: <span x-text="availableAkunCbt.length"></span>)</th>
                                        <th class="py-2 px-2 text-right text-xs font-medium text-gray-500 uppercase">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, index) in rows" :key="row.id">
                                        <tr class="border-t border-gray-200">

                                            <td class="px-2 py-1">
                                                <input type="text" x-model="row.nomor_pendaftaran"
                                                    :name="`peserta[${index}][nomor_pendaftaran]`"
                                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                                    required>
                                            </td>
                                            <td class="px-2 py-1">
                                                <input type="text" x-model="row.nama_pendaftar"
                                                    :name="`peserta[${index}][nama_pendaftar]`"
                                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                                    required>
                                            </td>
                                            <td class="px-2 py-1">
                                                <input type="text" x-model="row.nomor_telepon"
                                                    :name="`peserta[${index}][nomor_telepon]`"
                                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                                    required>
                                            </td>
                                            <td class="px-2 py-1">
                                                <select x-model="row.id_akun_cbt"
                                                    :name="`peserta[${index}][id_akun_cbt]`"
                                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                                    required>
                                                    <option value="">-- Pilih Akun --</option>
                                                    <template x-for="akun in availableAkunCbt" :key="akun.id">
                                                        <option :value="akun.id" x-text="akun.username"></option>
                                                    </template>
                                                </select>
                                            </td>
                                            <td class="px-2 py-1 text-right">
                                                <x-danger-button type="button" @click="removeRow(row.id)"
                                                    x-show="rows.length > 1">-</x-danger-button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-between items-center mt-4">
                            <x-secondary-button type="button" @click="addRow()">
                                + Tambah Baris
                            </x-secondary-button>

                            <x-primary-button type="submit">
                                Simpan Semua Peserta Baru
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Daftar Peserta Terdaftar (<span
                            class="font-bold">{{ $pesertaTerdaftar->count() }}</span>)</h3>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        No. Pendaftaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Nama Pendaftar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        No. Telepon</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Akun CBT (Terpakai)</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pesertaTerdaftar as $peserta)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $peserta->nomor_pendaftaran }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $peserta->nama_pendaftar }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $peserta->nomor_telepon }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-mono px-2 py-1 bg-gray-200 rounded">
                                                {{ $peserta->akunCbt->username ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('peserta.destroy', $peserta->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus peserta ini? Akun CBT akan dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button type="submit">
                                                    Hapus
                                                </x-danger-button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">
                                            Belum ada peserta yang terdaftar untuk jadwal ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
