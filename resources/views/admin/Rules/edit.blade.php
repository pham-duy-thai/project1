@extends('layout2.theme')

@section('title', 'Cập nhật Nội quy')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-warning mb-4">Cập nhật Nội quy</h2>

        <form action="{{ route('rules.update', $rule->id) }}" method="POST" class="col-md-8">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tiêu đề nội quy</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $rule->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả chi tiết</label>
                <textarea name="description" class="form-control" rows="5">{{ old('description', $rule->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Cập nhật</button>
            <a href="{{ route('rules.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
