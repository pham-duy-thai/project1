@extends('layout2.theme')

@section('title', 'Cập nhật sinh viên')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-warning mb-4">Cập nhật thông tin sinh viên</h2>

        <form action="{{ route('students.update', $student->id) }}" method="POST" class="col-md-6">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="student_code" class="form-label">Mã sinh viên</label>
                <input type="text" name="student_code" id="student_code" class="form-control"
                    value="{{ old('student_code', $student->student_code) }}" required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Họ và tên</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $student->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Giới tính</label>
                <select name="gender" id="gender" class="form-select">
                    <option value="Nam" {{ $student->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ $student->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="class" class="form-label">Lớp</label>
                <input type="text" name="class" id="class" class="form-control"
                    value="{{ old('class', $student->class) }}">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" name="phone" id="phone" class="form-control"
                    value="{{ old('phone', $student->phone) }}">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" name="address" id="address" class="form-control"
                    value="{{ old('address', $student->address) }}">
            </div>

            <div class="mb-3">
                <label for="date_of_birth" class="form-label">Ngày sinh</label>
                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
                    value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select name="status" id="status" class="form-select">
                    <option value="active" {{ $student->status == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="inactive" {{ $student->status == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Cập nhật</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
