@extends('layouts.app')

@section('title', 'Thêm phòng mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">Thêm phòng mới</h4>
                    <p class="mb-0 text-muted">Tạo phòng mới trong tòa nhà</p>
                </div>
                <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line mr-2"></i>Quay lại
                </a>
            </div>
        </div>

        <div class="col-lg-10 mx-auto">
            <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Basic Info Card -->
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Thông tin cơ bản</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="building_id">Tòa nhà <span class="text-danger">*</span></label>
                                    <select class="form-control @error('building_id') is-invalid @enderror" 
                                            id="building_id" name="building_id" required>
                                        <option value="">Chọn tòa nhà</option>
                                        @foreach($buildings as $building)
                                            <option value="{{ $building->id }}" 
                                                    {{ old('building_id', request('building_id')) == $building->id ? 'selected' : '' }}>
                                                {{ $building->dormitory->name }} - {{ $building->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('building_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="room_number">Số phòng <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('room_number') is-invalid @enderror" 
                                                   id="room_number" name="room_number" value="{{ old('room_number') }}" 
                                                   placeholder="VD: 101, A201" required>
                                            @error('room_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="floor">Tầng <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('floor') is-invalid @enderror" 
                                                   id="floor" name="floor" value="{{ old('floor', 1) }}" 
                                                   min="1" max="50" required>
                                            @error('floor')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="room_type_id">Loại phòng <span class="text-danger">*</span></label>
                                    <select class="form-control @error('room_type_id') is-invalid @enderror" 
                                            id="room_type_id" name="room_type_id" required>
                                        <option value="">Chọn loại phòng</option>
                                        @foreach($roomTypes as $type)
                                            <option value="{{ $type->id }}" data-capacity="{{ $type->capacity }}" {{ old('room_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }} ({{ $type->capacity }} người - {{ number_format($type->price_per_month) }} đ/tháng)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Còn trống</option>
                                        <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Đã thuê</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Đang bảo trì</option>
                                        <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Đã đặt trước</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="capacity">Sức chứa <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                                   id="capacity" name="capacity" value="{{ old('capacity') }}" min="1" required>
                                            @error('capacity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Tự động gợi ý theo loại phòng.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="area">Diện tích (m²)</label>
                                            <input type="number" class="form-control @error('area') is-invalid @enderror" 
                                                   id="area" name="area" value="{{ old('area') }}" step="0.01" min="0">
                                            @error('area')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="Mô tả về phòng...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-0">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" 
                                               name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Kích hoạt phòng</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Amenities & Images Card -->
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Tiện nghi</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Chọn tiện nghi có trong phòng</label>
                                    <div style="max-height: 300px; overflow-y: auto;">
                                        @foreach($amenities as $amenity)
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="amenity_{{ $amenity->id }}" 
                                                       name="amenities[]" 
                                                       value="{{ $amenity->id }}"
                                                       {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="amenity_{{ $amenity->id }}">
                                                    {{ $amenity->name }}
                                                    @if($amenity->description)
                                                        <small class="text-muted d-block">{{ $amenity->description }}</small>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Hình ảnh phòng</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="images">Upload hình ảnh (tối đa 5 ảnh)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('images.*') is-invalid @enderror" 
                                               id="images" name="images[]" accept="image/*" multiple>
                                        <label class="custom-file-label" for="images">Chọn ảnh...</label>
                                    </div>
                                    <small class="form-text text-muted">Định dạng: JPG, PNG. Kích thước tối đa: 2MB/ảnh</small>
                                    @error('images.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line mr-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line mr-2"></i>Lưu phòng
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('images').addEventListener('change', function(e) {
    var fileName = e.target.files.length > 1 ? e.target.files.length + ' files selected' : e.target.files[0]?.name || 'Choose file...';
    e.target.nextElementSibling.innerText = fileName;
});

// Auto fill capacity based on selected room type
document.getElementById('room_type_id').addEventListener('change', function(e) {
    var capacity = e.target.options[e.target.selectedIndex]?.getAttribute('data-capacity');
    if (capacity) {
        document.getElementById('capacity').value = capacity;
    }
});
</script>
@endpush
@endsection
