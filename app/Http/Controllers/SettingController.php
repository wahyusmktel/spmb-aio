<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache; // <-- PENTING

class SettingController extends Controller
{
    public function index()
    {
        // Ambil setting 'logo_path'
        $logoSetting = Setting::where('key', 'logo_path')->first();
        return view('settings.index', compact('logoSetting'));
    }

    public function updateLogo(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'logo_upload' => 'required|image|mimes:png,jpg,jpeg,svg|max:1024' // Maks 1MB
        ], [
            'logo_upload.required' => 'Anda harus memilih file logo.',
            'logo_upload.image' => 'File harus berupa gambar.',
        ]);

        // 2. Cari atau buat setting 'logo_path'
        $setting = Setting::firstOrCreate(['key' => 'logo_path']);

        // 3. Hapus logo lama (jika ada)
        if ($setting->value) {
            Storage::disk('public')->delete($setting->value);
        }

        // 4. Simpan logo baru
        $path = $request->file('logo_upload')->store('app-logo', 'public');

        // 5. Update database
        $setting->update(['value' => $path]);

        // 6. (SUPER PENTING) Hapus cache agar logo baru langsung tampil
        Cache::forget('app_logo_url');

        return redirect()->route('settings.index')->with('success', 'Logo aplikasi berhasil diupdate.');
    }
}
