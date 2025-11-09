<?php

namespace App\Http\Controllers;

use App\Models\JadwalSeleksi;
use App\Models\PesertaSeleksi;
use App\Models\PenugasanPetugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- PENTING
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiController extends Controller
{
    /**
     * Tampilkan halaman form absensi (ceklis)
     */
    public function index(JadwalSeleksi $jadwal)
    {
        // 1. Ambil data jadwal
        $jadwal->load('tahunPelajaran');

        // 2. Ambil daftar peserta
        $peserta = PesertaSeleksi::where('id_jadwal_seleksi', $jadwal->id)
            ->orderBy('nama_pendaftar')
            ->get();

        // 3. Ambil daftar petugas
        $petugas = PenugasanPetugas::with('guru', 'referensiTugas')
            ->where('id_jadwal_seleksi', $jadwal->id)
            ->get()
            ->sortBy(fn($p) => $p->guru->nama_guru);

        return view('absensi.index', compact('jadwal', 'peserta', 'petugas'));
    }

    /**
     * Simpan data absensi
     */
    public function store(Request $request, JadwalSeleksi $jadwal)
    {
        // Data yang masuk adalah 2 array:
        // $request->hadir_peserta (berisi ID peserta yang hadir)
        // $request->hadir_petugas (berisi ID petugas yang hadir)

        try {
            DB::transaction(function () use ($request, $jadwal) {

                // 1. RESET DULU: Set semua ke 'false' (tidak hadir)
                PesertaSeleksi::where('id_jadwal_seleksi', $jadwal->id)
                    ->update(['kehadiran' => false]);

                PenugasanPetugas::where('id_jadwal_seleksi', $jadwal->id)
                    ->update(['absensi_admin' => false]);

                // 2. SET HADIR: Update yang diceklis (hadir) menjadi 'true'
                if ($request->has('hadir_peserta')) {
                    PesertaSeleksi::whereIn('id', $request->hadir_peserta)
                        ->update(['kehadiran' => true]);
                }

                if ($request->has('hadir_petugas')) {
                    PenugasanPetugas::whereIn('id', $request->hadir_petugas)
                        ->update(['absensi_admin' => true]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan. ' . $e->getMessage());
        }

        return redirect()->route('absensi.index', $jadwal->id)->with('success', 'Absensi by sistem berhasil disimpan.');
    }

    /**
     * === METHOD BARU: DOWNLOAD LAPORAN HADIR PESERTA ===
     */
    public function downloadLaporanPeserta(JadwalSeleksi $jadwal)
    {
        // 1. Ambil data jadwal
        $jadwal->load('tahunPelajaran', 'penandatangan');

        // 2. Ambil data peserta
        $peserta = PesertaSeleksi::where('id_jadwal_seleksi', $jadwal->id)
            ->orderBy('nama_pendaftar')
            ->get();

        if ($peserta->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada peserta di jadwal ini.');
        }

        // 3. Hitung Statistik
        $stats = [
            'total' => $peserta->count(),
            'hadir' => $peserta->where('kehadiran', true)->count(),
            'tidak_hadir' => $peserta->where('kehadiran', false)->count(),
        ];

        // 4. Load PDF
        $pdf = Pdf::loadView('downloads.laporan-hadir-peserta', compact('jadwal', 'peserta', 'stats'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Hadir_Peserta_' . $jadwal->judul_kegiatan . '.pdf');
    }

    /**
     * === METHOD BARU: DOWNLOAD LAPORAN HADIR PETUGAS ===
     */
    public function downloadLaporanPetugas(JadwalSeleksi $jadwal)
    {
        // 1. Ambil data jadwal
        $jadwal->load('tahunPelajaran', 'penandatangan');

        // 2. Ambil data petugas
        $petugas = PenugasanPetugas::with('guru', 'referensiTugas')
            ->where('id_jadwal_seleksi', $jadwal->id)
            ->get()
            ->sortBy(fn($p) => $p->guru->nama_guru);

        if ($petugas->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada petugas di jadwal ini.');
        }

        // 3. Hitung Statistik
        $stats = [
            'total' => $petugas->count(),
            'hadir' => $petugas->where('absensi_admin', true)->count(),
            'tidak_hadir' => $petugas->where('absensi_admin', false)->count(),
        ];

        // 4. Load PDF
        $pdf = Pdf::loadView('downloads.laporan-hadir-petugas', compact('jadwal', 'petugas', 'stats'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Hadir_Petugas_' . $jadwal->judul_kegiatan . '.pdf');
    }
}
