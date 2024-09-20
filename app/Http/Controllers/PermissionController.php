<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    //THis method will show permissions page
    public function index() {

    }

    //THis method will show create permissions page
    public function create() {
        return view('permissions.create');
    }

    //THis method will insert a permissions in DB
    public function store() {
        
    }

    //THis method will show edit permissions page
    public function edit() {
        
    }

    //THis method will show update a permissions
    public function update() {
        
    }

    //THis method will show delete a permissions
    public function destroy() {
        
    }
}
