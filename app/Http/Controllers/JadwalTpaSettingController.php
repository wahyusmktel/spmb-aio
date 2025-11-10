<?php

namespace App\Http\Controllers;

use App\Models\JadwalSeleksi;
use App\Models\TpaGrupSoal;
use Illuminate\Http\Request;

class JadwalTpaSettingController extends Controller
{
    /**
     * Tampilkan halaman (ceklis)
     */
    public function index(JadwalSeleksi $jadwal)
    {
        // 1. (BARU) Hitung jumlah peserta di jadwal ini
        $jadwal->loadCount('pesertaSeleksi');

        // 2. Ambil semua grup soal yang aktif
        $semuaGrupSoal = TpaGrupSoal::where('status_aktif', true)->get();

        // 3. Ambil grup soal yang SUDAH terhubung (FIXED)
        $grupTerpilihIds = $jadwal->tpaGrupSoals()->pluck('tpa_grup_soals.id')->toArray();

        // Kirim $jadwal (yang sekarang berisi 'peserta_seleksi_count') ke view
        return view('jadwal-tpa-setting.index', compact('jadwal', 'semuaGrupSoal', 'grupTerpilihIds'));
    }

    /**
     * Simpan (sync) ceklis
     */
    public function sync(Request $request, JadwalSeleksi $jadwal)
    {
        $request->validate([
            'grup_soals' => 'nullable|array',
            'grup_soals.*' => 'exists:tpa_grup_soals,id' // Pastikan ID-nya valid
        ]);

        // Sync:
        // Ceklis yang ada -> di-attach
        // Ceklis yang hilang -> di-detach
        $jadwal->tpaGrupSoals()->sync($request->input('grup_soals', []));

        return redirect()->route('jadwal-tpa-setting.index', $jadwal->id)
            ->with('success', 'Setting Grup Soal TPA untuk jadwal ini berhasil disimpan.');
    }
}
