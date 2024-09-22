<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    // This method will show riles page
    public function index() {
        $roles = Role::orderBy('name', 'DESC')->paginate(2);
        return view('roles.list', [
            'roles' => $roles
        ]);
    }

    
    // This method will create riles page
    public function create() {
        $permissions = Permission::orderBy('name', 'ASC')->get();

        return view('roles.create', [
            'permissions' => $permissions
        ]);
    }

    // This method will store a riles DB
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3'
        ]);

        if ($validator->passes()) {
            $role = Role::create(['name'=>$request->name]);

            if (!empty($request->permission)) {
                foreach ($request->permission as $name) {
                    $role->givePermissionTo($name);
                }
            }

            return redirect()->route('roles.index')->with('success', 'Role added successfully');
        }else{
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
    }
}
