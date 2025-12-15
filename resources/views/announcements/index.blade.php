@extends('layouts.app')

@section('title', 'Quản lý thông báo')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">Quản lý thông báo</h4>
                    <p class="mb-0 text-muted">Danh sách thông báo cho sinh viên</p>
                </div>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                    <i class="ri-add-line mr-2"></i>Tạo thông báo
                </a>
                @endif
            </div>
        </div>

        <!-- Filter -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="priority">Mức độ</label>
                                <select class="form-control" name="priority">
                                    <option value="">Tất cả</option>
                                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Khẩn cấp</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Cao</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Bình thường</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Thấp</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="target_audience">Đối tượng</label>
                                <select class="form-control" name="target_audience">
                                    <option value="">Tất cả</option>
                                    <option value="all" {{ request('target_audience') == 'all' ? 'selected' : '' }}>Tất cả mọi người</option>
                                    <option value="students" {{ request('target_audience') == 'students' ? 'selected' : '' }}>Sinh viên</option>
                                    <option value="building" {{ request('target_audience') == 'building' ? 'selected' : '' }}>Theo tòa nhà</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-md-0">
                                <label for="search">Tìm kiếm</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                                       placeholder="Tiêu đề thông báo...">
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

        <!-- Announcements List -->
        @forelse($announcements as $announcement)
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle iq-card-icon bg-{{ 
                                    $announcement->priority === 'urgent' ? 'danger' : 
                                    ($announcement->priority === 'high' ? 'warning' : 
                                    ($announcement->priority === 'medium' ? 'primary' : 'info')) 
                                }}-light mr-3">
                                    <i class="ri-notification-line text-{{ 
                                        $announcement->priority === 'urgent' ? 'danger' : 
                                        ($announcement->priority === 'high' ? 'warning' : 
                                        ($announcement->priority === 'medium' ? 'primary' : 'info')) 
                                    }}" style="font-size: 24px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">{{ $announcement->title }}</h5>
                                    <div>
                                        <span class="badge badge-{{ 
                                            $announcement->priority === 'urgent' ? 'danger' : 
                                            ($announcement->priority === 'high' ? 'warning' : 
                                            ($announcement->priority === 'medium' ? 'primary' : 'info')) 
                                        }}">
                                            {{ $announcement->getPriorityLabelAttribute() }}
                                        </span>
                                        <span class="badge badge-secondary ml-1">
                                            {{ $announcement->getTargetAudienceLabelAttribute() }}
                                        </span>
                                        @if($announcement->is_published)
                                            <span class="badge badge-success ml-1">Đã xuất bản</span>
                                        @else
                                            <span class="badge badge-warning ml-1">Nháp</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted mb-2">{{ Str::limit(strip_tags($announcement->content), 200) }}</p>
                            <div class="text-muted small">
                                <i class="ri-time-line mr-1"></i>
                                @if($announcement->published_at)
                                    Đã xuất bản: {{ $announcement->published_at->format('d/m/Y H:i') }}
                                @else
                                    Tạo lúc: {{ $announcement->created_at->format('d/m/Y H:i') }}
                                @endif
                                @if($announcement->expires_at)
                                    | Hết hạn: {{ $announcement->expires_at->format('d/m/Y') }}
                                @endif
                            </div>
                        </div>
                        <div class="ml-3">
                            <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-sm btn-outline-primary mr-1">
                                <i class="ri-eye-line"></i> Xem
                            </a>
                            @if(Auth::user()->isAdmin())
                                @if(!$announcement->is_published)
                                    <form action="{{ route('announcements.publish', $announcement) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success mr-1">
                                            <i class="ri-send-plane-line"></i> Xuất bản
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('announcements.unpublish', $announcement) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning mr-1">
                                            <i class="ri-eye-off-line"></i> Ẩn
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-info mr-1">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Xóa thông báo này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-notification-line" style="font-size: 64px; color: #ccc;"></i>
                    <h5 class="mt-3">Chưa có thông báo nào</h5>
                    @if(Auth::user()->isAdmin())
                    <p class="text-muted">Nhấn nút "Tạo thông báo" để tạo mới</p>
                    @endif
                </div>
            </div>
        </div>
        @endforelse

        @if($announcements->hasPages())
        <div class="col-lg-12">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
