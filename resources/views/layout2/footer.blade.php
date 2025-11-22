<footer class="py-4 bg-light mt-auto" role="contentinfo">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <!-- Left: bản quyền & phiên bản -->
            <div class="col-md-6 text-muted small mb-2 mb-md-0">
                &copy; <span id="ktxCurrentYear"></span> Quản lý Ký túc xá - Trường Đại học Vinh
                <span class="ms-2">· Phiên bản <small class="text-muted">1.0.0</small></span>
            </div>

            <!-- Right: liên kết nhanh & liên hệ -->
            <div class="col-md-6 d-flex justify-content-md-end justify-content-start">
                <nav aria-label="Footer links">
                    <a class="me-3 text-decoration-none" href="privacy.html" aria-label="Chính sách bảo mật">Chính sách
                        bảo mật</a>
                    <a class="me-3 text-decoration-none" href="terms.html" aria-label="Điều khoản sử dụng">Điều khoản sử
                        dụng</a>
                    <a class="me-3 text-decoration-none" href="contact.html" aria-label="Liên hệ">Liên hệ</a>
                    <a class="text-decoration-none" href="mailto:support@vinh.edu.vn"
                        aria-label="Email hỗ trợ">support@vinh.edu.vn</a>
                </nav>
            </div>
        </div>
    </div>
</footer>

<!-- Script nhỏ để tự động cập nhật năm hiện tại -->
<script>
    (function() {
        const el = document.getElementById('ktxCurrentYear');
        if (el) el.textContent = new Date().getFullYear();
    })();
</script>
