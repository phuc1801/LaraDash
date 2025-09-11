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
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
                                <a class="nav-link" href="{{ route('article') }}">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Bài viết</span>
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

                <div class="container-fluid p-4 p-lg-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 mb-lg-5">
                        <div>
                            <h1 class="h3 mb-0">Quản lý bài viết</h1>
                            <p class="text-muted mb-0">
                            Theo dõi bài viết, quản lý việc xuất bản và phân tích hiệu quả nội dung
                            </p>
                        </div>
                        <div class="d-flex gap-2" x-data="exportPostsComponent" x-init="init()">
                            <button type="button" class="btn btn-outline-secondary" @click="exportPosts()">
                            <i class="bi bi-download me-2"></i>Xuất Excel
                            </button>
                            
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#postModal">
                            <i class="bi bi-plus-lg me-2"></i>Tạo bài mới
                            </button>
                        </div>
                    </div>
                    <!-- Modal Tạo bài viết -->
                    <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                            <form id="createPostForm">
                                <div class="modal-header">
                                <h5 class="modal-title" id="postModalLabel">Tạo bài viết mới</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                <!-- Tiêu đề -->
                                <div class="mb-3">
                                    <label class="form-label">Tiêu đề</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>

                                <!-- Nội dung -->
                                <div class="mb-3">
                                    <label class="form-label">Nội dung</label>
                                    <textarea class="form-control" name="content" rows="4" required></textarea>
                                </div>

                                <!-- Type -->
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <input type="text" class="form-control" name="type" value="1" required>
                                </div>

                                <!-- User ID -->
                                <div class="mb-3">
                                    <label class="form-label">User ID</label>
                                    <input type="text" class="form-control" name="user_id" value="{{ auth()->id() }}" required>
                                </div>

                                <!-- Images -->
                                <div class="mb-3">
                                    <label class="form-label">Ảnh</label>
                                    <input type="file" class="form-control" id="images" name="images[]" multiple>
                                    <div id="previewImages" class="d-flex flex-wrap mt-2 gap-2"></div>
                                </div>
                                </div>

                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Tạo bài viết</button>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>


                    <!-- Product Stats Widgets -->
                    <div class="row g-4 g-lg-5 mb-5" x-data="productStats" x-init="init()">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card stats-card">
                                <div class="card-body p-3 p-lg-4">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon bg-primary bg-opacity-10 text-primary me-3">
                                            <i class="bi bi-box"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-muted">Tổng số bài viết</h6>
                                            <h3 class="mb-0" x-text="stats.total"></h3>
                                            <small class="text-success">
                                                <i class="bi bi-arrow-up"></i> Đã được xuất bản
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6">
                            <div class="card stats-card">
                                <div class="card-body p-3 p-lg-4">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon bg-success bg-opacity-10 text-success me-3">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-muted">Bài viết đã xuất bản</h6>
                                            <h3 class="mb-0" x-text="stats.inStock"></h3>
                                            <small class="text-success">
                                                <i class="bi bi-arrow-up"></i> đầy đủ
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6">
                            <div class="card stats-card">
                                <div class="card-body p-3 p-lg-4">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon bg-warning bg-opacity-10 text-warning me-3">
                                            <i class="bi bi-exclamation-triangle"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-muted">Bài viết nháp</h6>
                                            <h3 class="mb-0" x-text="stats.outOfStock"></h3>
                                            <small class="text-warning">
                                                <i class="bi bi-exclamation-circle"></i> Chưa xuất bản
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6">
                            <div class="card stats-card">
                                <div class="card-body p-3 p-lg-4">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon bg-info bg-opacity-10 text-info me-3">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-muted">Tương tác</h6>
                                            <h3 class="mb-0" x-text="`${stats.totalValue.toLocaleString()}`"></h3>
                                            <small class="text-info">
                                                <i class="bi bi-arrow-up"></i> Tổng lượt xem
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table và Alpine.js -->
                    <div x-data="articlesManager()" x-init="fetchArticles()" class="table-responsive">

                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                <h5 class="card-title mb-0">Danh mục sản phẩm</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex gap-2">
                                        <!-- Search -->
                                        <div class="position-relative">
                                        <input type="search" class="form-control form-control-sm"
                                            placeholder="Tìm kiếm bài viết..."
                                            x-model="searchQuery"
                                            @input="filterProducts()"
                                            style="width: 200px;">
                                        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-2 text-muted"></i>
                                        </div>

                                        <!-- Category Filter -->
                                        <select class="form-select form-select-sm"
                                                x-model="categoryFilter"
                                                @change="filterProducts()"
                                                style="width: 150px;">
                                            <option value="">Tất cả bài viết</option>
                                            <option value="published">Đã xuất bản</option>
                                            <option value="draft">Chưa xuất bản</option>                               
                                        </select>

                                        <!-- Stock Filter -->
                                        <select 
                                            class="form-select form-select-sm" 
                                            x-model="stockFilter"
                                            style="width: 150px;"
                                        >
                                            <option value="">Tất cả ngày</option>
                                            <option value="in-stock">Trong một ngày</option>
                                            <option value="low-stock">Trong một tuần</option>
                                            <option value="out-of-stock">Trong một tháng</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">
                                <input type="checkbox" class="form-check-input"
                                        @change="toggleAll($event.target.checked)"
                                        :checked="selectedArticles.length === paginatedArticles.length && paginatedArticles.length > 0">
                                </th>
                                <th>Bài viết</th>
                                <th @click="sortBy('type')" class="sortable">Loại</th>
                                <th @click="sortBy('user_id')" class="sortable">Tác giả</th>
                                <th @click="sortBy('created_at')" class="sortable">Ngày tạo</th>
                                <th style="width: 120px;">Hoạt động</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template x-for="article in paginatedArticles" :key="article.id">
                                <tr>
                                <td><input type="checkbox" class="form-check-input" :value="article.id" x-model="selectedArticles"></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                    <img :src="article.images.length ? 'http://127.0.0.1:8000/assets/img/articles/' + article.images[0].image : 'default.jpg'"
                                        class="me-3" style="width:50px;height:50px;object-fit:cover;border-radius:5px;">
                                    <div>
                                        <div class="fw-medium" x-text="article.title"></div>
                                        <small class="text-muted" x-text="'ID: ' + article.id"></small>
                                    </div>
                                    </div>
                                </td>
                                <td><span x-text="article.type"></span></td>
                                <td x-text="article.author_name"></td>
                                <td x-text="new Date(article.created_at).toLocaleDateString()"></td>
                                <td>
                                    <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" @click.prevent="viewArticle(article)"><i class="bi bi-eye me-2"></i>View</a></li>
                                        <li><a class="dropdown-item" href="#" @click.prevent="editArticle(article)"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                        <li><a class="dropdown-item text-danger" href="#" @click.prevent="deleteArticle(article)"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                    </ul>
                                    </div>
                                </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <div class="text-muted">
                            Showing <span x-text="(currentPage - 1) * itemsPerPage + 1"></span> to 
                            <span x-text="Math.min(currentPage * itemsPerPage, filteredArticles.length)"></span> of 
                            <span x-text="filteredArticles.length"></span> results
                            </div>
                            <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                                <a class="page-link" href="#" @click.prevent="goToPage(currentPage - 1)">Previous</a>
                                </li>
                                <template x-for="page in totalPages" :key="page">
                                <li class="page-item" :class="{ 'active': page === currentPage }">
                                    <a class="page-link" href="#" @click.prevent="goToPage(page)" x-text="page"></a>
                                </li>
                                </template>
                                <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                                <a class="page-link" href="#" @click.prevent="goToPage(currentPage + 1)">Next</a>
                                </li>
                            </ul>
                            </nav>
                        </div>

                        <!-- Modal xem bài viết -->
                        <div class="modal fade" id="viewArticleModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" x-text="modalTitle"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                <img x-show="modalImage" :src="modalImage" class="img-fluid mb-3">
                                <p x-text="modalContent"></p>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                            </div>
                        </div>

                                            <!-- Modal chỉnh sửa bài viết -->
                    <div class="modal fade" id="editArticleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Article</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Title -->
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" class="form-control" x-model="editArticleData.title">
        </div>

        <!-- Content -->
        <div class="mb-3">
          <label class="form-label">Content</label>
          <textarea class="form-control" x-model="editArticleData.content" rows="5"></textarea>
        </div>

        <!-- Upload new images -->
        <div class="mb-3">
          <label class="form-label">Upload New Images</label>
          <input type="file" id="editImages" multiple class="form-control">
        </div>

        <!-- Preview / Delete existing images -->
        <div class="mb-3">
          <label class="form-label">Preview / Delete Existing Images</label>
          <div id="editPreviewImages" class="d-flex flex-wrap"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" @click="saveEdit()">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

                    </div>
                </div>
            </main>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
            <script src="assets/js/article.js"></script>
            <script src="assets/js/articleStats.js"></script>
            <script src="assets/js/articleExport.js"></script>
            



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
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
</body>
</html> 