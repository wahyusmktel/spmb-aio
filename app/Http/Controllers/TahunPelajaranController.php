<?php

namespace App\Http\Controllers;

use App\Models\TahunPelajaran;
use Illuminate\Http\Request;

class TahunPelajaranController extends Controller
{
    /**
     * Tampilkan halaman utama
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $tahunPelajarans = TahunPelajaran::latest()
            ->when($search, function ($query, $search) {
                $query->where('nama_tahun_pelajaran', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('tahun-pelajaran.index', compact('tahunPelajarans', 'search'));
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_tahun_pelajaran' => 'required|string|max:100|unique:tahun_pelajarans',
            'status' => 'required|boolean', // Pastikan data yang masuk 0 atau 1
        ]);

        $request->request->add(['form_type' => 'create']);

        TahunPelajaran::create([
            'nama_tahun_pelajaran' => $request->nama_tahun_pelajaran,
            'status' => $request->status,
        ]);

        return redirect()->route('tahun-pelajaran.index')->with('success', 'Tahun Pelajaran berhasil ditambahkan.');
    }

    /**
     * Update data
     */
    public function update(Request $request, TahunPelajaran $tahunPelajaran)
    {
        $request->validate([
            'nama_tahun_pelajaran' => 'required|string|max:100|unique:tahun_pelajarans,nama_tahun_pelajaran,' . $tahunPelajaran->id,
            'status' => 'required|boolean',
        ]);

        $request->request->add(['form_type' => 'edit']);

        $tahunPelajaran->update([
            'nama_tahun_pelajaran' => $request->nama_tahun_pelajaran,
            'status' => $request->status,
        ]);

        return redirect()->route('tahun-pelajaran.index')->with('success', 'Tahun Pelajaran berhasil diupdate.');
    }

    /**
     * Hapus data
     */
    public function destroy(TahunPelajaran $tahunPelajaran)
    {
        $tahunPelajaran->delete();
        return redirect()->route('tahun-pelajaran.index')->with('success', 'Tahun Pelajaran berhasil dihapus.');
    }
}
