@extends('layout2.theme')

@section('content')
    <div class="container">
        <h2>📋 Quản lý Đăng ký Phòng</h2>
        <hr>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Mã ĐK</th>
                        <th>Sinh viên</th>
                        <th>Mã SV</th>
                        <th>Phòng</th>
                        <th>Tòa nhà</th>
                        <th>Ngày đăng ký</th>
                        <th>Ngày kết thúc</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                        <tr>
                            <td>{{ $registration->id }}</td>
                            <td>{{ $registration->student->name ?? 'N/A' }}</td>
                            <td>{{ $registration->student->student_code ?? 'N/A' }}</td>
                            <td>{{ $registration->room->room_number ?? 'N/A' }}</td>
                            <td>{{ $registration->room->building->name ?? 'N/A' }}</td>
                            <td>{{ $registration->registration_date->format('d/m/Y') }}</td>
                            <td>{{ $registration->end_date ? $registration->end_date->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                @switch($registration->status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                    @break

                                    @case('approved')
                                        <span class="badge bg-success">Đã duyệt</span>
                                    @break

                                    @case('rejected')
                                        <span class="badge bg-danger">Từ chối</span>
                                    @break

                                    @case('completed')
                                        <span class="badge bg-secondary">Hoàn thành</span>
                                    @break
                                @endswitch
                            </td>
                            <td>
                                @if ($registration->status == 'pending')
                                    <form action="{{ route('admin.registrations.approve', $registration) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Xác nhận duyệt đăng ký này?')">
                                            ✓ Duyệt
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.registrations.reject', $registration) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Xác nhận từ chối?')">
                                            ✗ Từ chối
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">Đã xử lý</span>
                                @endif

                                <a href="{{ route('admin.registrations.show', $registration) }}"
                                    class="btn btn-sm btn-info">👁 Xem</a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Không có đăng ký nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
