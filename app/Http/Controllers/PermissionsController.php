<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    public function index()
    {
        // if ($request->has('search')) {
        //     $permissions = Permission::where('name', 'LIKE', '%' . $request->search . '%')->paginate(10);
        // } else {
        //     $permissions = Permission::paginate(10);
        // }
        $permissions = Permission::OrderByDesc('id')->get();
        $roles = Role::all();
        $permissions = Permission::paginate(10);
        return view('permissions.index', compact('permissions', 'roles'));
    }

    // public function search(Request $request)
    // {
    //     $search = $request->search;

    //     $permissions = Permission::where(function ($query) use ($search) {

    //         $query->where('name', 'like', "%$search%")
    //             ->orWhere('guard', 'like', "%$search%");
    //     })
    //     orWhereHas('name')
    //     ->get();

    //     return view('permissions.index', compact('permissions', 'search'));
    // }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => ['required', 'min:3']]);
        Permission::create($validated);
        return to_route('super admin.permissions.index')->with('message', 'Permissions Created successfully.');
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => ['required', 'min:3'],
            'guard_name' => ['required', 'in:web,api']
        ]);
        $permission->update($validated);

        return redirect()->route('super admin.permissions.index')->with('message', 'Permissions Updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return back()->with('message', 'Permission Deleted successfully');
    }

    public function assignRole(Request $request, Permission $permission)
    {
        if ($permission->hasRole($request->role)) {
            return back()->with('message', 'Role exists.');
        }

        $permission->assignRole($request->role);
        return back()->with('message', 'Role assigned.');
    }

    public function removeRole(Permission $permission, Role $role)
    {
        if ($permission->hasRole($role)) {
            $permission->removeRole($role);
            return back()->with('message', 'Role removed.');
        }

        return back()->with('message', 'Role not exists.');
    }
}
