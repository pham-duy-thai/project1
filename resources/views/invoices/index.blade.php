@extends('layouts.app')

@section('title', 'Quản lý hóa đơn')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">Quản lý hóa đơn</h4>
                    <p class="mb-0 text-muted">Danh sách hóa đơn tiền phòng</p>
                </div>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                    <i class="ri-add-line mr-2"></i>Tạo hóa đơn
                </a>
                @endif
            </div>
        </div>

        <!-- Filter -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label>Tháng/Năm</label>
                                <div class="row">
                                    <div class="col-6 pr-1">
                                        <input type="number" class="form-control" name="month" value="{{ request('month', now()->month) }}" min="1" max="12" placeholder="Tháng">
                                    </div>
                                    <div class="col-6 pl-1">
                                        <input type="number" class="form-control" name="year" value="{{ request('year', now()->year) }}" placeholder="Năm">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="status">Trạng thái</label>
                                <select class="form-control" name="status">
                                    <option value="">Tất cả</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Trả 1 phần</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="building_id">Tòa nhà</label>
                                <select class="form-control" name="building_id">
                                    <option value="">Tất cả</option>
                                    @foreach($buildings ?? [] as $building)
                                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                            {{ $building->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-md-0">
                                <label for="search">Tìm kiếm</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                                       placeholder="Mã SV, họ tên, số phòng...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="ri-search-line mr-2"></i>Lọc
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        </div>
        @endif

        <!-- Invoices Table -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-spacing mb-0">
                            <thead>
                                <tr>
                                    <th>Tháng</th>
                                    <th>Sinh viên</th>
                                    <th>Phòng</th>
                                    <th>Tiền phòng</th>
                                    <th>Điện/Nước/DV</th>
                                    <th>Tổng tiền</th>
                                    <th>Hạn TT</th>
                                    <th>Trạng thái</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr class="{{ $invoice->due_date < now() && $invoice->status !== 'paid' ? 'table-danger' : '' }}">
                                    <td><strong>{{ $invoice->month }}/{{ $invoice->year }}</strong></td>
                                    <td>
                                        <div>
                                            <strong>{{ $invoice->student->full_name }}</strong>
                                            <br><small class="text-muted">{{ $invoice->student->student_code }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $invoice->room->building->name }} - {{ $invoice->room->room_number }}</td>
                                    <td>{{ number_format($invoice->room_price) }} đ</td>
                                    <td>{{ number_format($invoice->electricity_cost + $invoice->water_cost + $invoice->service_cost) }} đ</td>
                                    <td class="font-weight-bold">{{ number_format($invoice->total_amount) }} đ</td>
                                    <td>
                                        @if($invoice->due_date < now() && $invoice->status !== 'paid')
                                            <span class="badge badge-danger">Quá hạn</span>
                                            <br><small>{{ $invoice->due_date->format('d/m/Y') }}</small>
                                        @else
                                            <span class="text-muted">{{ $invoice->due_date->format('d/m/Y') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $invoice->status === 'paid' ? 'success' : 
                                            ($invoice->status === 'partial' ? 'warning' : 'danger') 
                                        }}">
                                            {{ $invoice->getStatusLabelAttribute() }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary mr-1">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        @if($invoice->status !== 'paid')
                                            <form action="{{ route('payments.pay', $invoice) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="payment_method" value="cash">
                                                <input type="hidden" name="amount" value="{{ $invoice->remaining_amount }}">
                                                <button type="submit" class="btn btn-sm btn-success" title="Thanh toán">
                                                    <i class="ri-money-dollar-circle-line"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="ri-bill-line" style="font-size: 64px; color: #ccc;"></i>
                                        <h5 class="mt-3">Không có hóa đơn</h5>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($invoices->count() > 0)
                            <tfoot>
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="5" class="text-right">Tổng:</td>
                                    <td>{{ number_format($invoices->sum('total_amount')) }} đ</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
                @if($invoices->hasPages())
                <div class="card-footer">
                    {{ $invoices->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
