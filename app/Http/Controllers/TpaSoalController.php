<?php

namespace App\Http\Controllers;

use App\Models\TpaSoal;
use App\Models\TpaGrupSoal; // <-- PENTING
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- PENTING

class TpaSoalController extends Controller
{
    // Helper untuk ambil grup soal
    private function getGrupSoal()
    {
        return TpaGrupSoal::where('status_aktif', true)->orderBy('nama_grup')->get();
    }

    public function index(Request $request)
    {
        $filter_grup_id = $request->input('filter_grup_id');
        $grups = $this->getGrupSoal();

        $soals = TpaSoal::with('tpaGrupSoal')
            ->when($filter_grup_id, function ($q, $grup_id) {
                $q->where('tpa_grup_soal_id', $grup_id);
            })
            ->orderBy('tpa_grup_soal_id')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('tpa-soal.index', compact('soals', 'grups', 'filter_grup_id'));
    }

    public function create()
    {
        $grups = $this->getGrupSoal();
        return view('tpa-soal.create', compact('grups'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tpa_grup_soal_id' => 'required|exists:tpa_grup_soals,id',
            'pertanyaan_teks' => 'required|string',
            'gambar_soal' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'pilihan_e' => 'required|string',
            'jawaban_benar' => 'required|in:A,B,C,D,E',
        ]);

        if ($request->hasFile('gambar_soal')) {
            $path = $request->file('gambar_soal')->store('soal-tpa', 'public');
            $validatedData['gambar_soal'] = $path;
        }

        TpaSoal::create($validatedData);
        return redirect()->route('tpa-soal.index')->with('success', 'Soal TPA berhasil ditambahkan.');
    }

    public function edit(TpaSoal $tpaSoal)
    {
        $grups = $this->getGrupSoal();
        $soal = $tpaSoal; // Ganti nama variabel agar konsisten
        return view('tpa-soal.edit', compact('soal', 'grups'));
    }

    public function update(Request $request, TpaSoal $tpaSoal)
    {
        $validatedData = $request->validate([
            'tpa_grup_soal_id' => 'required|exists:tpa_grup_soals,id',
            'pertanyaan_teks' => 'required|string',
            'gambar_soal' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'pilihan_e' => 'required|string',
            'jawaban_benar' => 'required|in:A,B,C,D,E',
        ]);

        if ($request->hasFile('gambar_soal')) {
            if ($tpaSoal->gambar_soal) {
                Storage::disk('public')->delete($tpaSoal->gambar_soal);
            }
            $path = $request->file('gambar_soal')->store('soal-tpa', 'public');
            $validatedData['gambar_soal'] = $path;
        }

        $tpaSoal->update($validatedData);
        return redirect()->route('tpa-soal.index')->with('success', 'Soal TPA berhasil diupdate.');
    }

    public function destroy(TpaSoal $tpaSoal)
    {
        if ($tpaSoal->gambar_soal) {
            Storage::disk('public')->delete($tpaSoal->gambar_soal);
        }
        $tpaSoal->delete();
        return redirect()->route('tpa-soal.index')->with('success', 'Soal TPA berhasil dihapus.');
    }
}
