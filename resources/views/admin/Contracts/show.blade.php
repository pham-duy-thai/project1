@extends('layout2.theme')

@section('content')
    <div class="container">
        <h3>Chi tiết hợp đồng</h3>

        <p><strong>Sinh viên:</strong> {{ $contract->registration->student->name }}</p>
        <p><strong>Phòng:</strong> {{ $contract->registration->room->name }}</p>
        <p><strong>Bắt đầu:</strong> {{ $contract->start_date }}</p>
        <p><strong>Kết thúc:</strong> {{ $contract->end_date }}</p>
        <p><strong>Tổng tiền:</strong> {{ number_format($contract->total_amount, 0) }}</p>
        <p><strong>Trạng thái:</strong> {{ $contract->status }}</p>

        <a href="{{ route('admin.contracts.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
@endsection
