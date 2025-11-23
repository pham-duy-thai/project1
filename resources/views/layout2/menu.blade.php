<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <div class="sb-sidenav-menu-heading">Quản lý Ký túc xá</div>

                <!-- DASHBOARD -->
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Tổng quan
                </a>

                <!-- QUẢN LÝ TÀI KHOẢN -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseAccount">
                    <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                    Quản lý tài khoản
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAccount">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">Danh sách tài khoản</a>
                        <a class="nav-link" href="{{ route('admin.users.create') }}">Thêm tài khoản</a>
                        <a class="nav-link" href="{{ route('admin.roles.index') }}">Vai trò & Quyền</a>
                    </nav>
                </div>

                <!-- QUẢN LÝ SINH VIÊN -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseStudent">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                    Quản lý sinh viên
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseStudent">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.students.index') }}">Danh sách sinh viên</a>
                        <a class="nav-link" href="{{ route('admin.students.create') }}">Thêm sinh viên</a>
                    </nav>
                </div>

                <!-- TÒA & TẦNG -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseBuilding">
                    <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                    Quản lý tòa & tầng
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseBuilding">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.buildings.index') }}">Danh sách tòa nhà</a>
                        <a class="nav-link" href="{{ route('admin.floors.index') }}">Danh sách tầng</a>
                        <a class="nav-link" href="{{ route('admin.buildings.create') }}">Thêm tòa</a>
                        <a class="nav-link" href="{{ route('admin.floors.create') }}">Thêm tầng</a>
                    </nav>
                </div>

                <!-- QUẢN LÝ PHÒNG -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRoom">
                    <div class="sb-nav-link-icon"><i class="fas fa-door-open"></i></div>
                    Quản lý phòng
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseRoom">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.rooms.index') }}">Danh sách phòng</a>
                        <a class="nav-link" href="{{ route('admin.rooms.create') }}">Thêm phòng</a>
                    </nav>
                </div>

                <!-- QUẢN LÝ NỘI QUY -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRule">
                    <div class="sb-nav-link-icon"><i class="fas fa-gavel"></i></div>
                    Quản lý nội quy
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseRule">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.rules.index') }}">Danh sách nội quy</a>
                        <a class="nav-link" href="{{ route('admin.rules.create') }}">Thêm nội quy</a>
                    </nav>
                </div>

                <!-- DỊCH VỤ -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseService">
                    <div class="sb-nav-link-icon"><i class="fas fa-concierge-bell"></i></div>
                    Quản lý dịch vụ
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseService">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.services.index') }}">Danh sách dịch vụ</a>
                        <a class="nav-link" href="{{ route('admin.services.create') }}">Thêm dịch vụ</a>
                    </nav>
                </div>

                <!-- ĐĂNG KÝ PHÒNG -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseRegistration">
                    <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    Quản lý đăng ký phòng
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseRegistration">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.registrations.index') }}">Danh sách đăng ký</a>
                    </nav>
                </div>

                <!-- HỢP ĐỒNG -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseContract">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-contract"></i></div>
                    Quản lý hợp đồng
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseContract">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('admin.contracts.index') }}">Danh sách hợp đồng</a>
                        <a class="nav-link" href="{{ route('admin.contracts.create') }}">Tạo hợp đồng</a>
                    </nav>
                </div>

                <!-- THỐNG KÊ -->
                <a class="nav-link" href="{{ route('admin.statistics.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                    Thống kê
                </a>

            </div>
        </div>

        <div class="sb-sidenav-footer">
            <div class="small">Đăng nhập dưới tên:</div>
            Quản trị viên KTX
            <div style="margin-top:6px;">
                <a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    style="padding:0;color:#fff;">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </nav>
</div>
