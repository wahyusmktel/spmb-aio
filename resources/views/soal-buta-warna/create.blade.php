<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Soal Buta Warna Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('soal-buta-warna.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="gambar_soal" value="Gambar Soal (Max 1MB)" />
                        <input id="gambar_soal" name="gambar_soal" type="file" accept=".jpg,.jpeg,.png"
                            class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                            required />
                        <x-input-error :messages="$errors->get('gambar_soal')" class="mt-2" />
                    </div>

                    <hr class="border-gray-200">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="pilihan_a" value="Pilihan A (Wajib)" />
                            <x-text-input id="pilihan_a" name="pilihan_a" type="text" class="mt-1 block w-full"
                                :value="old('pilihan_a')" required />
                            <x-input-error :messages="$errors->get('pilihan_a')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="pilihan_b" value="Pilihan B (Wajib)" />
                            <x-text-input id="pilihan_b" name="pilihan_b" type="text" class="mt-1 block w-full"
                                :value="old('pilihan_b')" required />
                            <x-input-error :messages="$errors->get('pilihan_b')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="pilihan_c" value="Pilihan C (Wajib)" />
                            <x-text-input id="pilihan_c" name="pilihan_c" type="text" class="mt-1 block w-full"
                                :value="old('pilihan_c')" required />
                            <x-input-error :messages="$errors->get('pilihan_c')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="pilihan_d" value="Pilihan D (Opsional)" />
                            <x-text-input id="pilihan_d" name="pilihan_d" type="text" class="mt-1 block w-full"
                                :value="old('pilihan_d')" />
                        </div>
                        <div>
                            <x-input-label for="pilihan_e" value="Pilihan E (Opsional)" />
                            <x-text-input id="pilihan_e" name="pilihan_e" type="text" class="mt-1 block w-full"
                                :value="old('pilihan_e')" />
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <div>
                        <x-input-label for="jawaban_benar" value="Kunci Jawaban Benar" />
                        <select id="jawaban_benar" name="jawaban_benar"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
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
                        <a href="{{ route('soal-buta-warna.index') }}"><x-secondary-button
                                type="button">Batal</x-secondary-button></a>
                        <x-primary-button>Simpan Soal</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
