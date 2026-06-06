<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roleModel')->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::latest()->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'role_id'  => $request->role_id ?? null,
        ]);

        return redirect()->route('users.index')->with('success', 'User added!');
    }

    public function edit(User $user)
    {
        $roles = Role::latest()->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required',
        ]);

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'role'    => $request->role,
            'role_id' => $request->role_id ?? null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete yourself!');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted!');
    }

    public function show(User $user)
    {
        return redirect()->route('users.index');
    }
}