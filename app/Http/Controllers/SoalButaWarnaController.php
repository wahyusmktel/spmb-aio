<?php

namespace App\Http\Controllers;

use App\Models\SoalButaWarna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- PENTING

class SoalButaWarnaController extends Controller
{
    /**
     * Halaman daftar soal
     */
    public function index()
    {
        // Ambil soal, urutkan berdasarkan ID
        $soals = SoalButaWarna::orderBy('id')->paginate(10);
        return view('soal-buta-warna.index', compact('soals'));
    }

    /**
     * Halaman form tambah soal
     */
    public function create()
    {
        return view('soal-buta-warna.create');
    }

    /**
     * Simpan soal baru
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'gambar_soal' => 'required|image|mimes:jpg,jpeg,png|max:1024', // Wajib gambar, maks 1MB
            'pilihan_a' => 'required|string|max:50',
            'pilihan_b' => 'required|string|max:50',
            'pilihan_c' => 'required|string|max:50',
            'pilihan_d' => 'nullable|string|max:50',
            'pilihan_e' => 'nullable|string|max:50',
            'jawaban_benar' => 'required|in:A,B,C,D,E',
        ]);

        // 1. Simpan gambar
        $path = $request->file('gambar_soal')->store('soal-buta-warna', 'public');
        $validatedData['gambar_soal'] = $path;

        // 2. Simpan ke database
        SoalButaWarna::create($validatedData);

        return redirect()->route('soal-buta-warna.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    /**
     * Halaman form edit soal
     */
    public function edit(SoalButaWarna $soalButaWarna)
    {
        return view('soal-buta-warna.edit', ['soal' => $soalButaWarna]);
    }

    /**
     * Update soal
     */
    public function update(Request $request, SoalButaWarna $soalButaWarna)
    {
        $validatedData = $request->validate([
            'gambar_soal' => 'nullable|image|mimes:jpg,jpeg,png|max:1024', // TIDAK wajib saat update
            'pilihan_a' => 'required|string|max:50',
            'pilihan_b' => 'required|string|max:50',
            'pilihan_c' => 'required|string|max:50',
            'pilihan_d' => 'nullable|string|max:50',
            'pilihan_e' => 'nullable|string|max:50',
            'jawaban_benar' => 'required|in:A,B,C,D,E',
        ]);

        // Cek jika ada file gambar baru
        if ($request->hasFile('gambar_soal')) {
            // 1. Hapus gambar lama
            if ($soalButaWarna->gambar_soal) {
                Storage::disk('public')->delete($soalButaWarna->gambar_soal);
            }
            // 2. Simpan gambar baru
            $path = $request->file('gambar_soal')->store('soal-buta-warna', 'public');
            $validatedData['gambar_soal'] = $path;
        }

        // 3. Update database
        $soalButaWarna->update($validatedData);

        return redirect()->route('soal-buta-warna.index')->with('success', 'Soal berhasil diupdate.');
    }

    /**
     * Hapus soal
     */
    public function destroy(SoalButaWarna $soalButaWarna)
    {
        // Hapus gambar dari storage
        if ($soalButaWarna->gambar_soal) {
            Storage::disk('public')->delete($soalButaWarna->gambar_soal);
        }

        // Hapus data dari database
        $soalButaWarna->delete();

        return redirect()->route('soal-buta-warna.index')->with('success', 'Soal berhasil dihapus.');
    }
}
