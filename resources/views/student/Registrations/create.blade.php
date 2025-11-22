@extends('layout1.app')

@section('content')
    <div class="container">
        <h2>📝 Đăng ký Phòng Ký túc xá</h2>
        <hr>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('student.registrations.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="room_id" class="form-label">Chọn phòng <span class="text-danger">*</span></label>
                <select name="room_id" id="room_id" class="form-control @error('room_id') is-invalid @enderror" required>
                    <option value="">-- Chọn phòng --</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                            Phòng {{ $room->room_number }} - Tòa {{ $room->building->name ?? 'N/A' }}
                            (Còn {{ $room->capacity }} chỗ)
                        </option>
                    @endforeach
                </select>
                @error('room_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="registration_date" class="form-label">Ngày bắt đầu <span
                            class="text-danger">*</span></label>
                    <input type="date" name="registration_date" id="registration_date"
                        class="form-control @error('registration_date') is-invalid @enderror"
                        value="{{ old('registration_date') }}" required>
                    @error('registration_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">Ngày kết thúc dự kiến <span
                            class="text-danger">*</span></label>
                    <input type="date" name="end_date" id="end_date"
                        class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}"
                        required>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="alert alert-info">
                <strong>Lưu ý:</strong> Sau khi gửi, yêu cầu của bạn sẽ ở trạng thái "Chờ duyệt".
                Vui lòng kiểm tra thường xuyên để biết kết quả.
            </div>

            <button type="submit" class="btn btn-primary btn-lg">Gửi yêu cầu đăng ký</button>
            <a href="{{ route('student.registrations.index') }}" class="btn btn-secondary btn-lg">Hủy</a>
        </form>
    </div>
@endsection
