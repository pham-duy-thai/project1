@extends('layouts.app')

@section('title', 'Chỉnh sửa thông báo')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('announcements.index') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Quay lại
            </a>
        </div>

        <div class="col-lg-12">
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

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-edit-line mr-2"></i>Chỉnh sửa thông báo</h5>
                </div>
                <form action="{{ route('announcements.update', $announcement) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Basic Info -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $announcement->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="content">Nội dung <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" name="content" rows="10" required>{{ old('content', $announcement->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Hỗ trợ định dạng HTML cơ bản</small>
                                </div>
                            </div>

                            <!-- Settings -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type">Loại thông báo <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="general" {{ old('type', $announcement->type) == 'general' ? 'selected' : '' }}>Thông báo chung</option>
                                        <option value="maintenance" {{ old('type', $announcement->type) == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                                        <option value="payment" {{ old('type', $announcement->type) == 'payment' ? 'selected' : '' }}>Thanh toán</option>
                                        <option value="event" {{ old('type', $announcement->type) == 'event' ? 'selected' : '' }}>Sự kiện</option>
                                        <option value="emergency" {{ old('type', $announcement->type) == 'emergency' ? 'selected' : '' }}>Khẩn cấp</option>
                                        <option value="rule" {{ old('type', $announcement->type) == 'rule' ? 'selected' : '' }}>Nội quy</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="priority">Mức độ ưu tiên <span class="text-danger">*</span></label>
                                    <select class="form-control @error('priority') is-invalid @enderror" 
                                            id="priority" name="priority" required>
                                        <option value="medium" {{ old('priority', $announcement->priority) == 'medium' ? 'selected' : '' }}>Bình thường</option>
                                        <option value="high" {{ old('priority', $announcement->priority) == 'high' ? 'selected' : '' }}>Cao</option>
                                        <option value="urgent" {{ old('priority', $announcement->priority) == 'urgent' ? 'selected' : '' }}>Khẩn cấp</option>
                                        <option value="low" {{ old('priority', $announcement->priority) == 'low' ? 'selected' : '' }}>Thấp</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="target_audience">Đối tượng <span class="text-danger">*</span></label>
                                    <select class="form-control @error('target_audience') is-invalid @enderror" 
                                            id="target_audience" name="target_audience" required>
                                        <option value="all" {{ old('target_audience', $announcement->target_audience) == 'all' ? 'selected' : '' }}>Tất cả mọi người</option>
                                        <option value="students" {{ old('target_audience', $announcement->target_audience) == 'students' ? 'selected' : '' }}>Sinh viên</option>
                                        <option value="building" {{ old('target_audience', $announcement->target_audience) == 'building' ? 'selected' : '' }}>Theo tòa nhà</option>
                                    </select>
                                    @error('target_audience')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" id="building_select_group" style="display: {{ old('target_audience', $announcement->target_audience) == 'building' ? 'block' : 'none' }};">
                                    <label for="building_id">Chọn tòa nhà</label>
                                    <select class="form-control @error('building_id') is-invalid @enderror" 
                                            id="building_id" name="building_id">
                                        <option value="">-- Chọn tòa nhà --</option>
                                        @foreach($buildings ?? [] as $building)
                                            <option value="{{ $building->id }}" {{ old('building_id', $announcement->target_id) == $building->id ? 'selected' : '' }}>
                                                {{ $building->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('building_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="expires_at">Ngày hết hạn</label>
                                    <input type="date" class="form-control @error('expires_at') is-invalid @enderror" 
                                           id="expires_at" name="expires_at" value="{{ old('expires_at', $announcement->expires_at ? $announcement->expires_at->format('Y-m-d') : '') }}">
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Để trống nếu không có hạn</small>
                                </div>

                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="is_published" 
                                           name="is_published" value="1" {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_published">
                                        Xuất bản ngay lập tức
                                    </label>
                                </div>

                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="send_notification" 
                                           name="send_notification" value="1" {{ old('send_notification', $announcement->send_notification) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="send_notification">
                                        Gửi thông báo
                                    </label>
                                </div>

                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="send_email" 
                                           name="send_email" value="1" {{ old('send_email', $announcement->send_email) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="send_email">
                                        Gửi email
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-outline-secondary">Hủy</a>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line mr-2"></i>Cập nhật thông báo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide building select based on target audience
    document.getElementById('target_audience').addEventListener('change', function() {
        const buildingGroup = document.getElementById('building_select_group');
        if (this.value === 'building') {
            buildingGroup.style.display = 'block';
            document.getElementById('building_id').required = true;
        } else {
            buildingGroup.style.display = 'none';
            document.getElementById('building_id').required = false;
        }
    });
    
    // Trigger on page load if building is selected
    if (document.getElementById('target_audience').value === 'building') {
        document.getElementById('building_select_group').style.display = 'block';
    }
</script>
@endpush
@endsection

