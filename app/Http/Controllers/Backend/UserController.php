<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    //constructor
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:list-user')->only('index');
        $this->middleware('can:create-user')->only('create', 'store');
        $this->middleware('can:edit-user')->only('edit', 'update');
        $this->middleware('can:delete-user')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::query();
        $roles = Role::select("id", "name")->get();
        if ($request->name) {
            $users = $users->where('fname', 'like', '%' . $request->name . '%')
                ->orWhere('lname', 'like', '%' . $request->name . '%');
        }
        if ($request->phone) {
            $users = $users->where('phone', 'like', '%' . $request->phone . '%');
        }
        if ($request->role) {
            $users = $users->whereHas('roles', function ($query) use ($request) {
                $query->where('name', $request->role);
            });
        }
        $users = $users->where('id', '!=', 2)->orderBy('id', 'desc')->paginate(20);
        return view('backend.pages.user.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::select('id', 'name')->orderBy('id', 'desc')->get();
        return view('backend.pages.user.create')->with('roles', $roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'fname' => 'required|max:191',
            'lname' => 'required|max:191',
            'email' => 'required|max:191|email|unique:users',
            'role' => 'required',
            'phone' => 'required|max:15',
            'password' => 'required|max:191|min:4|same:password',
            'password_confirmation' => 'required|min:4|max:191|same:password',
        ]);
        try {
            DB::beginTransaction();

            $data = $request->all();
            unset($data['role']);
            unset($data['password_confirmation']);
            $data['password'] = bcrypt($request->password);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('backend/images/users/'), $filename);
                $data['image'] = $filename;
            }

            $user = User::create($data);
            $user->syncRoles($request->role);
            DB::commit();
            toast('User Created Successfully!', 'success');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            DB::rollBack();
            toast('something went wrong!', 'error');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::select('id', 'name')->get();
        return view('backend.pages.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:15',
            'role' => 'required',
        ]);
        $data = $request->all();
        if ($request->hasFile('image')) {
            // Delete old image
            if ($user->image && file_exists(public_path('backend/images/users/' . $user->image))) {
                unlink(public_path('backend/images/users/' . $user->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('backend/images/users/'), $filename);
            $data['image'] = $filename;
        }

        $user->update($data);
        $user->syncRoles($request->role);
        toast('User Updated Successfully!', 'success');
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        toast('User Deleted Successfully!', 'success');
        return redirect()->route('users.index');
    }
}
