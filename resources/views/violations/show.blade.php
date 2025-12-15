@extends('layouts.app')

@section('title', 'Chi tiết vi phạm')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Chi tiết vi phạm</h4>
                    <p class="mb-0 text-muted">Thông tin chi tiết về vi phạm của sinh viên</p>
                </div>
                <a href="{{ route('violations.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line mr-1"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-check-line mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Thông tin vi phạm -->
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="ri-alert-line mr-2"></i>Thông tin vi phạm</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Loại vi phạm:</h6>
                            <p class="mb-0">
                                @switch($violation->violation_type)
                                    @case('noise')
                                        <span class="badge badge-warning badge-lg">
                                            <i class="ri-volume-up-line"></i> Gây ồn
                                        </span>
                                        @break
                                    @case('smoking')
                                        <span class="badge badge-danger badge-lg">
                                            <i class="ri-fire-line"></i> Hút thuốc
                                        </span>
                                        @break
                                    @case('alcohol')
                                        <span class="badge badge-danger badge-lg">
                                            <i class="ri-goblet-line"></i> Sử dụng rượu bia
                                        </span>
                                        @break
                                    @case('unauthorized_guest')
                                        <span class="badge badge-warning badge-lg">
                                            <i class="ri-user-forbid-line"></i> Khách không phép
                                        </span>
                                        @break
                                    @case('damage')
                                        <span class="badge badge-danger badge-lg">
                                            <i class="ri-tools-fill"></i> Làm hư hỏng tài sản
                                        </span>
                                        @break
                                    @case('late_return')
                                        <span class="badge badge-info badge-lg">
                                            <i class="ri-time-line"></i> Về trễ giờ
                                        </span>
                                        @break
                                    @case('hygiene')
                                        <span class="badge badge-warning badge-lg">
                                            <i class="ri-delete-bin-line"></i> Vệ sinh kém
                                        </span>
                                        @break
                                    @default
                                        <span class="badge badge-secondary badge-lg">
                                            <i class="ri-error-warning-line"></i> Khác
                                        </span>
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Ngày vi phạm:</h6>
                            <p class="mb-0">{{ $violation->violation_date->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Tiền phạt:</h6>
                            <p class="mb-0">
                                <span class="badge badge-light badge-lg" style="font-size: 1.1rem;">
                                    <i class="ri-money-dollar-circle-line"></i> 
                                    {{ number_format($violation->fine_amount) }}đ
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Người báo cáo:</h6>
                            <p class="mb-0">{{ $violation->reportedBy->name }}</p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="font-weight-bold">Mô tả chi tiết:</h6>
                    <p class="mb-3">{{ $violation->description }}</p>

                    @if($violation->resolution_notes)
                        <div class="alert alert-info">
                            <h6 class="font-weight-bold mb-2">
                                <i class="ri-file-text-line mr-1"></i> Ghi chú xử lý:
                            </h6>
                            <p class="mb-0">{{ $violation->resolution_notes }}</p>
                            @if($violation->resolved_at)
                                <small class="text-muted d-block mt-2">
                                    Xử lý lúc: {{ $violation->resolved_at->format('d/m/Y H:i') }}
                                </small>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Hình ảnh bằng chứng -->
            @php
                $existingImages = [];
                if($violation->evidence_images && count($violation->evidence_images) > 0) {
                    foreach($violation->evidence_images as $image) {
                        if(\Storage::disk('public')->exists($image)) {
                            $existingImages[] = $image;
                        }
                    }
                }
            @endphp
            @if(count($existingImages) > 0)
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="ri-image-line mr-2"></i>Hình ảnh bằng chứng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($existingImages as $image)
                            <div class="col-md-4 mb-3">
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="evidence-gallery">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         class="img-fluid rounded shadow-sm" 
                                         alt="Evidence" 
                                         style="height: 200px; width: 100%; object-fit: cover;"
                                         onerror="this.onerror=null; this.parentElement.parentElement.style.display='none';">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Thông tin sinh viên -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="ri-user-line mr-2"></i>Thông tin sinh viên</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="45%">Mã SV</th>
                            <td>
                                <a href="{{ route('students.show', $violation->student) }}">
                                    {{ $violation->student->student_code }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Họ tên</th>
                            <td><strong>{{ $violation->student->full_name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $violation->student->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Điện thoại</th>
                            <td>{{ $violation->student->phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Khoa</th>
                            <td>{{ $violation->student->faculty }}</td>
                        </tr>
                        <tr>
                            <th>Lớp</th>
                            <td>{{ $violation->student->class }}</td>
                        </tr>
                        <tr>
                            <th>Phòng</th>
                            <td>
                                @if($violation->student->contract && $violation->student->contract->room)
                                    <a href="{{ route('rooms.show', $violation->student->contract->room) }}">
                                        {{ $violation->student->contract->room->room_number }}
                                    </a>
                                @else
                                    <span class="text-muted">Chưa có phòng</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Trạng thái & Thao tác -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="ri-settings-line mr-2"></i>Trạng thái</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="font-weight-bold">Trạng thái xử lý:</label>
                        @if($violation->is_resolved)
                            <span class="badge badge-success badge-lg d-block mt-1">
                                <i class="ri-checkbox-circle-line"></i> Đã xử lý
                            </span>
                        @else
                            <span class="badge badge-warning badge-lg d-block mt-1">
                                <i class="ri-time-line"></i> Chưa xử lý
                            </span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Thanh toán phạt:</label>
                        @if($violation->fine_paid)
                            <span class="badge badge-success badge-lg d-block mt-1">
                                <i class="ri-check-line"></i> Đã đóng tiền
                            </span>
                            @if($violation->fine_paid_at)
                                <small class="text-muted d-block mt-1">
                                    Ngày đóng: {{ $violation->fine_paid_at->format('d/m/Y') }}
                                </small>
                            @endif
                        @else
                            <span class="badge badge-danger badge-lg d-block mt-1">
                                <i class="ri-close-line"></i> Chưa đóng tiền
                            </span>
                        @endif
                    </div>

                    <hr>

                    @if(!$violation->is_resolved)
                        <button type="button" class="btn btn-success btn-block mb-2" 
                                data-toggle="modal" data-target="#resolveModal">
                            <i class="ri-checkbox-circle-line mr-1"></i> Đánh dấu đã xử lý
                        </button>
                    @endif

                    @if(!$violation->fine_paid && $violation->fine_amount > 0)
                        <form action="{{ route('violations.mark-paid', $violation) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-info btn-block"
                                    onclick="return confirm('Xác nhận sinh viên đã đóng tiền phạt?')">
                                <i class="ri-money-dollar-circle-line mr-1"></i> Xác nhận đã đóng phạt
                            </button>
                        </form>
                    @endif

                    @if(!$violation->fine_paid && $violation->fine_amount > 0 && $violation->fine_status !== 'waived')
                        <form action="{{ route('violations.waive-fine', $violation) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning btn-block"
                                    onclick="return confirm('Bạn có chắc muốn miễn phạt cho sinh viên này?')">
                                <i class="ri-forbid-line mr-1"></i> Miễn phạt
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('violations.edit', $violation) }}" class="btn btn-primary btn-block mb-2">
                        <i class="ri-edit-line mr-1"></i> Chỉnh sửa
                    </a>

                    <form action="{{ route('violations.destroy', $violation) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block"
                                onclick="return confirm('Bạn có chắc muốn xóa vi phạm này?')">
                            <i class="ri-delete-bin-line mr-1"></i> Xóa vi phạm
                        </button>
                    </form>
                </div>
            </div>

            <!-- Lịch sử vi phạm của sinh viên -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="ri-history-line mr-2"></i>Lịch sử vi phạm</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Tổng vi phạm:</strong> 
                        <span class="badge badge-danger">{{ $violation->student->violations->count() }}</span>
                    </p>
                    <p class="mb-2">
                        <strong>Chưa xử lý:</strong> 
                        <span class="badge badge-warning">{{ $violation->student->violations->where('is_resolved', false)->count() }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>Tổng tiền phạt:</strong> 
                        <span class="badge badge-info">{{ number_format($violation->student->violations->sum('fine_amount')) }}đ</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resolve Modal -->
<div class="modal fade" id="resolveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('violations.resolve', $violation) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Xử lý vi phạm</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="resolution_notes">Ghi chú xử lý <span class="text-danger">*</span></label>
                        <textarea name="resolution_notes" id="resolution_notes" rows="4" 
                                  class="form-control" required 
                                  placeholder="Mô tả biện pháp xử lý, kết quả..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ri-checkbox-circle-line mr-1"></i> Xác nhận
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.badge-lg {
    font-size: 0.95rem;
    padding: 0.5rem 0.75rem;
}
</style>
@endpush
@endsection
