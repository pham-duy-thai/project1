<nav id="navmenu" class="navmenu">
    <ul>
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a></li>

        @auth
            @if (auth()->user()->role_id == 1)
                <li><a href="{{ route('dashboard') }}">Quản trị</a></li>
                <li><a href="{{ route('students.index') }}">Sinh viên</a></li>
                <li><a href="{{ route('rooms.index') }}">Phòng</a></li>
                <li><a href="{{ route('registrations.index') }}">Đăng ký phòng</a></li>
            @elseif(auth()->user()->role_id == 2)
                <li><a href="{{ route('student.dashboard') }}">Trang của tôi</a></li>
                <li><a href="{{ route('registrations.index') }}">Đăng ký phòng</a></li>
            @endif
        @else
            <li><a href="#about">Giới thiệu</a></li>
            <li><a href="#contact">Liên hệ</a></li>
        @endauth
    </ul>
    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
</nav>
