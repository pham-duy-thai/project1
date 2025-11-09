@extends('layout2.theme')

@section('title', 'Chi tiết Đăng ký phòng')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-primary mb-4">Chi tiết Đăng ký phòng</h2>

        <div class="card col-md-6">
            <div class="card-body">
                <p><strong>Sinh viên:</strong> {{ $registration->student->name }}</p>
                <p><strong>Mã SV:</strong> {{ $registration->student->student_code }}</p>
                <p><strong>Phòng:</strong> {{ $registration->room->room_number }} -
                    {{ $registration->room->floor->building->name }}</p>
                <p><strong>Ngày đăng ký:</strong> {{ $registration->registration_date }}</p>
                <p><strong>Ngày kết thúc:</strong> {{ $registration->end_date ?? 'Chưa xác định' }}</p>
                <p><strong>Trạng thái:</strong>
                    <span class="badge bg-{{ $registration->status == 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($registration->status) }}
                    </span>
                </p>
            </div>
        </div>

        <a href="{{ route('registrations.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
    </div>
@endsection
