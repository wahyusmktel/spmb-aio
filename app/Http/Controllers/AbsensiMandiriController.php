<?php

namespace App\Http\Controllers;

use App\Models\PenugasanPetugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsensiMandiriController extends Controller
{
    /**
     * Tampilkan halaman daftar tugas
     */
    public function index()
    {
        // 1. Dapatkan user yang login
        $user = Auth::user();

        // 2. Cek apakah user ini terhubung ke data Guru
        if (!$user->guru) {
            // Jika tidak, tampilkan error (mungkin user Admin/Kepsek yg nyasar)
            return view('absensi-mandiri.index', ['tugas' => collect()]);
            // atau bisa redirect dengan error
            // return redirect('/dashboard')->with('error', 'Akun Anda tidak terhubung ke data Guru.');
        }

        // 3. Ambil ID Guru
        $guruId = $user->guru->id;

        // 4. Ambil semua penugasan untuk Guru tsb
        $tugas = PenugasanPetugas::with('jadwalSeleksi', 'referensiTugas')
            ->where('id_guru', $guruId)
            // Tampilkan yang jadwalnya sudah diterbitkan
            ->whereHas('jadwalSeleksi', fn($q) => $q->where('status', 'diterbitkan'))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('absensi-mandiri.index', compact('tugas'));
    }

    /**
     * Simpan absensi mandiri (upload foto & set waktu)
     */
    public function store(Request $request, PenugasanPetugas $penugasan)
    {
        // 1. Validasi: Wajib Foto, JPG/JPEG, maks 2MB
        $request->validate([
            'file_bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'file_bukti.required' => 'Foto bukti kehadiran wajib diunggah.',
            'file_bukti.image' => 'File harus berupa gambar.',
            'file_bukti.mimes' => 'File harus berekstensi .jpg, .jpeg, atau .png',
            'file_bukti.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        // 2. Security Check: Pastikan yang absen adalah guru yang benar
        $user = Auth::user();
        if (!$user->guru || $penugasan->id_guru !== $user->guru->id) {
            return redirect()->route('absensi-mandiri.index')->with('error', 'Anda tidak berhak mengakses tugas ini.');
        }

        // 3. Hapus file bukti lama (jika ada)
        if ($penugasan->file_bukti_mandiri) {
            Storage::disk('public')->delete($penugasan->file_bukti_mandiri);
        }

        // 4. Simpan file baru
        $path = $request->file('file_bukti')->store('bukti-mandiri/' . $penugasan->id, 'public');

        // 5. Update database
        $penugasan->update([
            'file_bukti_mandiri' => $path,
            'absensi_mandiri_at' => now() // Set waktu absen ke "sekarang"
        ]);

        return redirect()->route('absensi-mandiri.index')->with('success', 'Absensi mandiri berhasil disimpan.');
    }
}
