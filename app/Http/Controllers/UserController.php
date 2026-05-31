<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // 1. show list user di halaman index
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    // 2. redirect ke halaman create user
    public function create()
    {
        return view('users.create');
    }

    // 3. Simpan User Baru ke Database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tb_user,email',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['administrator', 'staff'])],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User baru berhasil ditambahkan.');
    }

    // redirect ke halaman edit user
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    //update data user di database
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('tb_user')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => ['required', Rule::in(['administrator', 'staff'])],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']); // Jangan timpa password lama jika input dikosongkan
        }

        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    //hapus data user di database
    public function destroy(User $user)
    {
        // Proteksi agar admin tidak menghapus akun dirinya sendiri yang sedang login
        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak diperbolehkan menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
