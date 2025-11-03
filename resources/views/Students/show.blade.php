@extends('layout.theme')

@section('content')
    <div class="container mt-4">
        <h2>Chi tiết sinh viên</h2>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $student->id }}</td>
            </tr>
            <tr>
                <th>Mã SV</th>
                <td>{{ $student->student_code }}</td>
            </tr>
            <tr>
                <th>Tên</th>
                <td>{{ $student->name }}</td>
            </tr>
            <tr>
                <th>Giới tính</th>
                <td>{{ $student->gender }}</td>
            </tr>
            <tr>
                <th>Lớp</th>
                <td>{{ $student->class }}</td>
            </tr>
            <tr>
                <th>Điện thoại</th>
                <td>{{ $student->phone }}</td>
            </tr>
            <tr>
                <th>Trạng thái</th>
                <td>{{ $student->status }}</td>
            </tr>
        </table>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
@endsection
