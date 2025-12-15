@extends('layouts.app')

@section('title', 'Dashboard - Quản lý Ký túc xá')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Dashboard - Quản lý Ký túc xá</h4>
                    <p class="mb-0 text-muted">Xin chào, {{ Auth::user()->name }}! Chào mừng đến với hệ thống quản lý.</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards Row 1 -->
        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mm-cart-text">
                            <h6 class="text-primary">Sinh viên</h6>
                            <h2>{{ $totalStudents }}</h2>
                            <p class="mb-0">Đang ở KTX</p>
                        </div>
                        <div class="rounded-circle iq-card-icon bg-primary-light">
                            <i class="ri-user-line text-primary" style="font-size: 32px;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('students.index') }}" class="text-primary">Xem danh sách <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mm-cart-text">
                            <h6 class="text-success">Phòng đã cho thuê</h6>
                            <h2>{{ $occupiedRooms }}</h2>
                            <div class="iq-progress-bar mt-2">
                                <span class="bg-success iq-progress progress-1" data-percent="{{ $occupancyRate }}" style="transition: width 2s ease 0s; width: {{ $occupancyRate }}%;"></span>
                            </div>
                            <p class="mb-0 mt-2">{{ $occupancyRate }}% lấp đầy</p>
                        </div>
                        <div class="rounded-circle iq-card-icon bg-success-light">
                            <i class="ri-home-smile-line text-success" style="font-size: 32px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mm-cart-text">
                            <h6 class="text-danger">Hóa đơn chưa TT</h6>
                            <h2>{{ $unpaidInvoices }}</h2>
                            <p class="mb-0 text-danger font-weight-bold">{{ number_format($totalUnpaidAmount) }} đ</p>
                        </div>
                        <div class="rounded-circle iq-card-icon bg-danger-light">
                            <i class="ri-bill-line text-danger" style="font-size: 32px;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('invoices.index') }}" class="btn btn-primary btn-sm">Xem hóa đơn</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mm-cart-text">
                            <h6 class="text-warning">Bảo trì chờ xử lý</h6>
                            <h2>{{ $pendingMaintenance }}</h2>
                            <p class="mb-0 text-danger">{{ $urgentMaintenance }} khẩn cấp</p>
                        </div>
                        <div class="rounded-circle iq-card-icon bg-warning-light">
                            <i class="ri-tools-line text-warning" style="font-size: 32px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards Row 2 -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Doanh thu tháng này</h4>
                    </div>
                </div>
                <div class="card-body">
                    <h3 class="text-success">{{ number_format($monthlyRevenue) }} đ</h3>
                    <p class="text-muted mb-0">Từ các hóa đơn đã thanh toán</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Hợp đồng sắp hết hạn</h4>
                    </div>
                </div>
                <div class="card-body">
                    <h3 class="text-warning">{{ $expiringContracts }}</h3>
                    <p class="text-muted mb-3">Hợp đồng hết hạn trong 30 ngày</p>
                    <div class="mt-3">
                        <a href="{{ route('contracts.index', ['expiring_soon' => 1]) }}" class="btn btn-warning btn-sm">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Phòng còn trống</h4>
                    </div>
                </div>
                <div class="card-body">
                    <h3 class="text-info">{{ $availableRooms }}</h3>
                    <p class="text-muted mb-3">Phòng sẵn sàng cho thuê</p>
                    <div class="mt-3">
                        <a href="{{ route('rooms.index', ['status' => 'available']) }}" class="btn btn-info btn-sm">Xem phòng trống</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Students -->
        <div class="col-lg-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Sinh viên mới</h4>
                    </div>
                    <a href="{{ route('students.index') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-spacing mb-0">
                            <thead>
                                <tr>
                                    <th>Sinh viên</th>
                                    <th>Email</th>
                                    <th>Ngày tạo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentStudents as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $student->avatar_url }}" class="rounded-circle mr-3" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; object-fit: cover; border-radius: 50% !important; display: block;" alt="Avatar">
                                            <div>
                                                <strong>{{ $student->full_name }}</strong>
                                                <br><small class="text-muted">{{ $student->student_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->user->email }}</td>
                                    <td>{{ $student->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Chưa có sinh viên mới</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Maintenance Requests -->
        <div class="col-lg-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Yêu cầu bảo trì gần đây</h4>
                    </div>
                    <a href="{{ route('maintenance.index') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    @forelse($recentMaintenanceRequests as $request)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $request->title }}</h6>
                                <p class="mb-1 text-muted small">{{ $request->room->building->name }} - {{ $request->room->room_number }}</p>
                                <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                            </div>
                            <div>
                                <span class="badge badge-{{ 
                                    $request->priority === 'urgent' ? 'danger' : 
                                    ($request->priority === 'high' ? 'warning' : 'info') 
                                }}">{{ $request->getPriorityLabelAttribute() }}</span>
                                <a href="{{ route('maintenance.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                    Chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted py-4">Chưa có yêu cầu bảo trì</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="col-lg-12">
            <div class="card card-block card-stretch card-height">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Thông báo mới nhất</h4>
                    </div>
                    <a href="{{ route('announcements.create') }}" class="btn btn-sm btn-primary">
                        <i class="ri-add-line mr-1"></i>Tạo thông báo
                    </a>
                </div>
                <div class="card-body">
                    @forelse($recentAnnouncements as $announcement)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-2">{{ $announcement->title }}</h6>
                                <p class="mb-2 text-muted">{{ Str::limit(strip_tags($announcement->content), 100) }}</p>
                                <small class="text-muted">{{ $announcement->published_at?->diffForHumans() ?? $announcement->created_at->diffForHumans() }}</small>
                            </div>
                            <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-sm btn-outline-primary">Xem</a>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted py-4">Chưa có thông báo</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
