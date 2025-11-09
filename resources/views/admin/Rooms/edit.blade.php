@extends('layout2.theme')

@section('title', 'Cập nhật Phòng')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-warning mb-4">Cập nhật Phòng</h2>

        <form action="{{ route('rooms.update', $room->id) }}" method="POST" class="col-md-6">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tầng</label>
                <select name="floor_id" class="form-select" required>
                    @foreach ($floors as $floor)
                        <option value="{{ $floor->id }}" {{ $room->floor_id == $floor->id ? 'selected' : '' }}>
                            {{ $floor->building->name }} - Tầng {{ $floor->floor_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Số phòng</label>
                <input type="text" name="room_number" class="form-control"
                    value="{{ old('room_number', $room->room_number) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Sức chứa (người)</label>
                <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $room->capacity) }}"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Số người hiện tại</label>
                <input type="number" name="current_occupancy" class="form-control"
                    value="{{ old('current_occupancy', $room->current_occupancy) }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Giá phòng (VNĐ)</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $room->price) }}" required>
            </div>

            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Cập nhật</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
