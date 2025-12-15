@extends('layouts.app')

@section('title', 'Ghi nhận vi phạm')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Ghi nhận vi phạm</h4>
                    <p class="mb-0 text-muted">Ghi nhận vi phạm của sinh viên</p>
                </div>
                <a href="{{ route('violations.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line mr-1"></i> Quay lại
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

    <form action="{{ route('violations.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Thông tin vi phạm -->
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="ri-alert-line mr-2"></i>Thông tin vi phạm</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id">Sinh viên <span class="text-danger">*</span></label>
                                    <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn sinh viên --</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->student_code }} - {{ $student->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="room_id">Phòng</label>
                                    <select name="room_id" id="room_id" class="form-control @error('room_id') is-invalid @enderror">
                                        <option value="">-- Chọn phòng (tùy chọn) --</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                                {{ $room->room_number }} - {{ $room->building->name }}
                                                @if($room->building->dormitory)
                                                    ({{ $room->building->dormitory->name }})
                                                @endif
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
                                    <label for="violation_date">Ngày vi phạm <span class="text-danger">*</span></label>
                                    <input type="date" name="violation_date" id="violation_date" 
                                        class="form-control @error('violation_date') is-invalid @enderror" 
                                        value="{{ old('violation_date', date('Y-m-d')) }}" 
                                        max="{{ date('Y-m-d') }}" required>
                                    @error('violation_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="severity">Mức độ nghiêm trọng <span class="text-danger">*</span></label>
                                    <select name="severity" id="severity" class="form-control @error('severity') is-invalid @enderror" required>
                                        <option value="">-- Chọn mức độ --</option>
                                        <option value="minor" {{ old('severity') == 'minor' ? 'selected' : '' }}>Nhẹ</option>
                                        <option value="moderate" {{ old('severity') == 'moderate' ? 'selected' : '' }}>Trung bình</option>
                                        <option value="serious" {{ old('severity') == 'serious' ? 'selected' : '' }}>Nghiêm trọng</option>
                                        <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Rất nghiêm trọng</option>
                                    </select>
                                    @error('severity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="title">Tiêu đề vi phạm <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" 
                                        class="form-control @error('title') is-invalid @enderror" 
                                        value="{{ old('title') }}" 
                                        placeholder="VD: Gây ồn sau giờ nghỉ, Hút thuốc trong phòng..." required>
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
                                        placeholder="Mô tả chi tiết về vi phạm..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fine_amount">Số tiền phạt (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" name="fine_amount" id="fine_amount" 
                                        class="form-control @error('fine_amount') is-invalid @enderror" 
                                        value="{{ old('fine_amount', 0) }}" 
                                        min="0" step="1000" required>
                                    @error('fine_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Nhập số tiền phạt (0 nếu không có phạt tiền)</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="evidence">Bằng chứng (hình ảnh/tài liệu)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('evidence.*') is-invalid @enderror" 
                                            id="evidence" name="evidence[]" multiple accept="image/*,.pdf,.doc,.docx">
                                        <label class="custom-file-label" for="evidence">Chọn tệp bằng chứng...</label>
                                    </div>
                                    <small class="form-text text-muted">Có thể chọn nhiều tệp. Tối đa 5MB mỗi tệp. Định dạng: JPG, PNG, PDF, DOC, DOCX</small>
                                    @error('evidence.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="evidencePreview" class="row mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Hướng dẫn -->
                <div class="card mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="ri-lightbulb-line mr-2"></i>Hướng dẫn</h5>
                    </div>
                    <div class="card-body">
                        <h6>Mức độ nghiêm trọng:</h6>
                        <ul class="pl-3 mb-3">
                            <li><strong class="text-success">Nhẹ:</strong> Vi phạm nhỏ, cảnh báo</li>
                            <li><strong class="text-info">Trung bình:</strong> Vi phạm cần xử lý</li>
                            <li><strong class="text-warning">Nghiêm trọng:</strong> Vi phạm nghiêm trọng</li>
                            <li><strong class="text-danger">Rất nghiêm trọng:</strong> Vi phạm rất nghiêm trọng, có thể bị đuổi</li>
                        </ul>

                        <h6>Lưu ý:</h6>
                        <ul class="pl-3 mb-0">
                            <li>Chọn đúng sinh viên vi phạm</li>
                            <li>Mô tả rõ ràng, chi tiết</li>
                            <li>Đính kèm bằng chứng nếu có</li>
                            <li>Nhập đúng số tiền phạt</li>
                        </ul>
                    </div>
                </div>

                <!-- Thông tin bổ sung -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="ri-information-line mr-2"></i>Thông tin</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Người báo cáo:</strong><br>
                            <span class="text-muted">{{ Auth::user()->name }}</span>
                        </p>
                        <p class="mb-0">
                            <strong>Ngày báo cáo:</strong><br>
                            <span class="text-muted">{{ now()->format('d/m/Y H:i') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Nút hành động -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-danger btn-block btn-lg">
                            <i class="ri-save-line mr-1"></i> Ghi nhận vi phạm
                        </button>
                        <a href="{{ route('violations.index') }}" class="btn btn-secondary btn-block">
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
    $('#evidence').on('change', function() {
        const files = this.files;
        let label = '';
        
        if (files.length === 0) {
            label = 'Chọn tệp bằng chứng...';
        } else if (files.length === 1) {
            label = files[0].name;
        } else {
            label = files.length + ' tệp đã chọn';
        }
        
        $(this).next('.custom-file-label').html(label);
        
        // Preview images
        const previewContainer = $('#evidencePreview');
        previewContainer.empty();
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            
            // Only preview images
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const preview = `
                        <div class="col-md-6 mb-3">
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
            } else {
                // Show file name for non-image files
                const preview = `
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body text-center p-3">
                                <i class="ri-file-line" style="font-size: 48px; color: #6c757d;"></i>
                                <p class="mb-0 mt-2"><small class="text-muted">${file.name}</small></p>
                            </div>
                        </div>
                    </div>
                `;
                previewContainer.append(preview);
            }
        }
    });

    // Format fine amount on input
    $('#fine_amount').on('input', function() {
        let value = $(this).val();
        if (value && !isNaN(value)) {
            // Round to nearest 1000
            value = Math.round(value / 1000) * 1000;
            $(this).val(value);
        }
    });
});
</script>
@endpush
@endsection

