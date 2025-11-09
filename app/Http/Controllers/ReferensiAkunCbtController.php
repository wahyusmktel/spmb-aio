<?php

namespace App\Http\Controllers;

use App\Models\ReferensiAkunCbt;
use App\Models\TahunPelajaran; // <-- TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- TAMBAHKAN INI

use App\Imports\ReferensiAkunCbtImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ReferensiAkunCbtController extends Controller
{
    /**
     * Tampilkan halaman utama (LOGIKA BARU)
     */
    public function index(Request $request)
    {
        // 1. Cari tahun pelajaran yang aktif
        $tahunAktif = TahunPelajaran::where('status', true)->first();

        // 2. JIKA TIDAK ADA, kirim 'null' ke view
        if (!$tahunAktif) {
            return view('referensi-akun-cbt.index', ['tahunAktif' => null]);
        }

        // 3. JIKA ADA, baru lanjutkan proses
        $search = $request->input('search');

        // Ambil akun HANYA untuk tahun aktif tersebut
        $referensiAkunCbt = ReferensiAkunCbt::with('pesertaSeleksi')
            ->where('id_tahun_pelajaran', $tahunAktif->id)
            ->latest()
            ->when($search, function ($query, $search) {
                $query->where('username', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('referensi-akun-cbt.index', compact('referensiAkunCbt', 'search', 'tahunAktif'));
    }

    /**
     * Simpan data baru (LOGIKA BARU)
     */
    public function store(Request $request)
    {
        // 1. Cek tahun aktif
        $tahunAktif = TahunPelajaran::where('status', true)->first();
        if (!$tahunAktif) {
            return redirect()->route('referensi-akun-cbt.index')->with('error', 'Gagal: Tidak ada tahun pelajaran yang aktif.');
        }

        // 2. Validasi
        $validatedData = $request->validate([
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('referensi_akun_cbts')->where('id_tahun_pelajaran', $tahunAktif->id),
            ],
            'password' => 'required|string|min:8', // Tambahkan min 8 karakter
            'status' => 'required|boolean',
        ], [
            'username.unique' => 'Username ini sudah dipakai di tahun pelajaran ini.',
        ]);

        $request->request->add(['form_type' => 'create']);

        // 3. Simpan (Password akan otomatis di-hash oleh Mutator di Model)
        ReferensiAkunCbt::create([
            'id_tahun_pelajaran' => $tahunAktif->id,
            'username' => $validatedData['username'],
            'password' => $validatedData['password'],
            'status' => $validatedData['status'],
        ]);

        return redirect()->route('referensi-akun-cbt.index')->with('success', 'Akun CBT berhasil ditambahkan.');
    }

    /**
     * Update data (LOGIKA UPDATE PASSWORD)
     */
    public function update(Request $request, ReferensiAkunCbt $referensiAkunCbt)
    {
        // 1. Validasi
        $validatedData = $request->validate([
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('referensi_akun_cbts')
                    ->where('id_tahun_pelajaran', $referensiAkunCbt->id_tahun_pelajaran)
                    ->ignore($referensiAkunCbt->id),
            ],
            'password' => 'nullable|string|min:8', // Buat NULLABLE saat update
            'status' => 'required|boolean',
        ]);

        $request->request->add(['form_type' => 'edit']);

        // 2. Siapkan data untuk diupdate
        $updateData = [
            'username' => $validatedData['username'],
            'status' => $validatedData['status'],
        ];

        // 3. Hanya update password JIKA DIISI
        if ($request->filled('password')) {
            $updateData['password'] = $validatedData['password']; // Mutator akan hash otomatis
        }

        $referensiAkunCbt->update($updateData);

        return redirect()->route('referensi-akun-cbt.index')->with('success', 'Akun CBT berhasil diupdate.');
    }

    /**
     * Hapus data
     */
    public function destroy(ReferensiAkunCbt $referensiAkunCbt)
    {
        $referensiAkunCbt->delete();
        return redirect()->route('referensi-akun-cbt.index')->with('success', 'Akun CBT berhasil dihapus.');
    }

    /**
     * === METHOD BARU UNTUK IMPORT ===
     */
    public function importExcel(Request $request)
    {
        // 1. Cek tahun aktif
        $tahunAktif = TahunPelajaran::where('status', true)->first();
        if (!$tahunAktif) {
            return redirect()->route('referensi-akun-cbt.index')->with('error', 'Gagal: Tidak ada tahun pelajaran yang aktif.');
        }

        // 2. Validasi file excel
        $request->validate([
            'file_import' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            // 3. Lakukan proses import, kirim $tahunAktif->id ke Import Class
            Excel::import(new ReferensiAkunCbtImport($tahunAktif->id), $request->file('file_import'));

            // 4. Jika berhasil
            return redirect()->route('referensi-akun-cbt.index')->with('success', 'Data akun CBT berhasil diimpor!');
        } catch (ValidationException $e) {
            // 5. Jika ada error validasi dari Excel
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(", ", $failure->errors());
            }
            return redirect()->route('referensi-akun-cbt.index')->with('import_errors', $errorMessages);
        }
    }
}
