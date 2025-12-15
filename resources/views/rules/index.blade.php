@extends('layouts.app')

@section('title', 'Nội quy ký túc xá')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Nội quy ký túc xá</h4>
                        <p class="mb-0 text-muted">Danh sách các nội quy và quy định</p>
                    </div>
                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('rules.create') }}" class="btn btn-primary">
                        <i class="ri-add-line mr-2"></i>Thêm nội quy
                    </a>
                    @endif
                </div>

                <!-- Filter -->
                <div class="card-body border-bottom">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="category">Phân loại</label>
                                <select class="form-control" name="category" onchange="this.form.submit()">
                                    <option value="">Tất cả</option>
                                    <option value="safety" {{ request('category')=='safety'?'selected':'' }}>An toàn</option>
                                    <option value="hygiene" {{ request('category')=='hygiene'?'selected':'' }}>Vệ sinh</option>
                                    <option value="discipline" {{ request('category')=='discipline'?'selected':'' }}>Kỷ luật</option>
                                    <option value="general" {{ request('category')=='general'?'selected':'' }}>Chung</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-md-0">
                                <label for="search">Tìm kiếm</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm theo tiêu đề...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary mr-2"><i class="ri-search-line"></i> Tìm</button>
                            <a href="{{ route('rules.index') }}" class="btn btn-outline-secondary"><i class="ri-refresh-line"></i></a>
                        </div>
                    </form>
                </div>

                <!-- Rules List -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="35%">Tiêu đề</th>
                                    <th width="12%">Phân loại</th>
                                    <th width="12%">Mức độ</th>
                                    <th width="12%">Mức phạt</th>
                                    @if(Auth::user()->isStudent())
                                    <th width="12%" class="text-center">Trạng thái</th>
                                    @endif
                                    <th width="12%" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rules as $index => $rule)
                                <tr>
                                    <td>{{ $rules->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $rule->title }}</strong>
                                        @if($rule->requires_acknowledgment)
                                        <span class="badge badge-info badge-sm ml-1">Yêu cầu xác nhận</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $rule->category === 'safety' ? 'danger' : ($rule->category === 'hygiene' ? 'info' : ($rule->category === 'discipline' ? 'warning' : 'secondary')) }}">
                                            {{ $rule->category_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($rule->severity === 'high')
                                        <span class="text-danger font-weight-bold">{{ $rule->severity_label }}</span>
                                        @elseif($rule->severity === 'medium')
                                        <span class="text-warning">{{ $rule->severity_label }}</span>
                                        @else
                                        <span class="text-secondary">{{ $rule->severity_label }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rule->penalty_amount)
                                        <span class="text-danger">{{ number_format($rule->penalty_amount) }}đ</span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    @if(Auth::user()->isStudent())
                                    <td class="text-center">
                                        @if(in_array($rule->id, $acknowledgedRules))
                                        <span class="badge badge-success"><i class="ri-check-line"></i> Đã xác nhận</span>
                                        @elseif($rule->requires_acknowledgment)
                                        <span class="badge badge-warning">Chưa xác nhận</span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    @endif
                                    <td class="text-center">
                                        <a href="{{ route('rules.show', $rule) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                        <a href="{{ route('rules.edit', $rule) }}" class="btn btn-sm btn-outline-info" title="Sửa">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('rules.destroy', $rule) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa nội quy này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->isStudent() ? 7 : 6 }}" class="text-center py-5">
                                        <i class="ri-book-2-line" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="mt-3 text-muted">Chưa có nội quy nào</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($rules->hasPages())
                <div class="card-footer">
                    {{ $rules->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
