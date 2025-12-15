@extends('layouts.app')

@section('title', 'Thêm sinh viên mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">Thêm sinh viên mới</h4>
                    <p class="mb-0 text-muted">Tạo sinh viên và tài khoản đăng nhập</p>
                </div>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line mr-2"></i>Quay lại
                </a>
            </div>
        </div>

        <div class="col-lg-10 mx-auto">
            <form action="{{ route('students.store') }}" method="POST">
                @csrf

                <!-- Student Info Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin sinh viên</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_code">Mã sinh viên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('student_code') is-invalid @enderror" 
                                           name="student_code" value="{{ old('student_code') }}" 
                                           placeholder="VD: SV001234" required>
                                    @error('student_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="citizen_id">CCCD/CMND <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('citizen_id') is-invalid @enderror" 
                                           name="citizen_id" value="{{ old('citizen_id') }}" 
                                           placeholder="12 số" required>
                                    @error('citizen_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="full_name">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                   name="full_name" value="{{ old('full_name') }}" required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_of_birth">Ngày sinh <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">Giới tính <span class="text-danger">*</span></label>
                                    <select class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                        <option value="">Chọn giới tính</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="permanent_address">Địa chỉ thường trú <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('permanent_address') is-invalid @enderror" 
                                      name="permanent_address" rows="2" required>{{ old('permanent_address') }}</textarea>
                            @error('permanent_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact_address">Địa chỉ liên hệ</label>
                            <textarea class="form-control @error('contact_address') is-invalid @enderror" 
                                      name="contact_address" rows="2">{{ old('contact_address') }}</textarea>
                            @error('contact_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parent_name">Người liên hệ khẩn cấp</label>
                                    <input type="text" class="form-control @error('parent_name') is-invalid @enderror" 
                                           name="parent_name" value="{{ old('parent_name') }}">
                                    @error('parent_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parent_phone">SĐT người liên hệ khẩn cấp</label>
                                    <input type="text" class="form-control @error('parent_phone') is-invalid @enderror" 
                                           name="parent_phone" value="{{ old('parent_phone') }}">
                                    @error('parent_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>Đã tốt nghiệp</option>
                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Đã bị đình chỉ</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- User Account Card -->
                <!-- Academic Info Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin học tập</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="faculty">Khoa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('faculty') is-invalid @enderror" name="faculty" value="{{ old('faculty') }}" required>
                                    @error('faculty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="major">Ngành <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('major') is-invalid @enderror" name="major" value="{{ old('major') }}" required>
                                    @error('major')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class">Lớp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('class') is-invalid @enderror" name="class" value="{{ old('class') }}" required>
                                    @error('class')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="academic_year">Năm học</label>
                                    <input type="number" min="2000" max="{{ date('Y') + 10 }}" class="form-control @error('academic_year') is-invalid @enderror" name="academic_year" value="{{ old('academic_year') }}">
                                    @error('academic_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Account Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tài khoản đăng nhập</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Tên hiển thị <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name', old('full_name')) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required>
                            <small class="form-text text-muted">Email để đăng nhập hệ thống</small>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           name="password" required>
                                    <small class="form-text text-muted">Tối thiểu 8 ký tự</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-close-line mr-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line mr-2"></i>Lưu sinh viên
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
