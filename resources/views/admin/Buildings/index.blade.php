@extends('layout2.theme')

@section('title', 'Quản lý Tòa nhà')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Danh sách Tòa nhà</h2>
            <a href="{{ route('buildings.create') }}" class="btn btn-success">+ Thêm Tòa</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Tên tòa</th>
                    <th>Số tầng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($buildings as $building)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $building->name }}</td>
                        <td>{{ $building->total_floors }}</td>
                        <td>
                            <a href="{{ route('buildings.edit', $building->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('buildings.destroy', $building->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Xóa tòa này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Chưa có tòa nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
