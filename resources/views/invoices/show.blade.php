@extends('layouts.app')

@section('title', 'Chi tiết hóa đơn')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('invoices.index') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Quay lại
            </a>
        </div>

        @if(session('success'))
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        </div>
        @endif

        <!-- Invoice Info -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-bill-line mr-2"></i>Hóa đơn tháng {{ $invoice->month }}/{{ $invoice->year }}
                    </h5>
                    <span class="badge badge-{{ 
                        $invoice->status === 'paid' ? 'success' : 
                        ($invoice->status === 'partial' ? 'warning' : 'danger') 
                    }} badge-lg">
                        {{ $invoice->getStatusLabelAttribute() }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Thông tin sinh viên</h6>
                            <p class="mb-1"><strong>Họ tên:</strong> {{ $invoice->student->full_name }}</p>
                            <p class="mb-1"><strong>Mã SV:</strong> {{ $invoice->student->student_code }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $invoice->student->user->email }}</p>
                            <p class="mb-1"><strong>SĐT:</strong> {{ $invoice->student->phone_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Thông tin phòng</h6>
                            <p class="mb-1"><strong>Tòa nhà:</strong> {{ $invoice->room->building->name }}</p>
                            <p class="mb-1"><strong>Số phòng:</strong> {{ $invoice->room->room_number }}</p>
                            <p class="mb-1"><strong>Loại phòng:</strong> {{ $invoice->room->roomType->name }}</p>
                            <p class="mb-1"><strong>Khu vực:</strong> {{ $invoice->room->building->dormitory->name }}</p>
                        </div>
                    </div>

                    <h6 class="text-muted mb-3">Chi tiết hóa đơn</h6>
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Khoản phí</th>
                                <th class="text-right">Đơn giá</th>
                                <th class="text-right">Số lượng</th>
                                <th class="text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Tiền phòng</strong></td>
                                <td class="text-right">{{ number_format($invoice->room_price) }} đ</td>
                                <td class="text-right">1 tháng</td>
                                <td class="text-right">{{ number_format($invoice->room_price) }} đ</td>
                            </tr>
                            <tr>
                                <td>Tiền điện ({{ $invoice->electricity_usage ?? 0 }} kWh)</td>
                                <td class="text-right">{{ number_format($invoice->electricity_unit_price ?? 3500) }} đ</td>
                                <td class="text-right">{{ $invoice->electricity_usage ?? 0 }}</td>
                                <td class="text-right">{{ number_format($invoice->electricity_cost) }} đ</td>
                            </tr>
                            <tr>
                                <td>Tiền nước ({{ $invoice->water_usage ?? 0 }} m³)</td>
                                <td class="text-right">{{ number_format($invoice->water_unit_price ?? 15000) }} đ</td>
                                <td class="text-right">{{ $invoice->water_usage ?? 0 }}</td>
                                <td class="text-right">{{ number_format($invoice->water_cost) }} đ</td>
                            </tr>
                            <tr>
                                <td>Phí dịch vụ (Internet, vệ sinh, bảo vệ)</td>
                                <td class="text-right" colspan="2">-</td>
                                <td class="text-right">{{ number_format($invoice->service_cost) }} đ</td>
                            </tr>
                            @if($invoice->other_fees > 0)
                            <tr>
                                <td>Phí khác <small class="text-muted">({{ $invoice->notes }})</small></td>
                                <td class="text-right" colspan="2">-</td>
                                <td class="text-right">{{ number_format($invoice->other_fees) }} đ</td>
                            </tr>
                            @endif
                            <tr class="bg-light font-weight-bold">
                                <td colspan="3" class="text-right">Tổng cộng:</td>
                                <td class="text-right text-primary" style="font-size: 1.2rem;">
                                    {{ number_format($invoice->total_amount) }} đ
                                </td>
                            </tr>
                            @if($invoice->paid_amount > 0)
                            <tr>
                                <td colspan="3" class="text-right">Đã thanh toán:</td>
                                <td class="text-right text-success">{{ number_format($invoice->paid_amount) }} đ</td>
                            </tr>
                            <tr class="bg-warning">
                                <td colspan="3" class="text-right"><strong>Còn lại:</strong></td>
                                <td class="text-right"><strong>{{ number_format($invoice->total_amount - $invoice->paid_amount) }} đ</strong></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Ngày tạo:</strong> {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
                            <p class="mb-1"><strong>Hạn thanh toán:</strong> 
                                <span class="{{ $invoice->due_date < now() && $invoice->status !== 'paid' ? 'text-danger font-weight-bold' : '' }}">
                                    {{ $invoice->due_date->format('d/m/Y') }}
                                    @if($invoice->due_date < now() && $invoice->status !== 'paid')
                                        (Quá hạn {{ now()->diffInDays($invoice->due_date) }} ngày)
                                    @endif
                                </span>
                            </p>
                        </div>
                        @if($invoice->payment_date)
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Ngày thanh toán:</strong> {{ $invoice->payment_date->format('d/m/Y H:i') }}</p>
                            <p class="mb-1"><strong>Phương thức:</strong> {{ $invoice->payment_method ?? 'Tiền mặt' }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History & Actions -->
        <div class="col-lg-4">
            <!-- Action Card -->
            @if($invoice->status !== 'paid')
            <div class="card">
                <div class="card-body text-center">
                    <i class="ri-money-dollar-circle-line text-success" style="font-size: 48px;"></i>
                    <h5 class="mt-3 mb-2">Thanh toán hóa đơn</h5>
                    <p class="text-muted mb-3">Số tiền cần thanh toán:</p>
                    <h3 class="text-primary mb-4">{{ number_format($invoice->total_amount - $invoice->paid_amount) }} đ</h3>
                    
                    <!-- Payment Form -->
                    <form action="{{ route('payments.pay', $invoice) }}" method="POST" id="paymentForm">
                        @csrf
                        <div class="form-group">
                            <label>Phương thức thanh toán</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="">-- Chọn phương thức --</option>
                                <option value="cash">Tiền mặt</option>
                                <option value="bank_transfer">Chuyển khoản</option>
                                <option value="vnpay">VNPay</option>
                                <option value="momo">MoMo</option>
                                <option value="zalopay">ZaloPay</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Số tiền thanh toán</label>
                            <input type="number" name="amount" class="form-control" value="{{ $invoice->remaining_amount }}" min="1000" max="{{ $invoice->remaining_amount }}" required>
                            <small class="text-muted">Tối đa: {{ number_format($invoice->remaining_amount) }}đ</small>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="ri-secure-payment-line mr-2"></i>Thanh toán ngay
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Payment History -->
            @if($invoice->payments->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ri-history-line mr-2"></i>Lịch sử thanh toán</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($invoice->payments as $payment)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong class="text-success">+{{ number_format($payment->amount) }} đ</strong>
                                    <br><small class="text-muted">{{ $payment->payment_method_label }}</small>
                                    <br><small class="text-muted">{{ $payment->created_at->format('d/m/Y H:i') }}</small>
                                    @if($payment->transaction_code)
                                    <br><small class="text-muted">Mã GD: {{ $payment->transaction_code }}</small>
                                    @endif
                                </div>
                                <span class="badge badge-{{ 
                                    $payment->status === 'completed' ? 'success' : 
                                    ($payment->status === 'pending' ? 'warning' : 'danger') 
                                }}">
                                    {{ $payment->getStatusLabelAttribute() }}
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
