@extends('layouts.app')

@section('title', 'Chỉnh sửa phòng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Chỉnh sửa phòng {{ $room->room_number }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('rooms.index') }}">Quản lý phòng</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa phòng</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('rooms.update', $room) }}" method="POST" enctype="multipart/form-data" id="editRoomForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Thông tin cơ bản -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="ri-information-line mr-2"></i>Thông tin cơ bản</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="building_id">Tòa nhà <span class="text-danger">*</span></label>
                                    <select name="building_id" id="building_id" class="form-control @error('building_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn tòa nhà --</option>
                                        @foreach($buildings as $building)
                                            <option value="{{ $building->id }}" 
                                                data-dormitory="{{ $building->dormitory->name ?? '' }}"
                                                {{ old('building_id', $room->building_id) == $building->id ? 'selected' : '' }}>
                                                {{ $building->name }} - {{ $building->dormitory->name ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('building_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="room_type_id">Loại phòng <span class="text-danger">*</span></label>
                                    <select name="room_type_id" id="room_type_id" class="form-control @error('room_type_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn loại phòng --</option>
                                        @foreach($roomTypes as $type)
                                            <option value="{{ $type->id }}" 
                                                data-capacity="{{ $type->capacity }}"
                                                data-price="{{ $type->price_per_month }}"
                                                {{ old('room_type_id', $room->room_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }} ({{ $type->capacity }} người - {{ number_format($type->price_per_month) }}đ/tháng)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="room_number">Số phòng <span class="text-danger">*</span></label>
                                    <input type="text" name="room_number" id="room_number" 
                                        class="form-control @error('room_number') is-invalid @enderror" 
                                        value="{{ old('room_number', $room->room_number) }}" required>
                                    @error('room_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="floor">Tầng <span class="text-danger">*</span></label>
                                    <input type="number" name="floor" id="floor" 
                                        class="form-control @error('floor') is-invalid @enderror" 
                                        value="{{ old('floor', $room->floor) }}" min="1" required>
                                    @error('floor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="capacity">Sức chứa <span class="text-danger">*</span></label>
                                    <input type="number" name="capacity" id="capacity" 
                                        class="form-control @error('capacity') is-invalid @enderror" 
                                        value="{{ old('capacity', $room->capacity) }}" min="1" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Tự động cập nhật theo loại phòng</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="area">Diện tích (m²)</label>
                                    <input type="number" name="area" id="area" 
                                        class="form-control @error('area') is-invalid @enderror" 
                                        value="{{ old('area', $room->area) }}" step="0.01" min="0">
                                    @error('area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>
                                            Còn trống
                                        </option>
                                        <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>
                                            Đã đầy
                                        </option>
                                        <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>
                                            Đang bảo trì
                                        </option>
                                        <option value="reserved" {{ old('status', $room->status) == 'reserved' ? 'selected' : '' }}>
                                            Đã đặt trước
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes">Ghi chú</label>
                                    <textarea name="notes" id="notes" rows="3" 
                                        class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $room->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                                        {{ old('is_active', $room->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        Kích hoạt phòng
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hình ảnh -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="ri-image-line mr-2"></i>Hình ảnh phòng</h5>
                    </div>
                    <div class="card-body">
                        @if($room->images && count($room->images) > 0)
                            <div class="mb-3">
                                <h6>Hình ảnh hiện tại:</h6>
                                <div class="row">
                                    @foreach($room->images as $index => $image)
                                                                                @storageFileExists($image)
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="{{ asset('storage/' . $image) }}" class="card-img-top" alt="Room image" style="height: 150px; object-fit: cover;" onerror="this.onerror=null; this.parentElement.parentElement.parentElement.style.display='none';">
                                                <div class="card-body p-2">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" 
                                                            id="remove_image_{{ $index }}" 
                                                            name="remove_images[]" 
                                                            value="{{ $image }}">
                                                        <label class="custom-control-label" for="remove_image_{{ $index }}">
                                                            <small class="text-danger">Xóa ảnh này</small>
                                                                                                @endstorageFileExists
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="images">Thêm hình ảnh mới</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('images.*') is-invalid @enderror" 
                                    id="images" name="images[]" multiple accept="image/*">
                                <label class="custom-file-label" for="images" data-browse="Chọn file">Chọn hình ảnh...</label>
                            </div>
                            <small class="form-text text-muted">Có thể chọn nhiều hình ảnh cùng lúc. Định dạng: JPG, PNG, GIF</small>
                            @error('images.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="imagePreview" class="row mt-3"></div>
                    </div>
                </div>

                <!-- Tiện ích -->
                <div class="card">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="ri-hotel-bed-line mr-2"></i>Tiện ích phòng</h5>
                        <button type="button" class="btn btn-sm btn-light" id="addAmenityBtn">
                            <i class="ri-add-line"></i> Thêm tiện ích
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="amenitiesContainer">
                            @if($room->amenities && $room->amenities->count() > 0)
                                @foreach($room->amenities as $index => $amenity)
                                    <div class="amenity-row mb-3 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group mb-2">
                                                    <label>Tiện ích</label>
                                                    <select name="amenities[{{ $index }}][id]" class="form-control">
                                                        @foreach($amenities as $am)
                                                            <option value="{{ $am->id }}" {{ $amenity->id == $am->id ? 'selected' : '' }}>
                                                                {{ $am->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group mb-2">
                                                    <label>Số lượng</label>
                                                    <input type="number" name="amenities[{{ $index }}][quantity]" 
                                                        class="form-control" value="{{ $amenity->pivot->quantity ?? 1 }}" min="1">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-2">
                                                    <label>Tình trạng</label>
                                                    <select name="amenities[{{ $index }}][condition]" class="form-control">
                                                        <option value="good" {{ ($amenity->pivot->condition ?? 'good') == 'good' ? 'selected' : '' }}>Tốt</option>
                                                        <option value="fair" {{ ($amenity->pivot->condition ?? 'good') == 'fair' ? 'selected' : '' }}>Khá</option>
                                                        <option value="poor" {{ ($amenity->pivot->condition ?? 'good') == 'poor' ? 'selected' : '' }}>Kém</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-amenity-btn mt-3">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center">Chưa có tiện ích nào. Nhấn "Thêm tiện ích" để thêm.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Hướng dẫn -->
                <div class="card">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="ri-lightbulb-line mr-2"></i>Hướng dẫn</h5>
                    </div>
                    <div class="card-body">
                        <ul class="pl-3 mb-0">
                            <li>Các trường có dấu <span class="text-danger">*</span> là bắt buộc</li>
                            <li>Sức chứa sẽ tự động cập nhật khi chọn loại phòng</li>
                            <li>Có thể tải lên nhiều hình ảnh cùng lúc</li>
                            <li>Chọn checkbox "Xóa ảnh này" để xóa hình ảnh cũ</li>
                            <li>Thêm tiện ích bằng nút "Thêm tiện ích"</li>
                            <li>Có thể xóa tiện ích không cần thiết</li>
                        </ul>
                    </div>
                </div>

                <!-- Thông tin bổ sung -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="ri-file-info-line mr-2"></i>Thông tin bổ sung</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Mã phòng:</strong></td>
                                <td>{{ $room->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Số sinh viên hiện tại:</strong></td>
                                <td>{{ $room->current_occupancy ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><strong>Ngày tạo:</strong></td>
                                <td>{{ $room->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Cập nhật lần cuối:</strong></td>
                                <td>{{ $room->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Nút hành động -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="ri-save-line mr-1"></i> Lưu thay đổi
                        </button>
                        <a href="{{ route('rooms.show', $room) }}" class="btn btn-secondary btn-block">
                            <i class="ri-arrow-go-back-line mr-1"></i> Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let amenityIndex = {{ $room->amenities->count() }};

    // Cập nhật capacity khi thay đổi room type
    $('#room_type_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const capacity = selectedOption.data('capacity');
        if (capacity) {
            $('#capacity').val(capacity);
        }
    });

    // Thêm tiện ích mới
    $('#addAmenityBtn').on('click', function() {
        const amenitiesHtml = `
            <div class="amenity-row mb-3 p-3 border rounded">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group mb-2">
                            <label>Tiện ích</label>
                            <select name="amenities[${amenityIndex}][id]" class="form-control" required>
                                <option value="">-- Chọn tiện ích --</option>
                                @foreach($amenities as $am)
                                    <option value="{{ $am->id }}">{{ $am->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-2">
                            <label>Số lượng</label>
                            <input type="number" name="amenities[${amenityIndex}][quantity]" 
                                class="form-control" value="1" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-2">
                            <label>Tình trạng</label>
                            <select name="amenities[${amenityIndex}][condition]" class="form-control" required>
                                <option value="good" selected>Tốt</option>
                                <option value="fair">Khá</option>
                                <option value="poor">Kém</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-amenity-btn mt-3">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Xóa thông báo "Chưa có tiện ích" nếu có
        $('#amenitiesContainer p.text-muted').remove();
        $('#amenitiesContainer').append(amenitiesHtml);
        amenityIndex++;
    });

    // Xóa tiện ích
    $(document).on('click', '.remove-amenity-btn', function() {
        $(this).closest('.amenity-row').remove();
        
        // Nếu không còn tiện ích nào, hiển thị lại thông báo
        if ($('#amenitiesContainer .amenity-row').length === 0) {
            $('#amenitiesContainer').html('<p class="text-muted text-center">Chưa có tiện ích nào. Nhấn "Thêm tiện ích" để thêm.</p>');
        }
    });

    // Custom file input label
    $('#images').on('change', function() {
        const files = this.files;
        let label = '';
        
        if (files.length === 0) {
            label = 'Chọn hình ảnh...';
        } else if (files.length === 1) {
            label = files[0].name;
        } else {
            label = files.length + ' files đã chọn';
        }
        
        $(this).next('.custom-file-label').html(label);
        
        // Preview images
        const previewContainer = $('#imagePreview');
        previewContainer.empty();
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = `
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" alt="Preview" style="height: 150px; object-fit: cover;">
                            <div class="card-body p-2 text-center">
                                <small class="text-muted">${file.name}</small>
                            </div>
                        </div>
                    </div>
                `;
                previewContainer.append(preview);
            };
            
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    $('#editRoomForm').on('submit', function(e) {
        const capacity = parseInt($('#capacity').val());
        const currentOccupancy = {{ $room->current_occupancy ?? 0 }};
        
        if (capacity < currentOccupancy) {
            e.preventDefault();
            alert('Sức chứa phòng không thể nhỏ hơn số sinh viên hiện tại (' + currentOccupancy + ')!');
            return false;
        }
    });
</script>
@endpush
@endsection
