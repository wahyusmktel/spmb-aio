<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role; // <-- PENTING

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:roles,name']);

        Role::create(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function destroy(Role $role)
    {
        // Jangan biarkan role krusial terhapus
        if (in_array($role->name, ['Admin', 'Staff TU', 'Kepala Sekolah', 'Guru'])) {
            return redirect()->route('roles.index')->with('error', 'Role utama tidak boleh dihapus.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
