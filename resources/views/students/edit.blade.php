<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2 class="mb-4">Sửa thông tin sinh viên</h2>

<form action="{{ route('students.update', $student->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Tên:</label>
        <div class="col-sm-10">
            <input type="text" name="name" value="{{ $student->name }}" class="form-control" required>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Mã sinh viên:</label>
        <div class="col-sm-10">
            <input type="text" name="student_code" value="{{ $student->student_code }}" class="form-control" required>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Số điện thoại:</label>
        <div class="col-sm-10">
            <input type="text" name="phone" value="{{ $student->phone }}" class="form-control" required>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Phòng ở:</label>
        <div class="col-sm-10">
            <select name="room_number" class="form-select" required>
                @foreach($rooms as $room)
                    <option value="{{ $room->name }}" 
                        {{ $student->room_number == $room->name ? 'selected' : '' }}
                        {{ $room->students_count >= $room->capacity && $student->room_number != $room->name ? 'disabled' : '' }}>
                        {{ $room->name }} ({{ $room->students_count }}/{{ $room->capacity }})
                        @if($room->students_count >= $room->capacity) - Hết chỗ @endif
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <div class="offset-sm-2 col-sm-10">
            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">⬅️ Quay lại</a>
        </div>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
