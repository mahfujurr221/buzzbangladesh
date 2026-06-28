<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::orderBy('id', 'desc')->paginate(10);
        $permissions = Permission::query();
        if ($request->name) {
            $permissions = $permissions->where('name', 'like', '%' . $request->name . '%');
        }
        $permissions = $permissions->orderBy('id', 'desc')->paginate(10);
        return view('backend.pages.role-permission.permission.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'string',
            'unique:permissions,name',
        ]);
        try {
            DB::beginTransaction();
            Permission::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);
            DB::commit();
            toast('Permission Created Successfully!', 'success');
            return redirect()->route('permissions.index');
        } catch (\Exception $e) {
            DB::rollBack();
            toast('Oops! Something Went Wrong!', 'error');
            return back();
        }
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);
        try {
            DB::beginTransaction();
            $permission->update([
                'name' => $request->name,
            ]);
            DB::commit();
            toast('Permission Updated Successfully!', 'success');
            return redirect()->route('permissions.index');
        } catch (\Exception $e) {
            DB::rollBack();
            toast('Oops! Something Went Wrong!', 'error');
            return back();
        }
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        toast('successfully deleted!', 'success');
    }
}
