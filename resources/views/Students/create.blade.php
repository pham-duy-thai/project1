@extends('layout.theme')

@section('content')
    <div class="container mt-4">
        <h2>Thêm sinh viên</h2>
        <form action="{{ route('students.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Mã sinh viên</label>
                <input type="text" name="student_code" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Tên</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Giới tính</label>
                <select name="gender" class="form-control" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Lớp</label>
                <input type="text" name="class" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Điện thoại</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
