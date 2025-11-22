<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <!-- Tổng quan -->
                <div class="sb-sidenav-menu-heading">Quản lý Ký túc xá</div>
                <a class="nav-link" href="dashboard.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Tổng quan
                </a>

                <!-- Quản lý tài khoản -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAccount"
                    aria-expanded="false" aria-controls="collapseAccount">
                    <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                    Quản lý tài khoản
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAccount" aria-labelledby="headingAccount"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="accounts-list.html">Danh sách tài khoản</a>
                        <a class="nav-link" href="accounts-add.html">Thêm tài khoản</a>
                        <a class="nav-link" href="roles-permissions.html">Vai trò & Quyền</a>
                        <a class="nav-link" href="account-settings.html">Cấu hình tài khoản</a>
                    </nav>
                </div>

                <!-- Quản lý sinh viên -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseStudent"
                    aria-expanded="false" aria-controls="collapseStudent">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                    Quản lý sinh viên
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseStudent" aria-labelledby="headingStudent"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="students-list.html">Danh sách sinh viên</a>
                        <a class="nav-link" href="students-add.html">Thêm sinh viên</a>
                        <a class="nav-link" href="students-import.html">Nhập khẩu danh sách</a>
                        <a class="nav-link" href="students-profile.html">Hồ sơ / hợp đồng</a>
                    </nav>
                </div>

                <!-- Quản lý tòa & tầng -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseBuilding" aria-expanded="false" aria-controls="collapseBuilding">
                    <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                    Quản lý tòa & tầng
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseBuilding" aria-labelledby="headingBuilding"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="buildings-list.html">Danh sách tòa nhà</a>
                        <a class="nav-link" href="floors-list.html">Danh sách tầng</a>
                        <a class="nav-link" href="building-add.html">Thêm tòa/tầng</a>
                        <a class="nav-link" href="building-config.html">Cấu hình tòa/tầng</a>
                    </nav>
                </div>

                <!-- Quản lý phòng -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRoom"
                    aria-expanded="false" aria-controls="collapseRoom">
                    <div class="sb-nav-link-icon"><i class="fas fa-door-open"></i></div>
                    Quản lý phòng
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseRoom" aria-labelledby="headingRoom"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="rooms-list.html">Danh sách phòng</a>
                        <a class="nav-link" href="room-types.html">Loại phòng</a>
                        <a class="nav-link" href="beds-management.html">Quản lý giường</a>
                        <a class="nav-link" href="room-availability.html">Kiểm tra trống/đầy</a>
                    </nav>
                </div>

                <!-- Quản lý nội quy -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseRule" aria-expanded="false" aria-controls="collapseRule">
                    <div class="sb-nav-link-icon"><i class="fas fa-gavel"></i></div>
                    Quản lý nội quy
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseRule" aria-labelledby="headingRule"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="rules-list.html">Danh sách nội quy</a>
                        <a class="nav-link" href="rule-add.html">Thêm/Chỉnh sửa nội quy</a>
                        <a class="nav-link" href="rule-acknowledgements.html">Xác nhận của sinh viên</a>
                    </nav>
                </div>

                <!-- Quản lý dịch vụ -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseService" aria-expanded="false" aria-controls="collapseService">
                    <div class="sb-nav-link-icon"><i class="fas fa-concierge-bell"></i></div>
                    Quản lý dịch vụ
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseService" aria-labelledby="headingService"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="services-list.html">Danh sách dịch vụ</a>
                        <a class="nav-link" href="service-add.html">Thêm dịch vụ</a>
                        <a class="nav-link" href="service-subscriptions.html">Đăng ký & Thanh toán dịch vụ</a>
                    </nav>
                </div>

                <!-- Quản lý đăng ký phòng -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseRegistration" aria-expanded="false"
                    aria-controls="collapseRegistration">
                    <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    Quản lý đăng ký phòng
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseRegistration" aria-labelledby="headingRegistration"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="registrations-list.html">Danh sách đăng ký</a>
                        <a class="nav-link" href="registration-new.html">Thêm đăng ký</a>
                        <a class="nav-link" href="registration-approval.html">Phê duyệt/ Từ chối</a>
                    </nav>
                </div>

                <!-- Quản lý hợp đồng -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseContract" aria-expanded="false" aria-controls="collapseContract">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-contract"></i></div>
                    Quản lý hợp đồng
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseContract" aria-labelledby="headingContract"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="contracts-list.html">Danh sách hợp đồng</a>
                        <a class="nav-link" href="contract-create.html">Tạo hợp đồng</a>
                        <a class="nav-link" href="contract-templates.html">Mẫu hợp đồng</a>
                        <a class="nav-link" href="contract-history.html">Lịch sử & Gia hạn</a>
                    </nav>
                </div>

                <!-- Khác -->
                <div class="sb-sidenav-menu-heading">Khác</div>
                <a class="nav-link" href="maintenance-requests.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                    Bảo trì & Sự cố
                </a>
                <a class="nav-link" href="reports.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                    Báo cáo
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="sb-sidenav-footer">
            <div class="small">Đăng nhập dưới tên:</div>
            Quản trị viên KTX
            <div style="margin-top:6px;">
                <a class="nav-link" href="logout.html" style="padding:0;color:#fff;">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </nav>
</div>
