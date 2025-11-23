@extends('layout2.theme')

@section('content')
    <div class="container mt-4">
        @php
            // Nếu controller chưa truyền $type, mặc định là 'building'
            $type = $type ?? 'building';
        @endphp

        <h4 class="mb-4">
            @if ($type === 'building')
                Chỉnh sửa Tòa Nhà
            @elseif ($type === 'floor')
                Chỉnh sửa Tầng
            @else
                Chỉnh sửa Dữ Liệu
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

        {{-- Form cập nhật --}}
        <form
            action="{{ $type === 'building' ? route('admin.buildings.update', $building->id) : route('admin.floors.update', $floor->id) }}"
            method="POST">
            @csrf
            @method('PUT')

            {{-- 🔹 Form cho TÒA NHÀ --}}
            @if ($type === 'building')
                <div class="mb-3">
                    <label class="form-label">Tên tòa nhà</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $building->name ?? '') }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tổng số tầng</label>
                    <input type="number" name="total_floors" class="form-control" min="1"
                        value="{{ old('total_floors', $building->total_floors ?? 1) }}" required>
                </div>
            @endif

            {{-- 🔹 Form cho TẦNG --}}
            @if ($type === 'floor')
                <div class="mb-3">
                    <label class="form-label">Chọn Tòa Nhà</label>
                    <select name="building_id" class="form-select" required>
                        <option value="">-- Chọn tòa --</option>
                        @foreach ($buildings as $b)
                            <option value="{{ $b->id }}"
                                {{ old('building_id', $floor->building_id ?? '') == $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số tầng</label>
                    <input type="number" name="floor_number" class="form-control" min="1"
                        value="{{ old('floor_number', $floor->floor_number ?? '') }}" required>
                </div>
            @endif

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ $type === 'building' ? route('admin.buildings.index') : route('floors.index') }}"
                class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
