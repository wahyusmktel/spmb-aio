<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Soal TPA Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('tpa-soal.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="tpa_grup_soal_id" value="Grup Soal (Kategori)" />
                        <select id="tpa_grup_soal_id" name="tpa_grup_soal_id"
                            class="border-gray-300 ... mt-1 block w-full" required>
                            <option value="">-- Pilih Grup Soal --</option>
                            @foreach ($grups as $grup)
                                <option value="{{ $grup->id }}"
                                    {{ old('tpa_grup_soal_id') == $grup->id ? 'selected' : '' }}>{{ $grup->nama_grup }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('tpa_grup_soal_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="pertanyaan_teks" value="Pertanyaan (Teks)" />
                        <textarea id="pertanyaan_teks" name="pertanyaan_teks" rows="4" class="border-gray-300 ... mt-1 block w-full"
                            required>{{ old('pertanyaan_teks') }}</textarea>
                        <x-input-error :messages="$errors->get('pertanyaan_teks')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="gambar_soal" value="Gambar Soal (Opsional, Max 1MB)" />
                        <input id="gambar_soal" name="gambar_soal" type="file" accept=".jpg,.jpeg,.png"
                            class="mt-1 block w-full text-sm ... " />
                        <x-input-error :messages="$errors->get('gambar_soal')" class="mt-2" />
                    </div>

                    <hr class="dark:border-gray-700">

                    <div>
                        <x-input-label for="pilihan_a" value="Pilihan A (Wajib)" />
                        <textarea id="pilihan_a" name="pilihan_a" rows="2" class="border-gray-300 ... mt-1 block w-full" required>{{ old('pilihan_a') }}</textarea>
                        <x-input-error :messages="$errors->get('pilihan_a')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="pilihan_b" value="Pilihan B (Wajib)" />
                        <textarea id="pilihan_b" name="pilihan_b" rows="2" class="border-gray-300 ... mt-1 block w-full" required>{{ old('pilihan_b') }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="pilihan_c" value="Pilihan C (Wajib)" />
                        <textarea id="pilihan_c" name="pilihan_c" rows="2" class="border-gray-300 ... mt-1 block w-full" required>{{ old('pilihan_c') }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="pilihan_d" value="Pilihan D (Wajib)" />
                        <textarea id="pilihan_d" name="pilihan_d" rows="2" class="border-gray-300 ... mt-1 block w-full" required>{{ old('pilihan_d') }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="pilihan_e" value="Pilihan E (Wajib)" />
                        <textarea id="pilihan_e" name="pilihan_e" rows="2" class="border-gray-300 ... mt-1 block w-full" required>{{ old('pilihan_e') }}</textarea>
                    </div>

                    <hr class="dark:border-gray-700">

                    <div>
                        <x-input-label for="jawaban_benar" value="Kunci Jawaban Benar" />
                        <select id="jawaban_benar" name="jawaban_benar" class="border-gray-300 ... mt-1 block w-full"
                            required>
                            <option value="">-- Pilih Kunci --</option>
                            <option value="A" {{ old('jawaban_benar') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('jawaban_benar') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C" {{ old('jawaban_benar') == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D" {{ old('jawaban_benar') == 'D' ? 'selected' : '' }}>D</option>
                            <option value="E" {{ old('jawaban_benar') == 'E' ? 'selected' : '' }}>E</option>
                        </select>
                        <x-input-error :messages="$errors->get('jawaban_benar')" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('tpa-soal.index') }}"><x-secondary-button
                                type="button">Batal</x-secondary-button></a>
                        <x-primary-button>Simpan Soal</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
