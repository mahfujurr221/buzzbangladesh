<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\QueryException;


class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-role', ['only' => ['index']]);
        $this->middleware('can:create-role', ['only' => ['store']]);
        $this->middleware('can:edit-role', ['only' => ['update']]);
        $this->middleware('can:delete-role', ['only' => ['destroy']]);
    }
    public function index()
    {
        $roles = Role::orderBy('id', 'desc')->get();
        return view('backend.pages.role-permission.role.index', compact('roles'));
    }

    public function store(Request $request)
    {
        // dd(Role::create($request->all()));
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        try {
            DB::beginTransaction();
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);
            DB::commit();
            toast('Role Created Successfully!', 'success');
        } catch (\Exception $e) {
            toast('Something Went Wrong!', 'error');
            DB::rollBack();
        }
        return redirect()->route('roles.index');
    }

    public function update(Request $request, Role $role)
    {
        // dd($role);
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);
        try {
            DB::beginTransaction();
            $role->update([
                'name' => $request->name,
            ]);
            DB::commit();
            toast('Role Updated Successfully!', 'success');
            return redirect()->route('roles.index')->with('success', 'Role Updated Successfully!');
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return redirect()->back()->withErrors(['name' => 'The role name has already been taken.'])->withInput();
            }
        }
    }

    public function destroy(Role $role)
    {
        // dd($role);
        if ($role->users->count() > 0) {
            toast('Role is assigned to some users!', 'error');
            return back();
        }
        if ($role->delete()) {
            toast('Role Deleted Successfully!', 'success');
        } else {
            toast('Something Went Wrong!', 'error');
        }
        return back();
    }

    public function addPermissionToRole($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            // return explode('-', $permission->name)[1];
            if (strpos($permission->name, '-') !== false) {
                return explode('-', $permission->name)[1];
            } else {
                return $permission->name;
            }
        });
        // dd($groupedPermissions);
        return view('backend.pages.role-permission.role.add-permission', compact('role', 'groupedPermissions'));
    }

    public function addPermissionToRoleUpdate(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'permission' => 'required',
        ]);
        $role = Role::findOrFail($id);
        $role->syncPermissions($request->permission);
        toast('Permission Assigned Successfully!', 'success');
        // return redirect()->route('roles.index');
        return back();
    }
}
