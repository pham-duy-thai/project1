{{-- @extends($layout)

@section('title', 'Sửa đăng ký')

@section('content')
    <div class="container-fluid px-4">

        <h2 class="mb-3 text-primary">Cập nhật trạng thái đăng ký</h2>

        <div class="card p-4 shadow">

            <form action="{{ route('admin.registrations.update', $registration->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Sinh viên:</label>
                    <input type="text" class="form-control" value="{{ $registration->student->name }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phòng:</label>
                    <input type="text" class="form-control" value="{{ $registration->room->room_number }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tòa:</label>
                    <input type="text" class="form-control" value="{{ $registration->room->building->name }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Trạng thái:</label>
                    <select name="status" class="form-control">
                        <option value="pending" {{ $registration->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $registration->status == 'approved' ? 'selected' : '' }}>Approved
                        </option>
                        <option value="rejected" {{ $registration->status == 'rejected' ? 'selected' : '' }}>Rejected
                        </option>
                    </select>
                </div>

                <button class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.registrations.show', $registration->id) }}" class="btn btn-secondary">Hủy</a>
            </form>

        </div>

    </div>
@endsection --}}
