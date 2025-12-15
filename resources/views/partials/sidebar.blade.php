<div class="iq-sidebar sidebar-default">
    <div class="iq-sidebar-logo d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" class="header-logo">
            <div class="d-inline-block p-2 rounded-circle bg-primary-light">
                <i class="ri-hotel-bed-line text-primary" style="font-size: 32px;"></i>
            </div>
            <span class="ml-2 font-weight-bold" style="font-size: 18px;">Ký túc xá</span>
        </a>
        <div class="iq-menu-bt-sidebar">
            <i class="las la-bars wrapper-menu"></i>
        </div>
    </div>
    <div class="data-scrollbar sidebar-scrollable" data-scroll="1">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">
                <!-- Dashboard -->
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="ri-dashboard-line iq-arrow-left"></i><span>Dashboard</span>
                    </a>
                </li>

                @if(Auth::user()->isAdmin())
                <!-- Quản lý Ký túc xá -->
                <li class="{{ request()->routeIs('dormitories.*') ? 'active' : '' }}">
                    <a href="{{ route('dormitories.index') }}">
                        <i class="ri-building-line iq-arrow-left"></i><span>Ký túc xá</span>
                    </a>
                </li>

                <!-- Quản lý Tòa nhà -->
                <li class="{{ request()->routeIs('buildings.*') ? 'active' : '' }}">
                    <a href="{{ route('buildings.index') }}">
                        <i class="ri-home-line iq-arrow-left"></i><span>Tòa nhà</span>
                    </a>
                </li>

                <!-- Quản lý Phòng -->
                <li class="{{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                    <a href="{{ route('rooms.index') }}">
                        <i class="ri-hotel-bed-line iq-arrow-left"></i><span>Phòng</span>
                    </a>
                </li>

                <!-- Quản lý Sinh viên -->
                <li class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <a href="{{ route('students.index') }}">
                        <i class="ri-user-line iq-arrow-left"></i><span>Sinh viên</span>
                    </a>
                </li>

                <!-- Quản lý Tài khoản -->
                <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}">
                        <i class="ri-shield-user-line iq-arrow-left"></i><span>Tài khoản</span>
                    </a>
                </li>

                <!-- Quản lý Hợp đồng -->
                <li class="{{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                    <a href="{{ route('contracts.index') }}">
                        <i class="ri-file-list-3-line iq-arrow-left"></i><span>Hợp đồng</span>
                    </a>
                </li>

                <!-- Quản lý Hóa đơn -->
                <li class="{{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <a href="{{ route('invoices.index') }}">
                        <i class="ri-bill-line iq-arrow-left"></i><span>Hóa đơn</span>
                    </a>
                </li>

                <!-- Thanh toán -->
                <li class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">
                    <a href="{{ route('payments.index') }}">
                        <i class="ri-money-dollar-circle-line iq-arrow-left"></i><span>Thanh toán</span>
                    </a>
                </li>

                <!-- Bảo trì -->
                <li class="{{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
                    <a href="{{ route('maintenance.index') }}">
                        <i class="ri-tools-line iq-arrow-left"></i><span>Bảo trì</span>
                    </a>
                </li>

                <!-- Thông báo -->
                <li class="{{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                    <a href="{{ route('announcements.index') }}">
                        <i class="ri-notification-line iq-arrow-left"></i><span>Thông báo</span>
                    </a>
                </li>

                <!-- Vi phạm -->
                <li class="{{ request()->routeIs('violations.*') ? 'active' : '' }}">
                    <a href="{{ route('violations.index') }}">
                        <i class="ri-alert-line iq-arrow-left"></i><span>Vi phạm</span>
                    </a>
                </li>

                <!-- Nội quy -->
                <li class="{{ request()->routeIs('rules.*') ? 'active' : '' }}">
                    <a href="{{ route('rules.index') }}">
                        <i class="ri-book-2-line iq-arrow-left"></i><span>Nội quy</span>
                    </a>
                </li>

                <!-- Dịch vụ -->
                <li class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
                    <a href="{{ route('services.index') }}">
                        <i class="ri-service-line iq-arrow-left"></i><span>Dịch vụ</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isStudent())
                @php($student = Auth::user()->student)
                @php($hasContract = $student ? \App\Models\Contract::where('student_id',$student->id)->whereIn('status',['pending','active'])->exists() : false)
                <!-- Trang cá nhân -->
                <li class="{{ request()->routeIs('students.my-profile') ? 'active' : '' }}">
                    <a href="{{ route('students.my-profile') }}">
                        <i class="ri-user-line iq-arrow-left"></i><span>Trang cá nhân</span>
                    </a>
                </li>
                <!-- Phòng của tôi -->
                <li class="{{ request()->routeIs('students.my-room') ? 'active' : '' }}">
                    <a href="{{ route('students.my-room') }}">
                        <i class="ri-hotel-bed-line iq-arrow-left"></i><span>Phòng của tôi</span>
                    </a>
                </li>
                <!-- Hợp đồng của tôi -->
                <li class="{{ request()->routeIs('contracts.my-contracts') ? 'active' : '' }}">
                    <a href="{{ route('contracts.my-contracts') }}">
                        <i class="ri-file-list-3-line iq-arrow-left"></i><span>Hợp đồng</span>
                    </a>
                </li>
                @if(!$hasContract)
                <!-- Đăng ký phòng -->
                <li class="{{ request()->routeIs('contracts.register-room') ? 'active' : '' }}">
                    <a href="{{ route('contracts.register-room') }}">
                        <i class="ri-add-circle-line iq-arrow-left"></i><span>Đăng ký phòng</span>
                    </a>
                </li>
                @endif
                <!-- Hóa đơn của tôi -->
                <li class="{{ request()->routeIs('students.my-invoices') ? 'active' : '' }}">
                    <a href="{{ route('students.my-invoices') }}">
                        <i class="ri-bill-line iq-arrow-left"></i><span>Hóa đơn</span>
                    </a>
                </li>
                <!-- Thanh toán của tôi -->
                <li class="{{ request()->routeIs('students.my-payments') ? 'active' : '' }}">
                    <a href="{{ route('students.my-payments') }}">
                        <i class="ri-money-dollar-circle-line iq-arrow-left"></i><span>Thanh toán</span>
                    </a>
                </li>
                <!-- Yêu cầu bảo trì -->
                <li class="{{ request()->routeIs('students.my-maintenance') ? 'active' : '' }}">
                    <a href="{{ route('students.my-maintenance') }}">
                        <i class="ri-tools-line iq-arrow-left"></i><span>Bảo trì</span>
                    </a>
                </li>
                <!-- Thông báo -->
                <li class="{{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                    <a href="{{ route('announcements.index') }}">
                        <i class="ri-notification-line iq-arrow-left"></i><span>Thông báo</span>
                    </a>
                </li>

                <!-- Nội quy -->
                <li class="{{ request()->routeIs('rules.*') ? 'active' : '' }}">
                    <a href="{{ route('rules.index') }}">
                        <i class="ri-book-2-line iq-arrow-left"></i><span>Nội quy</span>
                    </a>
                </li>

                <!-- Dịch vụ -->
                <li class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
                    <a href="{{ route('services.index') }}">
                        <i class="ri-service-line iq-arrow-left"></i><span>Dịch vụ</span>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
