@extends('layout1.app')

@section('title', 'Hồ sơ cá nhân')

@section('content')
    <h3 class="text-primary mb-3">Hồ sơ cá nhân</h3>

    <div class="card col-md-6">
        <div class="card-body">
            <p><strong>Tên:</strong> {{ $student->name }}</p>
            <p><strong>Mã sinh viên:</strong> {{ $student->student_code }}</p>
            <p><strong>Lớp:</strong> {{ $student->class }}</p>
            <p><strong>Giới tính:</strong> {{ $student->gender }}</p>
            <p><strong>Số điện thoại:</strong> {{ $student->phone }}</p>
            <p><strong>Trạng thái:</strong>
                <span class="badge bg-{{ $student->status == 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($student->status) }}
                </span>
            </p>
        </div>
    </div>
@endsection
