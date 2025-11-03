<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role_id == 1) {
            return redirect()->route('layout2.theme');
        } elseif ($user->role_id == 2) {
            return redirect()->route('layout1.app');
        }

        abort(403, 'Không xác định vai trò người dùng.');
    }


    public function admin()
    {
        return view('layout2.theme');
    }

    public function student()
    {
        return view('layout1.app');
    }
}
