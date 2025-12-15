@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('announcements.index') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Quay lại
            </a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-outline-info">
                    <i class="ri-edit-line"></i> Chỉnh sửa
                </a>
                @if(!$announcement->is_published)
                    <form action="{{ route('announcements.publish', $announcement) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="ri-send-plane-line"></i> Xuất bản
                        </button>
                    </form>
                @endif
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="rounded-circle iq-card-icon bg-{{ 
                            $announcement->priority === 'urgent' ? 'danger' : 
                            ($announcement->priority === 'high' ? 'warning' : 
                            ($announcement->priority === 'medium' ? 'primary' : 'info')) 
                        }}-light mr-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-notification-line text-{{ 
                                $announcement->priority === 'urgent' ? 'danger' : 
                                ($announcement->priority === 'high' ? 'warning' : 
                                ($announcement->priority === 'medium' ? 'primary' : 'info')) 
                            }}" style="font-size: 32px;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-2">{{ $announcement->title }}</h3>
                            <div class="mb-2">
                                <span class="badge badge-{{ 
                                    $announcement->priority === 'urgent' ? 'danger' : 
                                    ($announcement->priority === 'high' ? 'warning' : 
                                    ($announcement->priority === 'medium' ? 'primary' : 'info')) 
                                }} badge-lg">
                                    {{ $announcement->getPriorityLabelAttribute() }}
                                </span>
                                <span class="badge badge-secondary badge-lg ml-1">
                                    {{ $announcement->getTargetAudienceLabelAttribute() }}
                                </span>
                                @if($announcement->is_published)
                                    <span class="badge badge-success badge-lg ml-1">Đã xuất bản</span>
                                @else
                                    <span class="badge badge-warning badge-lg ml-1">Nháp</span>
                                @endif
                                @if($announcement->is_pinned)
                                    <span class="badge badge-info badge-lg ml-1"><i class="ri-pushpin-line"></i> Ghim</span>
                                @endif
                            </div>
                            <div class="text-muted">
                                <i class="ri-time-line mr-1"></i>
                                @if($announcement->published_at)
                                    {{ $announcement->published_at->format('d/m/Y H:i') }}
                                @else
                                    {{ $announcement->created_at->format('d/m/Y H:i') }}
                                @endif
                                <span class="mx-2">|</span>
                                <i class="ri-user-line mr-1"></i>{{ $announcement->creator->name }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="announcement-content" style="font-size: 1.05rem; line-height: 1.8;">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>

                    @if($announcement->expires_at)
                    <div class="alert alert-info mt-4">
                        <i class="ri-information-line mr-2"></i>
                        Thông báo này có hiệu lực đến: <strong>{{ $announcement->expires_at->format('d/m/Y') }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ri-information-line mr-2"></i>Thông tin</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Mức độ ưu tiên</small>
                        <span class="badge badge-{{ 
                            $announcement->priority === 'urgent' ? 'danger' : 
                            ($announcement->priority === 'high' ? 'warning' : 
                            ($announcement->priority === 'medium' ? 'primary' : 'info')) 
                        }} badge-lg">
                            {{ $announcement->getPriorityLabelAttribute() }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Đối tượng</small>
                        <strong>{{ $announcement->getTargetAudienceLabelAttribute() }}</strong>
                        @if($announcement->building)
                            <br><small>{{ $announcement->building->name }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Người tạo</small>
                        <strong>{{ $announcement->creator->name }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Ngày tạo</small>
                        <strong>{{ $announcement->created_at->format('d/m/Y H:i') }}</strong>
                    </div>

                    @if($announcement->published_at)
                    <div class="mb-3">
                        <small class="text-muted d-block">Ngày xuất bản</small>
                        <strong>{{ $announcement->published_at->format('d/m/Y H:i') }}</strong>
                    </div>
                    @endif

                    @if($announcement->expires_at)
                    <div class="mb-3">
                        <small class="text-muted d-block">Ngày hết hạn</small>
                        <strong class="{{ $announcement->expires_at < now() ? 'text-danger' : 'text-success' }}">
                            {{ $announcement->expires_at->format('d/m/Y') }}
                            @if($announcement->expires_at < now())
                                <br><small>(Đã hết hạn)</small>
                            @endif
                        </strong>
                    </div>
                    @endif

                    <div class="mb-3">
                        <small class="text-muted d-block">Trạng thái</small>
                        @if($announcement->is_published)
                            <span class="badge badge-success">Đã xuất bản</span>
                        @else
                            <span class="badge badge-warning">Nháp</span>
                        @endif
                        @if($announcement->is_pinned)
                            <span class="badge badge-info ml-1"><i class="ri-pushpin-line"></i> Ghim</span>
                        @endif
                    </div>
                </div>
            </div>

            @if(Auth::user()->isAdmin())
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ri-settings-line mr-2"></i>Quản lý</h6>
                </div>
                <div class="card-body">
                    @if(!$announcement->is_published)
                        <form action="{{ route('announcements.publish', $announcement) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="ri-send-plane-line mr-2"></i>Xuất bản
                            </button>
                        </form>
                    @else
                        <form action="{{ route('announcements.unpublish', $announcement) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="ri-eye-off-line mr-2"></i>Ẩn thông báo
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-info btn-block mb-2">
                        <i class="ri-edit-line mr-2"></i>Chỉnh sửa
                    </a>

                    <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" 
                          onsubmit="return confirm('Xác nhận xóa thông báo này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="ri-delete-bin-line mr-2"></i>Xóa thông báo
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
