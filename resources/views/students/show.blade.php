@extends('layouts.app')

@section('title', 'Chi tiết sinh viên')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Quay lại
            </a>
            @if(Auth::user()->isAdmin())
            <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-info">
                <i class="ri-edit-line"></i> Chỉnh sửa
            </a>
            @endif
        </div>

        <!-- Student Info -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-user-line mr-2"></i>Thông tin sinh viên</h5>
                    <span class="badge badge-{{ $student->status == 'active' ? 'success' : ($student->status == 'inactive' ? 'warning' : 'secondary') }} badge-lg">
                        {{ $student->getStatusLabelAttribute() }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center mb-3">
                            <img src="{{ $student->avatar_url }}" alt="{{ $student->full_name }}" 
                                 class="rounded-circle" style="width: 100px; height: 100px; min-width: 100px; min-height: 100px; object-fit: cover; border-radius: 50% !important; display: block;" onerror="this.onerror=null;this.src='{{ asset('assets/images/user/user-1.jpg') }}';">
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Họ và tên</small>
                                    <strong>{{ $student->full_name }}</strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Mã sinh viên</small>
                                    <strong>{{ $student->student_code }}</strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">CCCD/CMND</small>
                                    <strong>{{ $student->citizen_id }}</strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Giới tính</small>
                                    <strong>
                                        @if($student->gender === 'male') Nam
                                        @elseif($student->gender === 'female') Nữ
                                        @else Khác
                                        @endif
                                    </strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Ngày sinh</small>
                                    <strong>{{ $student->date_of_birth?->format('d/m/Y') }}</strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Email</small>
                                    <strong>{{ $student->user->email }}</strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Số điện thoại</small>
                                    <strong>{{ $student->phone }}</strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Trạng thái</small>
                                    <span class="badge badge-{{ $student->status == 'active' ? 'success' : ($student->status == 'inactive' ? 'warning' : 'secondary') }}">
                                        {{ $student->getStatusLabelAttribute() }}
                                    </span>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <small class="text-muted d-block">Địa chỉ thường trú</small>
                                    <strong>{{ $student->permanent_address }}</strong>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <small class="text-muted d-block">Địa chỉ liên hệ</small>
                                    <strong>{{ $student->contact_address }}</strong>
                                </div>
                                @if($student->parent_name)
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Người liên hệ khẩn cấp</small>
                                    <strong>{{ $student->parent_name }}</strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">SĐT khẩn cấp</small>
                                    <strong>{{ $student->parent_phone }}</strong>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Contract -->
            @if($currentContract)
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="ri-home-line mr-2"></i>Phòng hiện tại</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Tòa nhà:</strong> {{ $currentContract->room->building->name }}</p>
                            <p class="mb-2"><strong>Số phòng:</strong> {{ $currentContract->room->room_number }}</p>
                            <p class="mb-2"><strong>Loại phòng:</strong> {{ $currentContract->room->roomType->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Ngày bắt đầu:</strong> {{ $currentContract->start_date->format('d/m/Y') }}</p>
                            <p class="mb-2"><strong>Ngày kết thúc:</strong> {{ $currentContract->end_date->format('d/m/Y') }}</p>
                            <p class="mb-2"><strong>Tiền phòng:</strong> {{ number_format($currentContract->monthly_rent) }} đ/tháng</p>
                        </div>
                    </div>
                    <a href="{{ route('contracts.show', $currentContract) }}" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="ri-file-text-line mr-1"></i>Xem chi tiết hợp đồng
                    </a>
                </div>
            </div>
            @endif

            <!-- Invoice History -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ri-bill-line mr-2"></i>Lịch sử hóa đơn</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tháng</th>
                                    <th>Phòng</th>
                                    <th>Tổng tiền</th>
                                    <th>Đã TT</th>
                                    <th>Trạng thái</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->month }}/{{ $invoice->year }}</td>
                                    <td>{{ $invoice->room->building->name }} - {{ $invoice->room->room_number }}</td>
                                    <td>{{ number_format($invoice->total_amount) }} đ</td>
                                    <td class="text-success">{{ number_format($invoice->paid_amount) }} đ</td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $invoice->status === 'paid' ? 'success' : 
                                            ($invoice->status === 'partial' ? 'warning' : 'danger') 
                                        }}">
                                            {{ $invoice->getStatusLabelAttribute() }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">Chưa có hóa đơn</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($invoices->count() >= 10)
                    <div class="card-footer text-center">
                        <a href="{{ route('invoices.index', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-primary">
                            Xem tất cả hóa đơn
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-lg-4">
            <!-- Statistics -->
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3"><i class="ri-bar-chart-line mr-2"></i>Thống kê</h6>
                    <div class="mb-3">
                        <small class="text-muted d-block">Hóa đơn chưa thanh toán</small>
                        <h4 class="mb-0 text-danger">{{ $unpaidInvoicesCount }}</h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Tổng tiền nợ</small>
                        <h4 class="mb-0 text-warning">{{ number_format($totalDebt) }} đ</h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Yêu cầu bảo trì</small>
                        <h4 class="mb-0 text-info">{{ $maintenanceRequestsCount }}</h4>
                    </div>
                    <div>
                        <small class="text-muted d-block">Vi phạm</small>
                        <h4 class="mb-0 text-danger">{{ $violationsCount }}</h4>
                    </div>
                </div>
            </div>

            <!-- Recent Maintenance Requests -->
            @if($maintenanceRequests->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ri-tools-line mr-2"></i>Yêu cầu bảo trì gần đây</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($maintenanceRequests as $request)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ Str::limit($request->title, 30) }}</strong>
                                    <br><small class="text-muted">{{ $request->created_at->format('d/m/Y') }}</small>
                                </div>
                                <span class="badge badge-{{ 
                                    $request->status === 'completed' ? 'success' : 
                                    ($request->status === 'in_progress' ? 'info' : 'warning') 
                                }}">
                                    {{ $request->getStatusLabelAttribute() }}
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Violations -->
            @if($violations->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ri-alert-line mr-2"></i>Vi phạm</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($violations as $violation)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <strong>{{ $violation->violation_type }}</strong>
                                    <br><small class="text-muted">{{ $violation->created_at->format('d/m/Y') }}</small>
                                    <br><small class="text-danger">Phạt: {{ number_format($violation->fine_amount) }} đ</small>
                                </div>
                                <span class="badge badge-{{ $violation->is_resolved ? 'success' : 'danger' }}">
                                    {{ $violation->is_resolved ? 'Đã xử lý' : 'Chưa xử lý' }}
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
