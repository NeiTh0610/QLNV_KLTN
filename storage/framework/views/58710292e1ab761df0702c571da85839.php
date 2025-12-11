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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            margin: 0;
        }

        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        .app-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .sidebar {
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-self: flex-start;
            flex-shrink: 0;
        }
        
        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-nav {
            padding: 1.5rem 1rem;
            flex: 1;
            padding-bottom: 2rem;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.875rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .nav-link i {
            font-size: 1.25rem;
            margin-right: 0.75rem;
            width: 24px;
        }
        
        .main-content {
            padding: 2rem;
            transition: all 0.3s ease;
            flex: 1;
        }
        
        .main-content-wrapper {
            margin-top: 1.5rem;
        }
        
        .top-navbar {
            background: white;
            padding: 1rem 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .card-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stat-card {
            padding: 1.5rem;
            border-radius: 16px;
            background: white;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }
        
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .badge-custom {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .user-profile {
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            border-radius: 12px;
            margin-top: auto;
        }
        
        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
        }
        
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
        
        .alert-gradient {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
        }
        
        .alert-success-gradient {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        
        .alert-danger-gradient {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
        }
        
        .time-badge {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
        }
        
        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1.5rem;
        }

        /* Flatpickr Custom Dark Theme Styles - Trực quan rõ ràng */
        .flatpickr-calendar {
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            border: 2px solid rgba(102, 126, 234, 0.3);
            font-family: 'Poppins', sans-serif;
            background: #1a202c;
            color: #ffffff;
            width: 100%;
            max-width: 520px;
            min-width: 480px;
            overflow: hidden;
            padding: 0;
            margin: 0;
        }

        .flatpickr-months {
            border-radius: 20px 20px 0 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.25rem 1.5rem;
            border-bottom: 3px solid rgba(255,255,255,0.2);
            margin: 0;
        }

        .flatpickr-month {
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .flatpickr-current-month {
            color: white;
            font-weight: 800;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex: 1;
            text-align: center;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 150px;
            text-align: center;
            white-space: nowrap;
            overflow: visible;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .flatpickr-current-month .numInputWrapper {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            padding: 0.5rem 0.9rem;
            min-width: 95px;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .flatpickr-current-month .numInputWrapper input {
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            background: transparent;
            border: none;
            text-align: center;
            width: 100%;
            padding: 0;
            cursor: pointer;
        }

        .flatpickr-current-month .numInputWrapper input:hover {
            color: #ffd700;
        }

        .flatpickr-current-month .numInputWrapper .arrowUp,
        .flatpickr-current-month .numInputWrapper .arrowDown {
            color: white;
            border-color: rgba(255,255,255,0.4);
            width: 24px;
            height: 24px;
            line-height: 24px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            background: rgba(255,255,255,0.1);
            transition: all 0.2s ease;
        }

        .flatpickr-current-month .numInputWrapper .arrowUp:hover,
        .flatpickr-current-month .numInputWrapper .arrowDown:hover {
            color: #ffd700;
            border-color: rgba(255,255,255,0.6);
            background: rgba(255,255,255,0.25);
            transform: scale(1.05);
        }

        .flatpickr-monthDropdown-months {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23ffffff' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 12px;
            padding-right: 2.75rem !important;
        }

        .flatpickr-monthDropdown-months option {
            background: #ffffff;
            color: #1a202c;
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .flatpickr-monthDropdown-months option:hover,
        .flatpickr-monthDropdown-months option:focus {
            background: #667eea;
            color: #ffffff;
        }
        
        .flatpickr-monthDropdown-months option:checked {
            background: #667eea;
            color: #ffffff;
            font-weight: 700;
        }

        .flatpickr-prev-month,
        .flatpickr-next-month {
            color: white;
            fill: white;
            padding: 0.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.15);
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .flatpickr-prev-month:hover,
        .flatpickr-next-month:hover {
            background: rgba(255,255,255,0.3);
            color: #ffd700;
            fill: #ffd700;
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .flatpickr-weekdays {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%);
            border-bottom: 2px solid rgba(255,255,255,0.15);
            padding: 1rem 1.5rem;
            margin: 0;
            display: flex;
            width: 100%;
            gap: 4px;
        }

        .flatpickr-weekday {
            color: #ffffff;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.5rem 0;
            flex: 1;
            min-width: 0;
            text-align: center;
            box-sizing: border-box;
            text-shadow: 0 2px 4px rgba(0,0,0,0.4);
        }

        .flatpickr-days {
            background: #1a202c;
            padding: 1rem 1.5rem;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            gap: 6px;
        }

        .flatpickr-day {
            color: #ffffff;
            border-radius: 12px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            font-weight: 700;
            font-size: 1rem;
            height: 44px;
            line-height: 40px;
            margin: 0;
            flex: 1 1 calc(14.28% - 5px);
            min-width: 0;
            max-width: none;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            background: rgba(255,255,255,0.05);
        }

        .flatpickr-day:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.4) 0%, rgba(118, 75, 162, 0.4) 100%);
            border-color: rgba(102, 126, 234, 0.6);
            color: #ffffff;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #ffffff;
            color: #ffffff;
            font-weight: 800;
            font-size: 1.1rem;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            transform: scale(1.1);
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        .flatpickr-day.today {
            border-color: #ffd700;
            color: #ffd700;
            font-weight: 800;
            background: rgba(255, 215, 0, 0.2);
            border-width: 3px;
            text-shadow: 0 2px 4px rgba(255, 215, 0, 0.6);
            box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.3);
        }

        .flatpickr-day.today:hover {
            background: rgba(255, 215, 0, 0.3);
            border-color: #ffd700;
            color: #ffd700;
            text-shadow: 0 2px 4px rgba(255, 215, 0, 0.8);
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.4);
        }

        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #6b7280;
            opacity: 0.5;
            font-weight: 500;
            text-shadow: none;
            background: rgba(255,255,255,0.02);
        }

        .flatpickr-day.flatpickr-disabled:hover {
            background: rgba(255,255,255,0.05);
            border-color: transparent;
            transform: none;
        }

        .flatpickr-input {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            color: #2d3748;
        }

        .flatpickr-input:focus {
            border-color: #00d4ff;
            box-shadow: 0 0 0 0.2rem rgba(0, 212, 255, 0.25);
        }

        .flatpickr-time {
            border-top: 1px solid rgba(255,255,255,0.1);
            background: #1a202c;
        }

        .flatpickr-time input {
            font-weight: 500;
            color: #e2e8f0;
            background: transparent;
        }

        .flatpickr-time input:hover {
            background: rgba(255,255,255,0.1);
        }

        .flatpickr-time .flatpickr-time-separator {
            color: #e2e8f0;
        }

        .flatpickr-am-pm {
            color: #e2e8f0;
        }

        .flatpickr-am-pm:hover {
            background: rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <div class="app-layout">
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
        // Sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Update time
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('currentDateTime').textContent = 
                now.toLocaleDateString('vi-VN', options);
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Initialize Flatpickr for all date inputs
        document.addEventListener('DOMContentLoaded', function() {
            // Default options for date picker
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

            // Initialize all date inputs
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(function(input) {
                const options = { ...defaultOptions };
                
                // Set min date if attribute exists
                if (input.hasAttribute('min')) {
                    options.minDate = input.getAttribute('min');
                }
                
                // Set max date if attribute exists
                if (input.hasAttribute('max')) {
                    options.maxDate = input.getAttribute('max');
                }
                
                // Convert value from YYYY-MM-DD to Date object if exists
                if (input.value) {
                    options.defaultDate = input.value;
                }
                
                const fp = flatpickr(input, options);
                
                // Improve year selector - make it more visible
                if (fp.calendarContainer) {
                    const yearInput = fp.calendarContainer.querySelector('.numInputWrapper input');
                    if (yearInput) {
                        yearInput.setAttribute('title', 'Nhấn để chọn năm');
                        yearInput.style.cursor = 'pointer';
                    }
                }
            });

            // Initialize datetime-local inputs
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