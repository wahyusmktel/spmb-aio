<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\TahunPelajaranController;
use App\Http\Controllers\JadwalSeleksiController;
use App\Http\Controllers\ReferensiTugasController;
use App\Http\Controllers\PenugasanPetugasController;
use App\Http\Controllers\ReferensiAkunCbtController;
use App\Http\Controllers\PesertaSeleksiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\EvidenController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AbsensiMandiriController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SoalButaWarnaController;
use App\Http\Controllers\PesertaLoginController;
use App\Http\Controllers\PesertaDashboardController;
use App\Http\Controllers\TesButaWarnaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/seleksi/login', [PesertaLoginController::class, 'showLoginForm'])->name('peserta.login');
Route::post('/seleksi/login', [PesertaLoginController::class, 'login']);
Route::post('/seleksi/logout', [PesertaLoginController::class, 'logout'])->name('peserta.logout');

// Gunakan middleware 'auth:peserta' (guard peserta)
Route::middleware(['auth:peserta'])->group(function () {

    Route::get('/seleksi/dashboard', [PesertaDashboardController::class, 'index'])
        ->name('peserta.dashboard');

    // --- RUTE TES BUTA WARNA ---
    // Halaman "Mulai" atau "Lihat Hasil"
    Route::get('/seleksi/tes-buta-warna', [TesButaWarnaController::class, 'index'])
        ->name('tes-buta-warna.index');

    // Halaman Pengerjaan Soal
    Route::get('/seleksi/tes-buta-warna/kerjakan', [TesButaWarnaController::class, 'kerjakan'])
        ->name('tes-buta-warna.kerjakan');

    // Halaman Submit Jawaban
    Route::post('/seleksi/tes-buta-warna/submit', [TesButaWarnaController::class, 'submit'])
        ->name('tes-buta-warna.submit');
    // ----------------------------

    // (Nanti rute tes buta warna akan kita taruh di sini)

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('guru', GuruController::class);
    Route::post('/guru/import', [GuruController::class, 'importExcel'])->name('guru.import');
    Route::post('/guru/bulk-create-accounts', [GuruController::class, 'bulkCreateAccounts'])
        ->name('guru.bulk-create');

    Route::resource('tahun-pelajaran', TahunPelajaranController::class);

    Route::resource('jadwal-seleksi', JadwalSeleksiController::class);
    Route::get('/jadwal-seleksi/{jadwal}/download-kartu', [JadwalSeleksiController::class, 'downloadKartu'])
        ->name('jadwal.download-kartu');
    Route::get('/jadwal-seleksi/{jadwal}/download-daftar-hadir', [JadwalSeleksiController::class, 'downloadDaftarHadir'])
        ->name('jadwal.download-daftar-hadir');
    Route::get('/jadwal-seleksi/{jadwal}/download-daftar-hadir-peserta', [JadwalSeleksiController::class, 'downloadDaftarHadirPeserta'])
        ->name('jadwal.download-daftar-hadir-peserta');
    Route::get('/jadwal-seleksi/{jadwal}/download-berita-acara', [JadwalSeleksiController::class, 'downloadBeritaAcara'])
        ->name('jadwal.download-berita-acara');
    Route::get('/jadwal-seleksi/{jadwal}/download-spt', [JadwalSeleksiController::class, 'downloadSPT'])
        ->name('jadwal.download-spt');
    Route::get('/jadwal-seleksi/{jadwal}/download-laporan-kegiatan', [JadwalSeleksiController::class, 'downloadLaporanKegiatan'])
        ->name('jadwal.download-laporan-kegiatan');

    Route::resource('referensi-tugas', ReferensiTugasController::class);

    // --- RUTE BARU KITA (CRUD BERSARANG) ---
    // 1. Halaman utama (daftar petugas per jadwal)
    Route::get('/jadwal-seleksi/{jadwal}/petugas', [PenugasanPetugasController::class, 'index'])
        ->name('penugasan.index');

    // 2. Simpan petugas baru (Create)
    Route::post('/jadwal-seleksi/{jadwal}/petugas', [PenugasanPetugasController::class, 'store'])
        ->name('penugasan.store');

    // 3. Update tugas (Update) - (rute ini menargetkan penugasan spesifik)
    Route::patch('/penugasan-petugas/{penugasan}', [PenugasanPetugasController::class, 'update'])
        ->name('penugasan.update');

    // 4. Hapus petugas (Delete) - (rute ini menargetkan penugasan spesifik)
    Route::delete('/penugasan-petugas/{penugasan}', [PenugasanPetugasController::class, 'destroy'])
        ->name('penugasan.destroy');
    // ------------------------------------

    Route::resource('referensi-akun-cbt', ReferensiAkunCbtController::class);
    Route::post('/referensi-akun-cbt/import', [ReferensiAkunCbtController::class, 'importExcel'])
        ->name('referensi-akun-cbt.import');

    // 1. Halaman utama (daftar peserta & form inline)
    Route::get('/jadwal-seleksi/{jadwal}/peserta', [PesertaSeleksiController::class, 'index'])
        ->name('peserta.index');

    // 2. Simpan peserta baru (Handle BANYAK data dari form inline)
    Route::post('/jadwal-seleksi/{jadwal}/peserta', [PesertaSeleksiController::class, 'store'])
        ->name('peserta.store');

    // 3. Hapus peserta (Handle 1 data)
    Route::delete('/peserta-seleksi/{peserta}', [PesertaSeleksiController::class, 'destroy'])
        ->name('peserta.destroy');

    // 1. Halaman utama (GET) - Menampilkan form ceklis
    Route::get('/jadwal-seleksi/{jadwal}/absensi', [AbsensiController::class, 'index'])
        ->name('absensi.index');

    // 2. Simpan (POST) - Menyimpan data ceklis
    Route::post('/jadwal-seleksi/{jadwal}/absensi', [AbsensiController::class, 'store'])
        ->name('absensi.store');

    Route::get('/jadwal-seleksi/{jadwal}/absensi/download-peserta', [AbsensiController::class, 'downloadLaporanPeserta'])
        ->name('absensi.download.peserta');
    Route::get('/jadwal-seleksi/{jadwal}/absensi/download-petugas', [AbsensiController::class, 'downloadLaporanPetugas'])
        ->name('absensi.download.petugas');

    Route::get('/jadwal-seleksi/{jadwal}/eviden', [EvidenController::class, 'index'])
        ->name('eviden.index');

    Route::post('/jadwal-seleksi/{jadwal}/eviden/upload-hadir-peserta', [EvidenController::class, 'storeHadirPeserta'])
        ->name('eviden.store.hadir-peserta');

    Route::post('/jadwal-seleksi/{jadwal}/eviden/upload-hadir-petugas', [EvidenController::class, 'storeHadirPetugas'])
        ->name('eviden.store.hadir-petugas');

    Route::post('/jadwal-seleksi/{jadwal}/eviden/upload-berita-acara', [EvidenController::class, 'storeBeritaAcara'])
        ->name('eviden.store.berita-acara');

    // --- RUTE BARU STAFF TU ---
    // Gunakan middleware role dari Spatie
    Route::middleware(['role:Staff TU'])->group(function () {
        Route::get('/pengajuan-surat', [PengajuanSuratController::class, 'index'])
            ->name('pengajuan.index');
        Route::post('/pengajuan-surat/{jadwal}/terbitkan', [PengajuanSuratController::class, 'terbitkan'])
            ->name('pengajuan.terbitkan');
    });

    Route::middleware(['role:Kepala Sekolah'])->group(function () {
        Route::get('/persetujuan-spt', [PersetujuanController::class, 'index'])
            ->name('persetujuan.index');
        // Kita pakai POST untuk "menyetujui"
        Route::post('/persetujuan-spt/{jadwal}/approve', [PersetujuanController::class, 'approve'])
            ->name('persetujuan.approve');
    });

    Route::middleware(['role:Admin|Kepala Sekolah'])->group(function () {

        // Halaman untuk menampilkan filter & preview
        Route::get('/laporan/peserta', [LaporanController::class, 'indexPeserta'])
            ->name('laporan.peserta.index');

        // Rute untuk handle download PDF
        Route::get('/laporan/peserta/download', [LaporanController::class, 'downloadPeserta'])
            ->name('laporan.peserta.download');

        Route::get('/laporan/petugas', [LaporanController::class, 'indexPetugas'])
            ->name('laporan.petugas.index');
        Route::get('/laporan/petugas/download', [LaporanController::class, 'downloadPetugas'])
            ->name('laporan.petugas.download');
    });

    // --- RUTE BARU PORTAL GURU/PETUGAS ---
    Route::middleware(['role:Guru'])->group(function () {
        Route::get('/absensi-mandiri', [AbsensiMandiriController::class, 'index'])
            ->name('absensi-mandiri.index');

        // {penugasan} adalah ID dari tabel 'penugasan_petugas'
        Route::post('/absensi-mandiri/{penugasan}', [AbsensiMandiriController::class, 'store'])
            ->name('absensi-mandiri.store');
    });

    Route::middleware(['role:Admin'])->group(function () {
        // ... (Rute Laporan, dll)

        // --- RUTE ROLE BARU ---
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        // ----------------------
        Route::resource('users', UserController::class);

        Route::resource('soal-buta-warna', SoalButaWarnaController::class);
    });
});

require __DIR__ . '/auth.php';
