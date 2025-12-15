@extends('layouts.app')
@section('title','Hóa đơn của tôi')
@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-1 fw-bold">
                <i class="ri-file-list-line me-2 text-primary"></i>
                Hóa đơn của tôi
            </h3>
            <p class="text-muted mb-0">Quản lý và theo dõi các hóa đơn thanh toán</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 hover-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                <i class="ri-money-dollar-circle-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Tổng chưa thanh toán</h6>
                            <h4 class="mb-0 fw-bold text-warning">{{ number_format($stats['total_unpaid'],0,',','.') }} đ</h4>
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
                            <div class="stats-icon bg-danger bg-opacity-10 text-danger">
                                <i class="ri-alert-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Hóa đơn quá hạn</h6>
                            <h4 class="mb-0 fw-bold text-danger">{{ $stats['overdue_count'] }}</h4>
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
                                <i class="ri-calendar-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Hóa đơn tháng này</h6>
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
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="ri-filter-line me-1"></i>
                        Trạng thái thanh toán
                    </label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        @foreach(['pending'=>'Chờ thanh toán','partial'=>'Thanh toán 1 phần','paid'=>'Đã thanh toán','overdue'=>'Quá hạn','cancelled'=>'Đã hủy'] as $val=>$label)
                            <option value="{{ $val }}" {{ request('status')===$val?'selected':'' }}>{{ $label }}</option>
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
                        <a href="{{ route('invoices.my-invoices') }}" class="btn btn-outline-secondary w-100">
                            <i class="ri-close-line me-1"></i>
                            Xóa bộ lọc
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Invoices List -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-semibold">Danh sách hóa đơn</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Số hóa đơn</th>
                        <th>Phòng</th>
                        <th>Tháng</th>
                        <th class="text-end">Tổng tiền</th>
                        <th class="text-end">Đã thanh toán</th>
                        <th class="text-end">Còn lại</th>
                        <th>Hạn thanh toán</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center pe-3">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($invoices as $inv)
                    <tr>
                        <td class="ps-3">
                            <a href="{{ route('invoices.show', $inv) }}" class="text-decoration-none fw-semibold">
                                <i class="ri-file-text-line me-1"></i>
                                {{ $inv->invoice_number }}
                            </a>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $inv->contract->room->room_number ?? '-' }}</strong>
                                <br>
                                <small class="text-muted">{{ $inv->contract->room->building->name ?? '' }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">
                                <i class="ri-calendar-2-line me-1"></i>
                                {{ $inv->billing_month ?? '-' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <strong class="text-primary">{{ number_format($inv->total_amount,0,',','.') }} đ</strong>
                        </td>
                        <td class="text-end">
                            <span class="text-success">{{ number_format($inv->paid_amount,0,',','.') }} đ</span>
                        </td>
                        <td class="text-end">
                            <strong class="text-danger">{{ number_format($inv->remaining_amount,0,',','.') }} đ</strong>
                        </td>
                        <td>
                            @if($inv->due_date)
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-time-line text-muted"></i>
                                    <span>{{ $inv->due_date->format('d/m/Y') }}</span>
                                    @if($inv->status !== 'paid' && $inv->due_date->isPast())
                                        <span class="badge bg-danger">Quá hạn</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php($statusConfig = [
                                'pending' => ['class'=>'warning', 'icon'=>'time-line', 'text'=>'Chờ thanh toán'],
                                'partial' => ['class'=>'info', 'icon'=>'hourglass-line', 'text'=>'Thanh toán 1 phần'],
                                'paid' => ['class'=>'success', 'icon'=>'checkbox-circle-line', 'text'=>'Đã thanh toán'],
                                'overdue' => ['class'=>'danger', 'icon'=>'alert-line', 'text'=>'Quá hạn'],
                                'cancelled' => ['class'=>'secondary', 'icon'=>'close-circle-line', 'text'=>'Đã hủy']
                            ])
                            @php($status = $statusConfig[$inv->status] ?? ['class'=>'secondary','icon'=>'information-line','text'=>ucfirst($inv->status)])
                            <span class="badge bg-{{ $status['class'] }} px-3 py-2">
                                <i class="ri-{{ $status['icon'] }} me-1"></i>
                                {{ $status['text'] }}
                            </span>
                        </td>
                        <td class="text-center pe-3">
                            <div class="btn-group" role="group">
                                <a href="{{ route('invoices.show', $inv) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                    <i class="ri-eye-line"></i>
                                </a>
                                @if(in_array($inv->status, ['pending','partial','overdue']))
                                    <form method="POST" action="{{ route('payments.pay', $inv) }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="cash">
                                        <input type="hidden" name="amount" value="{{ $inv->remaining_amount }}">
                                        <button type="submit" class="btn btn-sm btn-success" title="Thanh toán">
                                            <i class="ri-secure-payment-line"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="ri-file-list-line text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-2 mb-0">Chưa có hóa đơn nào.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
            <div class="card-footer bg-white">
                {{ $invoices->withQueryString()->links() }}
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
