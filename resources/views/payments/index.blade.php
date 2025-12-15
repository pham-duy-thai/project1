@extends('layouts.app')

@section('title', 'Quản lý thanh toán')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title">Danh sách thanh toán</h4>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('payments.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tìm kiếm mã thanh toán</label>
                                    <input type="text" name="search" class="form-control" placeholder="Nhập mã thanh toán..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select name="status" class="form-control">
                                        <option value="">Tất cả</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Phương thức</label>
                                    <select name="payment_method" class="form-control">
                                        <option value="">Tất cả</option>
                                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                                        <option value="vnpay" {{ request('payment_method') == 'vnpay' ? 'selected' : '' }}>VNPay</option>
                                        <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>MoMo</option>
                                        <option value="zalopay" {{ request('payment_method') == 'zalopay' ? 'selected' : '' }}>ZaloPay</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="ri-search-line"></i> Lọc
                                    </button>
                                </div>
                            </div>
                            @if(request()->has('search') || request()->has('status') || request()->has('payment_method'))
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-block">
                                        <i class="ri-refresh-line"></i> Xóa bộ lọc
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </form>

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6>Tổng thanh toán</h6>
                                    <h3>{{ $payments->total() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6>Hoàn thành</h6>
                                    <h3>{{ number_format(\App\Models\Payment::where('status', 'completed')->sum('amount')) }} đ</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h6>Chờ xử lý</h6>
                                    <h3>{{ \App\Models\Payment::where('status', 'pending')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h6>Thất bại</h6>
                                    <h3>{{ \App\Models\Payment::where('status', 'failed')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Mã thanh toán</th>
                                    <th>Sinh viên</th>
                                    <th>Hóa đơn</th>
                                    <th>Số tiền</th>
                                    <th>Phương thức</th>
                                    <th>Ngày thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>
                                        <strong>{{ $payment->payment_code }}</strong>
                                        @if($payment->transaction_id)
                                        <br><small class="text-muted">{{ $payment->transaction_id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->student)
                                        <div>{{ $payment->student->full_name }}</div>
                                        <small class="text-muted">{{ $payment->student->student_code }}</small>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->invoice)
                                        <a href="{{ route('invoices.show', $payment->invoice_id) }}">
                                            {{ $payment->invoice->invoice_number }}
                                        </a>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($payment->amount) }} đ</strong>
                                    </td>
                                    <td>
                                        @if($payment->payment_method == 'cash')
                                        <span class="badge badge-secondary">Tiền mặt</span>
                                        @elseif($payment->payment_method == 'bank_transfer')
                                        <span class="badge badge-info">Chuyển khoản</span>
                                        @elseif($payment->payment_method == 'vnpay')
                                        <span class="badge badge-primary">VNPay</span>
                                        @elseif($payment->payment_method == 'momo')
                                        <span class="badge badge-danger">MoMo</span>
                                        @elseif($payment->payment_method == 'zalopay')
                                        <span class="badge badge-info">ZaloPay</span>
                                        @else
                                        <span class="badge badge-secondary">{{ $payment->payment_method }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->payment_date)
                                        <div>{{ $payment->payment_date->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $payment->payment_date->format('H:i') }}</small>
                                        @else
                                        <span class="text-muted">Chưa có</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->status == 'pending')
                                        <span class="badge badge-warning">Chờ xử lý</span>
                                        @elseif($payment->status == 'completed')
                                        <span class="badge badge-success">Hoàn thành</span>
                                        @elseif($payment->status == 'failed')
                                        <span class="badge badge-danger">Thất bại</span>
                                        @elseif($payment->status == 'cancelled')
                                        <span class="badge badge-secondary">Đã hủy</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-info" title="Chi tiết">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        
                                        @if($payment->status == 'pending' && $payment->payment_method == 'cash' && Auth::user()->isAdmin())
                                        <form action="{{ route('payments.confirm-cash', $payment->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Xác nhận thanh toán này?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Xác nhận">
                                                <i class="ri-checkbox-circle-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        @if($payment->status == 'pending' && (Auth::user()->student && Auth::user()->student->id == $payment->student_id || Auth::user()->isAdmin()))
                                        <form action="{{ route('payments.cancel', $payment->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Hủy thanh toán này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hủy">
                                                <i class="ri-close-circle-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="ri-file-list-line" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted mt-2">Không có thanh toán nào</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($payments->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Hiển thị {{ $payments->firstItem() ?? 0 }} - {{ $payments->lastItem() ?? 0 }} trong tổng số {{ $payments->total() }} thanh toán
                        </div>
                        <div>
                            {{ $payments->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
