@extends('layouts.app')

@section('title', 'Dashboard - Sinh viên')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-transparent mb-3">
                <div class="d-flex justify-content-between align-items-center">                             
                    <div>
                        <h3 class="mb-0">Xin chào, {{ Auth::user()->name }}</h3>
                        <p class="text-muted mb-0">Chào mừng bạn đến với hệ thống quản lý ký túc xá</p>
                    </div>
                    <div>
                        <span class="text-muted">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Contract Card -->
        @if($currentContract)
        <div class="col-lg-12">
            <div class="card card-block card-stretch" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h4 class="text-white mb-3">Hợp đồng hiện tại</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><i class="ri-hotel-bed-line mr-2"></i><strong>Phòng:</strong> {{ $currentContract->room->building->name }} - {{ $currentContract->room->room_number }}</p>
                                    <p class="mb-2"><i class="ri-user-line mr-2"></i><strong>Loại phòng:</strong> {{ $currentContract->room->roomType->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><i class="ri-calendar-line mr-2"></i><strong>Từ:</strong> {{ $currentContract->start_date->format('d/m/Y') }}</p>
                                    <p class="mb-2"><i class="ri-calendar-check-line mr-2"></i><strong>Đến:</strong> {{ $currentContract->end_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-right">
                            <div class="bg-white bg-opacity-25 rounded p-3 d-inline-block">
                                <h5 class="text-white mb-1">Tiền phòng/tháng</h5>
                                <h3 class="text-white mb-0">{{ number_format($currentContract->monthly_rent) }} đ</h3>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('contracts.show', $currentContract) }}" class="btn btn-light btn-sm">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="col-lg-12">
            <div class="alert alert-warning" role="alert">
                <i class="ri-information-line mr-2"></i>Bạn chưa có hợp đồng nào. Vui lòng liên hệ với ban quản lý ký túc xá.
            </div>
        </div>
        @endif

        <!-- Quick Stats -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-0 text-warning">{{ $unpaidInvoices }}</h2>
                            <p class="mb-0 text-muted">Hóa đơn chưa thanh toán</p>
                        </div>
                        <div class="rounded-circle iq-card-icon bg-warning-light">
                            <i class="ri-file-list-3-line text-warning" style="font-size: 28px;"></i>
                        </div>
                    </div>
                    @if($unpaidInvoices > 0)
                    <div class="mt-3">
                        <a href="#unpaid-invoices" class="text-warning">Xem chi tiết <i class="ri-arrow-down-line"></i></a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-0 text-info">{{ $maintenanceRequests }}</h2>
                            <p class="mb-0 text-muted">Yêu cầu bảo trì</p>
                        </div>
                        <div class="rounded-circle iq-card-icon bg-info-light">
                            <i class="ri-tools-line text-info" style="font-size: 28px;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('maintenance.index') }}" class="text-info">Xem tất cả <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-0 text-danger">{{ $violations }}</h2>
                            <p class="mb-0 text-muted">Vi phạm</p>
                        </div>
                        <div class="rounded-circle iq-card-icon bg-danger-light">
                            <i class="ri-alert-line text-danger" style="font-size: 28px;"></i>
                        </div>
                    </div>
                    @if($violations > 0)
                    <div class="mt-3">
                        <span class="badge badge-danger">Cần xử lý</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Unpaid Invoices -->
        @if(count($invoices) > 0)
        <div class="col-lg-12" id="unpaid-invoices">
            <div class="card card-block card-stretch">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Hóa đơn chưa thanh toán</h4>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-spacing mb-0">
                            <thead>
                                <tr>
                                    <th>Tháng</th>
                                    <th>Phòng</th>
                                    <th>Tiền phòng</th>
                                    <th>Điện/Nước/DV</th>
                                    <th>Tổng</th>
                                    <th>Hạn thanh toán</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->month }}/{{ $invoice->year }}</td>
                                    <td>{{ $invoice->room->building->name }} - {{ $invoice->room->room_number }}</td>
                                    <td>{{ number_format($invoice->room_price) }} đ</td>
                                    <td>{{ number_format($invoice->electricity_cost + $invoice->water_cost + $invoice->service_cost) }} đ</td>
                                    <td class="font-weight-bold">{{ number_format($invoice->total_amount) }} đ</td>
                                    <td>
                                        @if($invoice->due_date < now())
                                            <span class="badge badge-danger">Quá hạn</span>
                                        @else
                                            <span class="text-muted">{{ $invoice->due_date->format('d/m/Y') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary mr-1">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <form action="{{ route('payments.pay', $invoice) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="payment_method" value="cash">
                                            <input type="hidden" name="amount" value="{{ $invoice->remaining_amount }}">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="ri-money-dollar-circle-line"></i> Thanh toán
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Announcements -->
        <div class="col-lg-8">
            <div class="card card-block card-stretch">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Thông báo mới</h4>
                    </div>
                    <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    @forelse($announcements as $announcement)
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <div class="rounded-circle iq-card-icon bg-{{ $announcement->priority === 'urgent' ? 'danger' : ($announcement->priority === 'high' ? 'warning' : 'primary') }}-light mr-3">
                            <i class="ri-notification-line text-{{ $announcement->priority === 'urgent' ? 'danger' : ($announcement->priority === 'high' ? 'warning' : 'primary') }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $announcement->title }}</h6>
                                    @if($announcement->priority === 'urgent')
                                        <span class="badge badge-danger mb-2">Khẩn cấp</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $announcement->published_at?->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0 text-muted">{{ Str::limit(strip_tags($announcement->content), 150) }}</p>
                            <a href="{{ route('announcements.show', $announcement) }}" class="text-primary">Đọc thêm <i class="ri-arrow-right-line"></i></a>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted py-4">Không có thông báo mới</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Room Information -->
        @if($currentContract)
        <div class="col-lg-4">
            <div class="card card-block card-stretch">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Thông tin phòng</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Tòa nhà</h6>
                        <p class="mb-0">{{ $currentContract->room->building->name }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Số phòng</h6>
                        <p class="mb-0">{{ $currentContract->room->room_number }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Loại phòng</h6>
                        <p class="mb-0">{{ $currentContract->room->roomType->name }} ({{ $currentContract->room->roomType->capacity }} người)</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Diện tích</h6>
                        <p class="mb-0">{{ $currentContract->room->roomType->area }} m²</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Tiện nghi</h6>
                        <div>
                            @forelse($currentContract->room->amenities as $amenity)
                                <span class="badge badge-info mb-1">{{ $amenity->name }}</span>
                            @empty
                                <span class="text-muted">Chưa cập nhật</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('maintenance.create', ['room_id' => $currentContract->room_id]) }}" class="btn btn-sm btn-block btn-outline-primary">
                            <i class="ri-tools-line"></i> Yêu cầu bảo trì
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
