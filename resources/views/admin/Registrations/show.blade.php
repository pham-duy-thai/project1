@extends('layout2.theme')

@section('title', 'Chi tiết đăng ký phòng')

@section('content')
    <div class="container mt-4">
        <h3 class="text-primary mb-4">Chi tiết Đăng ký Phòng</h3>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3 text-secondary">Thông tin sinh viên</h5>
                <ul class="list-group mb-4">
                    <li class="list-group-item"><strong>Họ tên:</strong> {{ $registration->student->name ?? '—' }}</li>
                    <li class="list-group-item"><strong>Mã sinh viên:</strong>
                        {{ $registration->student->student_code ?? '—' }}</li>
                    <li class="list-group-item"><strong>Lớp:</strong> {{ $registration->student->class ?? '—' }}</li>
                    <li class="list-group-item"><strong>Số điện thoại:</strong> {{ $registration->student->phone ?? '—' }}
                    </li>
                </ul>

                <h5 class="mb-3 text-secondary">Thông tin phòng</h5>
                <ul class="list-group mb-4">
                    <li class="list-group-item"><strong>Tòa nhà:</strong> {{ $registration->room->building->name ?? '—' }}
                    </li>
                    <li class="list-group-item"><strong>Số phòng:</strong> {{ $registration->room->room_number ?? '—' }}
                    </li>
                    <li class="list-group-item"><strong>Tầng:</strong> {{ $registration->room->floor_number ?? '—' }}</li>
                    <li class="list-group-item"><strong>Sức chứa:</strong> {{ $registration->room->capacity ?? '—' }} người
                    </li>
                    <li class="list-group-item"><strong>Giá:</strong>
                        {{ number_format($registration->room->price ?? 0, 0, ',', '.') }} VNĐ</li>
                </ul>

                <h5 class="mb-3 text-secondary">Trạng thái đăng ký</h5>
                <ul class="list-group mb-4">
                    <li class="list-group-item">
                        <strong>Trạng thái hiện tại:</strong>
                        @php
                            $colors = [
                                'pending' => 'warning',
                                'approved' => 'primary',
                                'active' => 'success',
                                'rejected' => 'danger',
                                'completed' => 'secondary',
                            ];
                        @endphp
                        <span class="badge bg-{{ $colors[$registration->status] ?? 'secondary' }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                    </li>
                    <li class="list-group-item"><strong>Ngày đăng ký:</strong>
                        {{ $registration->registration_date ? \Carbon\Carbon::parse($registration->registration_date)->format('d/m/Y') : '—' }}
                    </li>
                    <li class="list-group-item"><strong>Ngày kết thúc:</strong>
                        {{ $registration->end_date ? \Carbon\Carbon::parse($registration->end_date)->format('d/m/Y') : '—' }}
                    </li>
                </ul>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>

                    @if ($registration->status === 'pending')
                        <a href="{{ url('admin/registrations/update-status/' . $registration->id . '/approved') }}"
                            class="btn btn-success" onclick="return confirm('Duyệt đăng ký này?')">
                            <i class="fas fa-check"></i> Duyệt
                        </a>
                        <a href="{{ url('admin/registrations/update-status/' . $registration->id . '/rejected') }}"
                            class="btn btn-danger" onclick="return confirm('Từ chối đăng ký này?')">
                            <i class="fas fa-times"></i> Từ chối
                        </a>
                    @elseif ($registration->status === 'approved')
                        <a href="{{ url('admin/registrations/update-status/' . $registration->id . '/active') }}"
                            class="btn btn-primary" onclick="return confirm('Chuyển sang trạng thái đang ở?')">
                            <i class="fas fa-door-open"></i> Kích hoạt
                        </a>
                    @elseif ($registration->status === 'active')
                        <a href="{{ url('admin/registrations/end/' . $registration->id) }}" class="btn btn-secondary"
                            onclick="return confirm('Kết thúc hợp đồng này?')">
                            <i class="fas fa-sign-out-alt"></i> Kết thúc
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
