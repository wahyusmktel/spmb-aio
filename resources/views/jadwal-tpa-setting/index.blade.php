<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Setting Soal TPA untuk:') }} <span class="font-bold">{{ $jadwal->judul_kegiatan }}</span>
        </h2>
        <a href="{{ route('jadwal-seleksi.index') }}" class="text-sm text-blue-500 hover:underline">&larr; Kembali ke
            Jadwal Seleksi</a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}</div>
            @endif

            @if ($jadwal->peserta_seleksi_count > 0)

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form action="{{ route('jadwal-tpa-setting.sync', $jadwal->id) }}" method="POST">
                        @csrf
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium">Pilih Grup Soal (Bank Soal)</h3>
                            <p class="text-sm text-gray-600">
                                Pilih grup soal TPA (yang berstatus Aktif) yang akan diujikan pada jadwal seleksi ini.
                            </p>

                            <div class="mt-6 space-y-4">
                                @forelse ($semuaGrupSoal as $grup)
                                    <label
                                        class="flex items-center p-4 border rounded-lg border-gray-200 hover:bg-gray-50">
                                        <input type="checkbox" name="grup_soals[]" value="{{ $grup->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{ in_array($grup->id, $grupTerpilihIds) ? 'checked' : '' }}>
                                        <span class="ml-3 text-sm font-medium">{{ $grup->nama_grup }}</span>
                                    </label>
                                @empty
                                    <p class="text-red-500">
                                        Anda harus membuat <a href="{{ route('tpa-grup-soal.index') }}"
                                            class="underline">Grup Soal TPA</a> terlebih dahulu.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <div class="p-6 bg-gray-50 border-t border-gray-200 text-right">
                            <x-primary-button>Simpan Setting</x-primary-button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="p-4 text-sm text-yellow-800 rounded-lg bg-yellow-50" role="alert">
                            <span class="font-medium">Perhatian!</span> Setting soal belum dapat dilakukan.
                            <p class="mt-2">
                                Anda harus **menambahkan peserta** ke jadwal kegiatan ini terlebih dahulu sebelum dapat
                                mengatur soal TPA.
                            </p>
                            <a href="{{ route('peserta.index', $jadwal->id) }}"
                                class="font-bold underline hover:text-yellow-900 mt-2 inline-block">
                                Klik di sini untuk menambahkan peserta &rarr;
                            </a>
                        </div>
                    </div>
                </div>

            @endif
        </div>
    </div>
</x-app-layout>
