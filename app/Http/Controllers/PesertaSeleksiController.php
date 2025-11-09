<?php

namespace App\Http\Controllers;

use App\Models\JadwalSeleksi;
use App\Models\PesertaSeleksi;
use App\Models\ReferensiAkunCbt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- PENTING UNTUK TRANSAKSI
use Illuminate\Validation\Rule;

class PesertaSeleksiController extends Controller
{
    /**
     * Tampilkan halaman utama (Daftar Peserta & Form Inline)
     */
    public function index(JadwalSeleksi $jadwal)
    {
        // 1. Eager load relasi tahunPelajaran
        $jadwal->load('tahunPelajaran');

        // 2. Ambil daftar peserta yang SUDAH terdaftar di jadwal ini
        $pesertaTerdaftar = PesertaSeleksi::with('akunCbt')
            ->where('id_jadwal_seleksi', $jadwal->id)
            ->get();

        // 3. Ambil daftar akun CBT yang MASIH TERSEDIA (status=true)
        //    dan HANYA dari tahun pelajaran yang sama dengan jadwal
        $availableAkunCbt = ReferensiAkunCbt::where('id_tahun_pelajaran', $jadwal->id_tahun_pelajaran)
            ->where('status', true)
            ->whereDoesntHave('pesertaSeleksi') // <-- KUNCI BARU
            ->orderBy('username')
            ->get();

        return view('peserta-seleksi.index', compact('jadwal', 'pesertaTerdaftar', 'availableAkunCbt'));
    }

    /**
     * Simpan BANYAK peserta baru dari form inline
     */
    public function store(Request $request, JadwalSeleksi $jadwal)
    {
        // 1. Validasi data (datanya dalam bentuk array 'peserta')
        $request->validate([
            'peserta' => 'required|array|min:1',
            'peserta.*.nomor_pendaftaran' => [
                'required',
                'string',
                Rule::unique('peserta_seleksis')->where('id_jadwal_seleksi', $jadwal->id),
            ],
            'peserta.*.nama_pendaftar' => 'required|string|max:255',
            'peserta.*.nomor_telepon' => 'required|string|max:20',
            'peserta.*.id_akun_cbt' => [
                'required',
                'distinct', // Pastikan tidak ada duplikat akun dalam 1 kali submit
                Rule::exists('referensi_akun_cbts', 'id')->where('status', true),
                Rule::unique('peserta_seleksis', 'id_akun_cbt'),
            ],
        ], [
            'peserta.*.nomor_pendaftaran.unique' => 'No. Pendaftaran sudah ada di jadwal ini.',
            'peserta.*.id_akun_cbt.distinct' => 'Akun CBT tidak boleh sama dalam satu kali input.',
            'peserta.*.id_akun_cbt.exists' => 'Akun CBT yang dipilih tidak tersedia/tidak valid.',
            'peserta.*.id_akun_cbt.unique' => 'Salah satu Akun CBT yang dipilih sudah terpakai.',
        ]);

        // 2. Gunakan DB Transaction
        // try {
        //     DB::transaction(function () use ($request, $jadwal) {
        //         $akunCbtIdsToUpdate = [];

        //         foreach ($request->peserta as $data) {
        //             PesertaSeleksi::create([
        //                 'id_jadwal_seleksi' => $jadwal->id,
        //                 'nomor_pendaftaran' => $data['nomor_pendaftaran'],
        //                 'nama_pendaftar' => $data['nama_pendaftar'],
        //                 'nomor_telepon' => $data['nomor_telepon'],
        //                 'id_akun_cbt' => $data['id_akun_cbt'],
        //             ]);

        //             // Kumpulkan ID Akun yang akan di-nonaktifkan
        //             $akunCbtIdsToUpdate[] = $data['id_akun_cbt'];
        //         }

        //         // 3. Update status semua akun CBT yang terpakai menjadi 'false'
        //         ReferensiAkunCbt::whereIn('id', $akunCbtIdsToUpdate)->update(['status' => false]);
        //     });
        // } catch (\Exception $e) {
        //     return redirect()->back()->with('error', 'Terjadi kesalahan. ' . $e->getMessage());
        // }

        foreach ($request->peserta as $data) {
            PesertaSeleksi::create([
                'id_jadwal_seleksi' => $jadwal->id,
                'nomor_pendaftaran' => $data['nomor_pendaftaran'],
                'nama_pendaftar' => $data['nama_pendaftar'],
                'nomor_telepon' => $data['nomor_telepon'],
                'id_akun_cbt' => $data['id_akun_cbt'],
            ]);
        }

        return redirect()->route('peserta.index', $jadwal->id)->with('success', 'Peserta seleksi berhasil ditambahkan.');
    }

    /**
     * Hapus 1 peserta
     */
    public function destroy(PesertaSeleksi $peserta)
    {
        // Ambil ID jadwal dan ID akun sebelum dihapus
        $id_jadwal = $peserta->id_jadwal_seleksi;
        // $id_akun_cbt = $peserta->id_akun_cbt;

        // try {
        //     DB::transaction(function () use ($peserta, $id_akun_cbt) {
        //         // 1. Hapus peserta
        //         $peserta->delete();

        //         // 2. Kembalikan status akun CBT menjadi 'true' (tersedia)
        //         ReferensiAkunCbt::where('id', $id_akun_cbt)->update(['status' => true]);
        //     });
        // } catch (\Exception $e) {
        //     return redirect()->back()->with('error', 'Terjadi kesalahan. ' . $e->getMessage());
        // }

        $peserta->delete();

        return redirect()->route('peserta.index', $id_jadwal)->with('success', 'Peserta berhasil dihapus dan akun CBT dikembalikan.');
    }
}
