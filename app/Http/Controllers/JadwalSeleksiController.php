<?php

namespace App\Http\Controllers;

use App\Models\JadwalSeleksi;
use App\Models\User; // <-- TAMBAHKAN INI
use App\Models\PesertaSeleksi; // <-- TAMBAHKAN INI
use Barryvdh\DomPDF\Facade\Pdf; // <-- TAMBAHKAN INI
use App\Models\PenugasanPetugas;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;

class JadwalSeleksiController extends Controller
{
    /**
     * Tampilkan halaman utama (LOGIKA DIUBAH TOTAL)
     */
    public function index(Request $request)
    {
        // 1. Cari dulu tahun pelajaran yang aktif
        $tahunAktif = TahunPelajaran::where('status', true)->first();

        // 2. JIKA TIDAK ADA, kirim 'null' ke view
        if (!$tahunAktif) {
            return view('jadwal-seleksi.index', ['tahunAktif' => null]);
        }

        // 3. JIKA ADA, baru lanjutkan proses ambil data
        $search = $request->input('search');

        // Ambil data users untuk dropdown (tetap perlu)
        // $users = User::select('id', 'name')->orderBy('name')->get();
        $users = User::role('Kepala Sekolah')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Ambil jadwal HANYA untuk tahun aktif tersebut
        $jadwalSeleksis = JadwalSeleksi::with('penandatangan')
            ->where('id_tahun_pelajaran', $tahunAktif->id) // <-- FILTER WAJIB
            ->latest()
            ->when($search, function ($query, $search) {
                $query->where('judul_kegiatan', 'like', "%{$search}%")
                    ->orWhere('lokasi_kegiatan', 'like', "%{$search}%")
                    ->orWhere('nomor_surat_tugas', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        // Kirim semua data + $tahunAktif ke view
        return view('jadwal-seleksi.index', compact('jadwalSeleksis', 'users', 'search', 'tahunAktif'));
    }

    /**
     * Simpan data baru (LOGIKA DIUBAH)
     */
    public function store(Request $request)
    {
        // 1. Cek lagi tahun aktif saat proses simpan
        $tahunAktif = TahunPelajaran::where('status', true)->first();

        // 2. Guard clause, jika tidak ada, tolak
        if (!$tahunAktif) {
            return redirect()->route('jadwal-seleksi.index')->with('error', 'Gagal: Tidak ada tahun pelajaran yang aktif saat ini.');
        }

        // 3. Validasi data (id_tahun_pelajaran tidak perlu divalidasi, karena kita inject)
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'tanggal_mulai_pelaksanaan' => 'required|date',
            'tanggal_akhir_pelaksanaan' => 'required|date|after_or_equal:tanggal_mulai_pelaksanaan',
            'lokasi_kegiatan' => 'required|string|max:255',
            // 'nomor_surat_tugas' => 'required|string|max:100', // <-- HAPUS VALIDASI INI
            'kota_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'id_penandatangan' => 'required|exists:users,id',
        ]);

        $request->request->add(['form_type' => 'create']);

        // Siapkan data create
        $data = $request->all();
        $data['id_tahun_pelajaran'] = $tahunAktif->id; // <-- INJECT TAHUN AKTIF
        $data['status'] = 'menunggu_nst'; // <-- STATUS BARU
        $data['nomor_surat_tugas'] = null; // <-- NST DIBUAT NULL

        JadwalSeleksi::create($data);

        return redirect()->route('jadwal-seleksi.index')->with('success', 'Pengajuan Jadwal Seleksi berhasil dibuat.');
    }

    /**
     * Update data (Tidak berubah)
     * Kita asumsikan data yang di-edit tetap terikat pada tahun pelajaran aslinya
     */
    public function update(Request $request, JadwalSeleksi $jadwalSeleksi)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'tanggal_mulai_pelaksanaan' => 'required|date',
            'tanggal_akhir_pelaksanaan' => 'required|date|after_or_equal:tanggal_mulai_pelaksanaan',
            'lokasi_kegiatan' => 'required|string|max:255',
            // 'nomor_surat_tugas' => 'required|string|max:100',
            'kota_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'id_penandatangan' => 'required|exists:users,id',
        ]);

        $request->request->add(['form_type' => 'edit']);
        $jadwalSeleksi->update($request->all());

        return redirect()->route('jadwal-seleksi.index')->with('success', 'Jadwal Seleksi berhasil diupdate.');
    }

    /**
     * Hapus data
     */
    public function destroy(JadwalSeleksi $jadwalSeleksi)
    {
        $jadwalSeleksi->delete();
        return redirect()->route('jadwal-seleksi.index')->with('success', 'Jadwal Seleksi berhasil dihapus.');
    }

    /**
     * === METHOD BARU UNTUK DOWNLOAD PDF KARTU ===
     */
    public function downloadKartu(JadwalSeleksi $jadwal)
    {
        // 1. Ambil semua peserta yang terdaftar di jadwal ini
        //    WAJIB eager load relasinya
        $peserta = PesertaSeleksi::with('akunCbt', 'jadwalSeleksi.tahunPelajaran')
            ->where('id_jadwal_seleksi', $jadwal->id)
            ->get();

        // 2. Cek jika tidak ada peserta
        if ($peserta->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada peserta terdaftar di jadwal ini.');
        }

        // 3. Load view PDF
        $pdf = Pdf::loadView('downloads.kartu-peserta', compact('peserta', 'jadwal'));

        // 4. Set ukuran kertas A4
        $pdf->setPaper('a4', 'portrait');

        // 5. Stream ke browser
        $namaFile = 'kartu_peserta_' . $jadwal->judul_kegiatan . '.pdf';
        return $pdf->stream($namaFile);
    }

    /**
     * === METHOD BARU UNTUK DOWNLOAD PDF DAFTAR HADIR ===
     */
    public function downloadDaftarHadir(JadwalSeleksi $jadwal)
    {
        // 1. Ambil data jadwal, eager load relasi yang perlu untuk KOP
        $jadwal->load('tahunPelajaran', 'penandatangan');

        // 2. Ambil semua petugas yang terdaftar di jadwal ini
        $petugas = PenugasanPetugas::with('guru', 'referensiTugas')
            ->where('id_jadwal_seleksi', $jadwal->id)
            ->get()
            // Sortir berdasarkan nama guru
            ->sortBy(fn($p) => $p->guru->nama_guru);

        // 3. Cek jika tidak ada petugas
        if ($petugas->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada petugas yang ditugaskan di jadwal ini.');
        }

        // 4. Load view PDF
        $pdf = Pdf::loadView('downloads.daftar-hadir-petugas', compact('petugas', 'jadwal'));

        // 5. Set ukuran kertas A4
        $pdf->setPaper('a4', 'portrait');

        // 6. Stream ke browser
        $namaFile = 'daftar_hadir_petugas_' . $jadwal->judul_kegiatan . '.pdf';
        return $pdf->stream($namaFile);
    }

    /**
     * === METHOD BARU UNTUK DOWNLOAD PDF DAFTAR HADIR PESERTA ===
     */
    public function downloadDaftarHadirPeserta(JadwalSeleksi $jadwal)
    {
        // 1. Ambil data jadwal, eager load relasi yang perlu untuk KOP
        $jadwal->load('tahunPelajaran', 'penandatangan');

        // 2. Ambil semua PESERTA yang terdaftar di jadwal ini
        $peserta = PesertaSeleksi::with('akunCbt')
            ->where('id_jadwal_seleksi', $jadwal->id)
            ->get()
            // Sortir berdasarkan nama pendaftar
            ->sortBy('nama_pendaftar');

        // 3. Cek jika tidak ada peserta
        if ($peserta->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada peserta yang terdaftar di jadwal ini.');
        }

        // 4. Load view PDF
        $pdf = Pdf::loadView('downloads.daftar-hadir-peserta', compact('peserta', 'jadwal'));

        // 5. Set ukuran kertas A4
        $pdf->setPaper('a4', 'portrait');

        // 6. Stream ke browser
        $namaFile = 'daftar_hadir_peserta_' . $jadwal->judul_kegiatan . '.pdf';
        return $pdf->stream($namaFile);
    }

    /**
     * === DOWNLOAD PDF BERITA ACARA (VERSI BARU DENGAN STATS ABSENSI) ===
     */
    public function downloadBeritaAcara(JadwalSeleksi $jadwal)
    {
        // 1. Ambil data jadwal
        $jadwal->load('tahunPelajaran', 'penandatangan');

        // 2. Ambil KOLEKSI PETUGAS
        $petugas = PenugasanPetugas::with('guru', 'referensiTugas')
            ->where('id_jadwal_seleksi', $jadwal->id)
            ->get()
            ->sortBy(fn($p) => $p->guru->nama_guru);

        // 3. Ambil KOLEKSI PESERTA (Bukan cuma count)
        $peserta = PesertaSeleksi::where('id_jadwal_seleksi', $jadwal->id)->get();

        // 4. VALIDASI (yang kita perbaiki di langkah sebelumnya)
        // Pastikan keduanya tidak kosong
        if ($petugas->isEmpty() || $peserta->isEmpty()) {
            $errors = [];
            if ($petugas->isEmpty()) $errors[] = 'Data Petugas masih kosong.';
            if ($peserta->isEmpty()) $errors[] = 'Data Peserta masih kosong.';

            $errorMessage = 'Gagal cetak Berita Acara: ' . implode(' ', $errors);

            return redirect()->route('jadwal-seleksi.index')->with('error', $errorMessage);
        }

        // 5. HITUNG STATISTIK PESERTA (BARU)
        $statsPeserta = [
            'total' => $peserta->count(),
            'hadir' => $peserta->where('kehadiran', true)->count(),
            'tidak_hadir' => $peserta->where('kehadiran', false)->count(),
        ];

        // 6. HITUNG STATISTIK PETUGAS (BARU)
        $statsPetugas = [
            'total' => $petugas->count(),
            'hadir' => $petugas->where('kehadiran', true)->count(),
            'tidak_hadir' => $petugas->where('kehadiran', false)->count(),
        ];

        // 7. Load view PDF (Kirim stats baru, bukan $jumlahPeserta)
        $pdf = Pdf::loadView('downloads.berita-acara', compact('jadwal', 'petugas', 'statsPeserta', 'statsPetugas'));

        // 8. Set ukuran kertas A4
        $pdf->setPaper('a4', 'portrait');

        // 9. Stream ke browser
        $namaFile = 'berita_acara_' . $jadwal->judul_kegiatan . '.pdf';
        return $pdf->stream($namaFile);
    }

    /**
     * === METHOD BARU: DOWNLOAD SURAT PERINTAH TUGAS (SPT) ===
     */
    public function downloadSPT(JadwalSeleksi $jadwal)
    {
        // 1. Eager load semua relasi yang perlu untuk surat
        $jadwal->load('tahunPelajaran', 'penandatangan');

        // 2. Ambil semua PETUGAS (ini daftar yang akan ditugaskan)
        $petugas = PenugasanPetugas::with('guru', 'referensiTugas')
            ->where('id_jadwal_seleksi', $jadwal->id)
            ->get()
            // Sortir berdasarkan nama guru
            ->sortBy(fn($p) => $p->guru->nama_guru);

        // 3. Cek jika tidak ada petugas
        if ($petugas->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada petugas yang ditugaskan di jadwal ini untuk dibuatkan SPT.');
        }

        // 4. Load view PDF
        $pdf = Pdf::loadView('downloads.spt', compact('jadwal', 'petugas'));

        // 5. Set ukuran kertas A4
        $pdf->setPaper('a4', 'portrait');

        // 6. Stream ke browser
        $namaFile = 'SPT_' . $jadwal->judul_kegiatan . '.pdf';
        return $pdf->stream($namaFile);
    }

    /**
     * === METHOD BARU: DOWNLOAD LAPORAN KEGIATAN LENGKAP ===
     */
    public function downloadLaporanKegiatan(JadwalSeleksi $jadwal)
    {
        // 1. Ambil data jadwal
        $jadwal->load('tahunPelajaran', 'penandatangan');

        // 2. Ambil KOLEKSI PETUGAS
        $petugas = PenugasanPetugas::with('guru', 'referensiTugas')
            ->where('id_jadwal_seleksi', $jadwal->id)
            ->get()
            ->sortBy(fn($p) => $p->guru->nama_guru);

        // 3. Ambil KOLEKSI PESERTA
        $peserta = PesertaSeleksi::with('akunCbt')
            ->where('id_jadwal_seleksi', $jadwal->id)
            ->get()
            ->sortBy('nama_pendaftar');

        // 4. VALIDASI (PENTING)
        if ($petugas->isEmpty() || $peserta->isEmpty()) {
            $errors = [];
            if ($petugas->isEmpty()) $errors[] = 'Data Petugas masih kosong.';
            if ($peserta->isEmpty()) $errors[] = 'Data Peserta masih kosong.';
            $errorMessage = 'Gagal cetak Laporan: ' . implode(' ', $errors);

            return redirect()->route('jadwal-seleksi.index')->with('error', $errorMessage);
        }

        // 5. HITUNG STATISTIK PESERTA
        $statsPeserta = [
            'total' => $peserta->count(),
            'hadir' => $peserta->where('kehadiran', true)->count(),
            'tidak_hadir' => $peserta->where('kehadiran', false)->count(),
        ];

        // 6. HITUNG STATISTIK PETUGAS
        $statsPetugas = [
            'total' => $petugas->count(),
            'hadir' => $petugas->where('absensi_admin', true)->count(),
            'tidak_hadir' => $petugas->where('absensi_admin', false)->count(),
        ];

        // 7. Load view PDF
        $pdf = Pdf::loadView('downloads.laporan-kegiatan', compact(
            'jadwal',
            'petugas',
            'peserta',
            'statsPeserta',
            'statsPetugas'
        ));

        // 8. Set ukuran kertas A4
        $pdf->setPaper('a4', 'portrait');

        // 9. Stream ke browser
        $namaFile = 'Laporan_Kegiatan_' . $jadwal->judul_kegiatan . '.pdf';
        return $pdf->stream($namaFile);
    }

    /**
     * === METHOD BARU: DOWNLOAD LAPORAN HASIL SELEKSI ===
     */
    public function downloadHasilSeleksi(JadwalSeleksi $jadwal)
    {
        // 1. Ambil data jadwal
        $jadwal->load('tahunPelajaran', 'penandatangan');

        // 2. Ambil semua PESERTA yang terdaftar di jadwal ini
        //    Kita eager load akun CBT-nya untuk username
        $peserta = PesertaSeleksi::with('akunCbt')
            ->where('id_jadwal_seleksi', $jadwal->id)
            ->get()
            // Urutkan berdasarkan Nama Pendaftar
            ->sortBy('nama_pendaftar');

        // 3. Validasi
        if ($peserta->isEmpty()) {
            return redirect()->route('jadwal-seleksi.index')->with('error', 'Gagal cetak hasil: Belum ada peserta di jadwal ini.');
        }

        // 4. Load view PDF (Kirim data peserta yang sudah lengkap)
        $pdf = Pdf::loadView('downloads.hasil-seleksi', compact('jadwal', 'peserta'));

        // 5. Set ukuran kertas A4
        $pdf->setPaper('a4', 'portrait');

        // 6. Stream ke browser
        $namaFile = 'Hasil_Seleksi_' . $jadwal->judul_kegiatan . '.pdf';
        return $pdf->stream($namaFile);
    }
}
