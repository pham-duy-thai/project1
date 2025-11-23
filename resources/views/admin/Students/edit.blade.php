@extends('layout2.theme')

@section('title', 'Chỉnh sửa sinh viên')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary mb-4">Chỉnh sửa sinh viên</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Mã sinh viên</label>
                    <input type="text" name="student_code" class="form-control"
                        value="{{ old('student_code', $student->student_code) }}" required>
                </div>
                <div class="col-md-6">
                    <label>Họ tên</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $student->name) }}"
                        required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Giới tính</label>
                    <select name="gender" class="form-select" required>
                        <option value="Nam" @if ($student->gender == 'Nam') selected @endif>Nam</option>
                        <option value="Nữ" @if ($student->gender == 'Nữ') selected @endif>Nữ</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Lớp</label>
                    <input type="text" name="class" class="form-control" value="{{ old('class', $student->class) }}"
                        required>
                </div>
                <div class="col-md-4">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}">
                </div>
            </div>

            <div class="mb-3">
                <label>Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="active" @if ($student->status == 'active') selected @endif>Đang hoạt động</option>
                    <option value="inactive" @if ($student->status == 'inactive') selected @endif>Ngừng</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
