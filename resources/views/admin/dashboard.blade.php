@extends('layout2.theme')

@section('content')
    <main>
        <div class="container-fluid px-4 mt-4">
            <h1 class="mt-4 text-primary">Dashboard - Quản lý Ký túc xá</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Trang chủ</li>
            </ol>

            {{-- Hàng 1: Tài khoản, Sinh viên, Tòa & Tầng, Phòng --}}
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x mb-2"></i>
                            <h5>Quản lý tài khoản</h5>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="text-white stretched-link">Xem chi tiết</a>
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-graduate fa-3x mb-2"></i>
                            <h5>Quản lý sinh viên</h5>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('students.index') }}" class="text-white stretched-link">Xem chi tiết</a>
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-building fa-3x mb-2"></i>
                            <h5>Quản lý tòa & tầng</h5>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('buildings.index') }}" class="text-white">Tòa nhà</a> |
                                <a href="{{ route('floors.index') }}" class="text-white">Tầng</a>
                            </div>
                            <i class="fas fa-angle-right"></i>
                        </div>

                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-door-open fa-3x mb-2"></i>
                            <h5>Quản lý phòng</h5>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('rooms.index') }}" class="text-white stretched-link">Xem chi tiết</a>
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hàng 2: Nội quy, Dịch vụ, Đăng ký phòng, Hợp đồng --}}
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-dark text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-3x mb-2"></i>
                            <h5>Quản lý nội quy</h5>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('rules.index') }}" class="text-white stretched-link">Xem chi tiết</a>
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-secondary text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-concierge-bell fa-3x mb-2"></i>
                            <h5>Quản lý dịch vụ</h5>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('services.index') }}" class="text-white stretched-link">Xem chi tiết</a>
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-list fa-3x mb-2"></i>
                            <h5>Quản lý đăng ký phòng</h5>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('registrations.index') }}" class="text-white stretched-link">Xem chi tiết</a>
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-file-contract fa-3x mb-2"></i>
                            <h5>Quản lý hợp đồng</h5>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('contracts.index') }}" class="text-white stretched-link">Xem chi tiết</a>
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hàng 3: Thống kê + Phân quyền --}}
            <div class="row">
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-pie fa-3x mb-2"></i>
                            <h5>Thống kê</h5>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('statistics.index') }}" class="text-white stretched-link">Xem chi tiết</a>
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-shield fa-3x mb-2"></i>
                            <h5>Phân quyền</h5>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('roles.index') }}" class="text-white stretched-link">Xem chi tiết</a>
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
