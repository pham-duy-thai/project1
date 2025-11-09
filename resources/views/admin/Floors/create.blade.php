@extends('layout2.theme')

@section('title', 'Thêm Tầng')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-success mb-4">Thêm tầng mới</h2>

        <form action="{{ route('floors.store') }}" method="POST" class="col-md-6">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tòa nhà</label>
                <select name="building_id" class="form-select" required>
                    <option value="">-- Chọn Tòa nhà --</option>
                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}">{{ $building->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Số tầng</label>
                <input type="number" name="floor_number" class="form-control" min="1" required>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu</button>
            <a href="{{ route('floors.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
