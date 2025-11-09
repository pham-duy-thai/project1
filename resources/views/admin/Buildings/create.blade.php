@extends('layout2.theme')

@section('title', 'Thêm Tòa nhà')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-success mb-4">Thêm Tòa nhà mới</h2>

        <form action="{{ route('buildings.store') }}" method="POST" class="col-md-6">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tên Tòa</label>
                <input type="text" name="name" class="form-control" required placeholder="VD: Tòa A">
            </div>
            <div class="mb-3">
                <label class="form-label">Tổng số tầng</label>
                <input type="number" name="total_floors" class="form-control" min="1" required>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu</button>
            <a href="{{ route('buildings.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
