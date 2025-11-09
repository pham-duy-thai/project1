<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $users = User::with('role')->get();
        return view('admin.users.index', compact('users'))->with('layout', 'layout2.theme');
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'))->with('layout', 'layout2.theme');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);
        return redirect()->route('users.index')->with('success', 'Tạo tài khoản thành công!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'))->with('layout', 'layout2.theme');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only('name', 'email', 'role_id'));
        return redirect()->route('users.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return back()->with('success', 'Xóa người dùng thành công!');
    }
}
