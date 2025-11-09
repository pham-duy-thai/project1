@extends('layout2.theme')

@section('title', 'Phân quyền người dùng')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="text-primary mb-3">Phân quyền người dùng</h2>

        <table class="table table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Tên quyền</th>
                    <th>Số người dùng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ ucfirst($role->name) }}</td>
                        <td>{{ $role->users->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
