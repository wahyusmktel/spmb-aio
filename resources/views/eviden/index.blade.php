<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Eviden Manual:') }} <span class="font-bold">{{ $jadwal->judul_kegiatan }}</span>
        </h2>
        <a href="{{ route('jadwal-seleksi.index') }}" class="text-sm text-blue-500 hover:underline">&larr; Kembali ke
            Jadwal Seleksi</a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <strong>Oops! Gagal mengunggah:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($jadwal->peserta_seleksi_count > 0 && $jadwal->penugasan_petugas_count > 0)

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form action="{{ route('eviden.store.hadir-peserta', $jadwal->id) }}" method="POST"
                            enctype="multipart/form-data" class="p-6">
                            @csrf
                            <h3 class="text-lg font-medium text-gray-900">Daftar Hadir Peserta (Scan)
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Upload file .jpg/.jpeg hasil scan.
                            </p>

                            @if ($jadwal->file_hadir_peserta)
                                <div class="mt-4">
                                    <a href="{{ Storage::url($jadwal->file_hadir_peserta) }}" target="_blank"
                                        class="text-sm font-medium text-green-600 hover:underline">
                                        Lihat File Terunggah
                                    </a>
                                </div>
                            @endif

                            <div class="mt-4">
                                <x-input-label for="file_upload_peserta" value="Pilih File (Maks 2MB)" />
                                <input id="file_upload_peserta" name="file_upload" type="file" accept=".jpg,.jpeg"
                                    class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                    required />
                            </div>
                            <x-primary-button class="mt-4">
                                Upload
                            </x-primary-button>
                        </form>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form action="{{ route('eviden.store.hadir-petugas', $jadwal->id) }}" method="POST"
                            enctype="multipart/form-data" class="p-6">
                            @csrf
                            <h3 class="text-lg font-medium text-gray-900">Daftar Hadir Petugas (Scan)
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Upload file .jpg/.jpeg hasil scan.
                            </p>

                            @if ($jadwal->file_hadir_petugas)
                                <div class="mt-4">
                                    <a href="{{ Storage::url($jadwal->file_hadir_petugas) }}" target="_blank"
                                        class="text-sm font-medium text-green-600 hover:underline">
                                        Lihat File Terunggah
                                    </a>
                                </div>
                            @endif

                            <div class="mt-4">
                                <x-input-label for="file_upload_petugas" value="Pilih File (Maks 2MB)" />
                                <input id="file_upload_petugas" name="file_upload" type="file" accept=".jpg,.jpeg"
                                    class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                    required />
                            </div>
                            <x-primary-button class="mt-4">
                                Upload
                            </x-primary-button>
                        </form>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form action="{{ route('eviden.store.berita-acara', $jadwal->id) }}" method="POST"
                            enctype="multipart/form-data" class="p-6">
                            @csrf
                            <h3 class="text-lg font-medium text-gray-900">Berita Acara (Scan)</h3>
                            <p class="text-sm text-gray-600 mt-1">Upload file .jpg/.jpeg hasil scan.
                            </p>

                            @if ($jadwal->file_berita_acara)
                                <div class="mt-4">
                                    <a href="{{ Storage::url($jadwal->file_berita_acara) }}" target="_blank"
                                        class="text-sm font-medium text-green-600 hover:underline">
                                        Lihat File Terunggah
                                    </a>
                                </div>
                            @endif

                            <div class="mt-4">
                                <x-input-label for="file_upload_ba" value="Pilih File (Maks 2MB)" />
                                <input id="file_upload_ba" name="file_upload" type="file" accept=".jpg,.jpeg"
                                    class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                    required />
                            </div>
                            <x-primary-button class="mt-4">
                                Upload
                            </x-primary-button>
                        </form>
                    </div>

                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div classid="alert-warning" class="p-4 text-sm text-yellow-800 rounded-lg bg-yellow-50"
                            role="alert">
                            <span class="font-medium">Perhatian!</span> Eviden belum dapat diunggah.
                            <p class="mt-2">
                                Pastikan Anda sudah menetapkan **Peserta Seleksi** dan **Petugas** untuk jadwal ini
                                sebelum mengunggah file eviden manual.
                            </p>
                            <ul class="list-disc list-inside mt-2">
                                @if ($jadwal->peserta_seleksi_count == 0)
                                    <li><a href="{{ route('peserta.index', $jadwal->id) }}"
                                            class="font-bold underline hover:text-yellow-900 mt-2 inline-block">Data
                                            Peserta masih kosong. Klik untuk melengkapi.</a></li>
                                @endif
                                @if ($jadwal->penugasan_petugas_count == 0)
                                    <li><a href="{{ route('penugasan.index', $jadwal->id) }}"
                                            class="font-bold underline hover:text-yellow-900 mt-2 inline-block">Data
                                            Petugas masih kosong. Klik untuk melengkapi.</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
