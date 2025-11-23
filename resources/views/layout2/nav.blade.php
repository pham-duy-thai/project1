<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="dashboard.html">Quản lý Ký túc xá</a>

    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-3" id="sidebarToggle" type="button"
        aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>



    <!-- Navbar Search (chuyển sang right on small screens) -->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" role="search"
        action="search.html" method="get">
        <div class="input-group">
            <input class="form-control" name="q" type="text"
                placeholder="Tìm kiếm sinh viên, phòng, hợp đồng..." aria-label="Tìm kiếm" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="submit" aria-label="Tìm kiếm nút">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <!-- Right side icons -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4 align-items-center">
        <!-- Notifications -->
        <li class="nav-item dropdown">
            <a class="nav-link position-relative" id="notificationsDropdown" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false" title="Thông báo">
                <i class="fas fa-bell fa-fw"></i>
                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"
                    id="notifBadge">3</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown"
                style="min-width:320px;">
                <li class="dropdown-header">Thông báo</li>
                <li>
                    <a class="dropdown-item d-flex align-items-start" href="maintenance-requests.html">
                        <div class="me-2"><i class="fas fa-tools text-warning"></i></div>
                        <div>
                            <div><strong>3</strong> yêu cầu bảo trì chưa xử lý</div>
                            <small class="text-muted">Gần đây</small>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-start" href="registration-approval.html">
                        <div class="me-2"><i class="fas fa-clipboard-list text-primary"></i></div>
                        <div>
                            <div><strong>2</strong> đăng ký phòng chờ duyệt</div>
                            <small class="text-muted">Hôm nay</small>
                        </div>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-center" href="notifications.html">Xem tất cả thông báo</a></li>
            </ul>
        </li>


        <!-- User dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarUserDropdown" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
                <span class="ms-1 d-none d-lg-inline" id="currentUserName">Admin KTX</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                <li><a class="dropdown-item" href="profile.html"><i class="fas fa-id-badge me-2"></i>Hồ sơ</a></li>
                <li><a class="dropdown-item" href="account-settings.html"><i class="fas fa-cogs me-2"></i>Cài đặt</a>
                </li>
                <li><a class="dropdown-item" href="activity-log.html"><i class="fas fa-list-alt me-2"></i>Nhật ký
                        hoạt động</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                        </button>
                    </form>
                </li>

        </li>
    </ul>
    </li>
    </ul>
</nav>
