<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hệ thống quản lý ký túc xá')</title>

    <!-- Favicons -->
    <link href="{{ asset('home/assets/img/favicon.png') }}" rel="icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link href="{{ asset('home/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/vendor/purecounter/purecounter.css') }}" rel="stylesheet">

    <!-- Main CSS -->
    <link href="{{ asset('home/assets/css/main.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body class="@yield('body-class', 'index-page')">

    @include('layout1.header')

    <main class="main">
        @yield('content')
    </main>

    @include('layout1.footer')

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- JS -->
    <script src="{{ asset('home/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('home/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('home/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('home/assets/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>
