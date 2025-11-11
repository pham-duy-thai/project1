@extends('layout2.theme')

@section('title', 'Quản lý đăng ký phòng')

@section('content')
    <div class="container-fluid mt-4">
        <h3 class="text-primary mb-4">Danh sách Đăng ký Phòng</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Sinh viên</th>
                    <th>Phòng</th>
                    <th>Tòa</th>
                    <th>Ngày đăng ký</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $r)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $r->student->name ?? '—' }}</td>
                        <td class="text-center">{{ $r->room->room_number ?? '—' }}</td>
                        <td class="text-center">{{ $r->room->building->name ?? '—' }}</td>
                        <td class="text-center">
                            {{ $r->registration_date ? \Carbon\Carbon::parse($r->registration_date)->format('d/m/Y') : '—' }}
                        </td>
                        <td class="text-center">
                            @php
                                $colors = [
                                    'pending' => 'warning',
                                    'approved' => 'primary',
                                    'active' => 'success',
                                    'rejected' => 'danger',
                                    'completed' => 'secondary',
                                ];
                            @endphp
                            <span class="badge bg-{{ $colors[$r->status] ?? 'secondary' }}">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.registrations.show', $r->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>

                            @if ($r->status === 'pending')
                                <a href="{{ url('admin/registrations/update-status/' . $r->id . '/approved') }}"
                                    class="btn btn-success btn-sm" onclick="return confirm('Duyệt đăng ký này?')">
                                    <i class="fas fa-check"></i> Duyệt
                                </a>
                                <a href="{{ url('admin/registrations/update-status/' . $r->id . '/rejected') }}"
                                    class="btn btn-danger btn-sm" onclick="return confirm('Từ chối đăng ký này?')">
                                    <i class="fas fa-times"></i> Từ chối
                                </a>
                            @elseif ($r->status === 'approved')
                                <a href="{{ url('admin/registrations/update-status/' . $r->id . '/active') }}"
                                    class="btn btn-primary btn-sm"
                                    onclick="return confirm('Chuyển sang trạng thái đang ở?')">
                                    <i class="fas fa-door-open"></i> Kích hoạt
                                </a>
                            @elseif ($r->status === 'active')
                                <a href="{{ url('admin/registrations/end/' . $r->id) }}" class="btn btn-secondary btn-sm"
                                    onclick="return confirm('Kết thúc hợp đồng này?')">
                                    <i class="fas fa-sign-out-alt"></i> Kết thúc
                                </a>
                            @endif

                            <form action="{{ route('registrations.destroy', $r->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Xóa đăng ký này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
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
