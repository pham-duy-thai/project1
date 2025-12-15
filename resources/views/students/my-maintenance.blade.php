@extends('layouts.app')
@section('title','Yêu cầu bảo trì của tôi')
@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 fw-bold">
                        <i class="ri-tools-line me-2 text-primary"></i>
                        Yêu cầu bảo trì của tôi
                    </h3>
                    <p class="text-muted mb-0">Quản lý và theo dõi các yêu cầu bảo trì phòng</p>
                </div>
                <div>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('maintenance.index') }}" class="btn btn-info shadow-sm mr-2">
                            <i class="ri-list-check me-1"></i>
                            Tất cả yêu cầu
                        </a>
                    @endif
                    <a href="{{ route('maintenance.create') }}" class="btn btn-primary shadow-sm">
                        <i class="ri-add-line me-1"></i>
                        Tạo yêu cầu mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
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

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 hover-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                <i class="ri-time-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Đang xử lý</h6>
                            <h4 class="mb-0 fw-bold text-warning">{{ $stats['pending_count'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 hover-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-success bg-opacity-10 text-success">
                                <i class="ri-check-double-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Đã hoàn thành</h6>
                            <h4 class="mb-0 fw-bold text-success">{{ $stats['completed_count'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 hover-scale">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-info bg-opacity-10 text-info">
                                <i class="ri-list-check"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Tổng yêu cầu</h6>
                            <h4 class="mb-0 fw-bold text-info">{{ $stats['total_count'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="ri-filter-line me-1"></i>
                        Trạng thái
                    </label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        @foreach(['pending'=>'Chờ duyệt','approved'=>'Đã duyệt','in_progress'=>'Đang xử lý','processed'=>'Đã xử lý','completed'=>'Hoàn thành','rejected'=>'Từ chối'] as $val=>$label)
                            <option value="{{ $val }}" {{ request('status')===$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="ri-flag-line me-1"></i>
                        Mức độ ưu tiên
                    </label>
                    <select name="priority" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @foreach(['low'=>'Thấp','medium'=>'Trung bình','high'=>'Cao','urgent'=>'Khẩn cấp'] as $val=>$label)
                            <option value="{{ $val }}" {{ request('priority')===$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="ri-folder-line me-1"></i>
                        Loại
                    </label>
                    <select name="category" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @foreach(['electrical'=>'Điện','plumbing'=>'Nước','furniture'=>'Đồ đạc','cleaning'=>'Vệ sinh','hvac'=>'Điều hòa','other'=>'Khác'] as $val=>$label)
                            <option value="{{ $val }}" {{ request('category')===$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line me-1"></i>
                        Lọc
                    </button>
                </div>
                @if(request('status') || request('priority') || request('category'))
                    <div class="col-md-2">
                        <a href="{{ route('students.my-maintenance') }}" class="btn btn-outline-secondary w-100">
                            <i class="ri-close-line me-1"></i>
                            Xóa bộ lọc
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Maintenance Requests List -->
    <div class="row g-4">
        @forelse($maintenanceRequests as $req)
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100 hover-card">
                    <div class="card-body">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-1 fw-bold">
                                    <i class="ri-tools-fill me-2 text-primary"></i>
                                    {{ $req->request_code }}
                                </h5>
                                <p class="text-muted small mb-0">
                                    @if($req->room)
                                        <i class="ri-home-4-line me-1"></i>
                                        Phòng {{ $req->room->room_number }} - {{ $req->room->building->name ?? '' }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Status Badge -->
                            @php($statusConfig = [
                                'pending' => ['class'=>'warning', 'icon'=>'time-line', 'text'=>'Chờ duyệt'],
                                'approved' => ['class'=>'info', 'icon'=>'check-line', 'text'=>'Đã duyệt'],
                                'in_progress' => ['class'=>'primary', 'icon'=>'loader-line', 'text'=>'Đang xử lý'],
                                'processed' => ['class'=>'success', 'icon'=>'check-double-line', 'text'=>'Đã xử lý'],
                                'completed' => ['class'=>'success', 'icon'=>'checkbox-circle-line', 'text'=>'Hoàn thành'],
                                'rejected' => ['class'=>'danger', 'icon'=>'close-circle-line', 'text'=>'Từ chối']
                            ])
                            @php($status = $statusConfig[$req->status] ?? ['class'=>'secondary','icon'=>'information-line','text'=>ucfirst($req->status)])
                            <span class="badge bg-{{ $status['class'] }} px-3 py-2">
                                <i class="ri-{{ $status['icon'] }} me-1"></i>
                                {{ $status['text'] }}
                            </span>
                        </div>

                        <!-- Category & Priority -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block">Loại yêu cầu</small>
                                    @php($catConfig = [
                                        'electrical'=>['icon'=>'flashlight-line','text'=>'Điện'],
                                        'plumbing'=>['icon'=>'drop-line','text'=>'Nước'],
                                        'furniture'=>['icon'=>'archive-line','text'=>'Đồ đạc'],
                                        'cleaning'=>['icon'=>'brush-line','text'=>'Vệ sinh'],
                                        'hvac'=>['icon'=>'temp-cold-line','text'=>'Điều hòa'],
                                        'other'=>['icon'=>'more-line','text'=>'Khác']
                                    ])
                                    @php($cat = $catConfig[$req->category] ?? ['icon'=>'folder-line','text'=>ucfirst($req->category)])
                                    <strong>
                                        <i class="ri-{{ $cat['icon'] }} me-1"></i>
                                        {{ $cat['text'] }}
                                    </strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block">Mức độ ưu tiên</small>
                                    @php($priConfig = [
                                        'low'=>['class'=>'secondary','icon'=>'arrow-down-line','text'=>'Thấp'],
                                        'medium'=>['class'=>'info','icon'=>'subtract-line','text'=>'Trung bình'],
                                        'high'=>['class'=>'warning','icon'=>'arrow-up-line','text'=>'Cao'],
                                        'urgent'=>['class'=>'danger','icon'=>'alarm-warning-line','text'=>'Khẩn cấp']
                                    ])
                                    @php($pri = $priConfig[$req->priority] ?? ['class'=>'secondary','icon'=>'flag-line','text'=>ucfirst($req->priority)])
                                    <strong class="text-{{ $pri['class'] }}">
                                        <i class="ri-{{ $pri['icon'] }} me-1"></i>
                                        {{ $pri['text'] }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Mô tả vấn đề</label>
                            <p class="mb-0">{{ Str::limit($req->description, 100) }}</p>
                        </div>

                        <!-- Timeline -->
                        <div class="d-flex align-items-center text-muted small mb-3">
                            <i class="ri-calendar-line me-2"></i>
                            <span>Gửi ngày {{ $req->created_at->format('d/m/Y') }} lúc {{ $req->created_at->format('H:i') }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('maintenance.show', $req) }}" class="btn btn-primary btn-sm flex-fill">
                                <i class="ri-eye-line me-1"></i>
                                Xem chi tiết
                            </a>
                            
                            @if(Auth::user()->student && $req->student_id == Auth::user()->student->id && in_array($req->status, ['pending', 'rejected']))
                                <form action="{{ route('maintenance.destroy', $req) }}" method="POST" class="d-inline" 
                                    onsubmit="return confirm('Bạn có chắc muốn xóa yêu cầu này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            @elseif(!(Auth::user()->student && $req->student_id == Auth::user()->student->id))
                                <small class="text-muted align-self-center">
                                    <i class="ri-information-line"></i> Yêu cầu do admin tạo
                                </small>
                            @else
                                <small class="text-muted align-self-center">
                                    <i class="ri-lock-line"></i> Không thể xóa
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <i class="ri-tools-line text-muted" style="font-size: 64px;"></i>
                        <h5 class="mt-3 mb-2">Chưa có yêu cầu bảo trì</h5>
                        <p class="text-muted mb-3">Bạn chưa tạo yêu cầu bảo trì nào.</p>
                        <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>
                            Tạo yêu cầu mới
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($maintenanceRequests->hasPages())
        <div class="mt-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    {{ $maintenanceRequests->withQueryString()->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.stats-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 28px;
}
.hover-scale {
    transition: transform 0.2s ease;
}
.hover-scale:hover {
    transform: translateY(-3px);
}
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
</style>
@endsection
