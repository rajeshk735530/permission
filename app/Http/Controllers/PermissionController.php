<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    
    public static function middleware() : array
    {
        return [
            new Middleware('permission:view permissions', only : ['index']),
            new Middleware('permission:edit permissions', only : ['edit']),
            new Middleware('permission:create permissions', only : ['create']),
            new Middleware('permission:delete permissions', only : ['destroy']),
        ];
    }
    //THis method will show permissions page
    public function index() {
        $permissions = Permission::orderBy('created_at', 'DESC')->paginate(15);
        return view('permissions.list', ['permissions' => $permissions]);
    }

    //THis method will show create permissions page
    public function create() {
        return view('permissions.create');
    }

    //THis method will insert a permissions in DB
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3'
        ]);

        if ($validator->passes()) {
            Permission::create(['name'=>$request->name]);
            return redirect()->route('permissions.index')->with('success', 'Permission added successfully');
        }else{
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
        }
    }

    //THis method will show edit permissions page
    public function edit($id) {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', ['permission' => $permission]);
    }

    //THis method will show update a permissions
    public function update($id, Request $request) {
        $permission = Permission::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:permissions,name,'.$id.',id'
        ]);

        if ($validator->passes()) {
            $permission->name = $request->name;
            $permission->save();

            return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
        }else{
            return redirect()->route('permissions.edit', $id)->withInput()->withErrors($validator);
        }
    }

    //THis method will show delete a permissions in DB
    public function destroy(Request $request) {
        $id = $request->id;

        $permission = Permission::find($id);

        if ($permission == null) {
            session()->flash('error', 'Permission not found');
            return response()->json([
                'status' => false
            ]);
        }

        $permission->delete();
        
        session()->flash('success', 'Permission deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }
}
