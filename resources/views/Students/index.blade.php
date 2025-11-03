@extends('layout.theme')

@section('content')
    <div class="container mt-4">
        <h2>Danh sách sinh viên</h2>
        <a href="{{ route('students.create') }}" class="btn btn-primary mb-2">Thêm mới</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mã SV</th>
                    <th>Tên</th>
                    <th>Giới tính</th>
                    <th>Lớp</th>
                    <th>Điện thoại</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->student_code }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->gender }}</td>
                        <td>{{ $student->class }}</td>
                        <td>{{ $student->phone }}</td>
                        <td>{{ $student->status }}</td>
                        <td>
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm">Xem</a>
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
