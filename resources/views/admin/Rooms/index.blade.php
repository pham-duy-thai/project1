@extends('layout2.theme')

@section('title', 'Quản lý Phòng')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Danh sách Phòng</h2>
            <a href="{{ route('rooms.create') }}" class="btn btn-success">+ Thêm Phòng</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Tòa</th>
                    <th>Tầng</th>
                    <th>Số phòng</th>
                    <th>Sức chứa</th>
                    <th>Đang ở</th>
                    <th>Giá (VNĐ)</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $room->floor->building->name }}</td>
                        <td>{{ $room->floor->floor_number }}</td>
                        <td>{{ $room->room_number }}</td>
                        <td>{{ $room->capacity }}</td>
                        <td>{{ $room->current_occupancy }}</td>
                        <td>{{ number_format($room->price, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Xóa phòng này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Chưa có dữ liệu phòng</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
