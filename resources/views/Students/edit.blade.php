@extends('layout.theme')

@section('content')
    <div class="container mt-4">
        <h2>Sửa sinh viên</h2>
        <form action="{{ route('students.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Mã sinh viên</label>
                <input type="text" name="student_code" class="form-control" value="{{ $student->student_code }}" required>
            </div>

            <div class="mb-3">
                <label>Tên</label>
                <input type="text" name="name" class="form-control" value="{{ $student->name }}" required>
            </div>

            <div class="mb-3">
                <label>Giới tính</label>
                <select name="gender" class="form-control" required>
                    <option value="Nam" {{ $student->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ $student->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Lớp</label>
                <input type="text" name="class" class="form-control" value="{{ $student->class }}" required>
            </div>

            <div class="mb-3">
                <label>Điện thoại</label>
                <input type="text" name="phone" class="form-control" value="{{ $student->phone }}">
            </div>

            <div class="mb-3">
                <label>Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="active" {{ $student->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $student->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
