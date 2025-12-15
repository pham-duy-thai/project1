@extends('layouts.app')

@section('title', 'Quản lý ký túc xá')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">Quản lý ký túc xá</h4>
                    <p class="mb-0 text-muted">Danh sách các ký túc xá trong hệ thống</p>
                </div>
                <a href="{{ route('dormitories.create') }}" class="btn btn-primary">
                    <i class="ri-add-line mr-2"></i>Thêm ký túc xá
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        @endif

        @forelse($dormitories as $dormitory)
        <div class="col-lg-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="mb-1">{{ $dormitory->name }}</h4>
                            <p class="text-muted mb-0"><i class="ri-map-pin-line mr-1"></i>{{ $dormitory->address }}</p>
                        </div>
                        <span class="badge badge-{{ $dormitory->is_active ? 'success' : 'secondary' }}">
                            {{ $dormitory->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                        </span>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="mb-2">
                                <small class="text-muted">Mã ký túc xá</small>
                                <p class="mb-0 font-weight-bold">{{ $dormitory->code }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-2">
                                <small class="text-muted">Số điện thoại</small>
                                <p class="mb-0">{{ $dormitory->phone ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="row">
                        <div class="col-3 text-center border-right">
                            <h5 class="mb-0 text-primary">{{ $dormitory->buildings_count ?? 0 }}</h5>
                            <small class="text-muted">Tòa nhà</small>
                        </div>
                        <div class="col-3 text-center border-right">
                            <h5 class="mb-0 text-info">{{ $dormitory->total_rooms ?? 0 }}</h5>
                            <small class="text-muted">Phòng</small>
                        </div>
                        <div class="col-3 text-center border-right">
                            <h5 class="mb-0 text-success">{{ $dormitory->occupied_rooms ?? 0 }}</h5>
                            <small class="text-muted">Đã thuê</small>
                        </div>
                        <div class="col-3 text-center">
                            <h5 class="mb-0 text-warning">{{ $dormitory->total_students ?? 0 }}</h5>
                            <small class="text-muted">Sinh viên</small>
                        </div>
                    </div>

                    @if($dormitory->description)
                    <p class="text-muted mt-3 mb-0">{{ Str::limit($dormitory->description, 100) }}</p>
                    @endif

                    <div class="mt-3 d-flex justify-content-between">
                        <div>
                            <a href="{{ route('dormitories.show', $dormitory) }}" class="btn btn-sm btn-outline-primary mr-1">
                                <i class="ri-eye-line"></i> Xem
                            </a>
                            <a href="{{ route('dormitories.edit', $dormitory) }}" class="btn btn-sm btn-outline-info">
                                <i class="ri-edit-line"></i> Sửa
                            </a>
                        </div>
                        <form action="{{ route('dormitories.destroy', $dormitory) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa ký túc xá này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="ri-delete-bin-line"></i> Xóa
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
                    <h5 class="mt-3">Chưa có ký túc xá nào</h5>
                    <p class="text-muted">Nhấn nút "Thêm ký túc xá" để tạo mới</p>
                    <a href="{{ route('dormitories.create') }}" class="btn btn-primary">
                        <i class="ri-add-line mr-2"></i>Thêm ký túc xá đầu tiên
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
