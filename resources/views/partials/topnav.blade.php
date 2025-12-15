<div class="iq-top-navbar">
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="iq-navbar-logo d-flex align-items-center justify-content-between">
                <i class="ri-menu-line wrapper-menu"></i>
                <a href="{{ route('dashboard') }}" class="header-logo d-flex align-items-center">
                    <i class="ri-hotel-bed-line text-primary" style="font-size: 32px;"></i>
                    <span class="ml-2 font-weight-bold">Ký túc xá</span>
                </a>
            </div>
            
            <div class="d-flex align-items-center ml-auto">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-label="Toggle navigation">
                    <i class="ri-menu-3-line"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav navbar-list align-items-center">
                        
                        <!-- Notifications -->
                        <li class="nav-item nav-icon dropdown">
                            <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-notification-line"></i>
                                <span class="bg-danger dots"></span>
                            </a>
                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <div class="card shadow-none m-0">
                                    <div class="card-body p-0 ">
                                        <div class="cust-title p-3">
                                            <h5 class="mb-0">Thông báo</h5>
                                        </div>
                                        <div class="px-3 pt-0 pb-0 sub-card">
                                            <a href="{{ route('announcements.index') }}" class="iq-sub-card">
                                                <div class="media align-items-center cust-card py-3">
                                                    <div>
                                                        <i class="ri-notification-line text-primary mr-3" style="font-size: 24px;"></i>
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-0">Xem tất cả thông báo</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <a class="right-ic btn btn-primary btn-block position-relative p-2" href="{{ route('announcements.index') }}" role="button">
                                            Xem tất cả
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- User Profile -->
                        <li class="nav-item nav-icon dropdown caption-content">
                            <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ Auth::user()->avatar_url ?? asset('assets/images/user/user-1.jpg') }}" class="rounded-circle" alt="user" style="width: 45px; height: 45px; min-width: 40px; min-height: 40px; object-fit: cover; border-radius: 50% !important; display: block;" onerror="this.onerror=null;this.src='{{ asset('assets/images/user/user-1.jpg') }}';">
                            </a>
                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton4">
                                <div class="card shadow-none m-0">
                                    <div class="card-body p-0 text-center">
                                        <div class="media-body profile-detail text-center">
                                            <img src="{{ Auth::user()->avatar_url ?? asset('assets/images/user/user-1.jpg') }}" alt="profile-bg" class="rounded-circle mb-3" style="width: 80px; height: 80px; min-width: 80px; min-height: 80px; object-fit: cover; border-radius: 50% !important; display: block; margin-left: auto; margin-right: auto;" onerror="this.onerror=null;this.src='{{ asset('assets/images/user/user-1.jpg') }}';">
                                            <h5><a href="{{ route('profile.edit') }}">{{ Auth::user()->name ?? 'User Name' }}</a></h5>
                                            <p class="mb-0">{{ Auth::user()->email }}</p>
                                            <p class="mb-0">
                                                @if(Auth::user()->isAdmin())
                                                    <span class="badge badge-danger">Admin</span>
                                                @elseif(Auth::user()->isStudent())
                                                    <span class="badge badge-info">Manager</span>
                                                @else
                                                    <span class="badge badge-success">Sinh viên</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <a href="{{ route('profile.edit') }}" class="iq-sub-card iq-bg-primary-hover">
                                        <div class="media align-items-center">
                                            <div class="rounded-circle iq-card-icon iq-bg-primary mr-3">
                                                <i class="ri-user-line"></i>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="mb-0">Thông tin cá nhân</h6>
                                                <p class="mb-0 font-size-12">Xem và chỉnh sửa</p>
                                            </div>
                                        </div>
                                    </a>
                                    @if(Auth::user()->isStudent())
                                    <a href="{{ route('students.my-room') }}" class="iq-sub-card iq-bg-warning-hover">
                                        <div class="media align-items-center">
                                            <div class="rounded-circle iq-card-icon iq-bg-warning mr-3">
                                                <i class="ri-home-line"></i>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="mb-0">Phòng của tôi</h6>
                                                <p class="mb-0 font-size-12">Thông tin phòng ở</p>
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                    <div class="d-inline-block w-100 text-center p-3">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary iq-sign-btn" role="button">
                                                Đăng xuất <i class="ri-login-box-line ml-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
