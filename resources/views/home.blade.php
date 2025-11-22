@extends('layout1.app')

@section('title', 'Trang chủ - Đăng ký Ký túc xá')

@section('content')

    <!-- Hero Section -->
    <section id="ktx-hero" class="section light-background">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <h1>Đăng ký Ký túc xá</h1>
                    <p>Hệ thống đăng ký – tra cứu – quản lý chỗ ở ký túc xá dành cho sinh viên.</p>

                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="number purecounter" data-purecounter-start="0"
                                data-purecounter-end="{{ $stats['students'] ?? 0 }}" data-purecounter-duration="2"></span>
                            <span class="label">Sinh viên đang ở</span>
                        </div>

                        <div class="stat-item">
                            <span class="number purecounter" data-purecounter-start="0"
                                data-purecounter-end="{{ $stats['rooms'] ?? 0 }}" data-purecounter-duration="2"></span>
                            <span class="label">Phòng</span>
                        </div>

                        <div class="stat-item">
                            <span class="number purecounter" data-purecounter-start="0"
                                data-purecounter-end="{{ $stats['available'] ?? 0 }}" data-purecounter-duration="2"></span>
                            <span class="label">Chỗ trống</span>
                        </div>
                    </div>

                    @guest
                        <div class="hero-buttons mt-3">
                            <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập</a>
                            <a href="{{ route('register') }}" class="btn btn-outline">Tạo tài khoản</a>
                        </div>
                    @endguest

                    @auth
                        <div class="hero-buttons mt-3">
                            <a href="#" class="btn btn-primary">Đăng ký ở KTX</a>
                        </div>
                    @endauth
                </div>

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <img src="{{ asset('assets/img/education/dorm-hero.webp') }}" class="img-fluid rounded">
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Rooms -->
    @if (!empty($featuredRooms))
        <section id="featured-rooms" class="section">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>Phòng nổi bật</h2>
                    <p>Các phòng được nhiều sinh viên quan tâm</p>
                </div>

                <div class="row gy-4" data-aos="fade-up" data-aos-delay="100">

                    @foreach ($featuredRooms as $room)
                        <div class="col-lg-4 col-md-6">
                            <div class="room-card shadow-sm">

                                <div class="room-image">
                                    <img src="{{ asset('assets/img/education/room.webp') }}" class="img-fluid">
                                    <div class="badge featured">Phòng {{ $room->name }}</div>
                                    <div class="price-badge">Còn {{ $room->available }}</div>
                                </div>

                                <div class="room-content">
                                    <h3>{{ $room->type }}</h3>
                                    <p>Sức chứa: {{ $room->capacity }}</p>
                                    <a href="#" class="btn btn-primary w-100">Đăng ký ngay</a>
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </section>
    @endif

@endsection
