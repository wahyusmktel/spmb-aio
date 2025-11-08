<?php

namespace App\Http\Controllers;

use App\Models\JadwalSeleksi;
use Illuminate\Http\Request;

class PengajuanSuratController extends Controller
{
    /**
     * Tampilkan halaman daftar pengajuan (hanya yang 'menunggu')
     */
    public function index()
    {
        $pengajuan = JadwalSeleksi::where('status', 'menunggu')
            ->with('tahunPelajaran')
            ->latest()
            ->paginate(10);

        return view('pengajuan-surat.index', compact('pengajuan'));
    }

    /**
     * Update jadwal, terbitkan Nomor Surat Tugas
     */
    public function terbitkan(Request $request, JadwalSeleksi $jadwal)
    {
        // Validasi input NST dari Staff TU
        $request->validate([
            'nomor_surat_tugas' => 'required|string|max:255',
        ]);

        $request->request->add(['form_type' => 'terbitkan']);

        // Update jadwalnya
        $jadwal->update([
            'nomor_surat_tugas' => $request->nomor_surat_tugas,
            'status' => 'diterbitkan',
            'published_by_user_id' => auth()->id(), // Catat siapa yang nerbitin
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Nomor Surat Tugas berhasil diterbitkan.');
    }
}
