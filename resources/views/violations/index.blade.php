@extends('layouts.app')

@section('title', 'Quản lý vi phạm')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Quản lý vi phạm</h4>
                    <p class="mb-0 text-muted">Danh sách các vi phạm của sinh viên</p>
                </div>
                <a href="{{ route('violations.create') }}" class="btn btn-primary">
                    <i class="ri-add-line mr-1"></i> Ghi nhận vi phạm
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('violations.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Tìm kiếm sinh viên</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Mã SV hoặc tên..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="violation_type">Loại vi phạm</label>
                            <select name="violation_type" id="violation_type" class="form-control">
                                <option value="">Tất cả</option>
                                <option value="noise" {{ request('violation_type') == 'noise' ? 'selected' : '' }}>Gây ồn</option>
                                <option value="smoking" {{ request('violation_type') == 'smoking' ? 'selected' : '' }}>Hút thuốc</option>
                                <option value="alcohol" {{ request('violation_type') == 'alcohol' ? 'selected' : '' }}>Sử dụng rượu bia</option>
                                <option value="unauthorized_guest" {{ request('violation_type') == 'unauthorized_guest' ? 'selected' : '' }}>Khách không phép</option>
                                <option value="damage" {{ request('violation_type') == 'damage' ? 'selected' : '' }}>Làm hư hỏng tài sản</option>
                                <option value="late_return" {{ request('violation_type') == 'late_return' ? 'selected' : '' }}>Về trễ giờ</option>
                                <option value="hygiene" {{ request('violation_type') == 'hygiene' ? 'selected' : '' }}>Vệ sinh kém</option>
                                <option value="other" {{ request('violation_type') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="is_resolved">Trạng thái</label>
                            <select name="is_resolved" id="is_resolved" class="form-control">
                                <option value="">Tất cả</option>
                                <option value="0" {{ request('is_resolved') === '0' ? 'selected' : '' }}>Chưa xử lý</option>
                                <option value="1" {{ request('is_resolved') === '1' ? 'selected' : '' }}>Đã xử lý</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fine_paid">Thanh toán</label>
                            <select name="fine_paid" id="fine_paid" class="form-control">
                                <option value="">Tất cả</option>
                                <option value="0" {{ request('fine_paid') === '0' ? 'selected' : '' }}>Chưa đóng</option>
                                <option value="1" {{ request('fine_paid') === '1' ? 'selected' : '' }}>Đã đóng</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="ri-search-line mr-1"></i> Lọc
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-check-line mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">Tổng vi phạm</div>
                            <h4 class="mb-0">{{ $statistics['total'] ?? 0 }}</h4>
                        </div>
                        <div>
                            <i class="ri-alert-line text-danger" style="font-size: 36px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">Chưa xử lý</div>
                            <h4 class="mb-0">{{ $statistics['unresolved'] ?? 0 }}</h4>
                        </div>
                        <div>
                            <i class="ri-time-line text-warning" style="font-size: 36px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">Tổng tiền phạt</div>
                            <h4 class="mb-0">{{ number_format($statistics['total_fines'] ?? 0) }}đ</h4>
                        </div>
                        <div>
                            <i class="ri-money-dollar-circle-line text-info" style="font-size: 36px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">Đã thu</div>
                            <h4 class="mb-0">{{ number_format($statistics['paid_fines'] ?? 0) }}đ</h4>
                        </div>
                        <div>
                            <i class="ri-check-line text-success" style="font-size: 36px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Violations List -->
    <div class="card">
        <div class="card-body">
            @if($violations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="12%">Mã sinh viên</th>
                                <th width="15%">Tên sinh viên</th>
                                <th width="10%">Phòng</th>
                                <th width="15%">Loại vi phạm</th>
                                <th width="10%" class="text-right">Tiền phạt</th>
                                <th width="10%">Ngày vi phạm</th>
                                <th width="10%">Thanh toán</th>
                                <th width="8%">Xử lý</th>
                                <th width="5%" class="text-center">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($violations as $violation)
                                <tr>
                                    <td>{{ ($violations->currentPage() - 1) * $violations->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('students.show', $violation->student) }}">
                                            {{ $violation->student->student_code }}
                                        </a>
                                    </td>
                                    <td>{{ $violation->student->full_name }}</td>
                                    <td>
                                        @if($violation->student->contract && $violation->student->contract->room)
                                            <a href="{{ route('rooms.show', $violation->student->contract->room) }}">
                                                {{ $violation->student->contract->room->room_number }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($violation->violation_type)
                                            @case('noise')
                                                <i class="ri-volume-up-line text-warning"></i> Gây ồn
                                                @break
                                            @case('smoking')
                                                <i class="ri-fire-line text-danger"></i> Hút thuốc
                                                @break
                                            @case('alcohol')
                                                <i class="ri-goblet-line text-danger"></i> Rượu bia
                                                @break
                                            @case('unauthorized_guest')
                                                <i class="ri-user-forbid-line text-warning"></i> Khách không phép
                                                @break
                                            @case('damage')
                                                <i class="ri-tools-fill text-danger"></i> Hư hỏng tài sản
                                                @break
                                            @case('late_return')
                                                <i class="ri-time-line text-info"></i> Về trễ
                                                @break
                                            @case('hygiene')
                                                <i class="ri-delete-bin-line text-warning"></i> Vệ sinh kém
                                                @break
                                            @default
                                                <i class="ri-error-warning-line text-secondary"></i> Khác
                                        @endswitch
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ number_format($violation->fine_amount) }}đ</strong>
                                    </td>
                                    <td>{{ $violation->violation_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($violation->fine_paid)
                                            <span class="badge badge-success">
                                                <i class="ri-check-line"></i> Đã đóng
                                            </span>
                                            @if($violation->fine_paid_at)
                                                <br><small class="text-muted">{{ $violation->fine_paid_at->format('d/m/Y') }}</small>
                                            @endif
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="ri-close-line"></i> Chưa đóng
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($violation->is_resolved)
                                            <span class="badge badge-success">
                                                <i class="ri-checkbox-circle-line"></i> Đã xử lý
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="ri-time-line"></i> Chưa xử lý
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('violations.show', $violation) }}" 
                                           class="btn btn-sm btn-info" title="Chi tiết">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light font-weight-bold">
                            <tr>
                                <td colspan="5" class="text-right">Tổng cộng:</td>
                                <td class="text-right">{{ number_format($violations->sum('fine_amount')) }}đ</td>
                                <td colspan="4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Hiển thị {{ $violations->firstItem() }} - {{ $violations->lastItem() }} 
                        trong tổng số {{ $violations->total() }} vi phạm
                    </div>
                    <div>
                        {{ $violations->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-shield-check-line" style="font-size: 64px; color: #28a745;"></i>
                    <p class="text-muted mt-3">Không tìm thấy vi phạm nào</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.border-left-danger {
    border-left: 4px solid #dc3545;
}
.border-left-warning {
    border-left: 4px solid #ffc107;
}
.border-left-info {
    border-left: 4px solid #17a2b8;
}
.border-left-success {
    border-left: 4px solid #28a745;
}
</style>
@endpush
@endsection
