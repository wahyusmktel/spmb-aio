<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru; // <-- PENTING
use Spatie\Permission\Models\Role; // <-- PENTING
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- PENTING
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Tampilkan halaman daftar user
     */
    public function index()
    {
        $users = User::with('roles', 'guru')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Tampilkan form create user
     */
    public function create()
    {
        $roles = Role::all();
        // Ambil HANYA guru yang belum terhubung ke user manapun
        $availableGurus = Guru::whereNull('user_id')->get();

        return view('users.create', compact('roles', 'availableGurus'));
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'roles' => 'required|array',
            'id_guru' => 'nullable|exists:gurus,id|unique:gurus,id,NULL,id,user_id,NULL',
        ], [
            'id_guru.unique' => 'Guru ini sudah terhubung ke akun user lain.'
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Buat User
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // 2. Assign Role
                $user->syncRoles($request->roles);

                // 3. Link ke Guru (jika role 'Guru' dipilih dan id_guru diisi)
                if (in_array('Guru', $request->roles) && $request->id_guru) {
                    Guru::find($request->id_guru)->update(['user_id' => $user->id]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan user: ' . $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    /**
     * Tampilkan form edit user
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        // Ambil guru yang belum ter-link, ATAU guru yang saat ini di-link oleh user ini
        $availableGurus = Guru::whereNull('user_id')
            ->orWhere('user_id', $user->id)
            ->get();

        return view('users.edit', compact('user', 'roles', 'availableGurus'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'roles' => 'required|array',
            'id_guru' => ['nullable', 'exists:gurus,id', Rule::unique('gurus', 'id')->ignore($user->guru->id ?? null, 'id')->whereNull('user_id')],
        ]);

        try {
            DB::transaction(function () use ($request, $user) {
                // 1. Update User
                $userData = $request->only('name', 'email');
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $user->update($userData);

                // 2. Assign Role
                $user->syncRoles($request->roles);

                // 3. Link/Unlink Guru
                // Lepaskan link guru lama (jika ada)
                Guru::where('user_id', $user->id)->update(['user_id' => null]);

                // Link ke Guru (jika role 'Guru' dipilih dan id_guru diisi)
                if (in_array('Guru', $request->roles) && $request->id_guru) {
                    Guru::find($request->id_guru)->update(['user_id' => $user->id]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update user: ' . $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id() || $user->id === 1) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus diri sendiri atau Super Admin.');
        }

        try {
            DB::transaction(function () use ($user) {
                // 1. Unlink guru
                Guru::where('user_id', $user->id)->update(['user_id' => null]);
                // 2. Hapus user (Spatie akan handle detach roles)
                $user->delete();
            });
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Gagal menghapus user.');
        }

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
