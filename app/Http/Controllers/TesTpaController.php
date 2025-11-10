<?php

namespace App\Http\Controllers;

use App\Models\HasilTpa;
use App\Models\JadwalSeleksi;
use App\Models\SoalButaWarna;
use App\Models\TpaSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TesTpaController extends Controller
{
    // Helper untuk ambil model Peserta
    private function getPeserta()
    {
        // Ambil akun CBT yg login, lalu ambil data peserta-nya
        return Auth::guard('peserta')->user()->pesertaSeleksi;
    }

    /**
     * Halaman index: Cek status, tampilkan tombol "Mulai" atau "Hasil"
     */
    public function index()
    {
        $peserta = $this->getPeserta();

        // Cek apakah jadwal ini ada setting TPA
        $jadwal = $peserta->jadwalSeleksi;
        $jadwal->loadCount('tpaGrupSoals');

        if ($jadwal->tpa_grup_soals_count == 0) {
            // Jika Admin belum setting soal untuk jadwal ini
            return view('peserta.tes-tpa.index', ['jadwal' => $jadwal, 'peserta' => $peserta]);
        }

        // Jika sudah mengerjakan, tampilkan hasil
        if ($peserta->status_tes_tpa) {
            $peserta->load('hasilTpa.tpaSoal.tpaGrupSoal'); // Load relasi bertingkat

            // Kelompokkan hasil berdasarkan Grup Soal
            $hasilPerGrup = $peserta->hasilTpa->groupBy('tpaSoal.tpaGrupSoal.nama_grup');

            return view('peserta.tes-tpa.index', compact('peserta', 'jadwal', 'hasilPerGrup'));
        }

        // Jika belum, tampilkan halaman "Mulai"
        return view('peserta.tes-tpa.index', compact('peserta', 'jadwal'));
    }

    /**
     * Halaman pengerjaan soal (Halaman CBT Utama)
     */
    public function kerjakan()
    {
        $peserta = $this->getPeserta();
        $jadwal = $peserta->jadwalSeleksi;

        // 1. Cek double-submit
        if ($peserta->status_tes_tpa) {
            return redirect()->route('tes-tpa.index');
        }

        // 2. Cek apakah Admin sudah mengatur timer
        if (!$jadwal->waktu_tpa_menit) {
            return redirect()->route('tes-tpa.index')->with('error', 'Admin belum mengatur durasi pengerjaan untuk tes ini.');
        }

        // 3. LOGIKA TIMER
        $waktuMulai = $peserta->tpa_mulai_at;
        $durasiMenit = $jadwal->waktu_tpa_menit;

        if (is_null($waktuMulai)) {
            // === PERTAMA KALI MEMBUKA TES ===
            // Set waktu mulai SEKARANG
            $waktuMulai = now();
            $peserta->update(['tpa_mulai_at' => $waktuMulai]);
        }

        // 4. Hitung waktu selesai
        $waktuSelesai = $waktuMulai->copy()->addMinutes($durasiMenit);

        // 5. Hitung sisa detik (false = boleh negatif)
        $sisaDetik = Carbon::now()->diffInSeconds($waktuSelesai, false);

        // Jika sisa detik negatif (waktu habis saat refresh), paksa 0
        if ($sisaDetik < 0) {
            $sisaDetik = 0;
        }

        // 6. Ambil grup soal (seperti sebelumnya)
        $grupSoals = $jadwal->tpaGrupSoals()
            ->with(['tpaSoals' => fn($q) => $q->inRandomOrder()])
            ->get();

        if ($grupSoals->isEmpty()) {
            return redirect()->route('peserta.dashboard')->with('error', 'Admin belum mengatur soal TPA untuk jadwal ini.');
        }

        // 7. Kirim $sisaDetik ke view
        return view('peserta.tes-tpa.kerjakan', [
            'grupSoals' => $grupSoals,
            'sisaDetik' => $sisaDetik
        ]);
    }

    /**
     * Proses submit semua jawaban
     */
    public function submit(Request $request)
    {
        $peserta = $this->getPeserta();

        // Validasi double-submit
        if ($peserta->status_tes_tpa) {
            return redirect()->route('tes-tpa.index');
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:A,B,C,D,E',
        ]);

        $allAnswers = $request->answers;

        // Ambil semua ID soal yang dijawab
        $soalIds = array_keys($allAnswers);
        $soals = TpaSoal::whereIn('id', $soalIds)->get()->keyBy('id'); // Ambil kunci jawaban

        $totalSoal = $soals->count();
        $totalBenar = 0;
        $hasilBatch = [];

        try {
            DB::transaction(function () use ($soals, $allAnswers, $peserta, &$totalBenar, &$hasilBatch, $totalSoal) {

                foreach ($allAnswers as $soalId => $jawabanPeserta) {
                    $soal = $soals->get($soalId);
                    if (!$soal) continue; // Skip jika soal tidak ditemukan

                    $isBenar = ($jawabanPeserta === $soal->jawaban_benar);

                    if ($isBenar) {
                        $totalBenar++;
                    }

                    $hasilBatch[] = [
                        'peserta_seleksi_id' => $peserta->id,
                        'tpa_soal_id' => $soalId,
                        'jawaban_peserta' => $jawabanPeserta,
                        'is_benar' => $isBenar,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // 1. Simpan semua jawaban
                HasilTpa::insert($hasilBatch);

                // 2. Hitung Nilai
                $nilai = ($totalSoal > 0) ? round(($totalBenar / $totalSoal) * 100) : 0;

                // 3. Update status & nilai peserta
                $peserta->update([
                    'status_tes_tpa' => true,
                    'nilai_tes_tpa' => $nilai,
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->route('tes-tpa.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->route('tes-tpa.index')->with('success', 'Tes TPA berhasil diselesaikan!');
    }
}
