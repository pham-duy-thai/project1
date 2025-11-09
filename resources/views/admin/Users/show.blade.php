{{-- @extends('layout.theme')

@section('title', 'Chi tiết người dùng')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4>Thông tin chi tiết người dùng</h4>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>Họ và tên</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Vai trò (Role)</th>
                        <td>{{ $user->role->name ?? 'Chưa có' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật lần cuối</th>
                        <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

                <div class="mt-3">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        ← Quay lại danh sách
                    </a>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                        ✏️ Sửa thông tin
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
