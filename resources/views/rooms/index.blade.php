@extends('layouts.app')

@section('title', 'Quản lý phòng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">Quản lý phòng</h4>
                    <p class="mb-0 text-muted">Danh sách các phòng trong hệ thống</p>
                </div>
                <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                    <i class="ri-add-line mr-2"></i>Thêm phòng
                </a>
            </div>
        </div>

        <!-- Advanced Filter -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('rooms.index') }}" class="row align-items-end">
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="dormitory_id">Ký túc xá</label>
                                <select class="form-control" id="dormitory_id" name="dormitory_id">
                                    <option value="">Tất cả</option>
                                    @foreach($dormitories as $dorm)
                                        <option value="{{ $dorm->id }}" {{ request('dormitory_id') == $dorm->id ? 'selected' : '' }}>
                                            {{ $dorm->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="building_id">Tòa nhà</label>
                                <select class="form-control" id="building_id" name="building_id">
                                    <option value="">Tất cả</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                            {{ $building->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="room_type_id">Loại phòng</label>
                                <select class="form-control" id="room_type_id" name="room_type_id">
                                    <option value="">Tất cả</option>
                                    @foreach($roomTypes as $type)
                                        <option value="{{ $type->id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group mb-md-0">
                                <label for="floor">Tầng</label>
                                <input type="number" class="form-control" id="floor" name="floor" 
                                       value="{{ request('floor') }}" placeholder="Tầng" min="1">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="status">Trạng thái</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Tất cả</option>
                                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Còn trống</option>
                                    <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Đã thuê</option>
                                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="search">Tìm kiếm</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Số phòng...">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="ri-search-line"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        </div>
        @endif

        <!-- Rooms Table -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-spacing mb-0">
                            <thead>
                                <tr>
                                    <th>Phòng</th>
                                    <th>Tòa nhà</th>
                                    <th>Loại phòng</th>
                                    <th>Tầng</th>
                                    <th>Giá thuê</th>
                                    <th>Sức chứa</th>
                                    <th>Trạng thái</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rooms as $room)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-40 rounded-circle bg-{{
                                                $room->status === 'available' ? 'info' :
                                                ($room->status === 'occupied' ? 'success' :
                                                ($room->status === 'maintenance' ? 'warning' : 'secondary'))
                                            }}-light mr-2">
                                                <i class="ri-hotel-bed-line text-{{
                                                    $room->status === 'available' ? 'info' :
                                                    ($room->status === 'occupied' ? 'success' :
                                                    ($room->status === 'maintenance' ? 'warning' : 'secondary'))
                                                }}"></i>
                                            </div>
                                            <strong>{{ $room->room_number }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $room->building->name }}</td>
                                    <td>{{ $room->roomType->name }}</td>
                                    <td>Tầng {{ $room->floor }}</td>
                                    <td class="font-weight-bold">{{ number_format($room->roomType->price_per_month) }} đ</td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ $room->current_occupancy }}/{{ $room->roomType->capacity }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{
                                            $room->status === 'available' ? 'info' :
                                            ($room->status === 'occupied' ? 'success' :
                                            ($room->status === 'maintenance' ? 'warning' : 'secondary'))
                                        }}">
                                            {{ $room->getStatusLabelAttribute() }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('rooms.show', $room) }}" class="btn btn-sm btn-outline-primary mr-1">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-outline-info mr-1">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Xóa phòng {{ $room->room_number }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="ri-hotel-bed-line" style="font-size: 64px; color: #ccc;"></i>
                                        <h5 class="mt-3">Không tìm thấy phòng nào</h5>
                                        <p class="text-muted">Thử thay đổi bộ lọc hoặc thêm phòng mới</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($rooms->hasPages())
                <div class="card-footer">
                    {{ $rooms->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
