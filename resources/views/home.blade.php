@extends('layout1.app')

@section('title', 'Trang chủ - Learner')

@section('content')
    <!-- Hero Section -->
    <section id="courses-hero" class="courses-hero section light-background">
        <div class="hero-content">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="hero-text">
                            <h1>Chào mừng
                                @auth
                                    {{ auth()->user()->name }}
                                @else
                                    đến với Learner
                                @endauth
                            </h1>
                            <p>Khám phá hàng nghìn khóa học chất lượng cao được thiết kế bởi các chuyên gia trong ngành.</p>

                            <div class="hero-stats">
                                <div class="stat-item">
                                    <span class="number purecounter" data-purecounter-start="0" data-purecounter-end="50000"
                                        data-purecounter-duration="2"></span>
                                    <span class="label">Học viên</span>
                                </div>
                                <div class="stat-item">
                                    <span class="number purecounter" data-purecounter-start="0" data-purecounter-end="1200"
                                        data-purecounter-duration="2"></span>
                                    <span class="label">Khóa học</span>
                                </div>
                                <div class="stat-item">
                                    <span class="number purecounter" data-purecounter-start="0" data-purecounter-end="98"
                                        data-purecounter-duration="2"></span>
                                    <span class="label">Tỷ lệ thành công %</span>
                                </div>
                            </div>

                            @guest
                                <div class="hero-buttons">
                                    <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập ngay</a>
                                    <a href="#" class="btn btn-outline">Tìm hiểu thêm</a>
                                </div>
                            @endguest
                        </div>
                    </div>

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="hero-image">
                            <div class="main-image">
                                <img src="{{ asset('assets/img/education/courses-13.webp') }}" alt="Online Learning"
                                    class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses Section -->
    <section id="featured-courses" class="featured-courses section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Khóa học nổi bật</h2>
            <p>Các khóa học được đánh giá cao nhất</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
                {{-- Danh sách khóa học sẽ được load từ database --}}
                <div class="col-lg-4 col-md-6">
                    <div class="course-card">
                        <div class="course-image">
                            <img src="{{ asset('assets/img/education/students-9.webp') }}" alt="Course" class="img-fluid">
                            <div class="badge featured">Nổi bật</div>
                            <div class="price-badge">Miễn phí</div>
                        </div>
                        <div class="course-content">
                            <h3><a href="#">Lập trình Web cơ bản</a></h3>
                            <p>Học HTML, CSS, JavaScript từ cơ bản đến nâng cao</p>
                            <a href="#" class="btn-course">Đăng ký ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
