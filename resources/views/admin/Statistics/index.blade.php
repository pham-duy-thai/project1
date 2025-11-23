@extends('layout2.theme')

@section('content')
    <div class="container">
        <h3>Thống kê theo tháng</h3>

        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-3">
                <label>Tháng</label>
                <select name="month" class="form-control">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ $m }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-3">
                <label>Năm</label>
                <select name="year" class="form-control">
                    @for ($y = 2020; $y <= 2030; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2 align-self-end">
                <button class="btn btn-primary">Xem</button>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Phòng</th>
                    <th>Tháng</th>
                    <th>Năm</th>
                    <th>Số sinh viên</th>
                    <th>Doanh thu (VND)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statistics as $s)
                    <tr>
                        <td>{{ $s['room_name'] }}</td>
                        <td>{{ $s['month'] }}</td>
                        <td>{{ $s['year'] }}</td>
                        <td>{{ $s['total_students'] }}</td>
                        <td>{{ number_format($s['revenue']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
