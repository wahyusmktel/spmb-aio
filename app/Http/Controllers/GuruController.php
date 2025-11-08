<?php

// app/Http/Controllers/GuruController.php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use App\Imports\GuruImport; // <-- TAMBAHKAN INI
use Maatwebsite\Excel\Facades\Excel; // <-- TAMBAHKAN INI
use Maatwebsite\Excel\Validators\ValidationException; // <-- TAMBAHKAN INI

class GuruController extends Controller
{
    /**
     * Tampilkan semua data guru (Halaman utama)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $gurus = Guru::latest() // Urutkan berdasarkan yang terbaru
            ->when($search, function ($query, $search) {
                // Kalo ada pencarian, filter datanya
                $query->where(function ($q) use ($search) {
                    $q->where('nama_guru', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('mata_pelajaran', 'like', "%{$search}%");
                });
            })
            ->paginate(10) // Ubah dari get() jadi paginate()
            ->withQueryString(); // <-- PENTING: Biar paginasinya tetep inget query pencariannya

        // Kirim $gurus (data paginasi) dan $search (buat nampilin di form)
        return view('guru.index', compact('gurus', 'search'));
    }

    /**
     * Simpan data guru baru (dari Modal Create)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|unique:gurus|max:20',
            'nama_guru' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:100',
        ]);

        // Tambahkan logic untuk validasi form_type jika perlu
        $request->request->add(['form_type' => 'create']);

        Guru::create($request->all());

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Update data guru (dari Modal Edit)
     */
    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nip' => 'required|string|max:20|unique:gurus,nip,' . $guru->id,
            'nama_guru' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:100',
        ]);

        // Tambahkan logic untuk validasi form_type jika perlu
        $request->request->add(['form_type' => 'edit']);

        $guru->update($request->all());

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diupdate.');
    }

    /**
     * Hapus data guru (dari Modal Delete)
     */
    public function destroy(Guru $guru)
    {
        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Data guru berhasil dihapus.');
    }

    public function importExcel(Request $request)
    {
        // 1. Validasi file excel
        $request->validate([
            'file_import' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            // 2. Lakukan proses import
            Excel::import(new GuruImport, $request->file('file_import'));

            // 3. Jika berhasil, redirect dengan pesan sukses
            return redirect()->route('guru.index')->with('success', 'Data guru berhasil diimpor!');
        } catch (ValidationException $e) {
            // 4. Jika ada error validasi dari Excel, tangkap dan kirim balik
            $failures = $e->failures();

            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(", ", $failure->errors());
            }

            // Redirect kembali dengan pesan error validasi
            return redirect()->route('guru.index')->with('import_errors', $errorMessages);
        }
    }
}
