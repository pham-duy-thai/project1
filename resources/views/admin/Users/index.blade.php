@extends('layout2.theme')

@section('title', 'Quản lý tài khoản')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Danh sách tài khoản</h2>
            <a href="{{ route('users.create') }}" class="btn btn-success">+ Thêm tài khoản</a>
        </div>

        {{-- Thông báo thành công --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Thông báo lỗi --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            {{-- Nếu là admin@gmail.com thì hiển thị "Admin", ngược lại là "Sinh viên" --}}
                            @if ($user->email === 'admin@gmail.com')
                                <span class="badge bg-danger">Admin</span>
                            @else
                                <span class="badge bg-primary">Sinh viên</span>
                            @endif
                        </td>
                        <td>
                            @if ($user->email !== 'admin@gmail.com')
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Xóa tài khoản này?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">Không thể xóa/sửa admin</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Chưa có tài khoản nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
