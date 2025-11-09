@extends('layout2.theme')

@section('title', 'Thêm sinh viên')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-success mb-4">Thêm sinh viên mới</h2>

        <form action="{{ route('students.store') }}" method="POST" class="col-md-6">
            @csrf
            <div class="mb-3">
                <label for="student_code" class="form-label">Mã sinh viên</label>
                <input type="text" name="student_code" id="student_code" class="form-control" required
                    placeholder="VD: SV001">
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Họ và tên</label>
                <input type="text" name="name" id="name" class="form-control" required
                    placeholder="Nhập họ tên sinh viên">
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Giới tính</label>
                <select name="gender" id="gender" class="form-select" required>
                    <option value="">-- Chọn giới tính --</option>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="class" class="form-label">Lớp</label>
                <input type="text" name="class" id="class" class="form-control" placeholder="VD: DHCNTT17A">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" name="phone" id="phone" class="form-control" placeholder="VD: 0901234567">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" name="address" id="address" class="form-control" placeholder="VD: Hà Nội">
            </div>

            <div class="mb-3">
                <label for="date_of_birth" class="form-label">Ngày sinh</label>
                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
