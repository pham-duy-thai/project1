@extends('layouts.app')

@section('title', 'Chỉnh sửa tòa nhà')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">Chỉnh sửa tòa nhà</h4>
                    <p class="mb-0 text-muted">Cập nhật thông tin tòa nhà</p>
                </div>
                <a href="{{ route('buildings.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line mr-2"></i>Quay lại
                </a>
            </div>
        </div>

        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('buildings.update', $building) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="dormitory_id">Ký túc xá <span class="text-danger">*</span></label>
                            <select class="form-control @error('dormitory_id') is-invalid @enderror" 
                                    id="dormitory_id" name="dormitory_id" required>
                                @foreach($dormitories as $dormitory)
                                    <option value="{{ $dormitory->id }}" 
                                            {{ old('dormitory_id', $building->dormitory_id) == $dormitory->id ? 'selected' : '' }}>
                                        {{ $dormitory->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dormitory_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Tên tòa nhà <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $building->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Mã tòa nhà</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $building->code) }}">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Giới tính <span class="text-danger">*</span></label>
                                    <select class="form-control @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender" required>
                                        <option value="male" {{ old('gender', $building->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender', $building->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="mixed" {{ old('gender', $building->gender) == 'mixed' ? 'selected' : '' }}>Cả hai</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label for="floors">Số tầng <span class="text-danger">*</span></label>
                                     <input type="number" class="form-control @error('floors') is-invalid @enderror" 
                                         id="floors" name="floors" 
                                         value="{{ old('floors', $building->floors) }}" 
                                           min="1" max="50" required>
                                    @error('floors')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $building->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', $building->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Kích hoạt tòa nhà</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('buildings.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line mr-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line mr-2"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
