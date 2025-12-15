@extends('layouts.app')

@section('title', 'Chi tiết hợp đồng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Chi tiết hợp đồng #{{ $contract->contract_number }}</h4>
                    <p class="mb-0 text-muted">Thông tin hợp đồng thuê phòng</p>
                </div>
                <div>
                    @can('manage-contracts')
                        @if($contract->status == 'pending')
                            <form action="{{ route('contracts.sign', $contract) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Phê duyệt hợp đồng này?')">
                                    <i class="ri-check-line mr-1"></i> Phê duyệt hợp đồng
                                </button>
                            </form>
                            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning">
                                <i class="ri-edit-line mr-1"></i> Chỉnh sửa
                            </a>
                        @endif
                        @if($contract->status == 'active')
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#terminateModal">
                                <i class="ri-stop-circle-line mr-1"></i> Chấm dứt hợp đồng
                            </button>
                        @endif
                    @endcan
                    <a href="{{ Auth::user()->role === 'student' ? route('contracts.my-contracts') : route('contracts.index') }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line mr-1"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-check-line mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ri-error-warning-line mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Thông tin hợp đồng -->
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-file-text-line mr-2"></i>Thông tin hợp đồng</h5>
                    @switch($contract->status)
                        @case('pending')
                            <span class="badge badge-warning"><i class="ri-time-line"></i> Chờ phê duyệt</span>
                            @break
                        @case('active')
                            <span class="badge badge-light"><i class="ri-check-circle-line"></i> Đang hoạt động</span>
                            @break
                        @case('expired')
                            <span class="badge badge-warning"><i class="ri-time-line"></i> Hết hạn</span>
                            @break
                        @case('terminated')
                            <span class="badge badge-danger"><i class="ri-close-circle-line"></i> Đã chấm dứt</span>
                            @break
                        @case('cancelled')
                            <span class="badge badge-secondary"><i class="ri-forbid-line"></i> Đã hủy</span>
                            @break
                    @endswitch
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="45%">Số hợp đồng</th>
                                    <td><strong class="text-primary">{{ $contract->contract_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Ngày bắt đầu</th>
                                    <td>{{ $contract->start_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày kết thúc</th>
                                    <td>
                                        {{ $contract->end_date->format('d/m/Y') }}
                                        @if($contract->status == 'active' && $contract->end_date->between(now(), now()->addDays(30)))
                                            <br><span class="badge badge-warning mt-1">Sắp hết hạn</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Thời gian thuê</th>
                                    <td>{{ $contract->start_date->diffInMonths($contract->end_date) }} tháng</td>
                                </tr>
                                <tr>
                                    <th>Chu kỳ thanh toán</th>
                                    <td>
                                        @switch($contract->payment_cycle)
                                            @case('monthly')
                                                Hàng tháng
                                                @break
                                            @case('semester')
                                                Theo học kỳ
                                                @break
                                            @case('yearly')
                                                Hàng năm
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="45%">Tiền thuê/tháng</th>
                                    <td><strong class="text-success">{{ number_format($contract->monthly_rent) }}đ</strong></td>
                                </tr>
                                <tr>
                                    <th>Tiền cọc</th>
                                    <td>{{ number_format($contract->deposit_amount) }}đ</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái hợp đồng</th>
                                    <td>
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
                                </tr>
                                <tr>
                                    <th>Ngày ký</th>
                                    <td>{{ $contract->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Cập nhật lần cuối</th>
                                    <td>{{ $contract->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($contract->notes)
                        <div class="alert alert-info mt-3">
                            <strong><i class="ri-information-line mr-1"></i> Ghi chú:</strong><br>
                            {{ $contract->notes }}
                        </div>
                    @endif

                    @if($contract->termination_reason)
                        <div class="alert alert-danger mt-3">
                            <strong><i class="ri-error-warning-line mr-1"></i> Lý do chấm dứt:</strong><br>
                            {{ $contract->termination_reason }}<br>
                            <small class="text-muted">Ngày chấm dứt: {{ $contract->termination_date ? $contract->termination_date->format('d/m/Y') : 'N/A' }}</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thông tin sinh viên -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="ri-user-line mr-2"></i>Thông tin sinh viên</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Mã sinh viên</th>
                                    <td><strong>{{ $contract->student->student_code }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Họ tên</th>
                                    <td>
                                        <a href="{{ route('students.show', $contract->student) }}" class="text-primary">
                                            {{ $contract->student->full_name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Giới tính</th>
                                    <td>{{ $contract->student->gender == 'male' ? 'Nam' : 'Nữ' }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày sinh</th>
                                    <td>{{ $contract->student->date_of_birth ? $contract->student->date_of_birth->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Email</th>
                                    <td>{{ $contract->student->email }}</td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại</th>
                                    <td>{{ $contract->student->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Khoa</th>
                                    <td>{{ $contract->student->faculty ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Lớp</th>
                                    <td>{{ $contract->student->class ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin phòng -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="ri-hotel-bed-line mr-2"></i>Thông tin phòng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Số phòng</th>
                                    <td>
                                        <a href="{{ route('rooms.show', $contract->room) }}" class="text-primary">
                                            <strong>{{ $contract->room->room_number }}</strong>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tòa nhà</th>
                                    <td>{{ $contract->room->building->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Ký túc xá</th>
                                    <td>{{ $contract->room->building->dormitory->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Loại phòng</th>
                                    <td>{{ $contract->room->roomType->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Sức chứa</th>
                                    <td>{{ $contract->room->capacity }} người</td>
                                </tr>
                                <tr>
                                    <th>Đang ở</th>
                                    <td>{{ $contract->room->current_occupancy ?? 0 }}/{{ $contract->room->capacity }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lịch sử thanh toán -->
            @if($contract->invoices && $contract->invoices->count() > 0)
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="ri-file-list-line mr-2"></i>Lịch sử hóa đơn ({{ $contract->invoices->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Mã hóa đơn</th>
                                    <th width="15%">Kỳ thanh toán</th>
                                    <th width="15%" class="text-right">Tổng tiền</th>
                                    <th width="15%" class="text-right">Đã thanh toán</th>
                                    <th width="15%" class="text-right">Còn lại</th>
                                    <th width="10%" class="text-center">Trạng thái</th>
                                    <th width="10%" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contract->invoices as $invoice)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                        <td>
                                            {{ $invoice->billing_period_start ? $invoice->billing_period_start->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="text-right">{{ number_format($invoice->total_amount) }}đ</td>
                                        <td class="text-right text-success">{{ number_format($invoice->paid_amount) }}đ</td>
                                        <td class="text-right text-danger">{{ number_format($invoice->remaining_amount) }}đ</td>
                                        <td class="text-center">
                                            @switch($invoice->status)
                                                @case('paid')
                                                    <span class="badge badge-success">Đã thanh toán</span>
                                                    @break
                                                @case('partial')
                                                    <span class="badge badge-warning">Thanh toán 1 phần</span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge badge-info">Chưa thanh toán</span>
                                                    @break
                                                @case('overdue')
                                                    <span class="badge badge-danger">Quá hạn</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge badge-secondary">Đã hủy</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <th colspan="3" class="text-right">Tổng cộng:</th>
                                    <th class="text-right">{{ number_format($contract->invoices->sum('total_amount')) }}đ</th>
                                    <th class="text-right text-success">{{ number_format($contract->invoices->sum('paid_amount')) }}đ</th>
                                    <th class="text-right text-danger">{{ number_format($contract->invoices->sum('remaining_amount')) }}đ</th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Thao tác -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="ri-flashlight-line mr-2"></i>Thao tác</h5>
                </div>
                <div class="card-body">
                    @can('manage-contracts')
                        @if($contract->status == 'pending')
                            <form action="{{ route('contracts.sign', $contract) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block mb-2" onclick="return confirm('Phê duyệt hợp đồng này?')">
                                    <i class="ri-check-line mr-1"></i> Phê duyệt hợp đồng
                                </button>
                            </form>
                            
                            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning btn-block mb-2">
                                <i class="ri-edit-line mr-1"></i> Chỉnh sửa hợp đồng
                            </a>

                            <form action="{{ route('contracts.destroy', $contract) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block mb-2" onclick="return confirm('Hủy hợp đồng này?')">
                                    <i class="ri-close-line mr-1"></i> Hủy hợp đồng
                                </button>
                            </form>
                        @endif

                        @if($contract->status == 'active')
                            <button type="button" class="btn btn-danger btn-block mb-2" data-toggle="modal" data-target="#terminateModal">
                                <i class="ri-stop-circle-line mr-1"></i> Chấm dứt hợp đồng
                            </button>

                            <a href="{{ route('invoices.create', ['contract_id' => $contract->id]) }}" class="btn btn-primary btn-block mb-2">
                                <i class="ri-file-add-line mr-1"></i> Tạo hóa đơn mới
                            </a>
                        @endif
                    @endcan

                    <a href="{{ Auth::user()->role === 'student' ? route('contracts.my-contracts') : route('contracts.index') }}" class="btn btn-secondary btn-block">
                        <i class="ri-arrow-left-line mr-1"></i> Quay lại danh sách
                    </a>
                </div>
            </div>

            <!-- Thống kê -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="ri-bar-chart-line mr-2"></i>Thống kê</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="mb-0">{{ $contract->invoices->count() }}</h4>
                                <small class="text-muted">Tổng hóa đơn</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="mb-0 text-success">{{ $contract->invoices->where('status', 'paid')->count() }}</h4>
                                <small class="text-muted">Đã thanh toán</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="mb-0 text-warning">{{ $contract->invoices->where('status', 'pending')->count() }}</h4>
                                <small class="text-muted">Chưa thanh toán</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="mb-0 text-danger">{{ $contract->invoices->where('status', 'overdue')->count() }}</h4>
                                <small class="text-muted">Quá hạn</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Tổng tiền:</span>
                            <strong>{{ number_format($contract->invoices->sum('total_amount')) }}đ</strong>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Đã thanh toán:</span>
                            <strong class="text-success">{{ number_format($contract->invoices->sum('paid_amount')) }}đ</strong>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between">
                            <span>Còn lại:</span>
                            <strong class="text-danger">{{ number_format($contract->invoices->sum('remaining_amount')) }}đ</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terminate Contract Modal -->
<div class="modal fade" id="terminateModal" tabindex="-1" role="dialog" aria-labelledby="terminateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('contracts.terminate', $contract) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="terminateModalLabel">
                        <i class="ri-stop-circle-line mr-2"></i>Chấm dứt hợp đồng
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ri-error-warning-line mr-1"></i> Bạn có chắc chắn muốn chấm dứt hợp đồng này không?
                    </div>

                    <div class="form-group">
                        <label for="termination_date">Ngày chấm dứt <span class="text-danger">*</span></label>
                        <input type="date" name="termination_date" id="termination_date" 
                            class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="reason">Lý do chấm dứt <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason" rows="3" 
                            class="form-control" required placeholder="Nhập lý do chấm dứt hợp đồng..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-stop-circle-line mr-1"></i> Chấm dứt hợp đồng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
