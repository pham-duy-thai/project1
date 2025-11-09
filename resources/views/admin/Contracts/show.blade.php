@extends('layout2.theme')

@section('title', 'Chi tiết Hợp đồng')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-primary mb-4">Chi tiết Hợp đồng</h2>

        <div class="card col-md-8">
            <div class="card-body">
                <h5 class="mb-3">Thông tin Hợp đồng</h5>
                <p><strong>Sinh viên:</strong> {{ $contract->roomRegistration->student->name }}</p>
                <p><strong>Phòng:</strong> {{ $contract->roomRegistration->room->room_number }} -
                    {{ $contract->roomRegistration->room->floor->building->name }}</p>
                <p><strong>Ngày bắt đầu:</strong> {{ $contract->start_date }}</p>
                <p><strong>Ngày kết thúc:</strong> {{ $contract->end_date }}</p>
                <p><strong>Tổng tiền:</strong> <span
                        class="text-success fw-bold">{{ number_format($contract->total_amount, 0, ',', '.') }} VNĐ</span></p>
            </div>
        </div>

        <a href="{{ route('contracts.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
    </div>
@endsection
