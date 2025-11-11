<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        // Chỉ yêu cầu đăng nhập
        $this->middleware('auth');
    }

    /**
     * Hiển thị danh sách người dùng
     */
    public function index()
    {
        // Chỉ cho phép admin xem danh sách
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'))->with('layout', 'layout2.theme');
    }

    public function create()
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        return view('admin.users.create')->with('layout', 'layout2.theme');
    }

    public function store(Request $request)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Thêm người dùng thành công!');
    }

    public function edit(User $user)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        return view('admin.users.edit', compact('user'))->with('layout', 'layout2.theme');
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        if ($user->email === 'admin@gmail.com') {
            return back()->with('error', 'Không thể xóa tài khoản quản trị chính.');
        }

        $user->delete();
        return back()->with('success', 'Xóa người dùng thành công!');
    }
}
