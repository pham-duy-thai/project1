<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'))->with('layout', 'layout2.theme');
    }

    public function create()
    {
        return view('admin.roles.create')->with('layout', 'layout2.theme');
    }

    public function store(Request $r)
    {
        Role::create(['name' => $r->name]);
        return back()->with('success', 'Thêm vai trò thành công');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'))->with('layout', 'layout2.theme');
    }

    public function update(Request $r, $id)
    {
        $role = Role::findOrFail($id);
        $role->update(['name' => $r->name]);
        return back()->with('success', 'Cập nhật vai trò thành công');
    }

    public function destroy($id)
    {
        Role::destroy($id);
        return back()->with('success', 'Xóa vai trò thành công');
    }
}
