<?php

namespace App\Http\Controllers;

use App\Models\JadwalSeleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- PENTING

class EvidenController extends Controller
{
    /**
     * Tampilkan halaman upload form
     */
    public function index(JadwalSeleksi $jadwal)
    {
        // Eager load jumlah relasi
        $jadwal->loadCount(['pesertaSeleksi', 'penugasanPetugas']);

        // Kirim $jadwal yang sudah berisi 'peserta_seleksi_count'
        // dan 'penugasan_petugas_count'
        return view('eviden.index', compact('jadwal'));
    }

    /**
     * Simpan file Hadir Peserta
     */
    public function storeHadirPeserta(Request $request, JadwalSeleksi $jadwal)
    {
        return $this->uploadFile($request, $jadwal, 'file_hadir_peserta', 'Eviden Daftar Hadir Peserta');
    }

    /**
     * Simpan file Hadir Petugas
     */
    public function storeHadirPetugas(Request $request, JadwalSeleksi $jadwal)
    {
        return $this->uploadFile($request, $jadwal, 'file_hadir_petugas', 'Eviden Daftar Hadir Petugas');
    }

    /**
     * Simpan file Berita Acara
     */
    public function storeBeritaAcara(Request $request, JadwalSeleksi $jadwal)
    {
        return $this->uploadFile($request, $jadwal, 'file_berita_acara', 'Eviden Berita Acara');
    }


    /**
     * Helper Function untuk proses upload
     * (Private method untuk menghindari repetisi kode)
     */
    private function uploadFile(Request $request, JadwalSeleksi $jadwal, string $columnName, string $fileType)
    {
        // 1. Validasi: Wajib JPG/JPEG, maks 2MB (2048 KB)
        $request->validate([
            'file_upload' => 'required|image|mimes:jpg,jpeg|max:2048'
        ], [
            'file_upload.required' => 'File belum dipilih.',
            'file_upload.image' => 'File harus berupa gambar.',
            'file_upload.mimes' => 'File harus berekstensi .jpg atau .jpeg',
            'file_upload.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        // 2. Cek file lama (jika ada) dan hapus
        if ($jadwal->$columnName) {
            Storage::disk('public')->delete($jadwal->$columnName);
        }

        // 3. Simpan file baru
        // Path: storage/app/public/eviden/[id_jadwal]/namafile.jpg
        $path = $request->file('file_upload')->store('eviden/' . $jadwal->id, 'public');

        // 4. Update database
        $jadwal->update([
            $columnName => $path
        ]);

        return redirect()->back()->with('success', $fileType . ' berhasil diunggah.');
    }
}
