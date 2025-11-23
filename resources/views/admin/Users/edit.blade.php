@extends('layout2.theme')

@section('title', 'Chỉnh sửa tài khoản')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary mb-4">Chỉnh sửa tài khoản</h2>

        {{-- Thông báo lỗi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Họ tên</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                    class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                    class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu (chỉ nhập nếu muốn đổi)</label>
                <input type="password" name="password" id="password" class="form-control"
                    placeholder="Để trống nếu không muốn đổi mật khẩu">
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu (nếu đổi)</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    placeholder="Nhập lại mật khẩu mới">
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
