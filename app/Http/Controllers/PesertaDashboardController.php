<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// (Kita tidak perlu App\Models\ReferensiAkunCbt, Auth akan otomatis ambil)

class PesertaDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil model AkunCBT yang sedang login
        $akun = Auth::guard('peserta')->user();

        // 2. Eager load relasi ke PesertaSeleksi, lalu ke JadwalSeleksi
        $akun->load('pesertaSeleksi.jadwalSeleksi');

        if (!$akun->pesertaSeleksi) {
            // Jika akun ini dibuat tapi belum di-mapping ke data peserta
            // (Seharusnya tidak terjadi jika alur admin benar)
            return view('peserta.dashboard', [
                'akun' => $akun,
                'peserta' => null,
                'jadwal' => null,
            ]);
        }

        // 3. Ambil data spesifik
        $peserta = $akun->pesertaSeleksi;
        $jadwal = $peserta->jadwalSeleksi;

        return view('peserta.dashboard', compact('akun', 'peserta', 'jadwal'));
    }
}
