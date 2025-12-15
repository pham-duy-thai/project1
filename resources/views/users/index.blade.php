@extends('layouts.app')

@section('title', 'Quản lý tài khoản')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">Quản lý tài khoản</h4>
                    <p class="mb-0 text-muted">Danh sách người dùng hệ thống</p>
                </div>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="ri-add-line mr-2"></i>Tạo tài khoản
                </a>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="role">Vai trò</label>
                                <select class="form-control" name="role">
                                    <option value="">Tất cả</option>
                                    <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Quản trị</option>
                                    <option value="student" {{ request('role')=='student'?'selected':'' }}>Sinh viên</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="status">Trạng thái</label>
                                <select class="form-control" name="status">
                                    <option value="">Tất cả</option>
                                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Đang hoạt động</option>
                                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Vô hiệu hóa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-md-0">
                                <label for="search">Tìm kiếm</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tên, email, SĐT...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block"><i class="ri-search-line mr-2"></i>Lọc</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="col-lg-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        </div>
        @endif

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-spacing mb-0">
                            <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Vai trò</th>
                                    <th>SĐT</th>
                                    <th>Trạng thái</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-40 rounded-circle bg-primary-light mr-2">
                                                <span class="font-size-14 text-primary">{{ strtoupper(substr($user->name,0,2)) }}</span>
                                            </div>
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                <br><small class="text-muted">{{ $user->created_at?->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge badge-{{ $user->role==='admin'?'danger':'secondary' }}">{{ $user->role_label }}</span></td>
                                    <td>{{ $user->phone ?? '—' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $user->is_active?'success':'secondary' }}">{{ $user->is_active?'Đang hoạt động':'Vô hiệu hóa' }}</span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-info mr-1"><i class="ri-edit-line"></i></a>
                                        <form action="{{ route('users.toggle', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $user->is_active?'warning':'success' }}">
                                                <i class="ri-shield-user-line mr-1"></i>{{ $user->is_active?'Vô hiệu hóa':'Kích hoạt' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa tài khoản {{ $user->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="ri-user-settings-line" style="font-size: 64px; color: #ccc;"></i>
                                        <h5 class="mt-3">Không có người dùng</h5>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                <div class="card-footer">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
