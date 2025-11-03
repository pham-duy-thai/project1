<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Hiển thị danh sách người dùng
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    // Form thêm người dùng
    public function create()
    {

        $roles = [
            1 => 'Admin',
            2 => ' Sinh viên',
        ];

        return view('users.create', compact('roles'));
    }

    // Xử lý lưu người dùng
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id'  => 'required|in:1,2',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Thêm người dùng thành công!');
    }

    // Form sửa người dùng
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = [
            1 => 'Admin',
            2 => 'Sinh viên',
        ];

        return view('users.edit', compact('user', 'roles'));
    }

    // Cập nhật người dùng
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role_id'  => 'required|in:1,2',
        ]);

        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->role_id = $request->role_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Cập nhật người dùng thành công!');
    }

    // Xóa người dùng
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Xóa người dùng thành công!');
    }
}
