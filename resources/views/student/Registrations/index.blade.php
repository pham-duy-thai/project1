@extends('layout1.app')

@section('title', 'Đăng ký Phòng Ký túc xá')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-primary">Đăng ký phòng</h3>
            <a href="{{ route('registrations.create') }}" class="btn btn-success">+ Đăng ký mới</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Phòng</th>
                    <th>Tòa</th>
                    <th>Ngày đăng ký</th>
                    <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $r)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $r->room->room_number ?? '—' }}</td>
                        <td class="text-center">{{ $r->room->building->name ?? '—' }}</td>
                        <td class="text-center">
                            {{ $r->registration_date ? \Carbon\Carbon::parse($r->registration_date)->format('d/m/Y') : '—' }}
                        </td>
                        <td class="text-center">
                            {{ $r->end_date ? \Carbon\Carbon::parse($r->end_date)->format('d/m/Y') : '—' }}</td>
                        <td class="text-center">
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'approved' => 'primary',
                                    'active' => 'success',
                                    'rejected' => 'danger',
                                    'completed' => 'secondary',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$r->status] ?? 'secondary' }}">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if ($r->status === 'pending')
                                <form action="{{ route('registrations.destroy', $r->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Bạn có chắc muốn hủy đăng ký này?')">
                                        <i class="fas fa-times"></i> Hủy
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">—</span>
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
