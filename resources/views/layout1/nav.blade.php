<nav id="navmenu" class="navmenu">
    <ul>
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a></li>

        @auth
            {{-- Menu theo role --}}
            @if (auth()->user()->role_id == 1)
                {{-- Admin Menu --}}
                <li><a href="{{ route('layout2.theme') }}">Quản trị</a></li>
                <li class="dropdown">
                    <a href="#"><span>Quản lý</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="{{ route('students.index') }}">Sinh viên</a></li>
                        <li><a href="#">Giảng viên</a></li>
                        <li><a href="#">Khóa học</a></li>
                        <li><a href="#">Người dùng</a></li>
                    </ul>
                </li>
            @elseif(auth()->user()->role_id == 2)
                {{-- Student Menu --}}
                <li><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                <li><a href="#">Khóa học của tôi</a></li>
                <li><a href="#">Điểm số</a></li>
            @endif
        @else
            <li><a href="#">Về chúng tôi</a></li>
            <li><a href="#">Khóa học</a></li>
            <li><a href="#">Liên hệ</a></li>
        @endauth
    </ul>
    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
</nav>
