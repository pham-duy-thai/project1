@extends('layout2.theme')

@section('title', 'Cập nhật Tầng')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-warning mb-4">Cập nhật Tầng</h2>

        <form action="{{ route('admin.floors.update', $floor->id) }}" method="POST" class="col-md-6">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tòa nhà</label>
                <select name="building_id" class="form-select" required>
                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}" {{ $building->id == $floor->building_id ? 'selected' : '' }}>
                            {{ $building->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Số tầng</label>
                <input type="number" name="floor_number" class="form-control" min="1"
                    value="{{ old('floor_number', $floor->floor_number) }}" required>
            </div>

            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Cập nhật</button>
            <a href="{{ route('admin.floors.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
