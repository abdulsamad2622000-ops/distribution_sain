<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        // Role uses BelongsToCompany -> auto-scoped to current company.
        $roles = Role::withCount('users')->with('permissions')->latest()->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('module')->orderBy('action')->get()->groupBy('module');
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'name'         => [
                'required', 'string', 'max:255',
                // unique within THIS company only
                Rule::unique('roles', 'name')->where('company_id', auth()->user()->company_id),
            ],
        ]);

        // company_id is stamped automatically by the BelongsToCompany trait
        $role = Role::create([
            'name'         => strtolower(str_replace(' ', '_', $request->name)),
            'display_name' => $request->display_name,
            'description'  => $request->description,
        ]);

        if ($request->permissions) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created!');
    }

    public function edit(Role $role)
    {
        $permissions     = Permission::orderBy('module')->orderBy('action')->get()->groupBy('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
        ]);

        $role->update([
            'display_name' => $request->display_name,
            'description'  => $request->description,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated!');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role — users are assigned to it!');
        }
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted!');
    }

    public function show(Role $role)
    {
        return redirect()->route('roles.index');
    }
}
