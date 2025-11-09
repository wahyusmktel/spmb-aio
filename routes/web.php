<?php

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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('guru', GuruController::class);
    Route::post('/guru/import', [GuruController::class, 'importExcel'])->name('guru.import');

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
    });
});

require __DIR__ . '/auth.php';
