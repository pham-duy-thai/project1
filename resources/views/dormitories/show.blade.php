@extends('layouts.app')

@section('title', $dormitory->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">{{ $dormitory->name }}</h4>
                    <p class="mb-0 text-muted"><i class="ri-map-pin-line mr-1"></i>{{ $dormitory->address }}</p>
                </div>
                <div>
                    <a href="{{ route('dormitories.edit', $dormitory) }}" class="btn btn-info mr-2">
                        <i class="ri-edit-line mr-2"></i>Chỉnh sửa
                    </a>
                    <a href="{{ route('dormitories.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line mr-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>

        <!-- Basic Info -->
        <div class="col-lg-4">
            <div class="card card-block card-stretch card-height">
                <div class="card-header">
                    <h5 class="card-title">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Mã ký túc xá</small>
                        <p class="mb-0 font-weight-bold">{{ $dormitory->code }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Trạng thái</small>
                        <p class="mb-0">
                            <span class="badge badge-{{ $dormitory->is_active ? 'success' : 'secondary' }}">
                                {{ $dormitory->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Số điện thoại</small>
                        <p class="mb-0">{{ $dormitory->phone ?? 'Chưa cập nhật' }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Địa chỉ</small>
                        <p class="mb-0">{{ $dormitory->address }}</p>
                    </div>
                    @if($dormitory->description)
                    <div class="mb-0">
                        <small class="text-muted">Mô tả</small>
                        <p class="mb-0">{{ $dormitory->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h2 class="mb-0 text-primary">{{ $dormitory->buildings->count() }}</h2>
                                    <p class="mb-0 text-muted">Tòa nhà</p>
                                </div>
                                <div class="rounded-circle iq-card-icon bg-primary-light">
                                    <i class="ri-building-line text-primary" style="font-size: 28px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h2 class="mb-0 text-info">{{ $stats['total_rooms'] }}</h2>
                                    <p class="mb-0 text-muted">Tổng số phòng</p>
                                </div>
                                <div class="rounded-circle iq-card-icon bg-info-light">
                                    <i class="ri-hotel-bed-line text-info" style="font-size: 28px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h2 class="mb-0 text-success">{{ $stats['occupied_rooms'] }}</h2>
                                    <p class="mb-0 text-muted">Phòng đã có người</p>
                                </div>
                                <div class="rounded-circle iq-card-icon bg-success-light">
                                    <i class="ri-checkbox-circle-line text-success" style="font-size: 28px;"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ $stats['occupancy_rate'] }}%"></div>
                                </div>
                                <small class="text-muted">Tỷ lệ: {{ $stats['occupancy_rate'] }}%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h2 class="mb-0 text-warning">{{ $stats['total_students'] }}</h2>
                                    <p class="mb-0 text-muted">Tổng sinh viên</p>
                                </div>
                                <div class="rounded-circle iq-card-icon bg-warning-light">
                                    <i class="ri-user-line text-warning" style="font-size: 28px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buildings List -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title">Danh sách tòa nhà</h5>
                    <a href="{{ route('buildings.create', ['dormitory_id' => $dormitory->id]) }}" class="btn btn-sm btn-primary">
                        <i class="ri-add-line mr-2"></i>Thêm tòa nhà
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-spacing mb-0">
                            <thead>
                                <tr>
                                    <th>Tên tòa nhà</th>
                                    <th>Giới tính</th>
                                    <th>Số tầng</th>
                                    <th>Tổng phòng</th>
                                    <th>Đã thuê</th>
                                    <th>Trạng thái</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dormitory->buildings as $building)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-40 rounded-circle bg-primary-light mr-2">
                                                <span class="font-size-14 text-primary">{{ substr($building->name, 0, 1) }}</span>
                                            </div>
                                            <strong>{{ $building->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        @if($building->gender === 'male')
                                            <span class="badge badge-info">Nam</span>
                                        @elseif($building->gender === 'female')
                                            <span class="badge badge-danger">Nữ</span>
                                        @else
                                            <span class="badge badge-secondary">Cả hai</span>
                                        @endif
                                    </td>
                                    <td>{{ $building->total_floors }}</td>
                                    <td>{{ $building->rooms->count() }}</td>
                                    <td>{{ $building->rooms->where('status', 'occupied')->count() }}</td>
                                    <td>
                                        <span class="badge badge-{{ $building->is_active ? 'success' : 'secondary' }}">
                                            {{ $building->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('buildings.show', $building) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('buildings.edit', $building) }}" class="btn btn-sm btn-outline-info">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">Chưa có tòa nhà nào</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
