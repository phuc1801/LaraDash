<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard - Modern Bootstrap Admin</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Comprehensive analytics dashboard with advanced charts, KPIs, and data visualizations">
    <meta name="keywords" content="bootstrap, admin, dashboard, analytics, charts, data visualization">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="bootstrap/favicon-CvUZKS4z.svg">
    <link rel="icon" type="image/png" href="bootstrap/favicon-B_cwPWBd.png">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="bootstrap/manifest-DTaoG9pG.json">
    
    <!-- Preload critical fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <!-- Custom Styles for Analytics Dashboard -->
  <script type="module" crossorigin src="bootstrap/main-BPhDq89w.js"></script>
  <script type="module" crossorigin src="bootstrap/analytics-C7yvD7Vh.js"></script>
  <link rel="stylesheet" crossorigin href="bootstrap/main-D9K-blpF.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body data-page="analytics" class="analytics-page">
    <!-- Admin App Container -->
    <div class="admin-app">
        <div class="admin-wrapper" id="admin-wrapper">
            
            <!-- Header -->
            <header class="admin-header">
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <!-- Logo/Brand -->
                        <a class="navbar-brand d-flex align-items-center" href="./index.html">
                            <img src="data:image/svg+xml,%3csvg%20width='32'%20height='32'%20viewBox='0%200%2032%2032'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3c!--%20Background%20circle%20for%20the%20M%20--%3e%3ccircle%20cx='16'%20cy='16'%20r='16'%20fill='url(%23logoGradient)'/%3e%3c!--%20Centered%20Letter%20M%20--%3e%3cpath%20d='M10%2024V8h2.5l2.5%206.5L17.5%208H20v16h-2V12.5L16.5%2020h-1L14%2012.5V24H10z'%20fill='white'%20font-weight='700'/%3e%3c!--%20Gradient%20definition%20--%3e%3cdefs%3e%3clinearGradient%20id='logoGradient'%20x1='0%25'%20y1='0%25'%20x2='100%25'%20y2='100%25'%3e%3cstop%20offset='0%25'%20style='stop-color:%236366f1;stop-opacity:1'%20/%3e%3cstop%20offset='100%25'%20style='stop-color:%238b5cf6;stop-opacity:1'%20/%3e%3c/linearGradient%3e%3c/defs%3e%3c/svg%3e" alt="Logo" height="32" class="d-inline-block align-text-top me-2">
                            <h1 class="h4 mb-0 fw-bold text-primary">AV Computer</h1>
                        </a>

                        <!-- Search Bar with Alpine.js -->
                        <div class="search-container flex-grow-1 mx-4" x-data="searchComponent">
                            <div class="position-relative">
                                <input type="search" 
                                       class="form-control" 
                                       placeholder="Search... (Ctrl+K)"
                                       x-model="query"
                                       @input="search()"
                                       data-search-input
                                       aria-label="Search">
                                <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
                                
                                <!-- Search Results Dropdown -->
                                <div x-show="results.length > 0" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="position-absolute top-100 start-0 w-100 bg-white border rounded-2 shadow-lg mt-1 z-3">
                                    <template x-for="result in results" :key="result.title">
                                        <a :href="result.url" class="d-block px-3 py-2 text-decoration-none text-dark border-bottom">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-text me-2 text-muted"></i>
                                                <span x-text="result.title"></span>
                                                <small class="ms-auto text-muted" x-text="result.type"></small>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side Icons -->
                        <div class="navbar-nav flex-row">
                            <!-- Theme Toggle with Alpine.js -->
                            <div x-data="themeSwitch">
                                <button class="btn btn-outline-secondary me-2" 
                                        type="button" 
                                        @click="toggle()"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="bottom"
                                        title="Toggle theme">
                                    <i class="bi bi-sun-fill" x-show="currentTheme === 'light'"></i>
                                    <i class="bi bi-moon-fill" x-show="currentTheme === 'dark'"></i>
                                </button>
                            </div>

                            <!-- Fullscreen Toggle -->
                            <button class="btn btn-outline-secondary me-2" 
                                    type="button" 
                                    data-fullscreen-toggle
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Toggle fullscreen">
                                <i class="bi bi-arrows-fullscreen icon-hover"></i>
                            </button>

                            <!-- Notifications -->
                            <div class="dropdown me-2">
                                <button class="btn btn-outline-secondary position-relative" 
                                        type="button" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false">
                                    <i class="bi bi-bell"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        3
                                    </span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><h6 class="dropdown-header">Notifications</h6></li>
                                    <li><a class="dropdown-item" href="#">New user registered</a></li>
                                    <li><a class="dropdown-item" href="#">Server status update</a></li>
                                    <li><a class="dropdown-item" href="#">New message received</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
                                </ul>
                            </div>

                            <!-- User Menu -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary d-flex align-items-center" 
                                        type="button" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false">
                                    <img src="data:image/svg+xml,%3csvg%20width='32'%20height='32'%20viewBox='0%200%2032%2032'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3c!--%20Background%20circle%20--%3e%3ccircle%20cx='16'%20cy='16'%20r='16'%20fill='url(%23avatarGradient)'/%3e%3c!--%20Person%20silhouette%20--%3e%3cg%20fill='white'%20opacity='0.9'%3e%3c!--%20Head%20--%3e%3ccircle%20cx='16'%20cy='12'%20r='5'/%3e%3c!--%20Body%20--%3e%3cpath%20d='M16%2018c-5.5%200-10%202.5-10%207v1h20v-1c0-4.5-4.5-7-10-7z'/%3e%3c/g%3e%3c!--%20Subtle%20border%20--%3e%3ccircle%20cx='16'%20cy='16'%20r='15.5'%20fill='none'%20stroke='rgba(255,255,255,0.2)'%20stroke-width='1'/%3e%3c!--%20Gradient%20definition%20--%3e%3cdefs%3e%3clinearGradient%20id='avatarGradient'%20x1='0%25'%20y1='0%25'%20x2='100%25'%20y2='100%25'%3e%3cstop%20offset='0%25'%20style='stop-color:%236b7280;stop-opacity:1'%20/%3e%3cstop%20offset='100%25'%20style='stop-color:%234b5563;stop-opacity:1'%20/%3e%3c/linearGradient%3e%3c/defs%3e%3c/svg%3e" 
                                         alt="User Avatar" 
                                         width="24" 
                                         height="24" 
                                         class="rounded-circle me-2">
                                    <span class="d-none d-md-inline">phuc</span>
                                    <i class="bi bi-chevron-down ms-1"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            <!-- Sidebar -->
            <aside class="admin-sidebar" id="admin-sidebar">
                <div class="sidebar-content">
                    <nav class="sidebar-nav">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2"></i>
                                    <span>Trang chủ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('analytics') }}">">
                                    <i class="bi bi-graph-up"></i>
                                    <span>Báo cáo thống kê</span>
                                    <span class="badge bg-primary rounded-pill ms-auto">Active</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users') }}">
                                    <i class="bi bi-people"></i>
                                    <span>Người dùng</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products') }}">
                                    <i class="bi bi-box"></i>
                                    <span>Sản phẩm</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('orders') }}">
                                    <i class="bi bi-bag-check"></i>
                                    <span>Đơn hàng</span>
                                </a>
                            </li>
                            
                            
                           
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('messages') }}">
                                    <i class="bi bi-chat-dots"></i>
                                    <span>Tin nhắn</span>
                                    <span class="badge bg-danger rounded-pill ms-auto">3</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('calendar') }}">
                                    <i class="bi bi-calendar-event"></i>
                                    <span>Sự kiện</span>
                                </a>
                            </li>
                           
                            <li class="nav-item mt-3">
                                <small class="text-muted px-3 text-uppercase fw-bold">Admin</small>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('settings') }}">
                                    <i class="bi bi-gear"></i>
                                    <span>Cài đặt</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('security') }}">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Bảo mật</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('help') }}">
                                    <i class="bi bi-question-circle"></i>
                                    <span>Trợ giúp & Hỗ trợ</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            <!-- Floating Hamburger Menu -->
            <button class="hamburger-menu" 
                    type="button" 
                    data-sidebar-toggle
                    aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>

            <!-- Main Content -->
            <main class="admin-main">
                <div class="container-fluid p-4 p-lg-5">
                    <h1>đây là bài viết</h1>
                   

                </div>
            </main>

            <!-- Footer -->
            <footer class="admin-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0 text-muted">© 2025 Modern Bootstrap Admin Template</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-0 text-muted">Built with Bootstrap 5.3.7</p>
                        </div>
                    </div>
                </div>
            </footer>

        </div> <!-- /.admin-wrapper -->
    </div>

    <!-- Page-specific Component -->

    <!-- Main App Script -->

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const toggleButton = document.querySelector('[data-sidebar-toggle]');
        const wrapper = document.getElementById('admin-wrapper');

        if (toggleButton && wrapper) {
          // Set initial state from localStorage
          const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
          if (isCollapsed) {
            wrapper.classList.add('sidebar-collapsed');
            toggleButton.classList.add('is-active');
          }

          // Attach click listener
          toggleButton.addEventListener('click', () => {
            const isCurrentlyCollapsed = wrapper.classList.contains('sidebar-collapsed');
            
            if (isCurrentlyCollapsed) {
              wrapper.classList.remove('sidebar-collapsed');
              toggleButton.classList.remove('is-active');
              localStorage.setItem('sidebar-collapsed', 'false');
            } else {
              wrapper.classList.add('sidebar-collapsed');
              toggleButton.classList.add('is-active');
              localStorage.setItem('sidebar-collapsed', 'true');
            }
          });
        }
      });
    </script>
</body>
</html> 