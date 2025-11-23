@extends('layout2.theme')

@section('title', 'Quản lý Tầng')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Danh sách Tầng</h2>
            <a href="{{ route('admin.floors.create') }}" class="btn btn-success">+ Thêm Tầng</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Tòa nhà</th>
                    <th>Số tầng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($floors as $floor)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $floor->building->name }}</td>
                        <td>{{ $floor->floor_number }}</td>
                        <td>
                            <a href="{{ route('admin.floors.edit', $floor->id) }}" class="btn btn-warning btn-sm"><i
                                    class="fas fa-edit"></i> Sửa</a>
                            <form action="{{ route('admin.floors.destroy', $floor->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Xóa tầng này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Chưa có tầng nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
