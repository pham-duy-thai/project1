@extends('layouts.app')

@section('title', 'Chỉnh sửa yêu cầu bảo trì')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Chỉnh sửa yêu cầu bảo trì</h4>
                    <p class="mb-0 text-muted">Cập nhật thông tin yêu cầu #{{ $maintenanceRequest->request_code }}</p>
                </div>
                <div>
                    <a href="{{ route('maintenance.show', $maintenanceRequest) }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line mr-1"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ri-error-warning-line mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Có lỗi xảy ra:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="ri-edit-line mr-2"></i>Thông tin yêu cầu</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('maintenance.update', $maintenanceRequest) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="room_id">Phòng <span class="text-danger">*</span></label>
                            <select name="room_id" id="room_id" class="form-control @error('room_id') is-invalid @enderror" required>
                                <option value="">-- Chọn phòng --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" 
                                        {{ old('room_id', $maintenanceRequest->room_id) == $room->id ? 'selected' : '' }}>
                                        {{ $room->building->dormitory->name ?? '' }} - 
                                        {{ $room->building->name ?? '' }} - 
                                        Phòng {{ $room->room_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" 
                                class="form-control @error('title') is-invalid @enderror" 
                                value="{{ old('title', $maintenanceRequest->title) }}" 
                                placeholder="Vd: Điều hòa không hoạt động" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="5" 
                                class="form-control @error('description') is-invalid @enderror" 
                                placeholder="Mô tả chi tiết vấn đề cần bảo trì..." required>{{ old('description', $maintenanceRequest->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Loại vấn đề <span class="text-danger">*</span></label>
                                    <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                                        <option value="">-- Chọn loại --</option>
                                        <option value="plumbing" {{ old('category', $maintenanceRequest->category) == 'plumbing' ? 'selected' : '' }}>
                                            Hệ thống nước
                                        </option>
                                        <option value="electrical" {{ old('category', $maintenanceRequest->category) == 'electrical' ? 'selected' : '' }}>
                                            Hệ thống điện
                                        </option>
                                        <option value="furniture" {{ old('category', $maintenanceRequest->category) == 'furniture' ? 'selected' : '' }}>
                                            Đồ nội thất
                                        </option>
                                        <option value="air_conditioning" {{ old('category', $maintenanceRequest->category) == 'air_conditioning' ? 'selected' : '' }}>
                                            Điều hòa
                                        </option>
                                        <option value="door_lock" {{ old('category', $maintenanceRequest->category) == 'door_lock' ? 'selected' : '' }}>
                                            Cửa và khóa
                                        </option>
                                        <option value="window" {{ old('category', $maintenanceRequest->category) == 'window' ? 'selected' : '' }}>
                                            Cửa sổ
                                        </option>
                                        <option value="other" {{ old('category', $maintenanceRequest->category) == 'other' ? 'selected' : '' }}>
                                            Khác
                                        </option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Mức độ ưu tiên <span class="text-danger">*</span></label>
                                    <select name="priority" id="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                        <option value="">-- Chọn mức độ --</option>
                                        <option value="low" {{ old('priority', $maintenanceRequest->priority) == 'low' ? 'selected' : '' }}>
                                            Thấp
                                        </option>
                                        <option value="medium" {{ old('priority', $maintenanceRequest->priority) == 'medium' ? 'selected' : '' }}>
                                            Trung bình
                                        </option>
                                        <option value="high" {{ old('priority', $maintenanceRequest->priority) == 'high' ? 'selected' : '' }}>
                                            Cao
                                        </option>
                                        <option value="urgent" {{ old('priority', $maintenanceRequest->priority) == 'urgent' ? 'selected' : '' }}>
                                            Khẩn cấp
                                        </option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if($maintenanceRequest->images && count($maintenanceRequest->images) > 0)
                        <div class="form-group">
                            <label>Hình ảnh hiện tại</label>
                            <div class="row">
                                @foreach($maintenanceRequest->images as $image)
                                    <div class="col-md-3 mb-2">
                                        <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Image">
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Không thể thay đổi hình ảnh khi chỉnh sửa</small>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('maintenance.show', $maintenanceRequest) }}" class="btn btn-secondary">
                                <i class="ri-close-line mr-1"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line mr-1"></i> Cập nhật yêu cầu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
