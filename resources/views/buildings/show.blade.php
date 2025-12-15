@extends('layouts.app')

@section('title', $building->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">{{ $building->name }}</h4>
                    <p class="mb-0 text-muted">{{ $building->dormitory->name }}</p>
                </div>
                <div>
                    <a href="{{ route('buildings.edit', $building) }}" class="btn btn-info mr-2">
                        <i class="ri-edit-line mr-2"></i>Chỉnh sửa
                    </a>
                    <a href="{{ route('buildings.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line mr-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>

        <!-- Basic Info & Stats -->
        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <h5 class="mb-3">Thông tin cơ bản</h5>
                    <div class="mb-2">
                        <small class="text-muted">Mã tòa</small>
                        <p class="mb-0 font-weight-bold">{{ $building->code ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Giới tính</small>
                        <p class="mb-0">
                            @if($building->gender === 'male')
                                <span class="badge badge-info">Nam</span>
                            @elseif($building->gender === 'female')
                                <span class="badge badge-danger">Nữ</span>
                            @else
                                <span class="badge badge-secondary">Cả hai</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Số tầng</small>
                        <p class="mb-0">{{ $building->total_floors }} tầng</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Trạng thái</small>
                        <p class="mb-0">
                            <span class="badge badge-{{ $building->is_active ? 'success' : 'secondary' }}">
                                {{ $building->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                            </span>
                        </p>
                    </div>
                    @if($building->description)
                    <div class="mt-3">
                        <small class="text-muted">Mô tả</small>
                        <p class="mb-0 small">{{ $building->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <h3 class="mb-0">{{ $stats['total_rooms'] }}</h3>
                            <p class="mb-0 text-muted">Tổng số phòng</p>
                        </div>
                        <div class="rounded-circle iq-card-icon bg-primary-light">
                            <i class="ri-hotel-bed-line text-primary" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <h3 class="mb-0 text-success">{{ $stats['occupied_rooms'] }}</h3>
                            <p class="mb-0 text-muted">Phòng đã thuê</p>
                        </div>
                        <div class="rounded-circle iq-card-icon bg-success-light">
                            <i class="ri-checkbox-circle-line text-success" style="font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-success" style="width: {{ $stats['occupancy_rate'] }}%"></div>
                    </div>
                    <small class="text-muted">Tỷ lệ: {{ $stats['occupancy_rate'] }}%</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <h3 class="mb-0 text-info">{{ $stats['available_rooms'] }}</h3>
                            <p class="mb-0 text-muted">Phòng trống</p>
                        </div>
                        <div class="rounded-circle iq-card-icon bg-info-light">
                            <i class="ri-door-open-line text-info" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rooms by Floor -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title">Phòng theo tầng</h5>
                    <a href="{{ route('rooms.create', ['building_id' => $building->id]) }}" class="btn btn-sm btn-primary">
                        <i class="ri-add-line mr-2"></i>Thêm phòng
                    </a>
                </div>
                <div class="card-body">
                    @foreach($roomsByFloor as $floor => $rooms)
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <span class="badge badge-primary">Tầng {{ $floor }}</span>
                            <span class="ml-2 text-muted small">({{ $rooms->count() }} phòng)</span>
                        </h6>
                        <div class="row">
                            @foreach($rooms as $room)
                            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
                                <a href="{{ route('rooms.show', $room) }}" 
                                   class="card card-body text-center p-2 h-100 
                                   @if($room->status === 'occupied') bg-success-light
                                   @elseif($room->status === 'maintenance') bg-warning-light
                                   @elseif($room->status === 'available') bg-info-light
                                   @else bg-light
                                   @endif"
                                   style="text-decoration: none;">
                                    <div class="mb-1">
                                        <i class="ri-hotel-bed-line" style="font-size: 24px;"></i>
                                    </div>
                                    <strong>{{ $room->room_number }}</strong>
                                    <small class="text-muted d-block">{{ $room->roomType->name }}</small>
                                    <span class="badge badge-sm badge-{{ 
                                        $room->status === 'occupied' ? 'success' : 
                                        ($room->status === 'maintenance' ? 'warning' : 
                                        ($room->status === 'available' ? 'info' : 'secondary')) 
                                    }} mt-1">
                                        {{ $room->getStatusLabelAttribute() }}
                                    </span>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    @if($roomsByFloor->isEmpty())
                    <div class="text-center py-5">
                        <i class="ri-hotel-bed-line" style="font-size: 64px; color: #ccc;"></i>
                        <h5 class="mt-3">Chưa có phòng nào</h5>
                        <p class="text-muted">Nhấn nút "Thêm phòng" để tạo phòng mới</p>
                        <a href="{{ route('rooms.create', ['building_id' => $building->id]) }}" class="btn btn-primary">
                            <i class="ri-add-line mr-2"></i>Thêm phòng đầu tiên
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
