<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0f2f5, #cfe2ff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            background-color: #fff;
            padding: 2.5rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .register-card h2 {
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            color: #0d6efd;
        }

        .btn-register {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-register:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .text-login {
            margin-top: 1rem;
            text-align: center;
        }

        .text-login a {
            text-decoration: none;
            color: #0d6efd;
        }

        .text-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-card">
        <h2>Đăng ký tài khoản</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
                <label class="form-label">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
                <label class="form-label">Chọn vai trò</label>
                <select name="role_id" class="form-select" required>
                    <option value="">-- Chọn vai trò --</option>
                    <option value="1">Admin</option>
                    <option value="2">Sinh viên</option>
                </select>
            </div>
            <button type="submit" class="btn btn-register w-100">Hoàn thành đăng ký</button>
        </form>

        <div class="text-login">
            <span>Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
