@extends('layout2.theme')

@section('title', 'Tạo Hợp đồng mới')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-success mb-4">Tạo Hợp đồng mới</h2>

        <form action="{{ route('contracts.store') }}" method="POST" class="col-md-8">
            @csrf
            <div class="mb-3">
                <label class="form-label">Đăng ký phòng</label>
                <select name="room_registration_id" class="form-select" required>
                    <option value="">-- Chọn đăng ký phòng --</option>
                    @foreach ($registrations as $reg)
                        <option value="{{ $reg->id }}">
                            {{ $reg->student->name }} - Phòng {{ $reg->room->room_number }}
                            ({{ $reg->room->floor->building->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày bắt đầu</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày kết thúc</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tổng tiền (VNĐ)</label>
                <input type="number" name="total_amount" class="form-control" min="0" step="1000" required>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu</button>
            <a href="{{ route('contracts.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
