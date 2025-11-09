@extends('layout2.theme')

@section('title', 'Quản lý Dịch vụ')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Danh sách Dịch vụ</h2>
            <a href="{{ route('services.create') }}" class="btn btn-success">+ Thêm Dịch vụ</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Tên dịch vụ</th>
                    <th>Giá (VNĐ)</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $service->name }}</td>
                        <td>{{ number_format($service->price, 0, ',', '.') }}</td>
                        <td>{{ $service->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Xóa dịch vụ này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Chưa có dịch vụ nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
