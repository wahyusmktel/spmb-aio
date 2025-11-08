<?php

namespace App\Http\Controllers;

use App\Models\JadwalSeleksi;
use App\Models\PenugasanPetugas;
use App\Models\Guru;
use App\Models\ReferensiTugas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- PENTING untuk validasi unique

class PenugasanPetugasController extends Controller
{
    /**
     * Tampilkan halaman utama (Daftar Petugas per Jadwal)
     */
    public function index(JadwalSeleksi $jadwal)
    {
        // 1. Ambil data jadwal (sudah di-inject)
        // 2. Ambil daftar penugasan untuk jadwal ini
        $penugasan = PenugasanPetugas::with('guru', 'referensiTugas')
                        ->where('id_jadwal_seleksi', $jadwal->id)
                        ->get();

        // 3. Ambil daftar guru (untuk dropdown tambah)
        $gurus = Guru::orderBy('nama_guru')->get();

        // 4. Ambil daftar referensi tugas (yang aktif saja)
        $referensiTugas = ReferensiTugas::where('status', true)->orderBy('deskripsi_tugas')->get();

        return view('penugasan-petugas.index', compact('jadwal', 'penugasan', 'gurus', 'referensiTugas'));
    }

    /**
     * Simpan penugasan baru (CREATE)
     */
    public function store(Request $request, JadwalSeleksi $jadwal)
    {
        $request->validate([
            'id_guru' => [
                'required',
                'exists:gurus,id',
                // Pastikan guru ini unik HANYA untuk jadwal ini
                Rule::unique('penugasan_petugas')->where(function ($query) use ($jadwal) {
                    return $query->where('id_jadwal_seleksi', $jadwal->id);
                }),
            ],
            'id_referensi_tugas' => 'required|exists:referensi_tugas,id',
        ], [
            // Custom error message
            'id_guru.unique' => 'Guru ini sudah ditugaskan pada jadwal ini.'
        ]);

        $request->request->add(['form_type' => 'create']);

        PenugasanPetugas::create([
            'id_jadwal_seleksi' => $jadwal->id,
            'id_guru' => $request->id_guru,
            'id_referensi_tugas' => $request->id_referensi_tugas,
        ]);

        return redirect()->route('penugasan.index', $jadwal->id)->with('success', 'Petugas berhasil ditambahkan.');
    }

    /**
     * Update penugasan (UPDATE)
     * (Hanya update referensi tugasnya)
     */
    public function update(Request $request, PenugasanPetugas $penugasan)
    {
        $request->validate([
            'id_referensi_tugas' => 'required|exists:referensi_tugas,id',
        ]);

        $request->request->add(['form_type' => 'edit']);

        $penugasan->update([
            'id_referensi_tugas' => $request->id_referensi_tugas,
        ]);

        // Redirect kembali ke halaman index DARI jadwal asalnya
        return redirect()->route('penugasan.index', $penugasan->id_jadwal_seleksi)->with('success', 'Tugas petugas berhasil diupdate.');
    }

    /**
     * Hapus penugasan (DELETE)
     */
    public function destroy(PenugasanPetugas $penugasan)
    {
        $id_jadwal = $penugasan->id_jadwal_seleksi;
        $penugasan->delete();

        return redirect()->route('penugasan.index', $id_jadwal)->with('success', 'Petugas berhasil dihapus.');
    }
}
