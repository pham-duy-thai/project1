@extends('layout2.theme')

@section('content')
    <div class="container mt-4">
        @php
            // Nếu controller không truyền biến $type, gán mặc định là 'building'
            $type = $type ?? 'building';
        @endphp

        <h4 class="mb-4">
            @if ($type === 'building')
                Thêm Tòa Nhà
            @elseif ($type === 'floor')
                Thêm Tầng
            @else
                Thêm Dữ Liệu
            @endif
        </h4>

        {{-- Hiển thị lỗi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form lưu --}}
        <form action="{{ $type === 'building' ? route('admin.buildings.store') : route('admin.floors.store') }}"
            method="POST">
            @csrf

            {{-- 🔹 Form cho TÒA NHÀ --}}
            @if ($type === 'building')
                <div class="mb-3">
                    <label class="form-label">Tên tòa nhà</label>
                    <input type="text" name="name" class="form-control" placeholder="VD: Tòa A" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tổng số tầng</label>
                    <input type="number" name="total_floors" min="1" class="form-control" required>
                </div>
            @endif

            {{-- 🔹 Form cho TẦNG --}}
            @if ($type === 'floor')
                <div class="mb-3">
                    <label class="form-label">Chọn Tòa Nhà</label>
                    <select name="building_id" class="form-select" required>
                        <option value="">-- Chọn tòa --</option>
                        @foreach ($buildings as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số tầng</label>
                    <input type="number" name="floor_number" min="1" class="form-control"
                        placeholder="VD: 1, 2, 3..." required>
                </div>
            @endif

            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="{{ $type === 'building' ? route('admin.buildings.index') : route('floors.index') }}"
                class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
