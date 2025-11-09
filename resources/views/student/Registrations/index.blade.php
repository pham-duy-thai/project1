@extends('layout1.app')

@section('title', 'Đăng ký phòng của tôi')

@section('content')
    <h3 class="text-primary mb-3">Lịch sử đăng ký phòng</h3>

    <table class="table table-bordered align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>#</th>
                <th>Phòng</th>
                <th>Ngày đăng ký</th>
                <th>Ngày kết thúc</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $reg)
                <tr class="text-center">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $reg->room->room_number }} - {{ $reg->room->floor->building->name }}</td>
                    <td>{{ $reg->registration_date }}</td>
                    <td>{{ $reg->end_date ?? '—' }}</td>
                    <td>
                        <span class="badge bg-{{ $reg->status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($reg->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Chưa có đăng ký nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('student.registrations.create') }}" class="btn btn-success mt-3">+ Đăng ký phòng mới</a>
@endsection
