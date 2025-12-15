@extends('layouts.app')
@section('title','Thanh toán của tôi')
@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-1 fw-bold">
                <i class="ri-wallet-line me-2 text-primary"></i>
                Lịch sử thanh toán
            </h3>
            <p class="text-muted mb-0">Theo dõi các giao dịch thanh toán của bạn</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 hover-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-success bg-opacity-10 text-success">
                                <i class="ri-check-double-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Tổng đã thanh toán</h6>
                            <h4 class="mb-0 fw-bold text-success">{{ number_format($stats['total_paid'],0,',','.') }} đ</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 hover-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                <i class="ri-time-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Đang chờ xử lý</h6>
                            <h4 class="mb-0 fw-bold text-warning">{{ $stats['pending_count'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 hover-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-info bg-opacity-10 text-info">
                                <i class="ri-calendar-check-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Thanh toán tháng này</h6>
                            <h4 class="mb-0 fw-bold text-info">{{ number_format($stats['this_month'],0,',','.') }} đ</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="ri-filter-line me-1"></i>
                        Trạng thái
                    </label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        @foreach(['pending'=>'Chờ xử lý','completed'=>'Hoàn thành','failed'=>'Thất bại','cancelled'=>'Đã hủy'] as $val=>$label)
                            <option value="{{ $val }}" {{ request('status')===$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="ri-bank-card-line me-1"></i>
                        Phương thức thanh toán
                    </label>
                    <select name="payment_method" class="form-select">
                        <option value="">-- Tất cả phương thức --</option>
                        @foreach(['cash'=>'Tiền mặt','bank_transfer'=>'Chuyển khoản','vnpay'=>'VNPay','momo'=>'MoMo','zalopay'=>'ZaloPay'] as $val=>$label)
                            <option value="{{ $val }}" {{ request('payment_method')===$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line me-1"></i>
                        Lọc
                    </button>
                </div>
                @if(request('status') || request('payment_method'))
                    <div class="col-md-2">
                        <a href="{{ route('payments.my-payments') }}" class="btn btn-outline-secondary w-100">
                            <i class="ri-close-line me-1"></i>
                            Xóa bộ lọc
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Payments List -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-semibold">Danh sách giao dịch</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Mã thanh toán</th>
                        <th>Hóa đơn</th>
                        <th>Phòng</th>
                        <th class="text-end">Số tiền</th>
                        <th class="text-center">Phương thức</th>
                        <th>Ngày thanh toán</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center pe-3">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($payments as $pay)
                    <tr>
                        <td class="ps-3">
                            <a href="{{ route('payments.show', $pay) }}" class="text-decoration-none fw-semibold">
                                <i class="ri-bill-line me-1"></i>
                                {{ $pay->payment_code }}
                            </a>
                        </td>
                        <td>
                            @if($pay->invoice)
                                <a href="{{ route('invoices.show', $pay->invoice) }}" class="text-decoration-none">
                                    <i class="ri-file-text-line me-1"></i>
                                    {{ $pay->invoice->invoice_number }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($pay->invoice && $pay->invoice->contract && $pay->invoice->contract->room)
                                <div>
                                    <strong>{{ $pay->invoice->contract->room->room_number }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $pay->invoice->contract->room->building->name ?? '' }}</small>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <strong class="text-success">{{ number_format($pay->amount,0,',','.') }} đ</strong>
                        </td>
                        <td class="text-center">
                            @php($methodConfig = [
                                'cash' => ['icon'=>'money-dollar-circle-line', 'color'=>'success', 'text'=>'Tiền mặt'],
                                'bank_transfer' => ['icon'=>'bank-line', 'color'=>'primary', 'text'=>'Chuyển khoản'],
                                'vnpay' => ['icon'=>'wallet-3-line', 'color'=>'danger', 'text'=>'VNPay'],
                                'momo' => ['icon'=>'wallet-2-line', 'color'=>'danger', 'text'=>'MoMo'],
                                'zalopay' => ['icon'=>'wallet-line', 'color'=>'info', 'text'=>'ZaloPay']
                            ])
                            @php($method = $methodConfig[$pay->payment_method] ?? ['icon'=>'question-line','color'=>'secondary','text'=>ucfirst($pay->payment_method)])
                            <span class="badge bg-{{ $method['color'] }} bg-opacity-10 text-{{ $method['color'] }} px-3 py-2">
                                <i class="ri-{{ $method['icon'] }} me-1"></i>
                                {{ $method['text'] }}
                            </span>
                        </td>
                        <td>
                            @if($pay->payment_date)
                                <div class="d-flex align-items-center">
                                    <i class="ri-calendar-check-line text-muted me-2"></i>
                                    <div>
                                        <div>{{ $pay->payment_date->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $pay->payment_date->format('H:i') }}</small>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php($statusConfig = [
                                'pending' => ['class'=>'warning', 'icon'=>'time-line', 'text'=>'Chờ xử lý'],
                                'completed' => ['class'=>'success', 'icon'=>'checkbox-circle-line', 'text'=>'Hoàn thành'],
                                'failed' => ['class'=>'danger', 'icon'=>'close-circle-line', 'text'=>'Thất bại'],
                                'cancelled' => ['class'=>'secondary', 'icon'=>'indeterminate-circle-line', 'text'=>'Đã hủy']
                            ])
                            @php($status = $statusConfig[$pay->status] ?? ['class'=>'secondary','icon'=>'information-line','text'=>ucfirst($pay->status)])
                            <span class="badge bg-{{ $status['class'] }} px-3 py-2">
                                <i class="ri-{{ $status['icon'] }} me-1"></i>
                                {{ $status['text'] }}
                            </span>
                        </td>
                        <td class="text-center pe-3">
                            <a href="{{ route('payments.show', $pay) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                <i class="ri-eye-line me-1"></i>
                                Chi tiết
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="ri-wallet-line text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-2 mb-0">Chưa có giao dịch thanh toán nào.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="card-footer bg-white">
                {{ $payments->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.stats-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 28px;
}
.hover-scale {
    transition: transform 0.2s ease;
}
.hover-scale:hover {
    transform: translateY(-3px);
}
</style>
@endsection
