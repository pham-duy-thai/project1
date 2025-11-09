@extends('layout1.app')

@section('title', 'Đăng ký phòng mới')

@section('content')
    <h3 class="text-success mb-4">Đăng ký phòng ở ký túc xá</h3>

    <form action="{{ route('student.registrations.store') }}" method="POST" class="col-md-6">
        @csrf

        <div class="mb-3">
            <label class="form-label">Chọn Phòng</label>
            <select name="room_id" class="form-select" required>
                <option value="">-- Chọn phòng trống --</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">
                        {{ $room->room_number }} - {{ $room->floor->building->name }}
                        ({{ $room->current_occupancy }}/{{ $room->capacity }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Ngày bắt đầu</label>
            <input type="date" name="registration_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ngày kết thúc (dự kiến)</label>
            <input type="date" name="end_date" class="form-control">
        </div>

        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Gửi đăng ký</button>
        <a href="{{ route('student.registrations') }}" class="btn btn-secondary">Hủy</a>
    </form>
@endsection
