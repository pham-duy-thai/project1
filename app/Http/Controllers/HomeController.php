<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Trang chủ hệ thống (chưa đăng nhập)
     */
    public function index()
    {
        // Nếu đã đăng nhập → tự động điều hướng theo role
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role_id == 2) {
                return redirect()->route('student.dashboard');
            }
        }

        // Nếu chưa đăng nhập → hiển thị trang chủ mặc định (FE)
        return view('home');
    }

    /**
     * Dashboard dành cho Admin (layout2)
     */
    public function adminDashboard()
    {
        return view('admin.dashboard')->with('layout', 'layout2.theme');
    }

    /**
     * Dashboard dành cho Sinh viên (layout1)
     */
    public function studentDashboard()
    {
        return view('student.dashboard')->with('layout', 'layout1.app');
    }
}
