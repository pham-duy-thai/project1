<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Hiển thị form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Hiển thị form register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Xử lý login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // ✅ Check role và chuyển hướng
            if ($user->role_id == 1) {
                return redirect()->route('layout2.theme'); // admin
            } elseif ($user->role_id == 2) {
                return redirect()->route('layout1.app'); // student
            } else {
                return redirect()->route('home'); // mặc định
            }
        }

        return back()->withErrors([
            'email' => 'Sai thông tin đăng nhập, vui lòng thử lại.',
        ]);
    }


    // Xử lý logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // Xử lý register (chưa login ngay)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed', // password_confirmation input
            'role_id' => 'required|in:1,2', // 1 = Admin, 2 = Student
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
        ]);

        // Sau khi đăng ký → về trang login
        return redirect()->route('login')->with('success', 'Đăng ký thành công. Vui lòng đăng nhập.');
    }
}
