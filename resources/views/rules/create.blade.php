@extends('layouts.app')

@section('title', 'Thêm nội quy mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('rules.index') }}" class="btn btn-outline-primary">
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
                    <h4 class="mb-0">Thêm nội quy mới</h4>
                </div>
                <form action="{{ route('rules.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           name="title" value="{{ old('title') }}" required>
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group">
                                    <label for="content">Nội dung <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              name="content" rows="10" required>{{ old('content') }}</textarea>
                                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category">Phân loại <span class="text-danger">*</span></label>
                                    <select class="form-control @error('category') is-invalid @enderror" name="category" required>
                                        <option value="general" {{ old('category')=='general'?'selected':'' }}>Chung</option>
                                        <option value="safety" {{ old('category')=='safety'?'selected':'' }}>An toàn</option>
                                        <option value="hygiene" {{ old('category')=='hygiene'?'selected':'' }}>Vệ sinh</option>
                                        <option value="discipline" {{ old('category')=='discipline'?'selected':'' }}>Kỷ luật</option>
                                    </select>
                                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group">
                                    <label for="severity">Mức độ <span class="text-danger">*</span></label>
                                    <select class="form-control @error('severity') is-invalid @enderror" name="severity" required>
                                        <option value="low" {{ old('severity')=='low'?'selected':'' }}>Thấp</option>
                                        <option value="medium" {{ old('severity','medium')=='medium'?'selected':'' }}>Trung bình</option>
                                        <option value="high" {{ old('severity')=='high'?'selected':'' }}>Cao</option>
                                    </select>
                                    @error('severity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group">
                                    <label for="penalty_amount">Mức phạt (VNĐ)</label>
                                    <input type="number" class="form-control @error('penalty_amount') is-invalid @enderror" 
                                           name="penalty_amount" value="{{ old('penalty_amount') }}" min="0" step="1000">
                                    @error('penalty_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted">Để trống nếu không có mức phạt</small>
                                </div>

                                <div class="form-group">
                                    <label for="order">Thứ tự hiển thị</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                           name="order" value="{{ old('order', 0) }}" min="0">
                                    @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" 
                                               name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Đang áp dụng</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="requires_acknowledgment" 
                                               name="requires_acknowledgment" {{ old('requires_acknowledgment') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="requires_acknowledgment">Yêu cầu xác nhận</label>
                                    </div>
                                    <small class="text-muted">Sinh viên cần xác nhận đã đọc</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('rules.index') }}" class="btn btn-outline-secondary mr-2">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line mr-2"></i>Lưu nội quy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
