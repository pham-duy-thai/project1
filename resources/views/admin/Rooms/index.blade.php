@extends('layout2.theme')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-3">Danh Sách Phòng</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('rooms.create') }}" class="btn btn-success mb-3">+ Thêm phòng</a>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Tòa</th>
                    <th>Tầng</th>
                    <th>Số phòng</th>
                    <th>Sức chứa</th>
                    <th>Giới tính</th>
                    <th>Giá (VNĐ)</th>
                    <th>Đang ở</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rooms as $room)
                    <tr class="text-center">
                        <td>{{ $room->id }}</td>
                        <td>{{ $room->building->name ?? '-' }}</td>
                        <td>{{ $room->floor_number }}</td>
                        <td>{{ $room->room_number }}</td>
                        <td>{{ $room->capacity }}</td>
                        <td>{{ ucfirst($room->gender) }}</td>
                        <td>{{ number_format($room->price, 0, ',', '.') }}</td>
                        <td>{{ $room->current_occupancy }}</td>
                        <td>
                            <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Bạn có chắc muốn xóa phòng này không?')">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Chưa có phòng nào được thêm.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
