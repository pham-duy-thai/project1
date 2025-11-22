@extends('layout2.theme')

@section('title', 'Quản lý Nội quy')

@section('content')
    <style>
        /* Cho bảng co giãn tự nhiên theo nội dung */
        table {
            table-layout: auto !important;
            width: 100%;
        }

        /* Giới hạn cột Mô tả vừa đủ, cho phép xuống dòng */
        td.description-cell {
            white-space: normal !important;
            word-break: break-word;
        }
    </style>

    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Danh sách Nội quy</h2>
            <a href="{{ route('rules.create') }}" class="btn btn-success">+ Thêm Nội quy</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 20%">Tiêu đề</th>
                    <th>Mô tả</th>
                    <th style="width: 15%">Ngày tạo</th>
                    <th style="width: 15%">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rules as $rule)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $rule->title }}</td>
                        <td class="description-cell">
                            {{ $rule->description ?: '—' }}
                        </td>

                        <!-- ĐÃ SỬA created_at NULL -->
                        <td class="text-center">
                            {{ optional($rule->created_at)->format('d/m/Y') }}
                        </td>

                        <td class="text-center">
                            <a href="{{ route('rules.edit', $rule->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>

                            <form action="{{ route('rules.destroy', $rule->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Xóa nội quy này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Chưa có nội quy nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
