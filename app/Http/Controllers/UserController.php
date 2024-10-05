<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    
    public static function middleware() : array
    {
        return [
            new Middleware('permission:view users', only : ['index']),
            new Middleware('permission:edit users', only : ['edit']),
            new Middleware('permission:create users', only : ['create']),
            new Middleware('permission:delete users', only : ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(5);
        return view('users.list', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'ASC')->get();
        return view('users.create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|min:3|unique:users,email',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required'
        ]);

        if ($validator->fails()) {

            return redirect()->route('users.create')->withInput()->withErrors($validator);

        }else{

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $user->syncRoles($request->role);

            return redirect()->route('users.index')->with('success', 'User Added successfully');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $hasRoles = $user->roles->pluck('id');

        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'hasRoles' => $hasRoles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|min:3|unique:users,email,'.$id.',id'
        ]);

        if ($validator->passes()) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            $user->syncRoles($request->role);

            return redirect()->route('users.index')->with('success', 'User updated successfully');
        }else{
            return redirect()->route('users.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);

        if ($user == null) {
            session()->flash('error', 'User not found');
            return response()->json([
                'status' => false
            ]);
        }

        $user->delete();
        
        session()->flash('success', 'User deleted successfully');
        return response()->json([
            'status' => true
        ]);
    } 
}
