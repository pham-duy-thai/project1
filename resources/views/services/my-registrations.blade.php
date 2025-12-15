@extends('layouts.app')

@section('title', 'Dịch vụ của tôi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Dịch vụ của tôi</h4>
                        <p class="mb-0 text-muted">Danh sách dịch vụ đã đăng ký</p>
                    </div>
                    <a href="{{ route('services.index') }}" class="btn btn-primary">
                        <i class="ri-add-line mr-2"></i>Đăng ký thêm
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Dịch vụ</th>
                                    <th width="12%">Loại</th>
                                    <th width="12%">Giá</th>
                                    <th width="12%">Ngày đăng ký</th>
                                    <th width="12%">Ngày hết hạn</th>
                                    <th width="12%">Trạng thái</th>
                                    <th width="10%" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registrations as $index => $registration)
                                <tr>
                                    <td>{{ $registrations->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $registration->service->icon ?? 'ri-service-line' }} mr-2" style="font-size: 24px; color: #3b82f6;"></i>
                                            <strong>{{ $registration->service->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $registration->service->type_label }}</span>
                                    </td>
                                    <td>{{ number_format($registration->service->price) }}đ</td>
                                    <td>{{ $registration->start_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($registration->end_date)
                                        {{ $registration->end_date->format('d/m/Y') }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $registration->status_badge }}">
                                            {{ $registration->status_label }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('services.show', $registration->service) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        @if($registration->status !== 'cancelled')
                                        <form action="{{ route('service-registrations.cancel', $registration) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn hủy đăng ký?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hủy">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="ri-inbox-line" style="font-size: 64px; color: #ccc;"></i>
                                        <h5 class="mt-3 text-muted">Bạn chưa đăng ký dịch vụ nào</h5>
                                        <a href="{{ route('services.index') }}" class="btn btn-primary mt-2">
                                            <i class="ri-add-line mr-2"></i>Khám phá dịch vụ
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($registrations->hasPages())
                <div class="card-footer">
                    {{ $registrations->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
