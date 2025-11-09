@extends('layout2.theme')

@section('title', 'Quản lý Hợp đồng')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Danh sách Hợp đồng</h2>
            <a href="{{ route('contracts.create') }}" class="btn btn-success">+ Tạo Hợp đồng</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Sinh viên</th>
                    <th>Phòng</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Tổng tiền (VNĐ)</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contracts as $contract)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $contract->roomRegistration->student->name }}</td>
                        <td>{{ $contract->roomRegistration->room->room_number }} -
                            {{ $contract->roomRegistration->room->floor->building->name }}</td>
                        <td>{{ $contract->start_date }}</td>
                        <td>{{ $contract->end_date }}</td>
                        <td>{{ number_format($contract->total_amount, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Xóa hợp đồng này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Chưa có hợp đồng nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
