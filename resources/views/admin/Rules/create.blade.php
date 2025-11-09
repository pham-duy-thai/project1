@extends('layout2.theme')

@section('title', 'Thêm Nội quy')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-success mb-4">Thêm Nội quy mới</h2>

        <form action="{{ route('rules.store') }}" method="POST" class="col-md-8">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tiêu đề nội quy</label>
                <input type="text" name="title" class="form-control" required placeholder="Nhập tiêu đề nội quy">
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả chi tiết</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Nhập nội dung chi tiết nội quy"></textarea>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu</button>
            <a href="{{ route('rules.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
