@extends('layouts.app')

@section('title', 'Tạo tài khoản')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">Tạo tài khoản</h4>
                    <p class="mb-0 text-muted">Tạo người dùng mới cho hệ thống</p>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="ri-arrow-left-line mr-2"></i>Quay lại</a>
            </div>
        </div>

        <div class="col-lg-8 mx-auto">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin tài khoản</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Tên hiển thị <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Vai trò <span class="text-danger">*</span></label>
                                    <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                                        <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Quản trị</option>
                                        <option value="student" {{ old('role')=='student'?'selected':'' }}>Sinh viên</option>
                                    </select>
                                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">Trạng thái</label>
                                    <select class="form-control @error('is_active') is-invalid @enderror" name="is_active">
                                        <option value="1" {{ old('is_active','1')=='1'?'selected':'' }}>Đang hoạt động</option>
                                        <option value="0" {{ old('is_active')==='0'?'selected':'' }}>Vô hiệu hóa</option>
                                    </select>
                                    @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}">
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bio">Giới thiệu</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" name="bio" rows="3">{{ old('bio') }}</textarea>
                            @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="ri-close-line mr-2"></i>Hủy</a>
                    <button type="submit" class="btn btn-primary"><i class="ri-save-line mr-2"></i>Tạo tài khoản</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
