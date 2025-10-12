<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin phòng</title>
    <!-- Link Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">

    <h2 class="mb-4">Sửa thông tin phòng</h2>
    <form action="{{ route('rooms.update', $room->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Tên phòng:</label>
            <div class="col-sm-10">
                <input type="text" name="name" value="{{ $room->name }}" class="form-control" required>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Sức chứa:</label>
            <div class="col-sm-10">
                <input type="number" name="capacity" value="{{ $room->capacity }}" class="form-control" required>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Trạng thái:</label>
            <div class="col-sm-10">
                <select name="status" class="form-select" required>
                    <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Còn trống</option>
                    <option value="full" {{ $room->status == 'full' ? 'selected' : '' }}>Đã đầy</option>
                    <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>Đang bảo trì</option>
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="offset-sm-2 col-sm-10">
                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </div>

    </form>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
