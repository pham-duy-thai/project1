@extends('layout1.app')

@section('title', 'Đăng ký Phòng')

@section('content')
    <div class="container mt-4">
        <h3 class="text-primary mb-4">Đăng ký Phòng Mới</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('registrations.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Chọn phòng</label>
                <select name="room_id" class="form-select" required>
                    <option value="">-- Chọn phòng --</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">
                            {{ $room->building->name ?? '' }} - Phòng {{ $room->room_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Đăng ký</button>
            <a href="{{ route('registrations.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
