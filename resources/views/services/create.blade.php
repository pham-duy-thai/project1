@extends('layouts.app')

@section('title', 'Thêm dịch vụ mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('services.index') }}" class="btn btn-outline-primary">
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
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Thêm dịch vụ mới</h4>
                </div>
                <form action="{{ route('services.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Tên dịch vụ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              name="description" rows="5">{{ old('description') }}</textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Loại dịch vụ <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror" name="type" required>
                                                <option value="monthly" {{ old('type')=='monthly'?'selected':'' }}>Theo tháng</option>
                                                <option value="one_time" {{ old('type')=='one_time'?'selected':'' }}>Một lần</option>
                                                <option value="per_use" {{ old('type')=='per_use'?'selected':'' }}>Theo lần sử dụng</option>
                                            </select>
                                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="unit">Đơn vị</label>
                                            <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                                   name="unit" value="{{ old('unit', 'tháng') }}" placeholder="tháng, lần...">
                                            @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="price">Giá dịch vụ (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           name="price" value="{{ old('price') }}" min="0" step="1000" required>
                                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="icon">Icon (Remix Icon class)</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           name="icon" value="{{ old('icon', 'ri-service-line') }}" placeholder="ri-wifi-line">
                                    @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted">Xem tại <a href="https://remixicon.com" target="_blank">remixicon.com</a></small>
                                </div>

                                <div class="form-group">
                                    <label for="max_registrations">Giới hạn đăng ký</label>
                                    <input type="number" class="form-control @error('max_registrations') is-invalid @enderror" 
                                           name="max_registrations" value="{{ old('max_registrations') }}" min="1">
                                    @error('max_registrations')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted">Để trống nếu không giới hạn</small>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" 
                                               name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Đang hoạt động</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="requires_approval" 
                                               name="requires_approval" {{ old('requires_approval') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="requires_approval">Cần duyệt</label>
                                    </div>
                                    <small class="text-muted">Admin phải duyệt đăng ký</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary mr-2">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line mr-2"></i>Lưu dịch vụ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
