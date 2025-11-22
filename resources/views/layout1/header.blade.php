<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto">
            <h1 class="sitename">Ký túc xá</h1>
        </a>

        @include('layout1.nav')

        <div class="header-actions d-flex align-items-center gap-3">
            @auth
                <div class="dropdown">
                    <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person me-2"></i>Hồ sơ
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a class="btn btn-primary" href="{{ route('login') }}">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập
                </a>
            @endauth
        </div>

    </div>
</header>
