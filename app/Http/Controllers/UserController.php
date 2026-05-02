<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Ambil ID user yang sedang login secara type-safe.
     */
    private function currentUserId(): int
    {
        /** @var User $user */
        $user = Auth::user();
        return $user->id;
    }

    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,pimpinan,kepala_gudang,karyawan',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'role'              => $request->role,
            'password'          => Hash::make($request->password),
            'is_active'         => $request->boolean('is_active', true),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users.index')
            ->with('success', "User {$request->name} berhasil dibuat.");
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|in:admin,pimpinan,kepala_gudang,karyawan',
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        // Cegah satu-satunya pimpinan/admin di-demote
        if ($user->isManagement() && !in_array($request->role, ['admin', 'pimpinan'])) {
            $managementCount = User::whereIn('role', ['admin', 'pimpinan'])->count();
            if ($managementCount <= 1) {
                return back()->with('error', 'Tidak dapat mengubah role. Minimal harus ada 1 Pimpinan atau Admin aktif.');
            }
        }

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === $this->currentUserId()) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($user->isManagement() && User::whereIn('role', ['admin', 'pimpinan'])->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus satu-satunya Pimpinan/Admin.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} berhasil dihapus.");
    }

    public function toggleActive(User $user)
    {
        if ($user->id === $this->currentUserId()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $user->refresh();
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "User {$user->name} berhasil {$status}.");
    }
}