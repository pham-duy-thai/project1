@extends('layouts.app')

@section('title', 'Chi tiết dịch vụ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('services.index') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Quay lại
            </a>
            @if(Auth::user()->isAdmin())
            <a href="{{ route('services.edit', $service) }}" class="btn btn-outline-info">
                <i class="ri-edit-line"></i> Chỉnh sửa
            </a>
            <a href="{{ route('services.registrations', $service) }}" class="btn btn-outline-success">
                <i class="ri-user-line"></i> Quản lý đăng ký
            </a>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <i class="{{ $service->icon ?? 'ri-service-line' }}" style="font-size: 36px; color: #3b82f6;" class="mr-3"></i>
                        <div>
                            <h4 class="mb-0">{{ $service->name }}</h4>
                            <span class="badge badge-info mt-1">{{ $service->type_label }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h3 class="text-primary mb-0">{{ number_format($service->price) }}đ</h3>
                            <small class="text-muted">/ {{ $service->unit ?? 'lần' }}</small>
                        </div>
                        <div class="col-md-6 text-right">
                            @if($service->is_active)
                            <span class="badge badge-success badge-lg">Đang hoạt động</span>
                            @else
                            <span class="badge badge-secondary badge-lg">Tạm dừng</span>
                            @endif
                        </div>
                    </div>

                    @if($service->description)
                    <div class="mb-4">
                        <h5>Mô tả dịch vụ:</h5>
                        <p style="white-space: pre-wrap; line-height: 1.8;">{{ $service->description }}</p>
                    </div>
                    @endif

                    @if($service->max_registrations)
                    <div class="alert alert-info">
                        <i class="ri-information-line mr-2"></i>
                        Còn lại: <strong>{{ $service->max_registrations - $service->activeRegistrations()->count() }}</strong> / {{ $service->max_registrations }} slot
                    </div>
                    @endif

                    @if(Auth::user()->isStudent())
                    <hr>
                    @if($myRegistration)
                        <div class="alert alert-success">
                            <h5><i class="ri-check-double-line mr-2"></i>Bạn đã đăng ký dịch vụ này</h5>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td width="30%">Trạng thái:</td>
                                    <td><span class="badge badge-{{ $myRegistration->status_badge }}">{{ $myRegistration->status_label }}</span></td>
                                </tr>
                                <tr>
                                    <td>Ngày đăng ký:</td>
                                    <td>{{ $myRegistration->start_date->format('d/m/Y') }}</td>
                                </tr>
                                @if($myRegistration->end_date)
                                <tr>
                                    <td>Ngày hết hạn:</td>
                                    <td>{{ $myRegistration->end_date->format('d/m/Y') }}</td>
                                </tr>
                                @endif
                            </table>
                            @if($myRegistration->status !== 'cancelled')
                            <form action="{{ route('service-registrations.cancel', $myRegistration) }}" method="POST" class="mt-3" onsubmit="return confirm('Bạn có chắc muốn hủy đăng ký?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="ri-close-line"></i> Hủy đăng ký
                                </button>
                            </form>
                            @endif
                        </div>
                    @elseif($service->is_active && $service->isAvailable())
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="mb-3">Đăng ký dịch vụ</h5>
                                <form action="{{ route('services.register', $service) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="notes">Ghi chú (không bắt buộc)</label>
                                        <textarea class="form-control" name="notes" rows="3" placeholder="Nhập ghi chú nếu cần...">{{ old('notes') }}</textarea>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="agree" required>
                                        <label class="form-check-label" for="agree">
                                            Tôi đồng ý với các điều khoản sử dụng dịch vụ
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        <i class="ri-add-circle-line mr-2"></i>Đăng ký ngay
                                    </button>
                                    @if($service->requires_approval)
                                    <small class="text-muted d-block mt-2">* Đăng ký cần được admin duyệt</small>
                                    @endif
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="ri-alert-line mr-2"></i>
                            Dịch vụ hiện không khả dụng để đăng ký
                        </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Thông tin</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="40%">Loại dịch vụ:</td>
                            <td><strong>{{ $service->type_label }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Giá:</td>
                            <td class="text-primary"><strong>{{ number_format($service->price) }}đ</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Đơn vị:</td>
                            <td>{{ $service->unit ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Trạng thái:</td>
                            <td>
                                @if($service->is_active)
                                <span class="badge badge-success">Đang hoạt động</span>
                                @else
                                <span class="badge badge-secondary">Tạm dừng</span>
                                @endif
                            </td>
                        </tr>
                        @if($service->requires_approval)
                        <tr>
                            <td class="text-muted">Duyệt:</td>
                            <td><span class="badge badge-warning">Cần duyệt</span></td>
                        </tr>
                        @endif
                        @if($service->max_registrations)
                        <tr>
                            <td class="text-muted">Giới hạn:</td>
                            <td>{{ $service->max_registrations }} đăng ký</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if(Auth::user()->isAdmin())
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Thống kê</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <i class="ri-user-line mr-2"></i>
                        <strong>{{ $service->registrations()->count() }}</strong> tổng đăng ký
                    </p>
                    <p class="mb-2">
                        <i class="ri-check-line mr-2 text-success"></i>
                        <strong>{{ $service->activeRegistrations()->count() }}</strong> đang hoạt động
                    </p>
                    <p class="mb-0">
                        <i class="ri-time-line mr-2 text-warning"></i>
                        <strong>{{ $service->registrations()->where('status', 'pending')->count() }}</strong> chờ duyệt
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
