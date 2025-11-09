@extends('layout2.theme')

@section('title', 'Cập nhật Dịch vụ')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-warning mb-4">Cập nhật Dịch vụ</h2>

        <form action="{{ route('services.update', $service->id) }}" method="POST" class="col-md-6">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tên dịch vụ</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $service->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Giá (VNĐ)</label>
                <input type="number" name="price" class="form-control" min="0" step="1000"
                    value="{{ old('price', $service->price) }}" required>
            </div>

            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Cập nhật</button>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
