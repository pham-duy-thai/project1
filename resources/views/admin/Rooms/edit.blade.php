@extends('layout2.theme')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Chỉnh sửa phòng</h4>
            </div>

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Building --}}
                    <div class="mb-3">
                        <label class="form-label">Tòa nhà</label>
                        <select name="building_id" class="form-control">
                            <option value="">-- Chọn tòa nhà --</option>
                            @foreach ($buildings as $building)
                                <option value="{{ $building->id }}"
                                    {{ $building->id == $room->building_id ? 'selected' : '' }}>
                                    {{ $building->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Floor number --}}
                    <div class="mb-3">
                        <label class="form-label">Số tầng</label>
                        <input type="number" name="floor_number" class="form-control"
                            value="{{ old('floor_number', $room->floor_number) }}" min="1" required>
                    </div>

                    {{-- Room number --}}
                    <div class="mb-3">
                        <label class="form-label">Số phòng</label>
                        <input type="text" name="room_number" class="form-control"
                            value="{{ old('room_number', $room->room_number) }}" required>
                    </div>

                    {{-- Capacity --}}
                    <div class="mb-3">
                        <label class="form-label">Sức chứa</label>
                        <input type="number" name="capacity" class="form-control"
                            value="{{ old('capacity', $room->capacity) }}" min="1" required>
                    </div>

                    {{-- Gender --}}
                    <div class="mb-3">
                        <label class="form-label">Giới tính</label>
                        <select name="gender" class="form-control" required>
                            <option value="nam" {{ $room->gender == 'nam' ? 'selected' : '' }}>Nam</option>
                            <option value="nu" {{ $room->gender == 'nu' ? 'selected' : '' }}>Nữ</option>
                        </select>
                    </div>

                    {{-- Current Occupancy --}}
                    <div class="mb-3">
                        <label class="form-label">Số lượng đang ở</label>
                        <input type="number" name="current_occupancy" class="form-control"
                            value="{{ old('current_occupancy', $room->current_occupancy) }}" min="0"
                            max="{{ $room->capacity }}">
                    </div>

                    {{-- Price --}}
                    <div class="mb-3">
                        <label class="form-label">Giá phòng (VND)</label>
                        <input type="number" step="0.01" name="price" class="form-control"
                            value="{{ old('price', $room->price) }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">Hủy</a>
                </form>

            </div>
        </div>
    </div>
@endsection
