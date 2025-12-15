@extends('layouts.app')

@section('title', 'Quản lý đăng ký - ' . $service->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('services.show', $service) }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Quay lại
            </a>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-1">Quản lý đăng ký: {{ $service->name }}</h4>
                    <p class="mb-0 text-muted">Danh sách sinh viên đã đăng ký dịch vụ này</p>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="12%">Mã SV</th>
                                    <th width="20%">Họ tên</th>
                                    <th width="12%">Ngày đăng ký</th>
                                    <th width="12%">Ngày hết hạn</th>
                                    <th width="12%">Trạng thái</th>
                                    <th width="15%">Ghi chú</th>
                                    <th width="12%" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registrations as $index => $registration)
                                <tr>
                                    <td>{{ $registrations->firstItem() + $index }}</td>
                                    <td><strong>{{ $registration->student->student_code }}</strong></td>
                                    <td>
                                        <a href="{{ route('students.show', $registration->student) }}" class="text-primary">
                                            {{ $registration->student->first_name }} {{ $registration->student->last_name }}
                                        </a>
                                    </td>
                                    <td>{{ $registration->start_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($registration->end_date)
                                        {{ $registration->end_date->format('d/m/Y') }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $registration->status_badge }}">
                                            {{ $registration->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($registration->notes, 30) ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($registration->status === 'pending')
                                        <form action="{{ route('service-registrations.approve', $registration) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Duyệt">
                                                <i class="ri-check-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @if($registration->status !== 'cancelled')
                                        <form action="{{ route('service-registrations.cancel', $registration) }}" method="POST" class="d-inline" onsubmit="return confirm('Hủy đăng ký này?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hủy">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="ri-user-line" style="font-size: 64px; color: #ccc;"></i>
                                        <h5 class="mt-3 text-muted">Chưa có đăng ký nào</h5>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($registrations->hasPages())
                <div class="card-footer">
                    {{ $registrations->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
