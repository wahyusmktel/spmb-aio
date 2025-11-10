<?php

namespace App\Http\Controllers;

use App\Models\TpaGrupSoal;
use Illuminate\Http\Request;

class TpaGrupSoalController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $grups = TpaGrupSoal::withCount('tpaSoals') // Hitung jumlah soal
            ->when($search, fn($q, $s) => $q->where('nama_grup', 'like', "%{$s}%"))
            ->latest()
            ->paginate(10);

        return view('tpa-grup-soal.index', compact('grups', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_grup' => 'required|string|max:255|unique:tpa_grup_soals',
            'deskripsi' => 'nullable|string',
            'status_aktif' => 'required|boolean',
        ]);

        $request->request->add(['form_type' => 'create']);
        TpaGrupSoal::create($validated);
        return redirect()->route('tpa-grup-soal.index')->with('success', 'Grup Soal TPA berhasil dibuat.');
    }

    public function update(Request $request, TpaGrupSoal $tpaGrupSoal)
    {
        $validated = $request->validate([
            'nama_grup' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('tpa_grup_soals')->ignore($tpaGrupSoal->id)],
            'deskripsi' => 'nullable|string',
            'status_aktif' => 'required|boolean',
        ]);

        $request->request->add(['form_type' => 'edit']);
        $tpaGrupSoal->update($validated);
        return redirect()->route('tpa-grup-soal.index')->with('success', 'Grup Soal TPA berhasil diupdate.');
    }

    public function destroy(TpaGrupSoal $tpaGrupSoal)
    {
        // (Soal di dalamnya akan otomatis terhapus karena 'cascadeOnDelete')
        $tpaGrupSoal->delete();
        return redirect()->route('tpa-grup-soal.index')->with('success', 'Grup Soal TPA (dan semua soal di dalamnya) berhasil dihapus.');
    }
}
