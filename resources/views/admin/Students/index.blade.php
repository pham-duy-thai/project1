@extends('layout2.theme')

@section('title', 'Danh sách sinh viên')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Danh sách sinh viên</h2>
            <a href="{{ route('students.create') }}" class="btn btn-success">+ Thêm sinh viên</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Mã SV</th>
                    <th>Họ tên</th>
                    <th>Giới tính</th>
                    <th>Lớp</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Ngày sinh</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->student_code }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->gender }}</td>
                        <td>{{ $student->class }}</td>
                        <td>{{ $student->phone }}</td>
                        <td>{{ $student->address ?? '-' }}</td>
                        <td>{{ $student->date_of_birth ? $student->date_of_birth->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Xóa sinh viên này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">Chưa có dữ liệu sinh viên</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
