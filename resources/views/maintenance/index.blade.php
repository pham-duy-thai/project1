@extends('layouts.app')

@section('title', 'Quản lý bảo trì')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Yêu cầu bảo trì</h4>
                    <p class="mb-0 text-muted">Quản lý các yêu cầu bảo trì phòng</p>
                </div>
                <div>
                    @if(Auth::user()->student)
                        <a href="{{ route('students.my-maintenance') }}" class="btn btn-info mr-2">
                            <i class="ri-user-line mr-1"></i>Yêu cầu của tôi
                        </a>
                    @endif
                    <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
                        <i class="ri-tools-line mr-2"></i>Tạo yêu cầu mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('maintenance.index') }}" class="row align-items-end">
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="search">Tìm kiếm</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Mã yêu cầu, tiêu đề...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="status">Trạng thái</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Tất cả</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Đã xử lý</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="priority">Độ ưu tiên</label>
                                <select class="form-control" id="priority" name="priority">
                                    <option value="">Tất cả</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Thấp</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Trung bình</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Cao</option>
                                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Khẩn cấp</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="category">Loại</label>
                                <select class="form-control" id="category" name="category">
                                    <option value="">Tất cả</option>
                                    <option value="electrical" {{ request('category') == 'electrical' ? 'selected' : '' }}>Điện</option>
                                    <option value="plumbing" {{ request('category') == 'plumbing' ? 'selected' : '' }}>Nước</option>
                                    <option value="furniture" {{ request('category') == 'furniture' ? 'selected' : '' }}>Nội thất</option>
                                    <option value="cleaning" {{ request('category') == 'cleaning' ? 'selected' : '' }}>Vệ sinh</option>
                                    <option value="air_conditioning" {{ request('category') == 'air_conditioning' ? 'selected' : '' }}>Điều hòa</option>
                                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="ri-search-line"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">
                                    <i class="ri-refresh-line"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Requests Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách yêu cầu ({{ $maintenanceRequests->total() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="10%">Mã yêu cầu</th>
                                    <th width="15%">Tiêu đề</th>
                                    <th width="10%">Phòng</th>
                                    <th width="10%">Người yêu cầu</th>
                                    <th width="10%">Loại</th>
                                    <th width="10%" class="text-center">Độ ưu tiên</th>
                                    <th width="10%" class="text-center">Trạng thái</th>
                                    <th width="10%">Ngày tạo</th>
                                    <th width="10%" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($maintenanceRequests as $request)
                                    <tr>
                                        <td>{{ $maintenanceRequests->firstItem() + $loop->index }}</td>
                                        <td><strong class="text-primary">{{ $request->request_code }}</strong></td>
                                        <td>{{ $request->title }}</td>
                                        <td>
                                            @if($request->room)
                                                <a href="{{ route('rooms.show', $request->room) }}">
                                                    {{ $request->room->room_number }}
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($request->student)
                                                {{ $request->student->full_name ?? $request->student->user->name ?? 'Sinh viên' }}
                                                @if($request->student->student_code)
                                                    <small class="text-muted d-block">({{ $request->student->student_code }})</small>
                                                @endif
                                            @else
                                                <span class="text-muted">Admin</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($request->category)
                                                @case('electrical')
                                                    <i class="ri-lightbulb-line text-warning"></i> Điện
                                                    @break
                                                @case('plumbing')
                                                    <i class="ri-drop-line text-info"></i> Nước
                                                    @break
                                                @case('furniture')
                                                    <i class="ri-home-line text-secondary"></i> Nội thất
                                                    @break
                                                @case('cleaning')
                                                    <i class="ri-brush-line text-success"></i> Vệ sinh
                                                    @break
                                                @case('air_conditioning')
                                                    <i class="ri-temp-cold-line text-primary"></i> Điều hòa
                                                    @break
                                                @default
                                                    <i class="ri-tools-line text-muted"></i> Khác
                                            @endswitch
                                        </td>
                                        <td class="text-center">
                                            @switch($request->priority)
                                                @case('urgent')
                                                    <span class="badge badge-danger"><i class="ri-alert-line"></i> Khẩn cấp</span>
                                                    @break
                                                @case('high')
                                                    <span class="badge badge-warning">Cao</span>
                                                    @break
                                                @case('medium')
                                                    <span class="badge badge-info">Trung bình</span>
                                                    @break
                                                @case('low')
                                                    <span class="badge badge-secondary">Thấp</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="text-center">
                                            @switch($request->status)
                                                @case('pending')
                                                    <span class="badge badge-warning">Chờ xử lý</span>
                                                    @break
                                                @case('approved')
                                                    <span class="badge badge-info">Đã duyệt</span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge badge-primary">Đang xử lý</span>
                                                    @break
                                                @case('processed')
                                                    <span class="badge badge-success" style="background-color: #28a745;">Đã xử lý</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge badge-success">Hoàn thành</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge badge-danger">Từ chối</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge badge-secondary">Đã hủy</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('maintenance.show', $request) }}" 
                                                    class="btn btn-sm btn-info" title="Xem chi tiết">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                
                                                @if(Auth::user()->isAdmin())
                                                    @if(in_array($request->status, ['pending', 'rejected']))
                                                        <a href="{{ route('maintenance.edit', $request) }}" 
                                                            class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @if($request->status == 'pending')
                                                        <form action="{{ route('maintenance.approve', $request) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" 
                                                                title="Duyệt yêu cầu" 
                                                                onclick="return confirm('Duyệt yêu cầu này?');">
                                                                <i class="ri-check-line"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if(in_array($request->status, ['pending', 'rejected', 'cancelled']))
                                                        <form action="{{ route('maintenance.destroy', $request) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                title="Xóa yêu cầu" 
                                                                onclick="return confirm('Bạn có chắc muốn xóa yêu cầu này?');">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <i class="ri-tools-line" style="font-size: 48px; color: #ccc;"></i>
                                            <p class="text-muted mt-2">Không tìm thấy yêu cầu bảo trì nào</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Hiển thị {{ $maintenanceRequests->firstItem() ?? 0 }} - {{ $maintenanceRequests->lastItem() ?? 0 }} 
                            trong tổng số {{ $maintenanceRequests->total() }} yêu cầu
                        </div>
                        <div>
                            {{ $maintenanceRequests->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
