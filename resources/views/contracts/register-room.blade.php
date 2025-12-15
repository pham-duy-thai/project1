@extends('layouts.app')
@section('title','Đăng ký phòng')
@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('contracts.my-contracts') }}">Hợp đồng</a></li>
                    <li class="breadcrumb-item active">Đăng ký phòng</li>
                </ol>
            </nav>
            <h3 class="mb-1 fw-bold">
                <i class="ri-home-add-line me-2 text-primary"></i>
                Đăng ký phòng mới
            </h3>
            <p class="text-muted mb-0">Điền thông tin để đăng ký thuê phòng ký túc xá</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="ri-error-warning-line me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="ri-error-warning-line me-2"></i>
            <strong>Có lỗi xảy ra:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Student Information Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-semibold">
                <i class="ri-user-3-line me-2 text-primary"></i>
                Thông tin sinh viên
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="info-box">
                        <label class="text-muted small mb-1">Mã sinh viên</label>
                        <p class="mb-0 fw-bold">{{ $student->student_code }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <label class="text-muted small mb-1">Họ và tên</label>
                        <p class="mb-0 fw-bold">{{ $student->full_name }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <label class="text-muted small mb-1">Khoa</label>
                        <p class="mb-0 fw-bold">{{ $student->faculty }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <label class="text-muted small mb-1">Lớp</label>
                        <p class="mb-0 fw-bold">{{ $student->class }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('contracts.store-registration') }}">
        @csrf
        
        <!-- Room Selection Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">
                    <i class="ri-home-4-line me-2 text-primary"></i>
                    Chọn phòng ký túc xá
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Phòng <span class="text-danger">*</span>
                    </label>
                    <select name="room_id" class="form-select form-select-lg" required id="roomSelect">
                        <option value="">-- Vui lòng chọn phòng --</option>
                        @foreach($availableRooms as $room)
                            <option value="{{ $room->id }}" 
                                    data-price="{{ $room->roomType->price_per_month ?? 0 }}"
                                    data-price-formatted="{{ number_format($room->roomType->price_per_month ?? 0,0,',','.') }}"
                                    data-capacity="{{ $room->capacity }}"
                                    data-building="{{ $room->building->name ?? '' }}"
                                    data-type="{{ $room->roomType->name ?? '' }}"
                                    {{ old('room_id')==$room->id?'selected':'' }}>
                                Phòng {{ $room->room_number }} - Tòa {{ $room->building->name }} - {{ $room->roomType->name ?? 'Loại?' }} ({{ $room->capacity }} chỗ) - {{ number_format($room->roomType->price_per_month ?? 0,0,',','.') }} đ/tháng
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">
                        <i class="ri-information-line me-1"></i>
                        Vui lòng chọn phòng phù hợp với nhu cầu của bạn
                    </small>
                </div>

                <!-- Room Info Preview -->
                <div id="roomPreview" class="alert alert-info border-0 d-none mt-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-center p-2 bg-white rounded">
                                <i class="ri-home-4-line text-primary fs-4"></i>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Tòa nhà</small>
                                    <strong id="previewBuilding">-</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-2 bg-white rounded">
                                <i class="ri-layout-grid-line text-success fs-4"></i>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Loại phòng</small>
                                    <strong id="previewType">-</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-2 bg-white rounded">
                                <i class="ri-price-tag-3-line text-warning fs-4"></i>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Giá phòng/tháng</small>
                                    <strong class="text-primary" id="previewPrice">-</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-2 bg-white rounded">
                                <i class="ri-user-line text-info fs-4"></i>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Sức chứa</small>
                                    <strong id="previewCapacity">-</strong> người
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contract Details Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">
                    <i class="ri-calendar-line me-2 text-primary"></i>
                    Thông tin hợp đồng
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="ri-calendar-check-line me-1"></i>
                            Ngày bắt đầu <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ old('start_date', now()->toDateString()) }}" 
                               min="{{ now()->toDateString() }}"
                               required>
                        <small class="text-muted">Ngày bạn muốn bắt đầu thuê</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="ri-loop-right-line me-1"></i>
                            Chu kỳ thanh toán <span class="text-danger">*</span>
                        </label>
                        <select name="payment_cycle" class="form-select" required>
                            @foreach(['monthly'=>'Hàng tháng','semester'=>'Học kỳ','yearly'=>'Năm'] as $val=>$label)
                                <option value="{{ $val }}" {{ old('payment_cycle')==$val?'selected':'' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Chọn cách thức thanh toán</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="ri-sticky-note-line me-1"></i>
                            Ghi chú
                        </label>
                        <input type="text" name="notes" class="form-control" 
                               value="{{ old('notes') }}" 
                               placeholder="Ghi chú thêm (nếu có)">
                        <small class="text-muted">Thông tin bổ sung (không bắt buộc)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Notice -->
        <div class="alert alert-warning border-0 shadow-sm mb-4">
            <h6 class="alert-heading">
                <i class="ri-error-warning-line me-2"></i>
                Lưu ý quan trọng
            </h6>
            <ul class="mb-0 ps-3">
                <li>Hợp đồng sẽ được gửi đến quản lý để phê duyệt</li>
                <li>Bạn sẽ nhận được thông báo khi hợp đồng được phê duyệt hoặc từ chối</li>
                <li>Vui lòng kiểm tra kỹ thông tin trước khi gửi</li>
            </ul>
        </div>

        <!-- Form Actions -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('contracts.my-contracts') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-1"></i>
                        Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="ri-send-plane-line me-2"></i>
                        Gửi đăng ký
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.info-box {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 3px solid #0d6efd;
}
</style>

<script>
document.getElementById('roomSelect')?.addEventListener('change', function() {
    const preview = document.getElementById('roomPreview');
    const selected = this.options[this.selectedIndex];
    
    if (this.value) {
        document.getElementById('previewBuilding').textContent = selected.dataset.building || '-';
        document.getElementById('previewType').textContent = selected.dataset.type || '-';
        document.getElementById('previewPrice').textContent = selected.dataset.priceFormatted + ' đ';
        document.getElementById('previewCapacity').textContent = selected.dataset.capacity || '-';
        preview.classList.remove('d-none');
    } else {
        preview.classList.add('d-none');
    }
});

// Trigger change event if there's an old value selected
window.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('roomSelect');
    if (roomSelect && roomSelect.value) {
        roomSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection