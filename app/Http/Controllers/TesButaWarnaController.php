<?php

namespace App\Http\Controllers;

use App\Models\SoalButaWarna;
use App\Models\HasilButaWarna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TesButaWarnaController extends Controller
{
    // Helper untuk ambil model Peserta
    private function getPeserta()
    {
        return Auth::guard('peserta')->user()->pesertaSeleksi;
    }

    /**
     * Halaman index: Cek status, tampilkan tombol "Mulai" atau "Hasil"
     */
    public function index()
    {
        $peserta = $this->getPeserta();

        // Eager load hasil jika sudah mengerjakan
        if ($peserta->status_tes_buta_warna) {
            $peserta->load('hasilButaWarna.soalButaWarna');
        }

        return view('peserta.tes-buta-warna.index', compact('peserta'));
    }

    /**
     * Halaman pengerjaan soal
     */
    public function kerjakan()
    {
        $peserta = $this->getPeserta();

        // Cek lagi, jangan sampai mengerjakan ulang
        if ($peserta->status_tes_buta_warna) {
            return redirect()->route('tes-buta-warna.index');
        }

        // Ambil semua soal (atau 10 soal acak)
        $soals = SoalButaWarna::inRandomOrder()->take(10)->get();
        // Jika ingin semua soal, ganti dengan:
        // $soals = SoalButaWarna::all();

        if ($soals->isEmpty()) {
            return redirect()->route('peserta.dashboard')->with('error', 'Admin belum menginput soal.');
        }

        return view('peserta.tes-buta-warna.kerjakan', compact('soals'));
    }

    /**
     * Proses submit jawaban
     */
    public function submit(Request $request)
    {
        $peserta = $this->getPeserta();

        // Validasi double-submit
        if ($peserta->status_tes_buta_warna) {
            return redirect()->route('tes-buta-warna.index');
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:A,B,C,D,E', // Pastikan jawabannya valid
        ]);

        // Ambil kunci jawaban dari DB
        $soals = SoalButaWarna::whereIn('id', array_keys($request->answers))->get();

        // JANGAN hitung $totalSoal di sini

        $totalBenar = 0;
        $hasilBatch = [];

        try {
            // Modifikasi 'use' (kita tidak perlu $totalSoal dari luar)
            DB::transaction(function () use ($soals, $request, $peserta, &$totalBenar, &$hasilBatch) {

                // === PERBAIKAN: Definisikan $totalSoal DI DALAM closure ===
                $totalSoal = $soals->count();
                // =======================================================

                foreach ($soals as $soal) {
                    $jawabanPeserta = $request->answers[$soal->id] ?? null;
                    $isBenar = ($jawabanPeserta === $soal->jawaban_benar);

                    if ($isBenar) {
                        $totalBenar++;
                    }

                    // Kumpulkan data untuk batch insert
                    $hasilBatch[] = [
                        'peserta_seleksi_id' => $peserta->id,
                        'soal_buta_warna_id' => $soal->id,
                        'jawaban_peserta' => $jawabanPeserta,
                        'is_benar' => $isBenar,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // 1. Simpan semua jawaban
                HasilButaWarna::insert($hasilBatch);

                // 2. Hitung Nilai (Skala 100)
                // BARIS INI SEKARANG SUDAH AMAN KARENA $totalSoal ADA
                $nilai = ($totalSoal > 0) ? round(($totalBenar / $totalSoal) * 100) : 0;

                // 3. Update status & nilai peserta
                $peserta->update([
                    'status_tes_buta_warna' => true,
                    'nilai_tes_buta_warna' => $nilai,
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->route('tes-buta-warna.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        // Redirect ke halaman hasil
        return redirect()->route('tes-buta-warna.index')->with('success', 'Tes berhasil diselesaikan!');
    }
}
