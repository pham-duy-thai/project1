@extends('layouts.app')

@section('title', 'Quản lý sinh viên')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1">Quản lý sinh viên</h4>
                    <p class="mb-0 text-muted">Danh sách sinh viên trong ký túc xá</p>
                </div>
                <a href="{{ route('students.create') }}" class="btn btn-primary">
                    <i class="ri-add-line mr-2"></i>Thêm sinh viên
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="status">Trạng thái</label>
                                <select class="form-control" name="status">
                                    <option value="">Tất cả</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang ở</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                    <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Đã tốt nghiệp</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-md-0">
                                <label for="gender">Giới tính</label>
                                <select class="form-control" name="gender">
                                    <option value="">Tất cả</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="building_id">Tòa nhà</label>
                                <select class="form-control" name="building_id">
                                    <option value="">Tất cả</option>
                                    @foreach($buildings ?? [] as $building)
                                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                            {{ $building->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-md-0">
                                <label for="search">Tìm kiếm</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                                       placeholder="Mã SV, họ tên, email...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="ri-search-line mr-2"></i>Lọc
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        </div>
        @endif

        <!-- Students Table -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-spacing mb-0">
                            <thead>
                                <tr>
                                    <th>Mã SV</th>
                                    <th>Họ tên</th>
                                    <th>Giới tính</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Phòng</th>
                                    <th>Trạng thái</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr>
                                    <td><strong>{{ $student->student_code }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-40 rounded-circle bg-primary-light mr-2">
                                                <span class="font-size-14 text-primary">{{ substr($student->full_name, 0, 2) }}</span>
                                            </div>
                                            <div>
                                                <strong>{{ $student->full_name }}</strong>
                                                <br><small class="text-muted">{{ $student->date_of_birth ? $student->date_of_birth->format('d/m/Y') : 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($student->gender === 'male')
                                            <span class="badge badge-info">Nam</span>
                                        @elseif($student->gender === 'female')
                                            <span class="badge badge-danger">Nữ</span>
                                        @else
                                            <span class="badge badge-secondary">Khác</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->user->email ?? 'N/A' }}</td>
                                    <td>{{ $student->phone }}</td>
                                    <td>
                                        @php
                                            $activeContract = $student->contracts->where('status', 'active')->first();
                                        @endphp
                                        @if($activeContract)
                                            <span class="badge badge-success">
                                                {{ $activeContract->room->building->name }} - {{ $activeContract->room->room_number }}
                                            </span>
                                        @else
                                            <span class="text-muted">Chưa có</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ $student->getStatusLabelAttribute() }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary mr-1">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-info mr-1">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Xóa sinh viên {{ $student->full_name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="ri-user-line" style="font-size: 64px; color: #ccc;"></i>
                                        <h5 class="mt-3">Không tìm thấy sinh viên</h5>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($students->hasPages())
                <div class="card-footer">
                    {{ $students->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
