<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Soal TPA ID: ') }} {{ $soal->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('tpa-soal.update', $soal->id) }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="tpa_grup_soal_id" value="Grup Soal (Kategori)" />
                        <select id="tpa_grup_soal_id" name="tpa_grup_soal_id"
                            class="border-gray-300 ... mt-1 block w-full" required>
                            <option value="">-- Pilih Grup Soal --</option>
                            @foreach ($grups as $grup)
                                <option value="{{ $grup->id }}"
                                    {{ old('tpa_grup_soal_id', $soal->tpa_grup_soal_id) == $grup->id ? 'selected' : '' }}>
                                    {{ $grup->nama_grup }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('tpa_grup_soal_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="pertanyaan_teks" value="Pertanyaan (Teks)" />
                        <textarea id="pertanyaan_teks" name="pertanyaan_teks" rows="4" class="border-gray-300 ... mt-1 block w-full"
                            required>{{ old('pertanyaan_teks', $soal->pertanyaan_teks) }}</textarea>
                        <x-input-error :messages="$errors->get('pertanyaan_teks')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="gambar_soal" value="Gambar Soal (Opsional, Max 1MB)" />
                        @if ($soal->gambar_soal)
                            <img src="{{ Storage::url($soal->gambar_soal) }}" alt="Soal {{ $soal->id }}"
                                class="w-40 h-auto object-cover rounded my-2">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Kosongkan jika tidak ingin mengubah
                                gambar.</p>
                        @endif
                        <input id="gambar_soal" name="gambar_soal" type="file" accept=".jpg,.jpeg,.png"
                            class="mt-1 block w-full text-sm ... " />
                        <x-input-error :messages="$errors->get('gambar_soal')" class="mt-2" />
                    </div>

                    <hr class="dark:border-gray-700">

                    <div>
                        <x-input-label for="pilihan_a" value="Pilihan A (Wajib)" />
                        <textarea id="pilihan_a" name="pilihan_a" rows="2" class="border-gray-300 ... mt-1 block w-full" required>{{ old('pilihan_a', $soal->pilihan_a) }}</textarea>
                        <x-input-error :messages="$errors->get('pilihan_a')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="pilihan_b" value="Pilihan B (Wajib)" />
                        <textarea id="pilihan_b" name="pilihan_b" rows="2" class="border-gray-300 ... mt-1 block w-full" required>{{ old('pilihan_b', $soal->pilihan_b) }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="pilihan_c" value="Pilihan C (Wajib)" />
                        <textarea id="pilihan_c" name="pilihan_c" rows="2" class="border-gray-300 ... mt-1 block w-full" required>{{ old('pilihan_c', $soal->pilihan_c) }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="pilihan_d" value="Pilihan D (Wajib)" />
                        <textarea id="pilihan_d" name="pilihan_d" rows="2" class="border-gray-300 ... mt-1 block w-full" required>{{ old('pilihan_d', $soal->pilihan_d) }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="pilihan_e" value="Pilihan E (Wajib)" />
                        <textarea id="pilihan_e" name="pilihan_e" rows="2" class="border-gray-300 ... mt-1 block w-full" required>{{ old('pilihan_e', $soal->pilihan_e) }}</textarea>
                    </div>

                    <hr class="dark:border-gray-700">

                    <div>
                        <x-input-label for="jawaban_benar" value="Kunci Jawaban Benar" />
                        <select id="jawaban_benar" name="jawaban_benar" class="border-gray-300 ... mt-1 block w-full"
                            required>
                            <option value="A"
                                {{ old('jawaban_benar', $soal->jawaban_benar) == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B"
                                {{ old('jawaban_benar', $soal->jawaban_benar) == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C"
                                {{ old('jawaban_benar', $soal->jawaban_benar) == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D"
                                {{ old('jawaban_benar', $soal->jawaban_benar) == 'D' ? 'selected' : '' }}>D</option>
                            <option value="E"
                                {{ old('jawaban_benar', $soal->jawaban_benar) == 'E' ? 'selected' : '' }}>E</option>
                        </select>
                        <x-input-error :messages="$errors->get('jawaban_benar')" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('tpa-soal.index') }}"><x-secondary-button
                                type="button">Batal</x-secondary-button></a>
                        <x-primary-button>Update Soal</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
