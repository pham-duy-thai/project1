@extends('layout2.theme')

@section('content')
    <div class="container">
        <h3>Tạo hợp đồng</h3>

        <form action="{{ route('admin.contracts.store') }}" method="POST">
            @csrf

            <label>Đăng ký phòng:</label>
            <select name="room_registration_id" class="form-control" required>
                <option value="">-- Chọn --</option>
                @foreach ($registrations as $reg)
                    <option value="{{ $reg->id }}">
                        {{ $reg->student->name }} - Phòng {{ $reg->room->name }}
                    </option>
                @endforeach
            </select>

            <label>Ngày bắt đầu:</label>
            <input type="date" name="start_date" class="form-control" required>

            <label>Ngày kết thúc:</label>
            <input type="date" name="end_date" class="form-control" required>

            <label>Tổng tiền:</label>
            <input type="number" name="total_amount" class="form-control" required>

            <label>Trạng thái:</label>
            <select name="status" class="form-control">
                <option value="active">active</option>
                <option value="expired">expired</option>
                <option value="cancelled">cancelled</option>
            </select>

            <button class="btn btn-primary mt-3">Tạo</button>
        </form>
    </div>
@endsection
