<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('login');
            }

            if (Auth::user()->role_id != 1) {
                abort(403, 'Chỉ quản trị viên mới được truy cập trang này.');
            }

            return $next($request);
        });
    }


    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Role::create($request->all());
        return redirect()->route('roles.index')->with('success', 'Thêm vai trò thành công.');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required']);
        $role->update($request->all());
        return redirect()->route('roles.index')->with('success', 'Cập nhật vai trò thành công.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return back()->with('success', 'Xóa vai trò thành công.');
    }
}
