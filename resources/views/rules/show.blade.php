@extends('layouts.app')

@section('title', 'Chi tiết nội quy')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('rules.index') }}" class="btn btn-outline-primary">
                <i class="ri-arrow-left-line"></i> Quay lại
            </a>
            @if(Auth::user()->isAdmin())
            <a href="{{ route('rules.edit', $rule) }}" class="btn btn-outline-info">
                <i class="ri-edit-line"></i> Chỉnh sửa
            </a>
            <form action="{{ route('rules.destroy', $rule) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa nội quy này?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="ri-delete-bin-line"></i> Xóa
                </button>
            </form>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ $rule->title }}</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-{{ $rule->category === 'safety' ? 'danger' : ($rule->category === 'hygiene' ? 'info' : ($rule->category === 'discipline' ? 'warning' : 'secondary')) }} mr-2">
                            {{ $rule->category_label }}
                        </span>
                        <span class="badge badge-{{ $rule->severity === 'high' ? 'danger' : ($rule->severity === 'medium' ? 'warning' : 'secondary') }}">
                            Mức độ: {{ $rule->severity_label }}
                        </span>
                        @if($rule->requires_acknowledgment)
                        <span class="badge badge-info ml-2">Yêu cầu xác nhận</span>
                        @endif
                    </div>

                    @if($rule->penalty_amount)
                    <div class="alert alert-warning">
                        <i class="ri-alert-line mr-2"></i>
                        <strong>Mức phạt:</strong> {{ number_format($rule->penalty_amount) }}đ
                    </div>
                    @endif

                    <div class="content">
                        <h5 class="mb-3">Nội dung:</h5>
                        <div style="white-space: pre-wrap; line-height: 1.8;">{{ $rule->content }}</div>
                    </div>

                    @if(Auth::user()->isStudent() && $rule->requires_acknowledgment)
                    <hr class="my-4">
                    @if($isAcknowledged)
                    <div class="alert alert-success">
                        <i class="ri-check-double-line mr-2"></i>
                        Bạn đã xác nhận đọc và hiểu nội quy này.
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="ri-information-line mr-2"></i>
                        Nội quy này yêu cầu bạn xác nhận đã đọc và hiểu.
                    </div>
                    <form action="{{ route('rules.acknowledge', $rule) }}" method="POST">
                        @csrf
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmRead" required>
                            <label class="form-check-label" for="confirmRead">
                                Tôi đã đọc và hiểu nội dung nội quy trên, cam kết tuân thủ nghiêm túc.
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-check-line mr-2"></i>Xác nhận đã đọc
                        </button>
                    </form>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Thông tin</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="40%">Phân loại:</td>
                            <td><strong>{{ $rule->category_label }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Mức độ:</td>
                            <td><strong>{{ $rule->severity_label }}</strong></td>
                        </tr>
                        @if($rule->penalty_amount)
                        <tr>
                            <td class="text-muted">Mức phạt:</td>
                            <td class="text-danger"><strong>{{ number_format($rule->penalty_amount) }}đ</strong></td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Thứ tự:</td>
                            <td>{{ $rule->order }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Trạng thái:</td>
                            <td>
                                @if($rule->is_active)
                                <span class="badge badge-success">Đang áp dụng</span>
                                @else
                                <span class="badge badge-secondary">Không hoạt động</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày tạo:</td>
                            <td>{{ $rule->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if(Auth::user()->isAdmin())
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Thống kê</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <i class="ri-user-check-line mr-2"></i>
                        <strong>{{ $rule->acknowledgments()->count() }}</strong> sinh viên đã xác nhận
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
