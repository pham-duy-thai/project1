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
        // Nếu đã đăng nhập → điều hướng theo email admin
        if (Auth::check()) {
            if (Auth::user()->email === 'admin@gmail.com') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('student.dashboard');
            }
        }

        // Nếu chưa đăng nhập → hiển thị giao diện trang chủ (FE)
        return view('home');
    }

    /**
     * Dashboard dành cho Admin (layout2)
     */
    public function adminDashboard()
    {
        // Có thể truyền thêm dữ liệu thống kê nếu cần
        return view('admin.dashboard')->with('layout', 'layout2.theme');
    }

    /**
     * Dashboard dành cho Sinh viên (layout1)
     */
    public function studentDashboard()
    {
        // Có thể truyền dữ liệu cá nhân / đăng ký phòng ở đây
        return view('student.dashboard')->with('layout', 'layout1.app');
    }
}
