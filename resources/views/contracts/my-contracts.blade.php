@extends('layouts.app')

@section('title', 'Hợp đồng của tôi')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 fw-bold">
                        <i class="ri-file-list-3-line me-2 text-primary"></i>
                        Hợp đồng của tôi
                    </h3>
                    <p class="text-muted mb-0">Quản lý và theo dõi các hợp đồng thuê phòng của bạn</p>
                </div>
                <div>
                    <a href="{{ route('contracts.register-room') }}" class="btn btn-primary shadow-sm">
                        <i class="ri-add-line me-1"></i>
                        Đăng ký phòng mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="ri-error-warning-line me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="ri-filter-line me-1"></i>
                        Trạng thái hợp đồng
                    </label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        @foreach(['pending'=>'Chờ duyệt','active'=>'Đang hoạt động','terminated'=>'Đã chấm dứt','expired'=>'Hết hạn'] as $value=>$label)
                            <option value="{{ $value }}" {{ request('status')===$value?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line me-1"></i>
                        Lọc
                    </button>
                </div>
                @if(request('status'))
                    <div class="col-md-2">
                        <a href="{{ route('contracts.my-contracts') }}" class="btn btn-outline-secondary w-100">
                            <i class="ri-close-line me-1"></i>
                            Xóa bộ lọc
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Contracts List -->
    <div class="row g-4">
        @forelse($contracts as $contract)
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100 hover-card">
                    <div class="card-body">
                        <!-- Status Badge -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1 fw-bold">
                                    <i class="ri-home-4-line me-2 text-primary"></i>
                                    {{ $contract->room->room_number }} - Tòa {{ $contract->room->building->name }}
                                </h5>
                                <p class="text-muted small mb-0">
                                    <i class="ri-user-line me-1"></i>
                                    Sức chứa: {{ $contract->room->capacity }} người
                                </p>
                            </div>
                            @php($statusConfig = [
                                'pending' => ['class'=>'warning', 'icon'=>'time-line', 'text'=>'Chờ duyệt'],
                                'active' => ['class'=>'success', 'icon'=>'checkbox-circle-line', 'text'=>'Đang hoạt động'],
                                'terminated' => ['class'=>'danger', 'icon'=>'close-circle-line', 'text'=>'Đã chấm dứt'],
                                'expired' => ['class'=>'secondary', 'icon'=>'history-line', 'text'=>'Hết hạn']
                            ])
                            @php($status = $statusConfig[$contract->status] ?? ['class'=>'secondary','icon'=>'information-line','text'=>ucfirst($contract->status)])
                            <span class="badge bg-{{ $status['class'] }} px-3 py-2">
                                <i class="ri-{{ $status['icon'] }} me-1"></i>
                                {{ $status['text'] }}
                            </span>
                        </div>

                        <!-- Contract Info -->
                        <div class="mb-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted d-block">Mã hợp đồng</small>
                                        <strong class="text-primary">{{ $contract->contract_number }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted d-block">Chu kỳ thanh toán</small>
                                        <strong>
                                            @switch($contract->payment_cycle)
                                                @case('monthly') Hàng tháng @break
                                                @case('semester') Học kỳ @break
                                                @case('yearly') Năm @break
                                                @default {{ ucfirst($contract->payment_cycle) }}
                                            @endswitch
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <i class="ri-calendar-line text-primary me-2"></i>
                                <small class="text-muted me-2">Thời gian:</small>
                                <span class="badge bg-light text-dark">{{ $contract->start_date->format('d/m/Y') }}</span>
                                <i class="ri-arrow-right-line mx-2 text-muted"></i>
                                <span class="badge bg-light text-dark">{{ $contract->end_date->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center p-2 bg-success bg-opacity-10 rounded">
                                    <i class="ri-money-dollar-circle-line text-success fs-4 me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Tiền phòng/tháng</small>
                                        <strong class="text-success">{{ number_format($contract->monthly_rent,0,',','.') }} đ</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center p-2 bg-info bg-opacity-10 rounded">
                                    <i class="ri-bank-card-line text-info fs-4 me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Tiền đặt cọc</small>
                                        <strong class="text-info">{{ number_format($contract->deposit_amount,0,',','.') }} đ</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('contracts.show', $contract) }}" class="btn btn-primary btn-sm flex-fill">
                                <i class="ri-eye-line me-1"></i>
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <i class="ri-file-list-3-line text-muted" style="font-size: 64px;"></i>
                        <h5 class="mt-3 mb-2">Chưa có hợp đồng</h5>
                        <p class="text-muted mb-3">Bạn chưa có hợp đồng thuê phòng nào.</p>
                        <a href="{{ route('contracts.register-room') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>
                            Đăng ký phòng ngay
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($contracts->hasPages())
        <div class="mt-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    {{ $contracts->withQueryString()->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
</style>
@endsection
