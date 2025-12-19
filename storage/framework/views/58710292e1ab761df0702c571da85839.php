<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - Hệ thống chấm công</title>
    <link rel="manifest" href="<?php echo e(asset('manifest.webmanifest')); ?>">
    <meta name="theme-color" content="#0f766e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="apple-touch-icon" href="<?php echo e(asset('icons/icon-192x192.png')); ?>">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        :root {
            --bg: #f6f7fb;
            --card: rgba(255, 255, 255, 0.9);
            --card-strong: #ffffff;
            --glass-border: rgba(255, 255, 255, 0.6);
            --shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
            --primary: #6366f1;
            --primary-weak: #eef2ff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0ea5e9;
            --text: #0f172a;
            --muted: #6b7280;
            --radius: 14px;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: radial-gradient(circle at 20% 20%, #eef2ff 0, #eef2ff 28%, transparent 30%), 
                        radial-gradient(circle at 80% 0%, #e0f2fe 0, #e0f2fe 25%, transparent 28%),
                        var(--bg);
            min-height: 100vh;
            margin: 0;
            color: var(--text);
        }

        .app-layout { display: flex; min-height: 100vh; }
        .app-main { flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, #111827 0%, #1f2937 100%);
            min-height: 100vh;
            position: sticky;
            top: 0;
            width: 260px;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.75rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .sidebar-nav {
            padding: 1.25rem 1rem 2rem;
            flex: 1;
            overflow-y: auto;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.85rem 1rem;
            border-radius: 12px;
            margin-bottom: 0.45rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1px;
            transition: all 0.2s ease;
        }

        .nav-link i { width: 22px; text-align: center; }

        .nav-link:hover { background: rgba(255, 255, 255, 0.08); color: #fff; transform: translateX(4px); }
        .nav-link.active { background: rgba(99, 102, 241, 0.18); color: #fff; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.28); }

        /* Main content */
        .main-content { padding: 2rem; flex: 1; }
        .main-content-wrapper { margin-top: 1.25rem; }

        .top-navbar {
            background: var(--card);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1rem 1.25rem;
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
            margin-bottom: 1.5rem;
        }

        /* Cards */
        .card {
            border: 1px solid var(--glass-border);
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover { transform: translateY(-4px); box-shadow: 0 24px 50px rgba(15, 23, 42, 0.12); }
        .card-gradient { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: #fff; }

        .stat-card {
            padding: 1.25rem;
            position: relative;
            background: var(--card-strong);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.08), transparent 50%);
            pointer-events: none;
        }

        .stat-icon {
            width: 54px; height: 54px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.35rem; font-weight: 700; color: #fff;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            box-shadow: 0 12px 30px rgba(99, 102, 241, 0.35);
        }

        /* Buttons */
        .btn-gradient, .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border: none;
            color: #fff;
            padding: 0.75rem 1.4rem;
            border-radius: 12px;
            font-weight: 700;
            letter-spacing: 0.1px;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.35);
            transition: all 0.2s ease;
        }

        .btn-gradient:hover, .btn-primary:hover { transform: translateY(-1px); color: #fff; }
        .btn-ghost { background: transparent; border: 1px solid var(--glass-border); color: var(--text); border-radius: 12px; }

        /* Badges */
        .badge-custom { padding: 0.45rem 0.9rem; border-radius: 10px; font-weight: 700; }
        .badge-soft-success { background: #ecfdf3; color: #15803d; }
        .badge-soft-warning { background: #fffbeb; color: #b45309; }
        .badge-soft-danger { background: #fef2f2; color: #b91c1c; }
        .badge-soft-info { background: #e0f2fe; color: #0369a1; }

        /* Alerts */
        .alert-gradient { border: none; border-radius: 12px; padding: 1rem 1.25rem; box-shadow: var(--shadow); }
        .alert-success-gradient { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; }
        .alert-danger-gradient { background: linear-gradient(135deg, #ef4444, #f97316); color: #fff; }

        /* Time badge */
        .time-badge {
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            color: #fff;
            padding: 0.55rem 1rem;
            border-radius: 12px;
            font-weight: 700;
            box-shadow: 0 12px 30px rgba(99, 102, 241, 0.28);
        }

        .section-title { font-size: 1.7rem; font-weight: 800; color: var(--text); }

        /* Profile */
        .user-profile {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(14, 165, 233, 0.08));
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 12px;
            margin-top: 1rem; /* nâng khu vực logout lên, tránh thừa khoảng trống đáy */
        }

        .user-avatar {
            width: 48px; height: 48px; border-radius: 12px;
            background: linear-gradient(135deg, #22c55e, #10b981);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem; font-weight: 800; color: #fff;
        }

        /* Table responsive improvements */
        @media (max-width: 768px) {
            .table-responsive { border-radius: 12px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .table { min-width: 800px; font-size: 0.9rem; }
            .table th, .table td { padding: 0.5rem 0.75rem; white-space: nowrap; }
            .table .btn { font-size: 0.8rem; padding: 0.35rem 0.65rem; }
        }

        /* Form responsive */
        @media (max-width: 768px) {
            .main-content { padding: 1rem; }
            .card-body { padding: 1rem; }
        }
        @media (max-width: 576px) { .main-content { padding: 0.75rem; } }

        /* Mobile Sidebar */
        @media (max-width: 992px) {
            .sidebar { position: fixed; height: 100vh; transform: translateX(-100%); width: 260px; }
            .sidebar.show { transform: translateX(0); }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 990; }
            .sidebar-overlay.show { display: block; }
            .top-navbar { margin-bottom: 1rem; }
            .time-badge span { display: none; }
        }

        /* Flatpickr light theme */
        .flatpickr-calendar {
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.2);
            border: 1px solid rgba(99, 102, 241, 0.2);
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: var(--text);
        }
        .flatpickr-months { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; border-radius: 16px 16px 0 0; }
        .flatpickr-weekday { color: #1f2937; font-weight: 700; }
        .flatpickr-day { border-radius: 10px; }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35);
        }
        .flatpickr-day.today { border: 1px solid #6366f1; color: #6366f1; }
    </style>
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar Overlay (mobile) -->
        <div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column" id="sidebar">
            <div class="sidebar-brand">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-3 p-2 me-3">
                        <i class="bi bi-clock-history text-primary fs-3"></i>
                    </div>
                    <div>
                        <h5 class="text-white mb-0 fw-bold">Chấm Công</h5>
                        <small class="text-white-50">Hệ thống quản lý</small>
                    </div>
                </div>
            </div>

            <div class="sidebar-nav flex-grow-1">
                <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="<?php echo e(route('attendance.check-in')); ?>" class="nav-link <?php echo e(request()->routeIs('attendance.check-in') ? 'active' : ''); ?>">
                    <i class="bi bi-fingerprint"></i>
                    <span>Chấm công</span>
                </a>
                
                <a href="<?php echo e(route('attendance.history')); ?>" class="nav-link <?php echo e(request()->routeIs('attendance.history') ? 'active' : ''); ?>">
                    <i class="bi bi-clock-history"></i>
                    <span>Lịch sử chấm công</span>
                </a>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-employees')): ?>
                <div class="text-white-50 text-uppercase small fw-bold px-3 mt-4 mb-2">Quản lý</div>
                
                <a href="<?php echo e(route('employees.index')); ?>" class="nav-link <?php echo e(request()->routeIs('employees.*') ? 'active' : ''); ?>">
                    <i class="bi bi-people"></i>
                    <span>Nhân viên</span>
                </a>
                
                <a href="<?php echo e(route('reports.index')); ?>" class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                    <i class="bi bi-bar-chart"></i>
                    <span>Báo cáo</span>
                </a>
                <?php endif; ?>
                
                <a href="<?php echo e(route('leave-requests.index')); ?>" class="nav-link <?php echo e(request()->routeIs('leave-requests.*') ? 'active' : ''); ?>">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Đơn xin nghỉ</span>
                </a>
                
                <a href="<?php echo e(route('overtime-requests.index')); ?>" class="nav-link <?php echo e(request()->routeIs('overtime-requests.*') ? 'active' : ''); ?>">
                    <i class="bi bi-clock-history"></i>
                    <span>Đăng ký làm thêm giờ</span>
                </a>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-employees')): ?>
                <a href="<?php echo e(route('payroll.index')); ?>" class="nav-link <?php echo e(request()->routeIs('payroll.index') || request()->routeIs('payroll.show') || request()->routeIs('payroll.generate') ? 'active' : ''); ?>">
                    <i class="bi bi-cash-stack"></i>
                    <span>Quản lý lương</span>
                </a>
                <?php else: ?>
                <a href="<?php echo e(route('payroll.my-payroll')); ?>" class="nav-link <?php echo e(request()->routeIs('payroll.my-payroll') ? 'active' : ''); ?>">
                    <i class="bi bi-wallet2"></i>
                    <span>Bảng lương của tôi</span>
                </a>
                <?php endif; ?>

                <?php if(auth()->user()->hasRole('admin')): ?>
                <div class="text-white-50 text-uppercase small fw-bold px-3 mt-4 mb-2">Quản trị</div>
                
                <a href="<?php echo e(route('employee-profiles.index')); ?>" class="nav-link <?php echo e(request()->routeIs('employee-profiles.*') ? 'active' : ''); ?>">
                    <i class="bi bi-file-person"></i>
                    <span>Hồ sơ nhân viên</span>
                </a>
                
                <a href="<?php echo e(route('contracts.index')); ?>" class="nav-link <?php echo e(request()->routeIs('contracts.*') ? 'active' : ''); ?>">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Hợp đồng</span>
                </a>
                
                <a href="<?php echo e(route('settings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">
                    <i class="bi bi-gear"></i>
                    <span>Cài đặt</span>
                </a>
                <?php endif; ?>
            </div>

            <div class="p-3">
                <div class="user-profile">
                    <div class="d-flex align-items-center mb-3">
                        <div class="user-avatar me-3">
                            <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                        </div>
                        <div class="flex-grow-1 text-white">
                            <div class="fw-semibold"><?php echo e(auth()->user()->name); ?></div>
                            <small class="text-white-50"><?php echo e(auth()->user()->email); ?></small>
                        </div>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-light btn-sm w-100">
                            <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cột nội dung + footer -->
        <div class="app-main">
            <!-- Main Content -->
            <div class="main-content">
                <!-- Top Navbar -->
                <div class="top-navbar">
                    <div class="d-flex align-items-center justify-content-between">
                        <button class="btn btn-light d-lg-none" id="sidebarToggle">
                            <i class="bi bi-list"></i>
                        </button>
                        <div class="flex-grow-1"></div>
                        <div class="time-badge">
                            <i class="bi bi-clock me-2"></i>
                            <span id="currentDateTime"></span>
                        </div>
                    </div>
                </div>

                <!-- Alerts -->
                <?php if(session('success')): ?>
                <div class="alert alert-success-gradient alert-gradient alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Thành công!</strong> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                <div class="alert alert-danger-gradient alert-gradient alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Lỗi!</strong> <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                <div class="alert alert-danger-gradient alert-gradient alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Có lỗi xảy ra:</strong>
                    <ul class="mb-0 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Page Content -->
                <div class="main-content-wrapper">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>

            <!-- Footer -->
            <footer class="main-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 footer-section">
                            <h5 class="footer-title">
                                <i class="bi bi-building"></i>
                                Về Công Ty
                            </h5>
                            <p class="footer-text">
                                <strong>Công Ty TNHH PHẠM NGỌC THIỆN</strong>
                            </p>
                            <p class="footer-text">
                                Hệ thống quản lý nhân sự và chấm công hiện đại, 
                                giúp doanh nghiệp quản lý hiệu quả và chuyên nghiệp.
                            </p>
                        </div>
                        
                        <div class="col-md-4 footer-section">
                            <h5 class="footer-title">
                                <i class="bi bi-geo-alt"></i>
                                Thông Tin Liên Hệ
                            </h5>
                            <div class="footer-contact">
                                <p class="footer-text">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>Hà Nội - Quảng Ninh </span>
                                </p>
                                <p class="footer-text">
                                    <i class="bi bi-telephone-fill"></i>
                                    <a href="tel:+84123456789">+84 983 383 135</a>
                                </p>
                                <p class="footer-text">
                                    <i class="bi bi-envelope-fill"></i>
                                    <a href="mailto:contact@company.com">thien123qnvn@gmail.com</a>
                                </p>
                                <p class="footer-text">
                                    <i class="bi bi-globe"></i>
                                    <a href="https://www.company.com" target="_blank">www.goldenbown.com</a>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-4 footer-section">
                            <h5 class="footer-title">
                                <i class="bi bi-info-circle"></i>
                                Thông Tin Hệ Thống
                            </h5>
                            <div class="footer-info">
                                <p class="footer-text">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Hệ thống bảo mật cao</span>
                                </p>
                                <p class="footer-text">
                                    <i class="bi bi-clock-history"></i>
                                    <span>Hỗ trợ 24/7</span>
                                </p>
                                <p class="footer-text">
                                    <i class="bi bi-people"></i>
                                    <span>Phục vụ hàng nghìn nhân viên</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="footer-divider"></div>
                    
                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="footer-copyright">
                                <i class="bi bi-c-circle"></i>
                                <?php echo e(date('Y')); ?> <strong>Công Ty TNHH PHẠM NGỌC THIỆN</strong>. 
                                Tất cả quyền được bảo lưu.
                            </p>
                            <p class="footer-version">
                                <i class="bi bi-code-slash"></i>
                                Phiên bản 1.0.0 | Phát triển bởi <strong>NeiTh</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        function openSidebar() {
            sidebar?.classList.add('show');
            sidebarOverlay?.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            sidebar?.classList.remove('show');
            sidebarOverlay?.classList.remove('show');
            document.body.style.overflow = '';
        }
        
        sidebarToggle?.addEventListener('click', function() {
            if (sidebar?.classList.contains('show')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
        
        sidebarOverlay?.addEventListener('click', closeSidebar);
        
        if (window.innerWidth < 992) {
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', closeSidebar);
            });
        }
        
        function updateDateTime() {
            const now = new Date();
            const isMobile = window.innerWidth < 992;
            const options = { 
                weekday: isMobile ? 'short' : 'long', 
                year: 'numeric', 
                month: isMobile ? 'short' : 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: isMobile ? undefined : '2-digit'
            };
            document.getElementById('currentDateTime').textContent = 
                now.toLocaleDateString('vi-VN', options);
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);
        
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                closeSidebar();
            }
        });

        // Initialize Flatpickr for all date inputs
        document.addEventListener('DOMContentLoaded', function() {
            const defaultOptions = {
                locale: 'vn',
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd/m/Y',
                allowInput: true,
                clickOpens: true,
                animate: true,
                monthSelectorType: 'dropdown',
                static: true,
                inline: false,
                showMonths: 1,
                enableTime: false,
                time_24hr: false,
                minDate: null,
                maxDate: null,
            };

            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(function(input) {
                const options = { ...defaultOptions };
                
                if (input.hasAttribute('min')) {
                    options.minDate = input.getAttribute('min');
                }
                
                if (input.hasAttribute('max')) {
                    options.maxDate = input.getAttribute('max');
                }
                
                if (input.value) {
                    options.defaultDate = input.value;
                }
                
                const fp = flatpickr(input, options);
                
                if (fp.calendarContainer) {
                    const yearInput = fp.calendarContainer.querySelector('.numInputWrapper input');
                    if (yearInput) {
                        yearInput.setAttribute('title', 'Nhấn để chọn năm');
                        yearInput.style.cursor = 'pointer';
                    }
                }
            });

            const datetimeInputs = document.querySelectorAll('input[type="datetime-local"]');
            datetimeInputs.forEach(function(input) {
                const options = {
                    ...defaultOptions,
                    enableTime: true,
                    time_24hr: true,
                    dateFormat: 'Y-m-d H:i',
                    altFormat: 'd/m/Y H:i',
                };
                
                if (input.value) {
                    options.defaultDate = input.value;
                }
                
                flatpickr(input, options);
            });
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    
    <style>
        /* Footer Styles */
        .main-footer {
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
            color: #e2e8f0;
            padding: 3rem 2rem 1.5rem;
            margin-top: 2rem;
            border-top: 3px solid rgba(102, 126, 234, 0.3);
            box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
        }
        
        .footer-section {
            margin-bottom: 2rem;
        }
        
        .footer-title {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .footer-title i {
            color: #667eea;
            font-size: 1.3rem;
        }
        
        .footer-text {
            color: #cbd5e0;
            font-size: 0.95rem;
            line-height: 1.8;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .footer-text i {
            color: #667eea;
            font-size: 1rem;
            margin-top: 0.2rem;
            flex-shrink: 0;
        }
        
        .footer-text strong {
            color: #ffffff;
            font-weight: 600;
        }
        
        .footer-text a {
            color: #cbd5e0;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-text a:hover {
            color: #667eea;
            text-decoration: underline;
        }
        
        .footer-contact p,
        .footer-info p {
            margin-bottom: 0.6rem;
        }
        
        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(102, 126, 234, 0.3) 50%, 
                transparent 100%);
            margin: 2rem 0 1.5rem;
        }
        
        .footer-copyright {
            color: #9ca3af;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .footer-copyright i {
            font-size: 0.85rem;
            margin-right: 0.25rem;
        }
        
        .footer-version {
            color: #718096;
            font-size: 0.85rem;
            margin-bottom: 0;
        }
        
        .footer-version i {
            font-size: 0.8rem;
            margin-right: 0.25rem;
        }
        
        .footer-copyright strong,
        .footer-version strong {
            color: #cbd5e0;
            font-weight: 600;
        }
        
        /* Responsive Footer */
        @media (max-width: 768px) {
            .main-footer {
                padding: 2rem 1.5rem 1rem;
                margin-top: 2rem;
            }
            
            .footer-section {
                margin-bottom: 2rem;
                text-align: center;
            }
            
            .footer-title {
                justify-content: center;
            }
            
            .footer-text {
                justify-content: center;
            }
        }
    </style>
</body>
</html>
<?php /**PATH C:\Users\Pham Thien\Documents\GitHub\QLNV_KLTN\resources\views/layouts/app.blade.php ENDPATH**/ ?>