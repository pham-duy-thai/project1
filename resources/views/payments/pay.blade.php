@extends('layouts.app')

@section('title', 'Thanh toán hóa đơn')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Quay lại hóa đơn
            </a>
        </div>

        @if(session('error'))
        <div class="col-lg-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        </div>
        @endif

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="ri-secure-payment-line mr-2"></i>Thanh toán hóa đơn</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Thông tin hóa đơn</h6>
                            <p class="mb-1"><strong>Tháng:</strong> {{ $invoice->month }}/{{ $invoice->year }}</p>
                            <p class="mb-1"><strong>Sinh viên:</strong> {{ $invoice->student->full_name }}</p>
                            <p class="mb-1"><strong>Phòng:</strong> {{ $invoice->room->building->name }} - {{ $invoice->room->room_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Chi tiết thanh toán</h6>
                            <p class="mb-1"><strong>Tổng hóa đơn:</strong> {{ number_format($invoice->total_amount) }} đ</p>
                            @if($invoice->paid_amount > 0)
                            <p class="mb-1"><strong>Đã thanh toán:</strong> <span class="text-success">{{ number_format($invoice->paid_amount) }} đ</span></p>
                            @endif
                            <p class="mb-1 text-danger"><strong>Còn lại:</strong> {{ number_format($remainingAmount) }} đ</p>
                        </div>
                    </div>

                    <hr>

                    <form action="{{ route('payments.process') }}" method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                        <div class="form-group">
                            <label for="amount">Số tiền thanh toán <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-lg @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ $remainingAmount }}" 
                                       min="1000" max="{{ $remainingAmount }}" step="1000" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">đ</span>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Tối thiểu: 1.000đ | Tối đa: {{ number_format($remainingAmount) }}đ</small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <button type="button" class="btn btn-outline-secondary btn-block" 
                                        onclick="setAmount({{ $remainingAmount }})">
                                    Thanh toán toàn bộ
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-outline-secondary btn-block" 
                                        onclick="setAmount({{ floor($remainingAmount / 2 / 1000) * 1000 }})">
                                    Thanh toán 50%
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-outline-secondary btn-block" 
                                        onclick="setAmount(1000000)">
                                    1,000,000đ
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Phương thức thanh toán <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input @error('payment_method') is-invalid @enderror" 
                                               id="vnpay" name="payment_method" value="vnpay" checked>
                                        <label class="custom-control-label" for="vnpay">
                                            <div class="border rounded p-3">
                                                <i class="ri-bank-card-line text-primary" style="font-size: 32px;"></i>
                                                <h6 class="mb-1 mt-2">VNPay</h6>
                                                <small class="text-muted">Thanh toán qua cổng VNPay (ATM, QR, Visa/Master)</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" 
                                               id="cash" name="payment_method" value="cash">
                                        <label class="custom-control-label" for="cash">
                                            <div class="border rounded p-3">
                                                <i class="ri-money-dollar-circle-line text-success" style="font-size: 32px;"></i>
                                                <h6 class="mb-1 mt-2">Tiền mặt</h6>
                                                <small class="text-muted">Thanh toán trực tiếp tại văn phòng KTX</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" 
                                               id="bank_transfer" name="payment_method" value="bank_transfer">
                                        <label class="custom-control-label" for="bank_transfer">
                                            <div class="border rounded p-3">
                                                <i class="ri-exchange-dollar-line text-info" style="font-size: 32px;"></i>
                                                <h6 class="mb-1 mt-2">Chuyển khoản</h6>
                                                <small class="text-muted">Chuyển khoản ngân hàng (cần xác nhận)</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" 
                                               id="momo" name="payment_method" value="momo">
                                        <label class="custom-control-label" for="momo">
                                            <div class="border rounded p-3">
                                                <i class="ri-wallet-line text-danger" style="font-size: 32px;"></i>
                                                <h6 class="mb-1 mt-2">Ví MoMo</h6>
                                                <small class="text-muted">Thanh toán qua ví điện tử MoMo</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" id="bank_transfer_info" style="display: none;">
                            <div class="alert alert-info">
                                <h6 class="mb-2"><i class="ri-information-line mr-2"></i>Thông tin chuyển khoản:</h6>
                                <p class="mb-1"><strong>Ngân hàng:</strong> Vietcombank</p>
                                <p class="mb-1"><strong>Số tài khoản:</strong> 1234567890</p>
                                <p class="mb-1"><strong>Chủ tài khoản:</strong> KY TUC XA DHQG</p>
                                <p class="mb-1"><strong>Nội dung:</strong> KTXHD {{ $invoice->id }} {{ $invoice->student->student_code }}</p>
                                <small class="text-danger">* Vui lòng chuyển khoản đúng nội dung để hệ thống tự động xác nhận</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Ghi chú</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" 
                                      placeholder="Ghi chú cho giao dịch (nếu có)">{{ old('notes') }}</textarea>
                        </div>

                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="agree_terms" required>
                            <label class="custom-control-label" for="agree_terms">
                                Tôi xác nhận thông tin thanh toán là chính xác và đồng ý với điều khoản sử dụng
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg btn-block" id="submit-btn">
                            <i class="ri-secure-payment-line mr-2"></i>Xác nhận thanh toán
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="ri-file-list-line mr-2"></i>Tóm tắt thanh toán</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng hóa đơn:</span>
                        <strong>{{ number_format($invoice->total_amount) }} đ</strong>
                    </div>
                    @if($invoice->paid_amount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Đã thanh toán:</span>
                        <strong class="text-success">-{{ number_format($invoice->paid_amount) }} đ</strong>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Còn lại:</span>
                        <h5 class="mb-0 text-danger">{{ number_format($remainingAmount) }} đ</h5>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Số tiền thanh toán:</span>
                        <h4 class="mb-0 text-primary" id="amount-display">{{ number_format($remainingAmount) }} đ</h4>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="mb-3"><i class="ri-information-line mr-2"></i>Lưu ý</h6>
                    <ul class="pl-3 mb-0 text-muted small">
                        <li>Thanh toán qua VNPay được xử lý ngay lập tức</li>
                        <li>Thanh toán tiền mặt cần đến văn phòng trong giờ hành chính</li>
                        <li>Chuyển khoản ngân hàng cần 1-2 giờ để xác nhận</li>
                        <li>Vui lòng lưu lại mã giao dịch sau khi thanh toán</li>
                        <li>Liên hệ hotline 1900xxxx nếu cần hỗ trợ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update amount display
    document.getElementById('amount').addEventListener('input', function() {
        const amount = parseInt(this.value) || 0;
        document.getElementById('amount-display').textContent = 
            amount.toLocaleString('vi-VN') + ' đ';
    });

    // Set amount helper
    function setAmount(value) {
        document.getElementById('amount').value = value;
        document.getElementById('amount-display').textContent = 
            value.toLocaleString('vi-VN') + ' đ';
    }

    // Show/hide bank transfer info
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const bankInfo = document.getElementById('bank_transfer_info');
            if (this.value === 'bank_transfer') {
                bankInfo.style.display = 'block';
            } else {
                bankInfo.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection
