<?php

namespace App\Http\Controllers;

use App\Models\ReferensiTugas;
use Illuminate\Http\Request;

class ReferensiTugasController extends Controller
{
    /**
     * Tampilkan halaman utama
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $referensiTugas = ReferensiTugas::latest()
            ->when($search, function ($query, $search) {
                $query->where('deskripsi_tugas', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('referensi-tugas.index', compact('referensiTugas', 'search'));
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'deskripsi_tugas' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $request->request->add(['form_type' => 'create']);

        ReferensiTugas::create([
            'deskripsi_tugas' => $request->deskripsi_tugas,
            'status' => $request->status,
        ]);

        return redirect()->route('referensi-tugas.index')->with('success', 'Referensi Tugas berhasil ditambahkan.');
    }

    /**
     * Update data
     */
    public function update(Request $request, ReferensiTugas $referensiTuga) // <-- Variabel $referensiTuga akan otomatis inject
    {
        $request->validate([
            'deskripsi_tugas' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $request->request->add(['form_type' => 'edit']);

        // Gunakan variabel $referensiTuga yang di-inject
        $referensiTuga->update([
            'deskripsi_tugas' => $request->deskripsi_tugas,
            'status' => $request->status,
        ]);

        return redirect()->route('referensi-tugas.index')->with('success', 'Referensi Tugas berhasil diupdate.');
    }

    /**
     * Hapus data
     */
    public function destroy(ReferensiTugas $referensiTuga)
    {
        $referensiTuga->delete();
        return redirect()->route('referensi-tugas.index')->with('success', 'Referensi Tugas berhasil dihapus.');
    }
}
