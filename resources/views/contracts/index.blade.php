@extends('layouts.app')

@section('title', 'Quản lý hợp đồng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Quản lý hợp đồng</h4>
                    <p class="mb-0 text-muted">Danh sách hợp đồng thuê phòng</p>
                </div>
                <a href="{{ route('contracts.create') }}" class="btn btn-primary">
                    <i class="ri-file-add-line mr-2"></i>Tạo hợp đồng mới
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('contracts.index') }}" class="row align-items-end">
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="search">Tìm kiếm</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Số hợp đồng...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="status">Trạng thái</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Tất cả</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ phê duyệt</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Đã chấm dứt</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label>&nbsp;</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="expiring_soon" 
                                        name="expiring_soon" value="1" {{ request('expiring_soon') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="expiring_soon">
                                        Sắp hết hạn (30 ngày)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="ri-search-line"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                                    <i class="ri-refresh-line"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Contracts Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách hợp đồng ({{ $contracts->total() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="12%">Số hợp đồng</th>
                                    <th width="15%">Sinh viên</th>
                                    <th width="12%">Phòng</th>
                                    <th width="10%">Ngày bắt đầu</th>
                                    <th width="10%">Ngày kết thúc</th>
                                    <th width="10%" class="text-right">Tiền thuê/tháng</th>
                                    <th width="10%" class="text-right">Tiền cọc</th>
                                    <th width="10%" class="text-center">Trạng thái</th>
                                    <th width="6%" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contracts as $contract)
                                    <tr>
                                        <td>{{ $contracts->firstItem() + $loop->index }}</td>
                                        <td>
                                            <strong class="text-primary">{{ $contract->contract_number }}</strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('students.show', $contract->student) }}">
                                                {{ $contract->student->full_name }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $contract->student->student_code }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('rooms.show', $contract->room) }}">
                                                {{ $contract->room->room_number }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $contract->room->building->name ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $contract->start_date->format('d/m/Y') }}</td>
                                        <td>
                                            {{ $contract->end_date->format('d/m/Y') }}
                                            @if($contract->status == 'active' && $contract->end_date->between(now(), now()->addDays(30)))
                                                <br><span class="badge badge-warning"><i class="ri-alarm-warning-line"></i> Sắp hết hạn</span>
                                            @endif
                                        </td>
                                        <td class="text-right">{{ number_format($contract->monthly_rent) }}đ</td>
                                        <td class="text-right">{{ number_format($contract->deposit_amount) }}đ</td>
                                        <td class="text-center">
                                            @switch($contract->status)
                                                @case('pending')
                                                    <span class="badge badge-warning">Chờ phê duyệt</span>
                                                    @break
                                                @case('active')
                                                    <span class="badge badge-success">Đang hoạt động</span>
                                                    @break
                                                @case('expired')
                                                    <span class="badge badge-warning">Hết hạn</span>
                                                    @break
                                                @case('terminated')
                                                    <span class="badge badge-danger">Đã chấm dứt</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge badge-secondary">Đã hủy</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('contracts.show', $contract) }}" 
                                                class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <i class="ri-file-search-line" style="font-size: 48px; color: #ccc;"></i>
                                            <p class="text-muted mt-2">Không tìm thấy hợp đồng nào</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Hiển thị {{ $contracts->firstItem() ?? 0 }} - {{ $contracts->lastItem() ?? 0 }} 
                            trong tổng số {{ $contracts->total() }} hợp đồng
                        </div>
                        <div>
                            {{ $contracts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
