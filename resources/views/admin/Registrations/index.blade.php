@extends('layout2.theme')

@section('title', 'Quản lý Đăng ký phòng')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-primary mb-3">Danh sách Đăng ký phòng</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Sinh viên</th>
                    <th>Phòng</th>
                    <th>Ngày đăng ký</th>
                    <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $reg)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $reg->student->name }} ({{ $reg->student->student_code }})</td>
                        <td>{{ $reg->room->room_number }} - {{ $reg->room->floor->building->name }}</td>
                        <td>{{ $reg->registration_date }}</td>
                        <td>{{ $reg->end_date ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $reg->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($reg->status) }}
                            </span>
                        </td>
                        <td>
                            @if ($reg->status == 'active')
                                <form
                                    action="{{ route('registrations.updateStatus', ['id' => $reg->id, 'status' => 'cancelled']) }}"
                                    method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-ban"></i> Hủy
                                    </button>
                                </form>
                            @else
                                <form
                                    action="{{ route('registrations.updateStatus', ['id' => $reg->id, 'status' => 'active']) }}"
                                    method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Duyệt
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Chưa có đăng ký nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
