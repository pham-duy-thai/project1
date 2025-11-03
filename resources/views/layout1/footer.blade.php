<footer id="footer" class="footer accent-background">
    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-5 col-md-12 footer-about">
                <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                    <span class="sitename">Learner</span>
                </a>
                <p>Hệ thống quản lý giáo dục trực tuyến hiện đại, giúp học viên và giảng viên kết nối dễ dàng.</p>
                <div class="social-links d-flex mt-4">
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-6 footer-links">
                <h4>Liên kết</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a href="#">Về chúng tôi</a></li>
                    <li><a href="#">Khóa học</a></li>
                    <li><a href="#">Điều khoản</a></li>
                    <li><a href="#">Chính sách</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-6 footer-links">
                <h4>Dịch vụ</h4>
                <ul>
                    <li><a href="#">Đào tạo trực tuyến</a></li>
                    <li><a href="#">Chứng chỉ</a></li>
                    <li><a href="#">Hỗ trợ học viên</a></li>
                    <li><a href="#">Tư vấn</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                <h4>Liên hệ</h4>
                <p>123 Đường ABC</p>
                <p>Hà Nội, Việt Nam</p>
                <p class="mt-4"><strong>Điện thoại:</strong> <span>+84 123 456 789</span></p>
                <p><strong>Email:</strong> <span>info@learner.vn</span></p>
            </div>
        </div>
    </div>

    <div class="container copyright text-center mt-4">
        <p>© <span>Copyright {{ date('Y') }}</span> <strong class="px-1 sitename">Learner</strong> <span>All Rights
                Reserved</span></p>
    </div>
</footer>
