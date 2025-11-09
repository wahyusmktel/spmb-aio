<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PesertaSeleksi;
use App\Models\JadwalSeleksi;
use Barryvdh\DomPDF\Facade\Pdf; // <-- Jangan lupa

class LaporanController extends Controller
{
    /**
     * Helper function untuk mengambil data rekap
     */
    private function getRekapDataPeserta(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');

        // Query dasar
        $query = PesertaSeleksi::with([
            'jadwalSeleksi:id,judul_kegiatan,id_tahun_pelajaran,tanggal_mulai_pelaksanaan', // Ambil kolom yg perlu saja
            'jadwalSeleksi.tahunPelajaran:id,nama_tahun_pelajaran',
            'akunCbt:id,username'
        ]);

        // Filter berdasarkan rentang tanggal JADWAL (jika diisi)
        if ($tanggal_mulai && $tanggal_akhir) {
            $query->whereHas('jadwalSeleksi', function ($q) use ($tanggal_mulai, $tanggal_akhir) {
                // Filter berdasarkan 'tanggal_mulai_pelaksanaan' dari JADWAL
                $q->whereBetween('tanggal_mulai_pelaksanaan', [$tanggal_mulai, $tanggal_akhir]);
            });
        }

        // Ambil data dan urutkan
        return $query->get()->sortBy('jadwalSeleksi.tanggal_mulai_pelaksanaan');
    }

    /**
     * Tampilkan halaman filter & preview
     */
    public function indexPeserta(Request $request)
    {
        $peserta = null;
        $stats = null;
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');

        // Hanya jalankan query jika user menekan tombol "Tampilkan Preview"
        if ($request->has('preview')) {
            $peserta = $this->getRekapDataPeserta($request);

            if ($peserta) {
                $stats = [
                    'total' => $peserta->count(),
                    'hadir' => $peserta->where('kehadiran', true)->count(),
                    'tidak_hadir' => $peserta->where('kehadiran', false)->count(),
                ];
            }
        }

        return view('laporan.peserta', compact('peserta', 'stats', 'tanggal_mulai', 'tanggal_akhir'));
    }

    /**
     * Handle download PDF
     */
    public function downloadPeserta(Request $request)
    {
        $peserta = $this->getRekapDataPeserta($request);
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');

        // Validasi
        if ($peserta->isEmpty()) {
            return redirect()->route('laporan.peserta.index')->with('error', 'Tidak ada data peserta untuk kriteria yang dipilih.');
        }

        // Hitung Statistik
        $stats = [
            'total' => $peserta->count(),
            'hadir' => $peserta->where('kehadiran', true)->count(),
            'tidak_hadir' => $peserta->where('kehadiran', false)->count(),
        ];

        $pdf = Pdf::loadView('downloads.rekap-peserta', compact('peserta', 'stats', 'tanggal_mulai', 'tanggal_akhir'));

        // Gunakan landscape (horizontal) agar tabel muat
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Rekapitulasi_Peserta.pdf');
    }
}
