@extends('layout2.theme')

@section('content')
    <div class="container">
        <h2>📄 Chi tiết Đăng ký #{{ $registration->id }}</h2>
        <hr>

        <div class="card">
            <div class="card-header bg-primary text-white">
                Thông tin Sinh viên
            </div>
            <div class="card-body">
                <p><strong>Họ tên:</strong> {{ $registration->student->name ?? 'N/A' }}</p>
                <p><strong>Mã SV:</strong> {{ $registration->student->student_code ?? 'N/A' }}</p>
                <p><strong>Lớp:</strong> {{ $registration->student->class ?? 'N/A' }}</p>
                <p><strong>Số điện thoại:</strong> {{ $registration->student->phone ?? 'N/A' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $registration->student->address ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                Thông tin Phòng
            </div>
            <div class="card-body">
                <p><strong>Số phòng:</strong> {{ $registration->room->room_number ?? 'N/A' }}</p>
                <p><strong>Tòa nhà:</strong> {{ $registration->room->building->name ?? 'N/A' }}</p>
                <p><strong>Sức chứa còn lại:</strong> {{ $registration->room->capacity ?? 0 }} người</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-success text-white">
                Thông tin Đăng ký
            </div>
            <div class="card-body">
                <p><strong>Ngày đăng ký:</strong> {{ $registration->registration_date->format('d/m/Y') }}</p>
                <p><strong>Ngày kết thúc dự kiến:</strong>
                    {{ $registration->end_date ? $registration->end_date->format('d/m/Y') : 'Chưa xác định' }}</p>
                <p><strong>Trạng thái:</strong>
                    @switch($registration->status)
                        @case('pending')
                            <span class="badge bg-warning text-dark">Chờ duyệt</span>
                        @break

                        @case('approved')
                            <span class="badge bg-success">Đã duyệt</span>
                        @break

                        @case('rejected')
                            <span class="badge bg-danger">Từ chối</span>
                        @break

                        @case('completed')
                            <span class="badge bg-secondary">Hoàn thành</span>
                        @break
                    @endswitch
                </p>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary">← Quay lại</a>

            @if ($registration->status == 'pending')
                <form action="{{ route('admin.registrations.approve', $registration) }}" method="POST"
                    style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success" onclick="return confirm('Xác nhận duyệt?')">✓
                        Duyệt</button>
                </form>

                <form action="{{ route('admin.registrations.reject', $registration) }}" method="POST"
                    style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận từ chối?')">✗ Từ
                        chối</button>
                </form>
            @endif
        </div>
    </div>
@endsection
