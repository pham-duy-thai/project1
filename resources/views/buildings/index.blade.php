@extends('layouts.app')

@section('title', 'Quản lý tòa nhà')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">Quản lý tòa nhà</h4>
                    <p class="mb-0 text-muted">Danh sách các tòa nhà trong hệ thống</p>
                </div>
                <a href="{{ route('buildings.create') }}" class="btn btn-primary">
                    <i class="ri-add-line mr-2"></i>Thêm tòa nhà
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('buildings.index') }}" class="row align-items-end">
                        <div class="col-md-3">
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
                                <label for="gender">Giới tính</label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="">Tất cả</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="mixed" {{ request('gender') == 'mixed' ? 'selected' : '' }}>Cả hai</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="is_active">Trạng thái</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="">Tất cả</option>
                                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="search">Tìm kiếm</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Tên tòa nhà...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="ri-search-line mr-2"></i>Lọc
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

        <!-- Buildings List -->
        @forelse($buildings as $building)
        <div class="col-lg-4 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-1">{{ $building->name }}</h5>
                            <p class="text-muted mb-0 small">{{ $building->dormitory->name }}</p>
                        </div>
                        <span class="badge badge-{{ $building->is_active ? 'success' : 'secondary' }}">
                            {{ $building->is_active ? 'Hoạt động' : 'Tạm đóng' }}
                        </span>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
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
                        <div class="col-6">
                            <small class="text-muted">Số tầng</small>
                            <p class="mb-0 font-weight-bold">{{ $building->total_floors }} tầng</p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="row border-top pt-3">
                        <div class="col-4 text-center">
                            <h6 class="mb-0">{{ $building->total_rooms }}</h6>
                            <small class="text-muted">Phòng</small>
                        </div>
                        <div class="col-4 text-center border-left border-right">
                            <h6 class="mb-0 text-success">{{ $building->occupied_rooms }}</h6>
                            <small class="text-muted">Đã thuê</small>
                        </div>
                        <div class="col-4 text-center">
                            <h6 class="mb-0 text-info">{{ $building->total_rooms - $building->occupied_rooms }}</h6>
                            <small class="text-muted">Trống</small>
                        </div>
                    </div>

                    @if($building->description)
                    <p class="text-muted mt-3 mb-0 small">{{ Str::limit($building->description, 80) }}</p>
                    @endif

                    <div class="mt-3 d-flex justify-content-between">
                        <div>
                            <a href="{{ route('buildings.show', $building) }}" class="btn btn-sm btn-outline-primary">
                                <i class="ri-eye-line"></i> Xem
                            </a>
                            <a href="{{ route('buildings.edit', $building) }}" class="btn btn-sm btn-outline-info">
                                <i class="ri-edit-line"></i>
                            </a>
                        </div>
                        <form action="{{ route('buildings.destroy', $building) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Xóa tòa nhà {{ $building->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-building-line" style="font-size: 64px; color: #ccc;"></i>
                    <h5 class="mt-3">Chưa có tòa nhà nào</h5>
                    <p class="text-muted">Nhấn nút "Thêm tòa nhà" để tạo mới</p>
                </div>
            </div>
        </div>
        @endforelse

        @if($buildings->hasPages())
        <div class="col-lg-12">
            {{ $buildings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
