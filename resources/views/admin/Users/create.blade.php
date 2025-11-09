@extends('layout2.theme')

@section('title', 'Thêm tài khoản mới')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-success mb-4">Thêm tài khoản</h2>

        <form action="{{ route('users.store') }}" method="POST" class="col-md-6">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên người dùng</label>
                <input type="text" id="name" name="name" class="form-control" required
                    placeholder="Nhập tên người dùng">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="Nhập email">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-control" required
                    placeholder="Tối thiểu 6 ký tự">
            </div>

            <div class="mb-3">
                <label for="role_id" class="form-label">Vai trò</label>
                <select id="role_id" name="role_id" class="form-select" required>
                    <option value="">-- Chọn vai trò --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
