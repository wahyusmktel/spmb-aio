<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PesertaSeleksi;
use App\Models\JadwalSeleksi;
use App\Models\PenugasanPetugas;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Helper function untuk mengambil data rekap (SUDAH DIMODIFIKASI)
     */
    private function getRekapDataPeserta(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $id_jadwal_seleksi = $request->input('id_jadwal_seleksi'); // <-- Filter baru

        $query = PesertaSeleksi::with([
            'jadwalSeleksi:id,judul_kegiatan,id_tahun_pelajaran,tanggal_mulai_pelaksanaan',
            'jadwalSeleksi.tahunPelajaran:id,nama_tahun_pelajaran',
            'akunCbt:id,username'
        ]);

        // --- LOGIKA FILTER BARU ---
        // Prioritas: Filter berdasarkan ID Kegiatan
        if ($id_jadwal_seleksi) {
            $query->where('id_jadwal_seleksi', $id_jadwal_seleksi);

            // Jika ID Kegiatan tidak diisi, baru filter berdasarkan rentang tanggal
        } elseif ($tanggal_mulai && $tanggal_akhir) {
            $query->whereHas('jadwalSeleksi', function ($q) use ($tanggal_mulai, $tanggal_akhir) {
                $q->whereBetween('tanggal_mulai_pelaksanaan', [$tanggal_mulai, $tanggal_akhir]);
            });
        }
        // Jika tidak ada filter, ambil semua

        return $query->get()->sortBy('jadwalSeleksi.tanggal_mulai_pelaksanaan');
    }

    /**
     * Tampilkan halaman filter & preview (SUDAH DIMODIFIKASI)
     */
    public function indexPeserta(Request $request)
    {
        $peserta = null;
        $stats = null;
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $id_jadwal_seleksi = $request->input('id_jadwal_seleksi');

        // Ambil daftar jadwal (yang statusnya terbit) untuk dropdown
        $jadwals = JadwalSeleksi::where('status', 'diterbitkan')
            ->orderBy('judul_kegiatan')
            ->get(['id', 'judul_kegiatan']);

        // Tampilkan preview jika ada request (baik filter tanggal ATAU filter jadwal)
        if ($request->has('preview') && ($id_jadwal_seleksi || ($tanggal_mulai && $tanggal_akhir))) {
            $peserta = $this->getRekapDataPeserta($request);

            if ($peserta) {
                $stats = [
                    'total' => $peserta->count(),
                    'hadir' => $peserta->where('kehadiran', true)->count(),
                    'tidak_hadir' => $peserta->where('kehadiran', false)->count(),
                ];
            }
        }

        return view('laporan.peserta', compact('peserta', 'stats', 'tanggal_mulai', 'tanggal_akhir', 'id_jadwal_seleksi', 'jadwals'));
    }

    /**
     * Handle download PDF (SUDAH DIMODIFIKASI)
     */
    public function downloadPeserta(Request $request)
    {
        $peserta = $this->getRekapDataPeserta($request);
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $id_jadwal_seleksi = $request->input('id_jadwal_seleksi');

        if ($peserta->isEmpty()) {
            return redirect()->route('laporan.peserta.index')->with('error', 'Tidak ada data peserta untuk kriteria yang dipilih.');
        }

        // Ambil nama jadwal (jika filter by jadwal) untuk judul PDF
        $jadwal_dipilih = null;
        if ($id_jadwal_seleksi) {
            $jadwal_dipilih = JadwalSeleksi::find($id_jadwal_seleksi);
        }

        $stats = [
            'total' => $peserta->count(),
            'hadir' => $peserta->where('kehadiran', true)->count(),
            'tidak_hadir' => $peserta->where('kehadiran', false)->count(),
        ];

        $pdf = Pdf::loadView('downloads.rekap-peserta', compact('peserta', 'stats', 'tanggal_mulai', 'tanggal_akhir', 'jadwal_dipilih'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Rekapitulasi_Peserta.pdf');
    }

    /**
     * Helper function untuk mengambil data rekap PETUGAS
     */
    private function getRekapDataPetugas(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $id_jadwal_seleksi = $request->input('id_jadwal_seleksi');

        // Query dasar
        $query = PenugasanPetugas::with([
            'jadwalSeleksi:id,judul_kegiatan,id_tahun_pelajaran,tanggal_mulai_pelaksanaan',
            'jadwalSeleksi.tahunPelajaran:id,nama_tahun_pelajaran',
            'guru:id,nama_guru,nip,mata_pelajaran', // Ambil relasi guru
            'referensiTugas:id,deskripsi_tugas' // Ambil relasi tugas
        ]);

        // Filter Prioritas: Berdasarkan ID Kegiatan
        if ($id_jadwal_seleksi) {
            $query->where('id_jadwal_seleksi', $id_jadwal_seleksi);

            // Filter Alternatif: Berdasarkan Rentang Tanggal
        } elseif ($tanggal_mulai && $tanggal_akhir) {
            $query->whereHas('jadwalSeleksi', function ($q) use ($tanggal_mulai, $tanggal_akhir) {
                $q->whereBetween('tanggal_mulai_pelaksanaan', [$tanggal_mulai, $tanggal_akhir]);
            });
        }

        return $query->get()->sortBy('jadwalSeleksi.tanggal_mulai_pelaksanaan');
    }

    /**
     * Tampilkan halaman filter & preview PETUGAS
     */
    public function indexPetugas(Request $request)
    {
        $petugas = null;
        $stats = null;
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $id_jadwal_seleksi = $request->input('id_jadwal_seleksi');

        // Ambil daftar jadwal (sama seperti laporan peserta)
        $jadwals = JadwalSeleksi::where('status', 'diterbitkan')
            ->orderBy('judul_kegiatan')
            ->get(['id', 'judul_kegiatan']);

        // Tampilkan preview jika ada filter
        if ($request->has('preview') && ($id_jadwal_seleksi || ($tanggal_mulai && $tanggal_akhir))) {
            $petugas = $this->getRekapDataPetugas($request);

            if ($petugas) {
                $stats = [
                    'total' => $petugas->count(),
                    'hadir' => $petugas->where('kehadiran', true)->count(),
                    'tidak_hadir' => $petugas->where('kehadiran', false)->count(),
                ];
            }
        }

        return view('laporan.petugas', compact('petugas', 'stats', 'tanggal_mulai', 'tanggal_akhir', 'id_jadwal_seleksi', 'jadwals'));
    }

    /**
     * Handle download PDF PETUGAS
     */
    public function downloadPetugas(Request $request)
    {
        $petugas = $this->getRekapDataPetugas($request);
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $id_jadwal_seleksi = $request->input('id_jadwal_seleksi');

        if ($petugas->isEmpty()) {
            return redirect()->route('laporan.petugas.index')->with('error', 'Tidak ada data petugas untuk kriteria yang dipilih.');
        }

        $jadwal_dipilih = null;
        if ($id_jadwal_seleksi) {
            $jadwal_dipilih = JadwalSeleksi::find($id_jadwal_seleksi);
        }

        $stats = [
            'total' => $petugas->count(),
            'hadir' => $petugas->where('kehadiran', true)->count(),
            'tidak_hadir' => $petugas->where('kehadiran', false)->count(),
        ];

        $pdf = Pdf::loadView('downloads.rekap-petugas', compact('petugas', 'stats', 'tanggal_mulai', 'tanggal_akhir', 'jadwal_dipilih'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Rekapitulasi_Petugas.pdf');
    }
}
