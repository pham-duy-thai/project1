@extends('layout2.theme')

@section('title', 'Thêm sinh viên')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary mb-4">Thêm sinh viên</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.students.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Mã sinh viên</label>
                    <input type="text" name="student_code" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Họ tên</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Giới tính</label>
                    <select name="gender" class="form-select" required>
                        <option value="">--Chọn giới tính--</option>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Lớp</label>
                    <input type="text" name="class" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label>Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="active">Đang hoạt động</option>
                    <option value="inactive">Ngừng</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
