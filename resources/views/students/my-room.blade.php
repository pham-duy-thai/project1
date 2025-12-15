@extends('layouts.app')

@section('title', 'Phòng của tôi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            @if(!$contract || !$room)
            <!-- No Room Assigned -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-home-line text-muted" style="font-size: 64px;"></i>
                    <h4 class="mt-3">Chưa được phân phòng</h4>
                    <p class="text-muted">Bạn chưa có phòng ở ký túc xá. Vui lòng liên hệ quản lý để được hỗ trợ.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                        <i class="ri-arrow-left-line"></i> Quay lại Dashboard
                    </a>
                </div>
            </div>
            @else
            <!-- Room Information -->
            <div class="row">
                <!-- Room Details -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="ri-home-line"></i> Thông tin phòng
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="media align-items-center mb-3">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                                            <i class="ri-building-line" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mb-0">Tòa nhà</h6>
                                            <p class="mb-0">{{ $room->building->name }} - {{ $room->building->dormitory->name }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="media align-items-center mb-3">
                                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                                            <i class="ri-door-line" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mb-0">Số phòng</h6>
                                            <p class="mb-0">Phòng {{ $room->room_number }} - Tầng {{ $room->floor }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="media align-items-center mb-3">
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                                            <i class="ri-group-line" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mb-0">Sức chứa</h6>
                                            <p class="mb-0">{{ $room->current_occupancy }}/{{ $room->capacity }} người</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="media align-items-center mb-3">
                                        <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                                            <i class="ri-ruler-line" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mb-0">Diện tích</h6>
                                            <p class="mb-0">{{ $room->area ?? 'N/A' }} m²</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="media align-items-center mb-3">
                                        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                                            <i class="ri-price-tag-3-line" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mb-0">Loại phòng</h6>
                                            <p class="mb-0">{{ $room->roomType->name }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="media align-items-center mb-3">
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                                            <i class="ri-money-dollar-circle-line" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mb-0">Giá phòng</h6>
                                            <p class="mb-0">{{ number_format($room->roomType->price_per_month) }}đ/tháng</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($room->roomType->amenities && count($room->roomType->amenities) > 0)
                            <div class="mt-4">
                                <h6 class="mb-3">Tiện nghi phòng:</h6>
                                <div class="row">
                                    @foreach($room->roomType->amenities as $amenity)
                                    <div class="col-md-6 mb-2">
                                        <i class="ri-checkbox-circle-fill text-success"></i> {{ $amenity }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Roommates -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="ri-group-line"></i> Bạn cùng phòng ({{ count($roommates) }})
                            </h4>
                        </div>
                        <div class="card-body">
                            @if(count($roommates) > 0)
                            <div class="row">
                                @foreach($roommates as $roommate)
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <div class="media align-items-center">
                                                <img src="{{ $roommate->user->avatar_url ?? asset('assets/images/user/user-1.jpg') }}" 
                                                     class="rounded-circle mr-3" 
                                                     alt="{{ $roommate->full_name }}"
                                                     style="width: 50px; height: 50px; min-width: 50px; min-height: 50px; object-fit: cover; border-radius: 50% !important; display: block;"
                                                     onerror="this.onerror=null;this.src='{{ asset('assets/images/user/user-1.jpg') }}';">
                                                <div class="media-body">
                                                    <h6 class="mb-1">{{ $roommate->full_name }}</h6>
                                                    <p class="mb-0 text-muted small">{{ $roommate->student_code }}</p>
                                                    <p class="mb-0 text-muted small">{{ $roommate->class ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="ri-user-line text-muted" style="font-size: 48px;"></i>
                                <p class="text-muted mt-2">Chưa có bạn cùng phòng</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contract Information -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="ri-file-list-line"></i> Hợp đồng
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Mã hợp đồng</small>
                                <h6>{{ $contract->contract_number }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Ngày bắt đầu</small>
                                <h6>{{ $contract->start_date->format('d/m/Y') }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Ngày kết thúc</small>
                                <h6>{{ $contract->end_date->format('d/m/Y') }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Trạng thái</small>
                                <div>
                                    @if($contract->status == 'active')
                                    <span class="badge badge-success">Đang hoạt động</span>
                                    @elseif($contract->status == 'pending')
                                    <span class="badge badge-warning">Chờ duyệt</span>
                                    @elseif($contract->status == 'expired')
                                    <span class="badge badge-secondary">Hết hạn</span>
                                    @elseif($contract->status == 'terminated')
                                    <span class="badge badge-danger">Đã chấm dứt</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Tiền cọc</small>
                                <h6 class="text-warning">{{ number_format($contract->deposit_amount) }}đ</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Giá thuê/tháng</small>
                                <h6 class="text-success">{{ number_format($contract->monthly_rent) }}đ</h6>
                            </div>
                            
                            <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-primary btn-block mt-3">
                                <i class="ri-eye-line"></i> Xem chi tiết hợp đồng
                            </a>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="ri-settings-line"></i> Thao tác nhanh
                            </h4>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('invoices.index') }}" class="btn btn-outline-primary btn-block mb-2">
                                <i class="ri-bill-line"></i> Hóa đơn của tôi
                            </a>
                            <a href="{{ route('maintenance.create') }}" class="btn btn-outline-warning btn-block mb-2">
                                <i class="ri-tools-line"></i> Báo cáo sự cố
                            </a>
                            <a href="{{ route('announcements.index') }}" class="btn btn-outline-info btn-block">
                                <i class="ri-notification-line"></i> Thông báo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
