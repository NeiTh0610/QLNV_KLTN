<?php $__env->startSection('title', 'Báo cáo'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            <i class="bi bi-bar-chart me-2"></i>
            Báo cáo chấm công
        </h2>
        <p class="text-muted mb-0">Thống kê và báo cáo chi tiết</p>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('reports.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Khoảng thời gian</label>
                        <select name="period" class="form-select" style="border-radius: 12px;">
                            <option value="day" <?php echo e(request('period') == 'day' ? 'selected' : ''); ?>>Theo ngày</option>
                            <option value="week" <?php echo e(request('period') == 'week' ? 'selected' : ''); ?>>Theo tuần</option>
                            <option value="month" <?php echo e(request('period') == 'month' ? 'selected' : ''); ?>>Theo tháng</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Chọn thời gian</label>
                        <input type="<?php echo e(request('period') == 'day' ? 'date' : (request('period') == 'week' ? 'week' : 'month')); ?>" 
                               name="date" 
                               value="<?php echo e(request('date', now()->format('Y-m'))); ?>"
                               class="form-control"
                               style="border-radius: 12px;">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Phòng ban</label>
                        <select name="department_id" class="form-select" style="border-radius: 12px;">
                            <option value="">Tất cả phòng ban</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dept->id); ?>" <?php echo e(request('department_id') == $dept->id ? 'selected' : ''); ?>>
                                    <?php echo e($dept->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-gradient flex-fill">
                                <i class="bi bi-search me-2"></i>
                                Xem
                            </button>
                            <a href="<?php echo e(route('reports.export', request()->all())); ?>" class="btn btn-success">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Card -->
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-calendar-range me-2"></i>
                Báo cáo từ <?php echo e($startDate->format('d/m/Y')); ?> đến <?php echo e($endDate->format('d/m/Y')); ?>

            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 fw-semibold">Mã NV</th>
                            <th class="py-3 px-4 fw-semibold">Họ tên</th>
                            <th class="py-3 px-4 fw-semibold">Phòng ban</th>
                            <th class="py-3 px-4 fw-semibold text-center">Tổng ngày</th>
                            <th class="py-3 px-4 fw-semibold text-center">Đúng giờ</th>
                            <th class="py-3 px-4 fw-semibold text-center">Đi muộn</th>
                            <th class="py-3 px-4 fw-semibold text-center">Về sớm</th>
                            <th class="py-3 px-4 fw-semibold text-center">Vắng mặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-3 px-4 fw-semibold"><?php echo e($report['user']->code); ?></td>
                            <td class="py-3 px-4"><?php echo e($report['user']->name); ?></td>
                            <td class="py-3 px-4"><?php echo e($report['user']->profile->department->name ?? '-'); ?></td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-primary rounded-pill"><?php echo e($report['total_days']); ?></span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge rounded-pill" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                                    <?php echo e($report['on_time']); ?>

                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-warning text-dark rounded-pill"><?php echo e($report['late']); ?></span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-danger rounded-pill"><?php echo e($report['early_leave']); ?></span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-dark rounded-pill"><?php echo e($report['absent']); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Không có dữ liệu báo cáo</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Department Summary Report Card -->
    <div class="card mt-4">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-building me-2"></i>
                Báo cáo tổng hợp theo phòng ban
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 fw-semibold">Phòng ban</th>
                            <th class="py-3 px-4 fw-semibold text-center">Số nhân viên</th>
                            <th class="py-3 px-4 fw-semibold text-center">Tổng ngày làm việc</th>
                            <th class="py-3 px-4 fw-semibold text-center">Đúng giờ</th>
                            <th class="py-3 px-4 fw-semibold text-center">Đi muộn</th>
                            <th class="py-3 px-4 fw-semibold text-center">Về sớm</th>
                            <th class="py-3 px-4 fw-semibold text-center">Vắng mặt</th>
                            <th class="py-3 px-4 fw-semibold text-center">% Ngày nghỉ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $departmentReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deptReport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-3 px-4 fw-semibold">
                                <?php echo e($deptReport['department']->name ?? 'Không xác định'); ?>

                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-info rounded-pill"><?php echo e($deptReport['total_employees']); ?></span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-primary rounded-pill"><?php echo e($deptReport['total_days']); ?></span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge rounded-pill" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                                    <?php echo e($deptReport['on_time']); ?>

                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-warning text-dark rounded-pill"><?php echo e($deptReport['late']); ?></span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-danger rounded-pill"><?php echo e($deptReport['early_leave']); ?></span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-dark rounded-pill"><?php echo e($deptReport['absent']); ?></span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge rounded-pill <?php echo e($deptReport['absent_percentage'] > 10 ? 'bg-danger' : ($deptReport['absent_percentage'] > 5 ? 'bg-warning text-dark' : 'bg-success')); ?>">
                                    <?php echo e(number_format($deptReport['absent_percentage'], 2)); ?>%
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Không có dữ liệu báo cáo phòng ban</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Documents\GitHub\QLNV_KLTN\resources\views/reports/index.blade.php ENDPATH**/ ?>