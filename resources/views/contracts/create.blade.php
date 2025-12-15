@extends('layouts.app')

@section('title', 'Tạo hợp đồng mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Tạo hợp đồng mới</h4>
                    <p class="mb-0 text-muted">Lập hợp đồng thuê phòng cho sinh viên</p>
                </div>
                <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line mr-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('contracts.store') }}" method="POST" id="contractForm">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Thông tin sinh viên -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="ri-user-line mr-2"></i>Thông tin sinh viên</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="student_id">Chọn sinh viên <span class="text-danger">*</span></label>
                            <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                                <option value="">-- Chọn sinh viên --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                        data-code="{{ $student->student_code }}"
                                        data-name="{{ $student->full_name }}"
                                        data-phone="{{ $student->phone }}"
                                        data-email="{{ $student->email }}"
                                        {{ old('student_id', $selectedStudentId) == $student->id ? 'selected' : '' }}>
                                        {{ $student->student_code }} - {{ $student->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="studentInfo" class="alert alert-info" style="display: none;">
                            <h6 class="mb-2">Thông tin sinh viên:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Mã SV:</strong> <span id="info_code"></span></p>
                                    <p class="mb-1"><strong>Họ tên:</strong> <span id="info_name"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Email:</strong> <span id="info_email"></span></p>
                                    <p class="mb-0"><strong>SĐT:</strong> <span id="info_phone"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin phòng -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="ri-hotel-bed-line mr-2"></i>Thông tin phòng</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="room_id">Chọn phòng <span class="text-danger">*</span></label>
                            <select name="room_id" id="room_id" class="form-control @error('room_id') is-invalid @enderror" required>
                                <option value="">-- Chọn phòng --</option>
                                @foreach($availableRooms as $room)
                                    <option value="{{ $room->id }}" 
                                        data-number="{{ $room->room_number }}"
                                        data-building="{{ $room->building->name ?? 'N/A' }}"
                                        data-dormitory="{{ $room->building->dormitory->name ?? 'N/A' }}"
                                        data-capacity="{{ $room->capacity }}"
                                        data-occupied="{{ $room->current_occupancy }}"
                                        data-price="{{ $room->roomType->price_per_month ?? 0 }}"
                                        {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->room_number }} - {{ $room->building->name }} 
                                        ({{ $room->current_occupancy }}/{{ $room->capacity }} - {{ number_format($room->roomType->price_per_month) }}đ/tháng)
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="roomInfo" class="alert alert-warning" style="display: none;">
                            <h6 class="mb-2">Thông tin phòng:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Số phòng:</strong> <span id="room_number"></span></p>
                                    <p class="mb-1"><strong>Tòa nhà:</strong> <span id="room_building"></span></p>
                                    <p class="mb-0"><strong>Ký túc xá:</strong> <span id="room_dormitory"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Sức chứa:</strong> <span id="room_capacity"></span></p>
                                    <p class="mb-0"><strong>Giá thuê/tháng:</strong> <span id="room_price" class="text-success"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin hợp đồng -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="ri-file-text-line mr-2"></i>Thông tin hợp đồng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" 
                                        class="form-control @error('start_date') is-invalid @enderror" 
                                        value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Ngày kết thúc <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" 
                                        class="form-control @error('end_date') is-invalid @enderror" 
                                        value="{{ old('end_date', now()->addMonths(6)->format('Y-m-d')) }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="monthly_rent">Tiền thuê/tháng (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" name="monthly_rent" id="monthly_rent" 
                                        class="form-control @error('monthly_rent') is-invalid @enderror" 
                                        value="{{ old('monthly_rent') }}" min="0" step="1000" required>
                                    @error('monthly_rent')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Tự động lấy từ giá phòng khi chọn phòng</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="deposit_amount">Tiền cọc (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" name="deposit_amount" id="deposit_amount" 
                                        class="form-control @error('deposit_amount') is-invalid @enderror" 
                                        value="{{ old('deposit_amount') }}" min="0" step="1000" required>
                                    @error('deposit_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Thường bằng 1 tháng tiền thuê</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_cycle">Chu kỳ thanh toán <span class="text-danger">*</span></label>
                                    <select name="payment_cycle" id="payment_cycle" 
                                        class="form-control @error('payment_cycle') is-invalid @enderror" required>
                                        <option value="monthly" {{ old('payment_cycle', 'monthly') == 'monthly' ? 'selected' : '' }}>Hàng tháng</option>
                                        <option value="semester" {{ old('payment_cycle') == 'semester' ? 'selected' : '' }}>Theo học kỳ</option>
                                        <option value="yearly" {{ old('payment_cycle') == 'yearly' ? 'selected' : '' }}>Hàng năm</option>
                                    </select>
                                    @error('payment_cycle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes">Ghi chú</label>
                                    <textarea name="notes" id="notes" rows="3" 
                                        class="form-control @error('notes') is-invalid @enderror" 
                                        placeholder="Các điều khoản đặc biệt, quy định...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Hướng dẫn -->
                <div class="card">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="ri-lightbulb-line mr-2"></i>Hướng dẫn</h5>
                    </div>
                    <div class="card-body">
                        <ol class="pl-3 mb-0">
                            <li>Chọn sinh viên cần tạo hợp đồng</li>
                            <li>Chọn phòng trống phù hợp</li>
                            <li>Nhập thời gian thuê (bắt đầu và kết thúc)</li>
                            <li>Xác nhận giá thuê và tiền cọc</li>
                            <li>Chọn chu kỳ thanh toán</li>
                            <li>Thêm ghi chú nếu cần</li>
                            <li>Nhấn "Tạo hợp đồng" để hoàn tất</li>
                        </ol>
                        <hr>
                        <p class="mb-0 text-muted"><small><i class="ri-information-line"></i> Hợp đồng sẽ tự động tạo các hóa đơn thanh toán định kỳ</small></p>
                    </div>
                </div>

                <!-- Tóm tắt -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="ri-file-list-line mr-2"></i>Tóm tắt hợp đồng</h5>
                    </div>
                    <div class="card-body" id="contractSummary">
                        <p class="text-center text-muted">Chọn sinh viên và phòng để xem tóm tắt</p>
                    </div>
                </div>

                <!-- Nút hành động -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="ri-file-add-line mr-1"></i> Tạo hợp đồng
                        </button>
                        <a href="{{ route('contracts.index') }}" class="btn btn-secondary btn-block">
                            <i class="ri-close-line mr-1"></i> Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Show student info when selected
    $('#student_id').on('change', function() {
        const selected = $(this).find('option:selected');
        if (selected.val()) {
            $('#info_code').text(selected.data('code'));
            $('#info_name').text(selected.data('name'));
            $('#info_email').text(selected.data('email'));
            $('#info_phone').text(selected.data('phone'));
            $('#studentInfo').slideDown();
        } else {
            $('#studentInfo').slideUp();
        }
        updateSummary();
    });

    // Show room info and auto-fill prices
    $('#room_id').on('change', function() {
        const selected = $(this).find('option:selected');
        if (selected.val()) {
            const price = selected.data('price');
            
            $('#room_number').text(selected.data('number'));
            $('#room_building').text(selected.data('building'));
            $('#room_dormitory').text(selected.data('dormitory'));
            $('#room_capacity').text(selected.data('occupied') + '/' + selected.data('capacity') + ' người');
            $('#room_price').text(new Intl.NumberFormat('vi-VN').format(price) + 'đ');
            $('#roomInfo').slideDown();
            
            // Auto-fill prices
            $('#monthly_rent').val(price);
            $('#deposit_amount').val(price);
        } else {
            $('#roomInfo').slideUp();
        }
        updateSummary();
    });

    // Update summary when form fields change
    $('#start_date, #end_date, #monthly_rent, #deposit_amount, #payment_cycle').on('change', updateSummary);

    function updateSummary() {
        const studentName = $('#student_id option:selected').data('name');
        const roomNumber = $('#room_id option:selected').data('number');
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();
        const monthlyRent = $('#monthly_rent').val();
        const deposit = $('#deposit_amount').val();
        const cycle = $('#payment_cycle option:selected').text();

        if (!studentName || !roomNumber) {
            $('#contractSummary').html('<p class="text-center text-muted">Chọn sinh viên và phòng để xem tóm tắt</p>');
            return;
        }

        // Calculate duration
        let duration = 0;
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            duration = Math.ceil((end - start) / (1000 * 60 * 60 * 24 * 30));
        }

        const totalRent = monthlyRent * duration;

        const summary = `
            <table class="table table-sm table-borderless">
                <tr>
                    <td><strong>Sinh viên:</strong></td>
                    <td>${studentName}</td>
                </tr>
                <tr>
                    <td><strong>Phòng:</strong></td>
                    <td>${roomNumber}</td>
                </tr>
                <tr>
                    <td><strong>Thời gian:</strong></td>
                    <td>${duration} tháng</td>
                </tr>
                <tr>
                    <td><strong>Tiền thuê/tháng:</strong></td>
                    <td class="text-success"><strong>${new Intl.NumberFormat('vi-VN').format(monthlyRent)}đ</strong></td>
                </tr>
                <tr>
                    <td><strong>Tiền cọc:</strong></td>
                    <td>${new Intl.NumberFormat('vi-VN').format(deposit)}đ</td>
                </tr>
                <tr>
                    <td><strong>Chu kỳ TT:</strong></td>
                    <td>${cycle}</td>
                </tr>
                <tr class="border-top">
                    <td><strong>Tổng tiền thuê:</strong></td>
                    <td class="text-primary"><strong>${new Intl.NumberFormat('vi-VN').format(totalRent)}đ</strong></td>
                </tr>
            </table>
        `;

        $('#contractSummary').html(summary);
    }

    // Initialize summary if values exist
    if ($('#student_id').val() && $('#room_id').val()) {
        $('#student_id').trigger('change');
        $('#room_id').trigger('change');
    }
});
</script>
@endpush
@endsection
