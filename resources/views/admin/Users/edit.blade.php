@extends('layout2.theme')

@section('title', 'Cập nhật tài khoản')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-warning mb-4">Cập nhật tài khoản</h2>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="col-md-6">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Tên người dùng</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control"
                    value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label for="role_id" class="form-label">Vai trò</label>
                <select id="role_id" name="role_id" class="form-select" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Cập nhật</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
