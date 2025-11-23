@extends('layout2.theme')

@section('content')
    <div class="container">
        <h3>Danh sách hợp đồng</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sinh viên</th>
                    <th>Phòng</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($contracts as $contract)
                    <tr>
                        <td>{{ $contract->registration->student->name ?? 'N/A' }}</td>
                        <td>{{ $contract->registration->room->name ?? 'N/A' }}</td>
                        <td>{{ $contract->start_date }}</td>
                        <td>{{ $contract->end_date }}</td>
                        <td>{{ number_format($contract->total_amount, 0) }}</td>
                        <td>{{ $contract->status }}</td>
                        <td>
                            <a href="{{ route('admin.contracts.show', $contract->id) }}" class="btn btn-info btn-sm">Xem</a>
                            <a href="{{ route('admin.contracts.edit', $contract->id) }}"
                                class="btn btn-warning btn-sm">Sửa</a>

                            <form action="{{ route('admin.contracts.destroy', $contract->id) }}" method="POST"
                                style="display:inline;">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Xóa hợp đồng này?')" class="btn btn-danger btn-sm">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection
