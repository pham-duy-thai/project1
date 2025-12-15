@extends('layouts.app')

@section('title', 'Chi tiết thanh toán')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title">Chi tiết thanh toán</h4>
                    </div>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line"></i> Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <!-- Payment Status Alert -->
                    @if($payment->status == 'completed')
                    <div class="alert alert-success">
                        <i class="ri-checkbox-circle-line"></i> Thanh toán thành công
                    </div>
                    @elseif($payment->status == 'pending')
                    <div class="alert alert-warning">
                        <i class="ri-time-line"></i> Đang chờ xác nhận
                    </div>
                    @elseif($payment->status == 'failed')
                    <div class="alert alert-danger">
                        <i class="ri-error-warning-line"></i> Thanh toán thất bại
                    </div>
                    @elseif($payment->status == 'cancelled')
                    <div class="alert alert-secondary">
                        <i class="ri-close-circle-line"></i> Đã hủy
                    </div>
                    @endif

                    <!-- Payment Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Mã thanh toán</h6>
                            <h5>{{ $payment->payment_code }}</h5>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Ngày thanh toán</h6>
                            <h5>{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y H:i') : 'N/A' }}</h5>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Số tiền</h6>
                            <h4 class="text-success">{{ number_format($payment->amount) }} đ</h4>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Phương thức</h6>
                            <h5>
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
                                {{ $payment->payment_method }}
                                @endif
                            </h5>
                        </div>
                    </div>

                    @if($payment->transaction_id)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted">Mã giao dịch</h6>
                            <p>{{ $payment->transaction_id }}</p>
                        </div>
                    </div>
                    @endif

                    @if($payment->notes)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted">Ghi chú</h6>
                            <p>{{ $payment->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Invoice Info -->
                    <hr>
                    <h5 class="mb-3">Thông tin hóa đơn</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Mã hóa đơn</th>
                                <td>
                                    <a href="{{ route('invoices.show', $payment->invoice_id) }}">
                                        {{ $payment->invoice->invoice_number }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Sinh viên</th>
                                <td>{{ $payment->student->full_name }} ({{ $payment->student->student_code }})</td>
                            </tr>
                            <tr>
                                <th>Phòng</th>
                                <td>
                                    {{ $payment->invoice->contract->room->building->name }} - 
                                    Phòng {{ $payment->invoice->contract->room->room_number }}
                                </td>
                            </tr>
                            <tr>
                                <th>Kỳ thanh toán</th>
                                <td>
                                    {{ $payment->invoice->billing_period_start->format('d/m/Y') }} - 
                                    {{ $payment->invoice->billing_period_end->format('d/m/Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Tổng hóa đơn</th>
                                <td class="text-danger font-weight-bold">{{ number_format($payment->invoice->total_amount) }} đ</td>
                            </tr>
                            <tr>
                                <th>Đã thanh toán</th>
                                <td class="text-success">{{ number_format($payment->invoice->paid_amount) }} đ</td>
                            </tr>
                            <tr>
                                <th>Còn lại</th>
                                <td class="text-warning font-weight-bold">{{ number_format($payment->invoice->remaining_amount) }} đ</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="col-lg-4">
            @if($payment->status == 'pending' && $payment->payment_method == 'cash' && Auth::user()->isAdmin())
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Xác nhận thanh toán</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Xác nhận đã nhận tiền mặt từ sinh viên</p>
                    <form action="{{ route('payments.confirm-cash', $payment) }}" method="POST" onsubmit="return confirm('Xác nhận đã nhận thanh toán?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="ri-checkbox-circle-line"></i> Xác nhận thanh toán
                        </button>
                    </form>
                </div>
            </div>
            @endif

            @if($payment->status == 'pending')
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hủy thanh toán</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Hủy giao dịch thanh toán này</p>
                    <form action="{{ route('payments.cancel', $payment) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy thanh toán này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="ri-close-circle-line"></i> Hủy thanh toán
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Quick Links -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thao tác</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('invoices.show', $payment->invoice_id) }}" class="btn btn-outline-primary btn-block mb-2">
                        <i class="ri-file-list-line"></i> Xem hóa đơn
                    </a>
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-block">
                        <i class="ri-list-check"></i> Danh sách thanh toán
                    </a>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Lịch sử</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <div class="d-flex">
                                <div class="mr-3">
                                    <i class="ri-checkbox-circle-fill text-success"></i>
                                </div>
                                <div>
                                    <strong>Tạo thanh toán</strong>
                                    <br><small class="text-muted">{{ $payment->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        </li>
                        @if($payment->status == 'completed')
                        <li class="mb-3">
                            <div class="d-flex">
                                <div class="mr-3">
                                    <i class="ri-check-double-line text-success"></i>
                                </div>
                                <div>
                                    <strong>Hoàn thành</strong>
                                    <br><small class="text-muted">{{ $payment->updated_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
