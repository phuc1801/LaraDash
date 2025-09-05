<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Login | Hệ thống quản lý</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
</head>
<body>
    <div class="login-wrapper">
        <!-- Background Animation -->
        <div class="background-animation">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>

        <div class="login-container">
            <div class="login-header">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="logo-text">
                        <span class="brand">Dashboard</span>
                        <span class="subtitle">Management System</span>
                    </div>
                </div>
                <h1>Đăng nhập hệ thống</h1>
                <p>Vui lòng nhập thông tin đăng nhập để truy cập bảng điều khiển</p>
            </div>
            
            <form id="loginForm" method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Tên đăng nhập</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="email" id="username" name="email" placeholder="Nhập email" required>
                    </div>
                    <div class="error-message" id="usernameError">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Vui lòng nhập tên đăng nhập</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-with-icon">
                        <i class="fas fa-key"></i>
                        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                        <button type="button" id="togglePassword" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="error-message" id="passwordError">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Vui lòng nhập mật khẩu</span>
                    </div>
                </div>
                
                <div class="remember-forgot">
                    <div class="remember-me">
                        <label class="checkbox-container">
                            <input type="checkbox" id="remember" name="remember">
                            <span class="checkmark"></span>
                            Ghi nhớ đăng nhập
                        </label>
                    </div>
                    <a href="#" class="forgot-password">Quên mật khẩu?</a>
                </div>
                
                <button type="submit" class="login-button">
                    <span class="button-text">Đăng nhập</span>
                    <div class="button-loader">
                        <div class="loader-spinner"></div>
                    </div>
                </button>
                
                <div class="divider">
                    <span>Hoặc tiếp tục với</span>
                </div>
                
                <div class="social-login">
                    <button type="button" class="social-btn google">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </button>
                    <button type="button" class="social-btn microsoft">
                        <i class="fab fa-microsoft"></i>
                        <span>Microsoft</span>
                    </button>
                    <button type="button" class="social-btn github">
                        <i class="fab fa-github"></i>
                        <span>GitHub</span>
                    </button>
                </div>
            </form>
            
            <div class="signup-link">
                Chưa có tài khoản? <a href="#">Liên hệ quản trị viên</a>
            </div>
        </div>
        
        <div class="login-footer">
            <p>&copy; 2025 phuc1801. Phiên bản 1.0</p>
            <div class="footer-links">
                <a href="#"><i class="fas fa-shield-alt"></i> Bảo mật</a>
                <a href="#"><i class="fas fa-question-circle"></i> Trợ giúp</a>
                <a href="#"><i class="fas fa-envelope"></i> Liên hệ</a>
            </div>
        </div>

        <!-- Notification Toast -->
        <div id="notification" class="notification-toast">
            <div class="toast-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="toast-content">
                <p class="toast-message">Đăng nhập thành công!</p>
            </div>
            <button class="toast-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <script src="{{ asset('assets/js/login.js') }}"></script>
</body>
</html>
