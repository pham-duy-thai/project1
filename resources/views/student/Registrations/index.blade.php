@extends('layou1.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>📜 Lịch sử Đăng ký Phòng</h2>
            <a href="{{ route('student.registrations.create') }}" class="btn btn-primary">+ Đăng ký phòng mới</a>
        </div>
        <hr>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>Mã ĐK</th>
                        <th>Phòng</th>
                        <th>Tòa nhà</th>
                        <th>Ngày đăng ký</th>
                        <th>Ngày kết thúc</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                        <tr>
                            <td>{{ $registration->id }}</td>
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
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Bạn chưa có đăng ký nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
