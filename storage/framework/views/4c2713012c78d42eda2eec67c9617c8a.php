<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Welcome Banner -->
    <div class="card card-gradient mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="bi bi-hand-wave me-2"></i>
                        Xin chào, <?php echo e(auth()->user()->name); ?>!
                    </h1>
                    <p class="lead mb-0 opacity-75">Chúc bạn một ngày làm việc hiệu quả và thành công</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="<?php echo e(route('attendance.check-in')); ?>" class="btn btn-light btn-lg">
                        <i class="bi bi-fingerprint me-2"></i>
                        Chấm công ngay
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="bi bi-check-circle text-white"></i>
                </div>
                <h6 class="text-muted mb-2 fw-semibold">Chấm công hôm nay</h6>
                <h3 class="fw-bold mb-3">
                    <?php if($todayAttendance): ?>
                        <span class="text-success">Đã chấm</span>
                    <?php else: ?>
                        <span class="text-warning">Chưa chấm</span>
                    <?php endif; ?>
                </h3>
                <?php if(!$todayAttendance): ?>
                <a href="<?php echo e(route('attendance.check-in')); ?>" class="btn btn-sm btn-gradient">
                    <i class="bi bi-arrow-right"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <i class="bi bi-calendar-check text-white"></i>
                </div>
                <h6 class="text-muted mb-2 fw-semibold">Ngày làm việc</h6>
                <h3 class="fw-bold mb-0"><?php echo e($monthlyWorkingDays ?? 0); ?></h3>
                <small class="text-muted">Tháng này</small>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="bi bi-file-earmark-text text-white"></i>
                </div>
                <h6 class="text-muted mb-2 fw-semibold">Đơn xin nghỉ</h6>
                <h3 class="fw-bold mb-0"><?php echo e($pendingLeaves ?? 0); ?></h3>
                <small class="text-muted">Chờ duyệt</small>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <i class="bi bi-cash-stack text-white"></i>
                </div>
                <h6 class="text-muted mb-2 fw-semibold">Lương tháng này</h6>
                <h3 class="fw-bold mb-0"><?php echo e(number_format(($currentMonthSalary ?? 0) / 1000000, 1)); ?>M</h3>
                <small class="text-muted"><?php echo e(number_format($currentMonthSalary ?? 0, 0, ',', '.')); ?>đ</small>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Today's Attendance -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="stat-icon me-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); width: 48px; height: 48px;">
                            <i class="bi bi-clock-history text-white fs-5"></i>
                        </div>
                        <h5 class="card-title mb-0 fw-bold">Chấm công hôm nay</h5>
                    </div>

                    <?php if($todayAttendance): ?>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #11998e15 0%, #38ef7d15 100%);">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-box-arrow-in-right text-success fs-4 me-2"></i>
                                    <span class="text-muted small">Check-in</span>
                                </div>
                                <h4 class="fw-bold mb-0">
                                    <?php echo e($todayAttendance->check_in_at ? \Carbon\Carbon::parse($todayAttendance->check_in_at)->format('H:i') : '--:--'); ?>

                                </h4>
                                <?php if($todayAttendance->check_in_at): ?>
                                <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($todayAttendance->check_in_at)->format('d/m/Y')); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%);">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-box-arrow-right text-info fs-4 me-2"></i>
                                    <span class="text-muted small">Check-out</span>
                                </div>
                                <h4 class="fw-bold mb-0">
                                    <?php echo e($todayAttendance->check_out_at ? \Carbon\Carbon::parse($todayAttendance->check_out_at)->format('H:i') : '--:--'); ?>

                                </h4>
                                <?php if($todayAttendance->check_out_at): ?>
                                <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($todayAttendance->check_out_at)->format('d/m/Y')); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-clock text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3 mb-4">Bạn chưa chấm công hôm nay</p>
                        <a href="<?php echo e(route('attendance.check-in')); ?>" class="btn btn-gradient">
                            <i class="bi bi-fingerprint me-2"></i>
                            Chấm công ngay
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Leave Requests -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon me-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); width: 48px; height: 48px;">
                                <i class="bi bi-file-earmark-text text-white fs-5"></i>
                            </div>
                            <h5 class="card-title mb-0 fw-bold">Đơn xin nghỉ</h5>
                        </div>
                        <a href="<?php echo e(route('leave-requests.index')); ?>" class="btn btn-sm btn-outline-primary">
                            Xem tất cả <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <?php if($pendingLeaves > 0): ?>
                    <div class="alert alert-warning d-flex align-items-center mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            Bạn có <strong><?php echo e($pendingLeaves); ?></strong> đơn đang chờ duyệt
                        </div>
                    </div>
                    <?php endif; ?>

                    <a href="<?php echo e(route('leave-requests.create')); ?>" class="btn btn-outline-primary w-100 py-3 border-2 border-dashed">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tạo đơn xin nghỉ mới
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Admin\Documents\GitHub\QLNV_KLTN\resources\views/dashboard.blade.php ENDPATH**/ ?>