@extends('layout1.app')

@section('title', 'Thông tin cá nhân')

@section('content')
    <div class="container py-5">
        <h2 class="text-primary mb-4 text-center">Thông tin cá nhân</h2>

        @if ($student)
            <div class="card shadow-sm mx-auto" style="max-width: 600px;">
                <div class="card-body">
                    <p><strong>Mã sinh viên:</strong> {{ $student->student_code }}</p>
                    <p><strong>Họ tên:</strong> {{ $student->name }}</p>
                    <p><strong>Giới tính:</strong> {{ $student->gender }}</p>
                    <p><strong>Lớp:</strong> {{ $student->class }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $student->phone ?? 'Chưa cập nhật' }}</p>
                    <p><strong>Trạng thái:</strong>
                        @if ($student->status === 'active')
                            <span class="badge bg-success">Đang hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Ngừng</span>
                        @endif
                    </p>
                </div>
            </div>
        @else
            <div class="alert alert-warning text-center">
                Không tìm thấy thông tin sinh viên của bạn.
            </div>
        @endif
    </div>
@endsection
