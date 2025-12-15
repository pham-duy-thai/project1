@extends('layouts.app')

@section('title', 'Chỉnh sửa hợp đồng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Chỉnh sửa hợp đồng {{ $contract->contract_number }}</h4>
                    <p class="mb-0 text-muted">Cập nhật thông tin hợp đồng thuê phòng</p>
                </div>
                <a href="{{ route('contracts.show', $contract) }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line mr-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    @if($contract->status === 'active')
        <div class="alert alert-warning">
            <i class="ri-alert-line mr-2"></i> Không thể chỉnh sửa hợp đồng đang hoạt động.
        </div>
    @endif

    <form action="{{ route('contracts.update', $contract) }}" method="POST" id="contractForm">
        @csrf
        @method('PUT')
        
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
                            <select name="student_id" id="student_id" 
                                class="form-control @error('student_id') is-invalid @enderror" 
                                {{ $contract->status === 'active' ? 'disabled' : '' }} required>
                                <option value="">-- Chọn sinh viên --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                        data-code="{{ $student->student_code }}"
                                        data-name="{{ $student->full_name }}"
                                        data-phone="{{ $student->phone }}"
                                        data-email="{{ $student->email }}"
                                        {{ old('student_id', $contract->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->student_code }} - {{ $student->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="studentInfo" class="alert alert-info">
                            <h6 class="mb-2">Thông tin sinh viên:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Mã SV:</strong> <span id="info_code">{{ $contract->student->student_code }}</span></p>
                                    <p class="mb-1"><strong>Họ tên:</strong> <span id="info_name">{{ $contract->student->full_name }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Email:</strong> <span id="info_email">{{ $contract->student->email }}</span></p>
                                    <p class="mb-0"><strong>SĐT:</strong> <span id="info_phone">{{ $contract->student->phone }}</span></p>
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
                            <select name="room_id" id="room_id" 
                                class="form-control @error('room_id') is-invalid @enderror" 
                                {{ $contract->status === 'active' ? 'disabled' : '' }} required>
                                <option value="">-- Chọn phòng --</option>
                                @foreach($availableRooms as $room)
                                    <option value="{{ $room->id }}" 
                                        data-number="{{ $room->room_number }}"
                                        data-building="{{ $room->building->name ?? 'N/A' }}"
                                        data-dormitory="{{ $room->building->dormitory->name ?? 'N/A' }}"
                                        data-capacity="{{ $room->capacity }}"
                                        data-occupied="{{ $room->current_occupancy }}"
                                        data-price="{{ $room->roomType->price_per_month ?? 0 }}"
                                        {{ old('room_id', $contract->room_id) == $room->id ? 'selected' : '' }}>
                                        {{ $room->room_number }} - {{ $room->building->name }} 
                                        ({{ $room->current_occupancy }}/{{ $room->capacity }} - {{ number_format($room->roomType->price_per_month) }}đ/tháng)
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="roomInfo" class="alert alert-warning">
                            <h6 class="mb-2">Thông tin phòng:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Số phòng:</strong> <span id="room_number">{{ $contract->room->room_number }}</span></p>
                                    <p class="mb-1"><strong>Tòa nhà:</strong> <span id="room_building">{{ $contract->room->building->name }}</span></p>
                                    <p class="mb-0"><strong>Ký túc xá:</strong> <span id="room_dormitory">{{ $contract->room->building->dormitory->name }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Sức chứa:</strong> <span id="room_capacity">{{ $contract->room->current_occupancy }}/{{ $contract->room->capacity }} người</span></p>
                                    <p class="mb-0"><strong>Giá thuê/tháng:</strong> <span id="room_price" class="text-success">{{ number_format($contract->room->roomType->price_per_month) }}đ</span></p>
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
                                    <input type="text" name="start_date" id="start_date" 
                                        class="form-control @error('start_date') is-invalid @enderror" 
                                        value="{{ old('start_date', $contract->start_date->format('d/m/Y')) }}" 
                                        placeholder="dd/mm/yyyy"
                                        {{ $contract->status === 'active' ? 'readonly' : '' }} required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Ngày kết thúc <span class="text-danger">*</span></label>
                                    <input type="text" name="end_date" id="end_date" 
                                        class="form-control @error('end_date') is-invalid @enderror" 
                                        value="{{ old('end_date', $contract->end_date->format('d/m/Y')) }}" 
                                        placeholder="dd/mm/yyyy"
                                        {{ $contract->status === 'active' ? 'readonly' : '' }} required>
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
                                        value="{{ old('monthly_rent', $contract->monthly_rent) }}" 
                                        min="0" step="1000" 
                                        {{ $contract->status === 'active' ? 'readonly' : '' }} required>
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
                                        value="{{ old('deposit_amount', $contract->deposit_amount) }}" 
                                        min="0" step="1000" 
                                        {{ $contract->status === 'active' ? 'readonly' : '' }} required>
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
                                        class="form-control @error('payment_cycle') is-invalid @enderror" 
                                        {{ $contract->status === 'active' ? 'disabled' : '' }} required>
                                        <option value="monthly" {{ old('payment_cycle', $contract->payment_cycle) == 'monthly' ? 'selected' : '' }}>Hàng tháng</option>
                                        <option value="semester" {{ old('payment_cycle', $contract->payment_cycle) == 'semester' ? 'selected' : '' }}>Theo học kỳ</option>
                                        <option value="yearly" {{ old('payment_cycle', $contract->payment_cycle) == 'yearly' ? 'selected' : '' }}>Hàng năm</option>
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
                                        placeholder="Các điều khoản đặc biệt, quy định..."
                                        {{ $contract->status === 'active' ? 'readonly' : '' }}>{{ old('notes', $contract->notes) }}</textarea>
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
                <!-- Thông tin trạng thái -->
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="ri-information-line mr-2"></i>Trạng thái hợp đồng</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td><strong>Mã hợp đồng:</strong></td>
                                <td>{{ $contract->contract_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Trạng thái:</strong></td>
                                <td>
                                    @if($contract->status === 'pending')
                                        <span class="badge badge-warning">Chờ phê duyệt</span>
                                    @elseif($contract->status === 'active')
                                        <span class="badge badge-success">Đang hoạt động</span>
                                    @elseif($contract->status === 'terminated')
                                        <span class="badge badge-danger">Đã chấm dứt</span>
                                    @elseif($contract->status === 'expired')
                                        <span class="badge badge-secondary">Hết hạn</span>
                                    @else
                                        <span class="badge badge-dark">{{ $contract->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Ngày tạo:</strong></td>
                                <td>{{ $contract->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @if($contract->signed_at)
                            <tr>
                                <td><strong>Ngày phê duyệt:</strong></td>
                                <td>{{ $contract->signed_at->format('d/m/Y') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Hướng dẫn -->
                <div class="card">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="ri-lightbulb-line mr-2"></i>Hướng dẫn</h5>
                    </div>
                    <div class="card-body">
                        @if($contract->status === 'active')
                            <div class="alert alert-info mb-0">
                                <i class="ri-lock-line mr-2"></i> Hợp đồng đang hoạt động không thể chỉnh sửa. Chỉ có thể xem thông tin hoặc chấm dứt hợp đồng.
                            </div>
                        @else
                            <ol class="pl-3 mb-0">
                                <li>Cập nhật thông tin sinh viên nếu cần</li>
                                <li>Thay đổi phòng nếu cần thiết</li>
                                <li>Điều chỉnh thời gian thuê</li>
                                <li>Cập nhật giá thuê và tiền cọc</li>
                                <li>Thay đổi chu kỳ thanh toán</li>
                                <li>Cập nhật ghi chú</li>
                                <li>Nhấn "Cập nhật" để lưu thay đổi</li>
                            </ol>
                        @endif
                    </div>
                </div>

                <!-- Tóm tắt -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="ri-file-list-line mr-2"></i>Tóm tắt hợp đồng</h5>
                    </div>
                    <div class="card-body" id="contractSummary">
                        <!-- Summary will be updated by JavaScript -->
                    </div>
                </div>

                <!-- Nút hành động -->
                @if($contract->status !== 'active')
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="ri-save-line mr-1"></i> Cập nhật hợp đồng
                        </button>
                        <a href="{{ route('contracts.show', $contract) }}" class="btn btn-secondary btn-block">
                            <i class="ri-close-line mr-1"></i> Hủy bỏ
                        </a>
                    </div>
                </div>
                @endif
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
            
            // Auto-fill prices only if not in active status
            @if($contract->status !== 'active')
            $('#monthly_rent').val(price);
            $('#deposit_amount').val(price);
            @endif
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

    // Initialize summary on page load
    updateSummary();
});
</script>
@endpush
@endsection
