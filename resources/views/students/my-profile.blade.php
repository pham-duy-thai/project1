@extends('layouts.app')
@section('title', 'Trang cá nhân')
@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">Trang cá nhân</h4>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <img src="{{ $student->avatar_url }}" alt="Avatar" class="rounded-circle mb-2" style="width: 80px; height: 80px; min-width: 80px; min-height: 80px; object-fit: cover; border-radius: 50% !important; display: block; margin-left: auto; margin-right: auto;">
                    <h6 class="mb-1">{{ $student->full_name }}</h6>
                    <p class="text-muted small mb-0">{{ $student->student_code }}</p>
                    <span class="badge bg-{{ $student->status==='active'?'success':'secondary' }} mt-2">{{ $student->status_label }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Thông tin sinh viên</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $student->email }}
                        </div>
                        <div class="col-md-6">
                            <strong>Điện thoại:</strong> {{ $student->phone }}
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày sinh:</strong> {{ $student->date_of_birth->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Giới tính:</strong> {{ ucfirst($student->gender) }}
                        </div>
                        <div class="col-md-6">
                            <strong>CMND/CCCD:</strong> {{ $student->citizen_id }}
                        </div>
                        <div class="col-md-6">
                            <strong>Khoa:</strong> {{ $student->faculty }}
                        </div>
                        <div class="col-md-6">
                            <strong>Ngành:</strong> {{ $student->major }}
                        </div>
                        <div class="col-md-6">
                            <strong>Lớp:</strong> {{ $student->class }}
                        </div>
                        <div class="col-12">
                            <strong>Địa chỉ thường trú:</strong> {{ $student->permanent_address }}
                        </div>
                        @if($student->parent_name)
                        <div class="col-md-6">
                            <strong>Phụ huynh:</strong> {{ $student->parent_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>SĐT phụ huynh:</strong> {{ $student->parent_phone }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ri-bill-line text-primary" style="font-size:32px"></i>
                        <div class="ms-3">
                            <h6 class="mb-0">{{ $unpaidInvoicesCount }}</h6>
                            <small class="text-muted">Hóa đơn chưa thanh toán</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ri-money-dollar-circle-line text-danger" style="font-size:32px"></i>
                        <div class="ms-3">
                            <h6 class="mb-0">{{ number_format($totalDebt,0,',','.') }} đ</h6>
                            <small class="text-muted">Tổng công nợ</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ri-tools-line text-warning" style="font-size:32px"></i>
                        <div class="ms-3">
                            <h6 class="mb-0">{{ $maintenanceRequestsCount }}</h6>
                            <small class="text-muted">Yêu cầu bảo trì</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ri-alert-line text-secondary" style="font-size:32px"></i>
                        <div class="ms-3">
                            <h6 class="mb-0">{{ $violationsCount }}</h6>
                            <small class="text-muted">Vi phạm</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($currentContract)
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Hợp đồng hiện tại</h6>
            <a href="{{ route('contracts.show', $currentContract) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <strong>Mã HĐ:</strong> {{ $currentContract->contract_number }}
                </div>
                <div class="col-md-3">
                    <strong>Phòng:</strong> {{ $currentContract->room->room_number }} - Tòa {{ $currentContract->room->building->name }}
                </div>
                <div class="col-md-3">
                    <strong>Tiền phòng/tháng:</strong> {{ number_format($currentContract->monthly_rent,0,',','.') }} đ
                </div>
                <div class="col-md-3">
                    <strong>Thời gian:</strong> {{ $currentContract->start_date->format('d/m/Y') }} - {{ $currentContract->end_date->format('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Hóa đơn gần đây</h6>
                    <a href="{{ route('students.my-invoices') }}" class="text-primary small">Xem tất cả →</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Số HĐ</th>
                                <th>Tháng</th>
                                <th>Số tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($invoices as $inv)
                            <tr>
                                <td><a href="{{ route('invoices.show', $inv) }}">{{ $inv->invoice_number }}</a></td>
                                <td>{{ $inv->billing_month ?? '-' }}</td>
                                <td>{{ number_format($inv->amount,0,',','.') }} đ</td>
                                <td>
                                    @php($map=['pending'=>'warning','paid'=>'success','overdue'=>'danger','cancelled'=>'secondary'])
                                    <span class="badge bg-{{ $map[$inv->status]??'secondary' }}">{{ ucfirst($inv->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Chưa có hóa đơn</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Lịch sử hợp đồng</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã HĐ</th>
                                <th>Phòng</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($contractHistory as $ct)
                            <tr>
                                <td><a href="{{ route('contracts.show', $ct) }}">{{ $ct->contract_number }}</a></td>
                                <td>{{ $ct->room->room_number }}-{{ $ct->room->building->name }}</td>
                                <td>{{ $ct->start_date->format('d/m/Y') }} - {{ $ct->end_date->format('d/m/Y') }}</td>
                                <td>
                                    @php($map=['pending'=>'warning','active'=>'success','terminated'=>'danger','expired'=>'secondary'])
                                    <span class="badge bg-{{ $map[$ct->status]??'secondary' }}">{{ ucfirst($ct->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Chưa có lịch sử</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Yêu cầu bảo trì gần đây</h6>
                    <a href="{{ route('students.my-maintenance') }}" class="text-primary small">Xem tất cả →</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mô tả</th>
                                <th>Ngày gửi</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($maintenanceRequests as $req)
                            <tr>
                                <td>{{ Str::limit($req->description,40) }}</td>
                                <td>{{ $req->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @php($map=['pending'=>'warning','approved'=>'info','in_progress'=>'primary','completed'=>'success','rejected'=>'danger'])
                                    <span class="badge bg-{{ $map[$req->status]??'secondary' }}">{{ ucfirst($req->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Chưa có yêu cầu</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Vi phạm gần đây</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mô tả</th>
                                <th>Ngày vi phạm</th>
                                <th>Mức phạt</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($violations as $viol)
                            <tr>
                                <td>{{ Str::limit($viol->description,40) }}</td>
                                <td>{{ $viol->violation_date->format('d/m/Y') }}</td>
                                <td>{{ number_format($viol->fine_amount??0,0,',','.') }} đ</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Không có vi phạm</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
