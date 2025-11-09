@extends('layout2.theme')

@section('title', 'Thêm Phòng mới')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-success mb-4">Thêm Phòng mới</h2>

        <form action="{{ route('rooms.store') }}" method="POST" class="col-md-6">
            @csrf

            <div class="mb-3">
                <label class="form-label">Chọn Tòa nhà</label>
                <select name="building_id" id="building_id" class="form-select" required>
                    <option value="">-- Chọn tòa --</option>
                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}">{{ $building->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Chọn Tầng</label>
                <select name="floor_id" id="floor_id" class="form-select" required>
                    <option value="">-- Chọn tầng --</option>
                    @foreach ($floors as $floor)
                        <option value="{{ $floor->id }}">{{ $floor->building->name }} - Tầng {{ $floor->floor_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Số phòng</label>
                <input type="text" name="room_number" class="form-control" required placeholder="VD: P101">
            </div>

            <div class="mb-3">
                <label class="form-label">Sức chứa (người)</label>
                <input type="number" name="capacity" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Giá phòng (VNĐ)</label>
                <input type="number" name="price" class="form-control" min="0" step="1000" required>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
