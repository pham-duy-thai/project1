@extends('layout2.theme')

@section('content')
    <div class="container">
        <h3>Sửa hợp đồng</h3>

        <form action="{{ route('admin.contracts.update', $contract->id) }}" method="POST">
            @csrf @method('PUT')

            <label>Ngày bắt đầu:</label>
            <input type="date" name="start_date" class="form-control" value="{{ $contract->start_date }}" required>

            <label>Ngày kết thúc:</label>
            <input type="date" name="end_date" class="form-control" value="{{ $contract->end_date }}" required>

            <label>Tổng tiền:</label>
            <input type="number" name="total_amount" class="form-control" value="{{ $contract->total_amount }}" required>

            <label>Trạng thái:</label>
            <select name="status" class="form-control">
                <option value="active" {{ $contract->status == 'active' ? 'selected' : '' }}>active</option>
                <option value="expired" {{ $contract->status == 'expired' ? 'selected' : '' }}>expired</option>
                <option value="cancelled" {{ $contract->status == 'cancelled' ? 'selected' : '' }}>cancelled</option>
            </select>

            <button class="btn btn-primary mt-3">Cập nhật</button>
        </form>
    </div>
@endsection
