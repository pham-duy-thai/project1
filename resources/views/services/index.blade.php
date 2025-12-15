@extends('layouts.app')

@section('title', 'Dịch vụ ký túc xá')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Dịch vụ ký túc xá</h4>
                        <p class="mb-0 text-muted">Danh sách các dịch vụ tiện ích</p>
                    </div>
                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('services.create') }}" class="btn btn-primary">
                        <i class="ri-add-line mr-2"></i>Thêm dịch vụ
                    </a>
                    @endif
                </div>

                <!-- Filter -->
                <div class="card-body border-bottom">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="type">Loại dịch vụ</label>
                                <select class="form-control" name="type" onchange="this.form.submit()">
                                    <option value="">Tất cả</option>
                                    <option value="monthly" {{ request('type')=='monthly'?'selected':'' }}>Theo tháng</option>
                                    <option value="one_time" {{ request('type')=='one_time'?'selected':'' }}>Một lần</option>
                                    <option value="per_use" {{ request('type')=='per_use'?'selected':'' }}>Theo lần sử dụng</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-md-0">
                                <label for="search">Tìm kiếm</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên dịch vụ...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary mr-2"><i class="ri-search-line"></i> Tìm</button>
                            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary"><i class="ri-refresh-line"></i></a>
                            @if(Auth::user()->isStudent())
                            <a href="{{ route('services.my-registrations') }}" class="btn btn-outline-info">
                                <i class="ri-list-check"></i> Của tôi
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Services Grid -->
                <div class="card-body">
                    <div class="row">
                        @forelse($services as $service)
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card h-100 shadow-sm service-card">
                                <div class="card-body text-center">
                                    <div class="service-icon mb-3">
                                        <i class="{{ $service->icon ?? 'ri-service-line' }}" style="font-size: 48px; color: #3b82f6;"></i>
                                    </div>
                                    <h5 class="card-title mb-2">{{ $service->name }}</h5>
                                    <p class="text-muted small mb-3" style="min-height: 40px;">{{ Str::limit($service->description, 60) }}</p>
                                    
                                    <div class="price mb-3">
                                        <h4 class="text-primary mb-0">{{ number_format($service->price) }}đ</h4>
                                        <small class="text-muted">/ {{ $service->unit ?? 'lần' }}</small>
                                    </div>

                                    <div class="mb-3">
                                        <span class="badge badge-info">{{ $service->type_label }}</span>
                                        @if(!$service->is_active)
                                        <span class="badge badge-secondary">Tạm dừng</span>
                                        @endif
                                    </div>

                                    @if($service->max_registrations)
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            Còn: {{ $service->max_registrations - $service->activeRegistrations()->count() }}/{{ $service->max_registrations }} slot
                                        </small>
                                    </div>
                                    @endif

                                    @if(Auth::user()->isStudent())
                                        @if(in_array($service->id, $myRegistrations))
                                        <span class="badge badge-success badge-lg">
                                            <i class="ri-check-line"></i> Đã đăng ký
                                        </span>
                                        @elseif($service->is_active && $service->isAvailable())
                                        <a href="{{ route('services.show', $service) }}" class="btn btn-primary btn-block">
                                            <i class="ri-add-circle-line"></i> Đăng ký
                                        </a>
                                        @else
                                        <button class="btn btn-secondary btn-block" disabled>Không khả dụng</button>
                                        @endif
                                    @else
                                        <a href="{{ route('services.show', $service) }}" class="btn btn-outline-primary btn-block">
                                            <i class="ri-eye-line"></i> Chi tiết
                                        </a>
                                    @endif
                                </div>
                                @if(Auth::user()->isAdmin())
                                <div class="card-footer bg-light">
                                    <div class="btn-group btn-group-sm d-flex" role="group">
                                        <a href="{{ route('services.edit', $service) }}" class="btn btn-outline-info flex-fill">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <a href="{{ route('services.registrations', $service) }}" class="btn btn-outline-primary flex-fill">
                                            <i class="ri-user-line"></i> {{ $service->registrations()->count() }}
                                        </a>
                                        <form action="{{ route('services.destroy', $service) }}" method="POST" class="flex-fill" onsubmit="return confirm('Xóa dịch vụ này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="ri-service-line" style="font-size: 64px; color: #ccc;"></i>
                                <h5 class="mt-3 text-muted">Chưa có dịch vụ nào</h5>
                                @if(Auth::user()->isAdmin())
                                <p class="text-muted">Nhấn nút "Thêm dịch vụ" để tạo mới</p>
                                @endif
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                @if($services->hasPages())
                <div class="card-footer">
                    {{ $services->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.service-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}
.service-icon i {
    transition: transform 0.3s;
}
.service-card:hover .service-icon i {
    transform: scale(1.1);
}
</style>
@endsection
