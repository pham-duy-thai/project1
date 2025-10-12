<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách phòng</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">

    <h2 class="mb-3">Danh sách phòng</h2>
    <a href="{{ route('rooms.create') }}" class="btn btn-primary mb-3">Thêm phòng</a>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Tên phòng</th>
                <th>Sức chứa</th>
                <th>Giá tiền (VNĐ)</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
            <tr>
                <td>{{ $room->name }}</td>
                <td>{{ $room->capacity }}</td>
                <td>{{ number_format($room->price, 0, ',', '.') }} VNĐ</td>
                <td>
                    @if($room->students_count >= $room->capacity)
                        <span class="text-danger">Hết chỗ</span>
                    @elseif($room->status == 'maintenance')
                        <span class="text-warning">Đang bảo trì</span>
                    @else
                        <span class="text-success">Còn chỗ</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
