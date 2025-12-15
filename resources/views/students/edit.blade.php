@extends('layouts.app')

@section('title', 'Chỉnh sửa sinh viên')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Quay lại danh sách
            </a>
        </div>

        <div class="col-lg-12">
            <form action="{{ route('students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Student Information -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ri-user-line mr-2"></i>Thông tin sinh viên</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="student_code">Mã sinh viên <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('student_code') is-invalid @enderror" 
                                                   id="student_code" name="student_code" value="{{ old('student_code', $student->student_code) }}" required>
                                            @error('student_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="citizen_id">CCCD/CMND <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('citizen_id') is-invalid @enderror" 
                                                   id="citizen_id" name="citizen_id" value="{{ old('citizen_id', $student->citizen_id) }}" required>
                                            @error('citizen_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="full_name">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                           id="full_name" name="full_name" value="{{ old('full_name', $student->full_name) }}" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="date_of_birth">Ngày sinh <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" 
                                           value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}" required>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="gender">Giới tính <span class="text-danger">*</span></label>
                                    <select class="form-control @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender" required>
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $student->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="permanent_address">Địa chỉ thường trú <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('permanent_address') is-invalid @enderror" 
                                              id="permanent_address" name="permanent_address" rows="2" required>{{ old('permanent_address', $student->permanent_address) }}</textarea>
                                    @error('permanent_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="contact_address">Địa chỉ liên hệ</label>
                                    <textarea class="form-control @error('contact_address') is-invalid @enderror" 
                                              id="contact_address" name="contact_address" rows="2">{{ old('contact_address', $student->contact_address) }}</textarea>
                                    @error('contact_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="parent_name">Người liên hệ khẩn cấp</label>
                                            <input type="text" class="form-control @error('parent_name') is-invalid @enderror" 
                                                   id="parent_name" name="parent_name" 
                                                   value="{{ old('parent_name', $student->parent_name) }}">
                                            @error('parent_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="parent_phone">SĐT người liên hệ khẩn cấp</label>
                                            <input type="text" class="form-control @error('parent_phone') is-invalid @enderror" 
                                                   id="parent_phone" name="parent_phone" 
                                                   value="{{ old('parent_phone', $student->parent_phone) }}">
                                            @error('parent_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="status">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                        <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                                        <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Đã tốt nghiệp</option>
                                        <option value="suspended" {{ old('status', $student->status) == 'suspended' ? 'selected' : '' }}>Đã bị đình chỉ</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="faculty">Khoa <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('faculty') is-invalid @enderror" 
                                                   id="faculty" name="faculty" value="{{ old('faculty', $student->faculty) }}" required>
                                            @error('faculty')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="major">Ngành <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('major') is-invalid @enderror" 
                                                   id="major" name="major" value="{{ old('major', $student->major) }}" required>
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
                                            <input type="text" class="form-control @error('class') is-invalid @enderror" 
                                                   id="class" name="class" value="{{ old('class', $student->class) }}" required>
                                            @error('class')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="academic_year">Năm học</label>
                                            <input type="number" min="2000" max="{{ date('Y') + 10 }}" class="form-control @error('academic_year') is-invalid @enderror" 
                                                   id="academic_year" name="academic_year" value="{{ old('academic_year', $student->academic_year) }}">
                                            @error('academic_year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Info (Read-only) -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="ri-lock-line mr-2"></i>Tài khoản đăng nhập</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Tên hiển thị <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $student->user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $student->user->email) }}" readonly>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Email không thể thay đổi</small>
                                </div>

                                <div class="form-group">
                                    <label>Vai trò</label>
                                    <input type="text" class="form-control" value="Sinh viên" readonly disabled>
                                </div>

                                <div class="alert alert-info">
                                    <small>
                                        <i class="ri-information-line mr-1"></i>
                                        Để thay đổi mật khẩu, vui lòng sử dụng chức năng "Đổi mật khẩu" trong tài khoản
                                    </small>
                                </div>
                            </div>
                        </div>

                        @if($student->activeContract)
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="ri-home-line mr-2"></i>Phòng hiện tại</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>Tòa nhà:</strong> {{ $student->activeContract->room->building->name }}</p>
                                <p class="mb-1"><strong>Số phòng:</strong> {{ $student->activeContract->room->room_number }}</p>
                                <p class="mb-1"><strong>Từ ngày:</strong> {{ $student->activeContract->start_date->format('d/m/Y') }}</p>
                                <p class="mb-0"><strong>Đến ngày:</strong> {{ $student->activeContract->end_date->format('d/m/Y') }}</p>
                                <a href="{{ route('contracts.show', $student->activeContract) }}" class="btn btn-sm btn-outline-primary mt-2">
                                    Xem hợp đồng
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between">
                                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Hủy</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line mr-2"></i>Cập nhật thông tin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
