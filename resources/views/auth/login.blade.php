<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập - Hệ thống chấm công</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 2rem;
            text-align: center;
            color: white;
        }
        
        .login-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            backdrop-filter: blur(10px);
        }
        
        .login-body {
            padding: 2.5rem;
        }
        
        .form-control {
            border-radius: 12px;
            padding: 0.875rem 1.25rem;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        
        .form-control-icon {
            padding-left: 3rem;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.875rem;
            border-radius: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }
        
        .divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            color: #94a3b8;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon">
                <i class="bi bi-clock-history" style="font-size: 2.5rem;"></i>
            </div>
            <h2 class="mb-2 fw-bold">Chào mừng trở lại!</h2>
            <p class="mb-0 opacity-75">Đăng nhập để tiếp tục</p>
        </div>

        <div class="login-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email hoặc Số điện thoại</label>
                    <div class="position-relative">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="text" 
                               name="email" 
                               class="form-control form-control-icon" 
                               placeholder="Nhập email hoặc số điện thoại"
                               value="{{ old('email') }}"
                               required 
                               autofocus>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Mật khẩu</label>
                    <div class="position-relative">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="form-control form-control-icon" 
                               placeholder="Nhập mật khẩu"
                               required>
                        <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>
                    
                </div>

                <button type="submit" class="btn btn-login w-100 mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Đăng nhập
                </button>

                @if($errors->any())
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
                @endif
            </form>

            <div class="divider">
                <span>HOẶC</span>
            </div>

            <div class="text-center">
                <small class="text-muted">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    Thông tin của bạn được bảo mật an toàn
                </small>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this;
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    </script>
</body>
</html>
