@extends('layout1.app')

@section('title', 'Trang sinh viên')

@section('content')
    <h2 class="text-primary">Xin chào, {{ Auth::user()->name }}</h2>
    <p>Chào mừng bạn đến hệ thống quản lý ký túc xá. Hãy kiểm tra thông tin phòng và đăng ký khi cần!</p>

    <div class="card mt-3">
        <div class="card-body">
            <h5>Thông tin tài khoản</h5>
            <ul>
                <li>Email: {{ Auth::user()->email }}</li>
                <li>Trạng thái: <span class="text-success">Hoạt động</span></li>
            </ul>
        </div>
    </div>
@endsection
