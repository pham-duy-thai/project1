@extends('layout2.theme')

@section('title', 'Thêm Dịch vụ')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-success mb-4">Thêm Dịch vụ mới</h2>

        <form action="{{ route('services.store') }}" method="POST" class="col-md-6">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tên dịch vụ</label>
                <input type="text" name="name" class="form-control" required
                    placeholder="VD: Tiền điện, Nước, Internet...">
            </div>

            <div class="mb-3">
                <label class="form-label">Giá (VNĐ)</label>
                <input type="number" name="price" class="form-control" min="0" step="1000" required>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu</button>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
