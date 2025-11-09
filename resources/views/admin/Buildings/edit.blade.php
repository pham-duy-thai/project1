@extends('layout2.theme')

@section('title', 'Cập nhật Tòa nhà')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-warning mb-4">Cập nhật Tòa nhà</h2>

        <form action="{{ route('buildings.update', $building->id) }}" method="POST" class="col-md-6">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Tên Tòa</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $building->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tổng số tầng</label>
                <input type="number" name="total_floors" class="form-control"
                    value="{{ old('total_floors', $building->total_floors) }}" min="1" required>
            </div>

            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Cập nhật</button>
            <a href="{{ route('buildings.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
