@extends('layout.theme')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-center text-primary">Quản lý người dùng</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('users.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Thêm người dùng
            </a>
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th width="5%">#</th>
                    <th>Tên người dùng</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th width="20%">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr class="text-center">
                        <td>{{ $index + 1 }}</td>
                        <td class="text-start">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @switch($user->role_id)
                                @case(1)
                                    <span class="badge bg-danger">Admin</span>
                                @break

                                @case(2)
                                    <span class="badge bg-info text-dark">Quản lý sinh viên</span>
                                @break

                                @case(3)
                                    <span class="badge bg-success">Sinh viên</span>
                                @break

                                @default
                                    <span class="badge bg-secondary">Không xác định</span>
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này không?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Chưa có người dùng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endsection
