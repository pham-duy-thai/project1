@extends('layouts.app')

@section('title', 'Chi tiết phòng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Chi tiết phòng {{ $room->room_number }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('rooms.index') }}">Quản lý phòng</a></li>
                        <li class="breadcrumb-item active">Chi tiết phòng</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Thông tin phòng -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="ri-information-line mr-2"></i>Thông tin phòng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Số phòng</th>
                                    <td><strong class="text-primary">{{ $room->room_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Tòa nhà</th>
                                    <td>{{ $room->building->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Ký túc xá</th>
                                    <td>{{ $room->building->dormitory->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tầng</th>
                                    <td>{{ $room->floor }}</td>
                                </tr>
                                <tr>
                                    <th>Loại phòng</th>
                                    <td>{{ $room->roomType->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Sức chứa</th>
                                    <td>{{ $room->capacity }} người</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Số người ở hiện tại</th>
                                    <td>
                                        <strong>{{ $room->current_occupancy ?? 0 }}/{{ $room->capacity }}</strong>
                                        <div class="progress mt-2" style="height: 20px;">
                                            @php
                                                $occupancyPercent = $room->capacity > 0 ? (($room->current_occupancy ?? 0) / $room->capacity) * 100 : 0;
                                                $progressClass = $occupancyPercent >= 100 ? 'bg-danger' : ($occupancyPercent >= 75 ? 'bg-warning' : 'bg-success');
                                            @endphp
                                            <div class="progress-bar {{ $progressClass }}" role="progressbar" 
                                                style="width: {{ $occupancyPercent }}%;" 
                                                aria-valuenow="{{ $occupancyPercent }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($occupancyPercent) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Diện tích</th>
                                    <td>{{ $room->area ? $room->area . ' m²' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Giá thuê</th>
                                    <td class="text-success"><strong>{{ number_format($room->roomType->price_per_month ?? 0) }}đ/tháng</strong></td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        @switch($room->status)
                                            @case('available')
                                                <span class="badge badge-success"><i class="ri-checkbox-circle-line"></i> Còn trống</span>
                                                @break
                                            @case('occupied')
                                                <span class="badge badge-danger"><i class="ri-close-circle-line"></i> Đã đầy</span>
                                                @break
                                            @case('maintenance')
                                                <span class="badge badge-warning"><i class="ri-tools-line"></i> Đang bảo trì</span>
                                                @break
                                            @case('reserved')
                                                <span class="badge badge-info"><i class="ri-bookmark-line"></i> Đã đặt trước</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kích hoạt</th>
                                    <td>
                                        @if($room->is_active)
                                            <span class="badge badge-success"><i class="ri-check-line"></i> Đang hoạt động</span>
                                        @else
                                            <span class="badge badge-secondary"><i class="ri-close-line"></i> Không hoạt động</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($room->notes)
                                <tr>
                                    <th>Ghi chú</th>
                                    <td>{{ $room->notes }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hình ảnh phòng -->
            @php
                $existingImages = [];
                if($room->images && count($room->images) > 0) {
                    foreach($room->images as $image) {
                        if(\Storage::disk('public')->exists($image)) {
                            $existingImages[] = $image;
                        }
                    }
                }
            @endphp
            @if(count($existingImages) > 0)
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="ri-image-line mr-2"></i>Hình ảnh phòng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($existingImages as $image)
                            <div class="col-md-3 mb-3">
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="room-gallery" data-title="Phòng {{ $room->room_number }}">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded shadow-sm" 
                                        alt="Room image" style="height: 150px; width: 100%; object-fit: cover; cursor: pointer;"
                                        onerror="this.onerror=null; this.parentElement.parentElement.style.display='none';">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Tiện ích -->
            @if($room->amenities && $room->amenities->count() > 0)
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="ri-hotel-bed-line mr-2"></i>Tiện ích phòng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="35%">Tên tiện ích</th>
                                    <th width="15%" class="text-center">Số lượng</th>
                                    <th width="15%" class="text-center">Tình trạng</th>
                                    <th width="30%">Mô tả</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($room->amenities as $index => $amenity)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><i class="ri-checkbox-circle-line text-success mr-1"></i>{{ $amenity->name }}</td>
                                        <td class="text-center"><strong>{{ $amenity->pivot->quantity ?? 1 }}</strong></td>
                                        <td class="text-center">
                                            @switch($amenity->pivot->condition ?? 'good')
                                                @case('good')
                                                    <span class="badge badge-success">Tốt</span>
                                                    @break
                                                @case('fair')
                                                    <span class="badge badge-warning">Khá</span>
                                                    @break
                                                @case('poor')
                                                    <span class="badge badge-danger">Kém</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td><small>{{ $amenity->description ?? 'N/A' }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Sinh viên đang ở -->
            @if($currentOccupants && $currentOccupants->count() > 0)
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="ri-user-line mr-2"></i>Sinh viên đang ở ({{ $currentOccupants->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Mã SV</th>
                                    <th width="20%">Họ tên</th>
                                    <th width="10%" class="text-center">Giới tính</th>
                                    <th width="15%">Số điện thoại</th>
                                    <th width="12%">Ngày bắt đầu</th>
                                    <th width="12%">Ngày kết thúc</th>
                                    <th width="11%" class="text-center">Hợp đồng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($currentOccupants as $index => $contract)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $contract->student->student_code }}</strong></td>
                                        <td>
                                            <a href="{{ route('students.show', $contract->student->id) }}" class="text-primary">
                                                {{ $contract->student->first_name }} {{ $contract->student->last_name }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            @if($contract->student->gender == 'male')
                                                <i class="ri-men-line text-primary"></i> Nam
                                            @else
                                                <i class="ri-women-line text-danger"></i> Nữ
                                            @endif
                                        </td>
                                        <td>{{ $contract->student->phone ?? 'N/A' }}</td>
                                        <td>{{ $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') : 'N/A' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-info" title="Xem hợp đồng">
                                                <i class="ri-file-text-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Lịch sử bảo trì -->
            @if($maintenanceHistory && $maintenanceHistory->count() > 0)
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="ri-tools-line mr-2"></i>Lịch sử bảo trì ({{ $maintenanceHistory->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Ngày tạo</th>
                                    <th width="30%">Tiêu đề</th>
                                    <th width="15%" class="text-center">Độ ưu tiên</th>
                                    <th width="15%" class="text-center">Trạng thái</th>
                                    <th width="20%" class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maintenanceHistory as $index => $maintenance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $maintenance->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $maintenance->title }}</td>
                                        <td class="text-center">
                                            @switch($maintenance->priority)
                                                @case('low')
                                                    <span class="badge badge-info">Thấp</span>
                                                    @break
                                                @case('medium')
                                                    <span class="badge badge-warning">Trung bình</span>
                                                    @break
                                                @case('high')
                                                    <span class="badge badge-danger">Cao</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="text-center">
                                            @switch($maintenance->status)
                                                @case('pending')
                                                    <span class="badge badge-warning">Chờ xử lý</span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge badge-info">Đang xử lý</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge badge-success">Hoàn thành</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge badge-secondary">Đã hủy</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('maintenance.show', $maintenance->id) }}" class="btn btn-sm btn-primary" title="Xem chi tiết">
                                                <i class="ri-eye-line"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Thao tác nhanh -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="ri-flashlight-line mr-2"></i>Thao tác nhanh</h5>
                </div>
                <div class="card-body">
                    @php
                        $canAddStudent = ($room->current_occupancy ?? 0) < $room->capacity && $room->is_active && $room->status != 'maintenance';
                    @endphp

                    @if($canAddStudent)
                        <a href="{{ route('contracts.create', ['room_id' => $room->id]) }}" class="btn btn-success btn-block mb-2">
                            <i class="ri-user-add-line mr-1"></i> Thêm sinh viên
                        </a>
                    @else
                        <button class="btn btn-secondary btn-block mb-2" disabled title="Phòng đã đầy hoặc không khả dụng">
                            <i class="ri-user-add-line mr-1"></i> Thêm sinh viên
                        </button>
                    @endif

                    <a href="{{ route('maintenance.create', ['room_id' => $room->id]) }}" class="btn btn-warning btn-block mb-2">
                        <i class="ri-tools-line mr-1"></i> Tạo yêu cầu bảo trì
                    </a>

                    <a href="{{ route('rooms.edit', $room) }}" class="btn btn-primary btn-block mb-2">
                        <i class="ri-edit-line mr-1"></i> Chỉnh sửa phòng
                    </a>

                    @php
                        $canDelete = ($room->current_occupancy ?? 0) == 0 && (!isset($currentOccupants) || $currentOccupants->count() == 0);
                    @endphp

                    @if($canDelete)
                        <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline" 
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa phòng này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="ri-delete-bin-line mr-1"></i> Xóa phòng
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary btn-block" disabled title="Không thể xóa phòng đang có sinh viên">
                            <i class="ri-delete-bin-line mr-1"></i> Xóa phòng
                        </button>
                    @endif
                </div>
            </div>

            <!-- Thống kê -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="ri-bar-chart-line mr-2"></i>Thống kê</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><strong>Tỷ lệ lấp đầy</strong></span>
                            <span class="badge badge-primary">{{ round($occupancyPercent ?? 0) }}%</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            @php
                                $occupancyPercent = $room->capacity > 0 ? (($room->current_occupancy ?? 0) / $room->capacity) * 100 : 0;
                                $progressClass = $occupancyPercent >= 100 ? 'bg-danger' : ($occupancyPercent >= 75 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <div class="progress-bar {{ $progressClass }}" role="progressbar" 
                                style="width: {{ $occupancyPercent }}%;" 
                                aria-valuenow="{{ $occupancyPercent }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $room->current_occupancy ?? 0 }}/{{ $room->capacity }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-primary mb-0">{{ $room->contracts_count ?? 0 }}</h3>
                                <small class="text-muted">Tổng hợp đồng</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-success mb-0">{{ $room->active_contracts_count ?? 0 }}</h3>
                                <small class="text-muted">HĐ đang hoạt động</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-warning mb-0">{{ $maintenanceHistory ? $maintenanceHistory->count() : 0 }}</h3>
                                <small class="text-muted">Yêu cầu bảo trì</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-danger mb-0">{{ $room->pending_maintenance_count ?? 0 }}</h3>
                                <small class="text-muted">Bảo trì chờ xử lý</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin hệ thống -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="ri-information-line mr-2"></i>Thông tin hệ thống</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td><i class="ri-key-line text-muted mr-2"></i><strong>ID:</strong></td>
                            <td>{{ $room->id }}</td>
                        </tr>
                        <tr>
                            <td><i class="ri-calendar-line text-muted mr-2"></i><strong>Ngày tạo:</strong></td>
                            <td>{{ $room->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><i class="ri-refresh-line text-muted mr-2"></i><strong>Cập nhật:</strong></td>
                            <td>{{ $room->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><i class="ri-time-line text-muted mr-2"></i><strong>Cập nhật:</strong></td>
                            <td>{{ $room->updated_at->diffForHumans() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    // Lightbox configuration
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': 'Ảnh %1 / %2'
    });

    // Auto dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush
@endsection
