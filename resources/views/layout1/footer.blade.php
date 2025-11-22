<footer id="footer" class="footer accent-background">
    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-5 col-md-12 footer-about">
                <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                    <span class="sitename">Ký túc xá</span>
                </a>
                <p>Hệ thống quản lý ký túc xá trực tuyến giúp quản trị, đăng ký, và theo dõi sinh viên dễ dàng.</p>
            </div>

            <div class="col-lg-3 col-6 footer-links">
                <h4>Liên kết</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a href="#">Điều khoản</a></li>
                    <li><a href="#">Chính sách</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-12 footer-contact text-center text-md-start">
                <h4>Liên hệ</h4>
                <p>123 Đường ABC, Hà Nội</p>
                <p><strong>Điện thoại:</strong> +84 123 456 789</p>
                <p><strong>Email:</strong> info@kytucxa.vn</p>
            </div>
        </div>
    </div>

    <div class="container copyright text-center mt-4">
        <p>© {{ date('Y') }} <strong class="px-1">Ký túc xá</strong> - All Rights Reserved</p>
    </div>
</footer>
