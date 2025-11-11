@extends('layout2.theme')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Thêm Phòng Mới</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf

            {{-- 🔹 Chọn Tòa Nhà --}}
            <div class="mb-3">
                <label class="form-label">Chọn Tòa Nhà</label>
                <select id="buildingSelect" name="building_id" class="form-select" required>
                    <option value="">-- Chọn tòa nhà --</option>
                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}" data-total="{{ $building->total_floors }}">
                            {{ $building->name }} ({{ $building->total_floors }} tầng)
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 🔹 Chọn Tầng --}}
            <div class="mb-3">
                <label class="form-label">Chọn Tầng</label>
                <select name="floor_number" id="floorSelect" class="form-select" required>
                    <option value="">-- Chọn tầng --</option>
                </select>
            </div>

            {{-- 🔹 Nhập Số Phòng --}}
            <div class="mb-3">
                <label class="form-label">Số phòng</label>
                <input type="text" name="room_number" class="form-control" placeholder="VD: 101"
                    value="{{ old('room_number') }}" required>
            </div>

            {{-- 🔹 Sức chứa --}}
            <div class="mb-3">
                <label class="form-label">Sức chứa</label>
                <input type="number" name="capacity" class="form-control" min="1" value="{{ old('capacity', 4) }}"
                    required>
            </div>

            {{-- 🔹 Giới tính phòng --}}
            <div class="mb-3">
                <label class="form-label">Giới tính</label>
                <select name="gender" class="form-select" required>
                    <option value="nam" {{ old('gender') == 'nam' ? 'selected' : '' }}>Nam</option>
                    <option value="nu" {{ old('gender') == 'nu' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>

            {{-- 🔹 Giá phòng --}}
            <div class="mb-3">
                <label class="form-label">Giá phòng (VNĐ)</label>
                <input type="number" name="price" class="form-control" min="0" step="100000"
                    value="{{ old('price') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Thêm phòng</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buildingSelect = document.getElementById('buildingSelect');
            const floorSelect = document.getElementById('floorSelect');

            // Khi chọn tòa, tự sinh danh sách tầng
            buildingSelect.addEventListener('change', function() {
                const totalFloors = this.selectedOptions[0].dataset.total;
                floorSelect.innerHTML = '<option value="">-- Chọn tầng --</option>';
                if (totalFloors) {
                    for (let i = 1; i <= totalFloors; i++) {
                        const opt = document.createElement('option');
                        opt.value = i;
                        opt.textContent = 'Tầng ' + i;
                        floorSelect.appendChild(opt);
                    }
                }
            });
        });
    </script>
@endsection
