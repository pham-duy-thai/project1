@extends('layouts.app')

@section('title', 'Tạo yêu cầu bảo trì')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Tạo yêu cầu bảo trì</h4>
                    <p class="mb-0 text-muted">Gửi yêu cầu bảo trì, sửa chữa phòng</p>
                </div>
                <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line mr-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line mr-2"></i>
        <strong>Có lỗi xảy ra:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
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

    <form action="{{ route('maintenance.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
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
                                    <label for="room_id">Phòng <span class="text-danger">*</span></label>
                                    <select name="room_id" id="room_id" class="form-control @error('room_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn phòng --</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" {{ old('room_id', request('room_id')) == $room->id ? 'selected' : '' }}>
                                                {{ $room->room_number }} - {{ $room->building->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Loại <span class="text-danger">*</span></label>
                                    <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                                        <option value="">-- Chọn loại --</option>
                                        <option value="electrical" {{ old('category') == 'electrical' ? 'selected' : '' }}>Hệ thống điện</option>
                                        <option value="plumbing" {{ old('category') == 'plumbing' ? 'selected' : '' }}>Hệ thống nước</option>
                                        <option value="furniture" {{ old('category') == 'furniture' ? 'selected' : '' }}>Đồ nội thất</option>
                                        <option value="air_conditioning" {{ old('category') == 'air_conditioning' ? 'selected' : '' }}>Điều hòa</option>
                                        <option value="door_lock" {{ old('category') == 'door_lock' ? 'selected' : '' }}>Cửa và khóa</option>
                                        <option value="window" {{ old('category') == 'window' ? 'selected' : '' }}>Cửa sổ</option>
                                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Độ ưu tiên <span class="text-danger">*</span></label>
                                    <select name="priority" id="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                        <option value="low" {{ old('priority', 'medium') == 'low' ? 'selected' : '' }}>Thấp</option>
                                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Trung bình</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Cao</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Khẩn cấp</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" 
                                        class="form-control @error('title') is-invalid @enderror" 
                                        value="{{ old('title') }}" 
                                        placeholder="VD: Đèn phòng không sáng, Vòi nước bị hỏng..." required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">Mô tả chi tiết <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" rows="5" 
                                        class="form-control @error('description') is-invalid @enderror" 
                                        placeholder="Mô tả chi tiết vấn đề cần bảo trì..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="images">Hình ảnh minh họa</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('images.*') is-invalid @enderror" 
                                            id="images" name="images[]" multiple accept="image/*">
                                        <label class="custom-file-label" for="images">Chọn hình ảnh...</label>
                                    </div>
                                    <small class="form-text text-muted">Có thể chọn nhiều ảnh. Định dạng: JPG, PNG, GIF</small>
                                    @error('images.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="imagePreview" class="row mt-3"></div>
                            </div>
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
                        <h6>Độ ưu tiên:</h6>
                        <ul class="pl-3 mb-3">
                            <li><strong class="text-danger">Khẩn cấp:</strong> Sự cố nguy hiểm, cần xử lý ngay</li>
                            <li><strong class="text-warning">Cao:</strong> Ảnh hưởng lớn đến sinh hoạt</li>
                            <li><strong class="text-info">Trung bình:</strong> Cần sửa chữa trong vài ngày</li>
                            <li><strong class="text-secondary">Thấp:</strong> Có thể chờ xử lý</li>
                        </ul>

                        <h6>Lưu ý:</h6>
                        <ul class="pl-3 mb-0">
                            <li>Mô tả rõ ràng vấn đề cần bảo trì</li>
                            <li>Đính kèm hình ảnh nếu có</li>
                            <li>Chọn đúng loại và độ ưu tiên</li>
                        </ul>
                    </div>
                </div>

                <!-- Nút hành động -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="ri-send-plane-line mr-1"></i> Gửi yêu cầu
                        </button>
                        <a href="{{ route('maintenance.index') }}" class="btn btn-secondary btn-block">
                            <i class="ri-close-line mr-1"></i> Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Custom file input label and preview
    $('#images').on('change', function() {
        const files = this.files;
        let label = '';
        
        if (files.length === 0) {
            label = 'Chọn hình ảnh...';
        } else if (files.length === 1) {
            label = files[0].name;
        } else {
            label = files.length + ' hình ảnh đã chọn';
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
});
</script>
@endpush
@endsection
