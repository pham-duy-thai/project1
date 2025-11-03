@extends('layout.theme')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-center text-success">Thêm người dùng mới</h2>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Họ và tên</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Địa chỉ Email</label>
                <input type="email" name="email" id="email"
                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="role_id" class="form-label">Vai trò</label>
                <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                    <option value="">-- Chọn vai trò --</option>
                    <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Admin</option>
                    <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>Quản lý sinh viên</option>
                    <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>Sinh viên</option>
                </select>
                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Lưu người dùng
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
@endsection
