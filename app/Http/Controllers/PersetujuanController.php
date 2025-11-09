<?php

namespace App\Http\Controllers;

use App\Models\JadwalSeleksi;
use Illuminate\Http\Request;

class PersetujuanController extends Controller
{
    /**
     * Tampilkan halaman daftar persetujuan
     */
    public function index()
    {
        // Tampilkan jadwal yang statusnya 'menunggu_acc'
        // DAN HANYA yang 'id_penandatangan'-nya adalah user yang sedang login
        $persetujuan = JadwalSeleksi::where('status', 'menunggu_acc')
            ->where('id_penandatangan', auth()->id())
            ->with('tahunPelajaran')
            ->latest()
            ->paginate(10);

        return view('persetujuan.index', compact('persetujuan'));
    }

    /**
     * Setujui (Approve) jadwal
     */
    public function approve(JadwalSeleksi $jadwal)
    {
        // Security check: pastikan yang approve adalah penandatangan yang sah
        if ($jadwal->id_penandatangan !== auth()->id()) {
            return redirect()->route('persetujuan.index')->with('error', 'Anda tidak berwenang menyetujui jadwal ini.');
        }

        // Update status jadi 'diterbitkan'
        $jadwal->update([
            'status' => 'diterbitkan',
            // Kita bisa tambah 'approved_at' => now() jika ada kolomnya
        ]);

        return redirect()->route('persetujuan.index')->with('success', 'Surat Perintah Tugas berhasil disetujui dan diterbitkan.');
    }
}
