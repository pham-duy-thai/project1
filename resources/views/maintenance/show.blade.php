@extends('layouts.app')

@section('title', 'Chi tiết yêu cầu bảo trì')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Yêu cầu bảo trì #{{ $maintenanceRequest->request_code }}</h4>
                    <p class="mb-0 text-muted">Chi tiết yêu cầu và trạng thái xử lý</p>
                </div>
                <div>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line mr-1"></i> Quay lại danh sách
                        </a>
                    @else
                        <a href="{{ route('students.my-maintenance') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line mr-1"></i> Quay lại
                        </a>
                    @endif
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

    <div class="row">
        <div class="col-lg-8">
            <!-- Thông tin yêu cầu -->
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-information-line mr-2"></i>Thông tin yêu cầu</h5>
                    @switch($maintenanceRequest->status)
                        @case('pending')
                            <span class="badge badge-warning"><i class="ri-time-line"></i> Chờ xử lý</span>
                            @break
                        @case('approved')
                            <span class="badge badge-info"><i class="ri-check-line"></i> Đã duyệt</span>
                            @break
                        @case('in_progress')
                            <span class="badge badge-light"><i class="ri-loader-line"></i> Đang xử lý</span>
                            @break
                        @case('processed')
                            <span class="badge badge-success" style="background-color: #28a745;"><i class="ri-check-double-line"></i> Đã xử lý</span>
                            @break
                        @case('completed')
                            <span class="badge badge-success"><i class="ri-checkbox-circle-line"></i> Hoàn thành</span>
                            @break
                        @case('rejected')
                            <span class="badge badge-danger"><i class="ri-close-circle-line"></i> Từ chối</span>
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
                                    <th width="40%">Mã yêu cầu</th>
                                    <td><strong class="text-primary">{{ $maintenanceRequest->request_code }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Phòng</th>
                                    <td>
                                        @if($maintenanceRequest->room)
                                            <a href="{{ route('rooms.show', $maintenanceRequest->room) }}">
                                                {{ $maintenanceRequest->room->room_number }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Loại</th>
                                    <td>
                                        @switch($maintenanceRequest->category)
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
                                </tr>
                                <tr>
                                    <th>Độ ưu tiên</th>
                                    <td>
                                        @switch($maintenanceRequest->priority)
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
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Người yêu cầu</th>
                                    <td>
                                        @if($maintenanceRequest->student)
                                            <strong>{{ $maintenanceRequest->student->full_name ?? ($maintenanceRequest->student->user ? $maintenanceRequest->student->user->name : 'Sinh viên') }}</strong>
                                            @if($maintenanceRequest->student->student_code)
                                                <small class="text-muted d-block">({{ $maintenanceRequest->student->student_code }})</small>
                                            @endif
                                        @elseif($maintenanceRequest->student_id)
                                            @php
                                                // Fallback: Load trực tiếp từ database
                                                $student = \App\Models\Student::with('user')->find($maintenanceRequest->student_id);
                                            @endphp
                                            @if($student)
                                                <strong>{{ $student->full_name ?? ($student->user ? $student->user->name : 'Sinh viên') }}</strong>
                                                @if($student->student_code)
                                                    <small class="text-muted d-block">({{ $student->student_code }})</small>
                                                @endif
                                            @else
                                                <span class="text-warning">
                                                    <i class="ri-error-warning-line"></i> 
                                                    Sinh viên ID: {{ $maintenanceRequest->student_id }} (Không tìm thấy)
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">Admin</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ $maintenanceRequest->created_at ? $maintenanceRequest->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Người phụ trách</th>
                                    <td>{{ $maintenanceRequest->assignedToUser->name ?? 'Chưa phân công' }}</td>
                                </tr>
                                <tr>
                                    <th>Cập nhật lần cuối</th>
                                    <td>{{ $maintenanceRequest->updated_at ? $maintenanceRequest->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <h6 class="font-weight-bold">Tiêu đề:</h6>
                    <p>{{ $maintenanceRequest->title }}</p>

                    <h6 class="font-weight-bold">Mô tả chi tiết:</h6>
                    <p>{{ $maintenanceRequest->description }}</p>

                    @if($maintenanceRequest->admin_notes)
                        <div class="alert alert-info">
                            <h6 class="font-weight-bold mb-2"><i class="ri-admin-line mr-1"></i> Ghi chú của quản lý:</h6>
                            <p class="mb-0">{{ $maintenanceRequest->admin_notes }}</p>
                        </div>
                    @endif

                    @if($maintenanceRequest->completion_notes)
                        <div class="alert alert-success">
                            <h6 class="font-weight-bold mb-2"><i class="ri-checkbox-circle-line mr-1"></i> Ghi chú hoàn thành:</h6>
                            <p class="mb-0">{{ $maintenanceRequest->completion_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Hình ảnh -->
            @php
                $existingImages = [];
                if($maintenanceRequest->images && count($maintenanceRequest->images) > 0) {
                    foreach($maintenanceRequest->images as $image) {
                        if(\Storage::disk('public')->exists($image)) {
                            $existingImages[] = $image;
                        }
                    }
                }
            @endphp
            @if(count($existingImages) > 0)
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="ri-image-line mr-2"></i>Hình ảnh minh họa</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($existingImages as $image)
                            <div class="col-md-3 mb-3">
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="maintenance-gallery">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded shadow-sm" 
                                        alt="Image" style="height: 150px; width: 100%; object-fit: cover;"
                                        onerror="this.onerror=null; this.parentElement.parentElement.style.display='none';">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Thao tác -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="ri-settings-line mr-2"></i>Quản lý yêu cầu</h5>
                </div>
                <div class="card-body">
                    @php
                        $user = Auth::user();
                        $isAdmin = $user->isAdmin();
                        $isOwner = $user->student && $maintenanceRequest->student_id == $user->student->id;
                    @endphp

                    @if($isAdmin)
                        <!-- Admin actions -->
                        @if($maintenanceRequest->status == 'pending')
                            <form action="{{ route('maintenance.approve', $maintenanceRequest) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Duyệt yêu cầu này?');">
                                    <i class="ri-check-line mr-1"></i> Duyệt yêu cầu
                                </button>
                            </form>

                            <button type="button" class="btn btn-danger btn-block mb-2" data-toggle="modal" data-target="#rejectModal">
                                <i class="ri-close-line mr-1"></i> Từ chối yêu cầu
                            </button>
                        @endif

                        @if($maintenanceRequest->status == 'approved')
                            <form action="{{ route('maintenance.start', $maintenanceRequest) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-block" onclick="return confirm('Bắt đầu xử lý yêu cầu này?');">
                                    <i class="ri-play-line mr-1"></i> Bắt đầu xử lý
                                </button>
                            </form>
                        @endif

                        @if($maintenanceRequest->status == 'in_progress')
                            <form action="{{ route('maintenance.process', $maintenanceRequest) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Đánh dấu yêu cầu này là đã xử lý?');">
                                    <i class="ri-check-double-line mr-1"></i> Đánh dấu đã xử lý
                                </button>
                            </form>
                            <button type="button" class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#completeModal">
                                <i class="ri-checkbox-circle-line mr-1"></i> Hoàn thành
                            </button>
                        @endif

                        @if($maintenanceRequest->status == 'processed')
                            <button type="button" class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#completeModal">
                                <i class="ri-checkbox-circle-line mr-1"></i> Hoàn thành
                            </button>
                        @endif

                        @if(in_array($maintenanceRequest->status, ['pending', 'rejected']))
                            <a href="{{ route('maintenance.edit', $maintenanceRequest) }}" class="btn btn-warning btn-block mb-2">
                                <i class="ri-edit-line mr-1"></i> Chỉnh sửa yêu cầu
                            </a>
                        @endif

                        @if(in_array($maintenanceRequest->status, ['pending', 'rejected', 'cancelled']))
                            <form action="{{ route('maintenance.destroy', $maintenanceRequest) }}" method="POST" 
                                onsubmit="return confirm('Bạn có chắc muốn xóa yêu cầu này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="ri-delete-bin-line mr-1"></i> Xóa yêu cầu
                                </button>
                            </form>
                        @endif
                    @elseif($isOwner)
                        <!-- Student owner actions - CHỈ XÓA, KHÔNG CHỈNH SỬA -->
                        @if(in_array($maintenanceRequest->status, ['pending', 'rejected']))
                            <form action="{{ route('maintenance.destroy', $maintenanceRequest) }}" method="POST" 
                                onsubmit="return confirm('Bạn có chắc muốn xóa yêu cầu này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="ri-delete-bin-line mr-1"></i> Xóa yêu cầu
                                </button>
                            </form>
                            <small class="text-muted mt-2 d-block text-center">
                                <i class="ri-information-line"></i> Chỉ có thể xóa yêu cầu chờ duyệt hoặc bị từ chối
                            </small>
                        @else
                            <div class="alert alert-info mb-0">
                                <small><i class="ri-information-line mr-1"></i> Yêu cầu đang được xử lý, không thể xóa.</small>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-secondary mb-0">
                            <small><i class="ri-information-line mr-1"></i> Bạn không có quyền thao tác với yêu cầu này.</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="ri-time-line mr-2"></i>Lịch sử xử lý</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @if($maintenanceRequest->created_at)
                        <div class="timeline-item">
                            <div class="timeline-badge bg-primary"><i class="ri-add-line"></i></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $maintenanceRequest->created_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0">Yêu cầu được tạo</p>
                            </div>
                        </div>
                        @endif

                        @if($maintenanceRequest->approved_at)
                        <div class="timeline-item">
                            <div class="timeline-badge bg-success"><i class="ri-check-line"></i></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $maintenanceRequest->approved_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0">Đã được duyệt</p>
                            </div>
                        </div>
                        @endif

                        @if($maintenanceRequest->started_at)
                        <div class="timeline-item">
                            <div class="timeline-badge bg-info"><i class="ri-play-line"></i></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $maintenanceRequest->started_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0">Bắt đầu xử lý</p>
                            </div>
                        </div>
                        @endif

                        @if($maintenanceRequest->completed_at)
                        <div class="timeline-item">
                            <div class="timeline-badge bg-success"><i class="ri-checkbox-circle-line"></i></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $maintenanceRequest->completed_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0">Hoàn thành</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('/maintenance/' . $maintenanceRequest->id . '/reject') }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Từ chối yêu cầu</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="admin_notes">Lý do từ chối <span class="text-danger">*</span></label>
                        <textarea name="admin_notes" id="admin_notes" rows="3" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Complete Modal -->
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('/maintenance/' . $maintenanceRequest->id . '/complete') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Hoàn thành yêu cầu</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="completion_notes">Ghi chú hoàn thành</label>
                        <textarea name="completion_notes" id="completion_notes" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Hoàn thành</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -21px;
    top: 30px;
    width: 2px;
    height: calc(100% - 20px);
    background: #dee2e6;
}
.timeline-badge {
    position: absolute;
    left: -30px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
}
</style>
@endpush
@endsection
