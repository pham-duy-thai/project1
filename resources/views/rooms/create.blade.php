<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý phòng</title>
    <!-- Link Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

    <div class="container">

        <h2 class="mb-4">Thêm phòng mới</h2>
        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Tên phòng:</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Sức chứa:</label>
                <div class="col-sm-10">
                    <input type="number" name="capacity" class="form-control" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Giá phòng (VNĐ):</label>
                <div class="col-sm-10">
                    <input type="number" name="price" value="{{ old('price') }}" class="form-control" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Trạng thái:</label>
                <div class="col-sm-10">
                    <select name="status" class="form-select" required>
                        <option value="available">Còn trống</option>
                        <option value="full">Đã đầy</option>
                        <option value="maintenance">Đang bảo trì</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="offset-sm-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </form>

        <h2 class="mb-3">Danh sách phòng</h2>
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
                <tr>
                    <td>Phòng A101</td>
                    <td>4</td>
                    <td>2,600,000</td>
                    <td>Còn trống</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="#" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
                <tr>
                    <td>Phòng B202</td>
                    <td>3</td>
                    <td>2,000,000</td>
                    <td>Đang bảo trì</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="#" class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
                <!-- Thêm các phòng khác -->
            </tbody>
        </table>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
