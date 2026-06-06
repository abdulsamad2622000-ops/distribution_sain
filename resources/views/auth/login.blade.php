<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSH Distribution — Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b2838 50%, #0d1b2a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-logo { text-align: center; margin-bottom: 30px; }
        .login-logo h2 { color: #00b4d8; font-weight: 700; font-size: 26px; }
        .login-logo p { color: #6c757d; font-size: 13px; margin: 0; }
        .form-control { border-radius: 10px; padding: 12px 15px; font-size: 14px; }
        .form-control:focus { border-color: #00b4d8; box-shadow: 0 0 0 0.2rem rgba(0,180,216,0.15); }
        .btn-login {
            background: linear-gradient(135deg, #00b4d8, #0096c7);
            border: none; border-radius: 10px;
            padding: 12px; font-size: 15px;
            font-weight: 600; width: 100%; color: white;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,180,216,0.4); color: white; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <h2><i class="bi bi-building"></i> NSH Distribution</h2>
            <p>ERP Management System</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:14px;">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#f8f9fa;">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" name="email"
                        class="form-control"
                        value="{{ old('email', 'admin@nsh.com') }}"
                        placeholder="Enter email" style="border-radius:0 10px 10px 0;" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:14px;">Password</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#f8f9fa;">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password"
                        class="form-control"
                        placeholder="Enter password" style="border-radius:0 10px 10px 0;" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i> Login
            </button>
        </form>

        <div style="background:#f8f9fa;border-radius:10px;padding:10px 15px;font-size:12px;color:#6c757d;text-align:center;margin-top:15px;">
            <strong>Email:</strong> admin@nsh.com &nbsp;|&nbsp; <strong>Password:</strong> admin123
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>