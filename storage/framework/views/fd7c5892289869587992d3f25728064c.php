

<?php $__env->startSection('title', 'Bảng lương của tôi'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-wallet2 me-2"></i>
                Bảng lương của tôi
            </h2>
            <p class="text-muted mb-0">Xem thông tin lương của bạn</p>
        </div>
        <div>
            <input type="month" 
                   id="monthFilter" 
                   value="<?php echo e($month); ?>"
                   class="form-control"
                   style="border-radius: 12px; border: 2px solid #e2e8f0;">
        </div>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $payrolls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payroll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <!-- Payroll Card -->
    <div class="card mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-calendar-month me-2"></i>
                    Tháng <?php echo e(\Carbon\Carbon::parse($payroll->period_start)->format('m/Y')); ?>

                </h5>
                <?php if($payroll->status === 'paid'): ?>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Đã thanh toán
                    </span>
                <?php elseif($payroll->status === 'confirmed'): ?>
                    <span class="badge bg-info">
                        <i class="bi bi-clock me-1"></i>
                        Đã xác nhận
                    </span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">
                        <i class="bi bi-pencil me-1"></i>
                        Nháp
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <!-- Summary -->
                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3">Tổng quan</h6>
                    <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block">Thực lĩnh</small>
                                <h3 class="fw-bold mb-0 text-primary"><?php echo e(number_format($payroll->net_pay, 0, ',', '.')); ?>đ</h3>
                            </div>
                            <i class="bi bi-cash-stack text-primary" style="font-size: 3rem; opacity: 0.2;"></i>
                        </div>
                    </div>
                    <?php
                        $salaryType = $payroll->user->profile->salaryGrade->salary_type ?? 'monthly';
                        $baseSalary = $payroll->user->profile->base_salary_override ?? $payroll->user->profile->salaryGrade->base_salary ?? 0;
                        
                        // Với part-time: Lấy số giờ làm việc trung bình mỗi ngày từ database
                        $hoursPerDay = $salaryType === 'hourly' && $payroll->hours_per_day !== null 
                            ? $payroll->hours_per_day 
                            : 0;
                    ?>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #11998e15 0%, #38ef7d15 100%);">
                                <small class="text-muted d-block">Số ngày làm</small>
                                <h5 class="fw-bold mb-0">
                                    <?php echo e(number_format($payroll->working_days, 1)); ?> ngày
                                </h5>
                            </div>
                        </div>
                        <?php if($salaryType === 'hourly' && $hoursPerDay > 0): ?>
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%);">
                                <small class="text-muted d-block">Số giờ/ngày</small>
                                <h5 class="fw-bold mb-0">
                                    <?php echo e(number_format($hoursPerDay, 1)); ?> giờ
                                </h5>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%);">
                                <small class="text-muted d-block">Phụ cấp</small>
                                <h6 class="fw-bold mb-0"><?php echo e(number_format($payroll->allowances, 0, ',', '.')); ?>đ</h6>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Details -->
                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3">Chi tiết khấu trừ</h6>
                    <?php if($salaryType === 'monthly'): ?>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><i class="bi bi-dash-circle text-danger me-2"></i>BHXH</td>
                            <td class="text-end text-danger fw-semibold"><?php echo e(number_format($payroll->social_insurance, 0, ',', '.')); ?>đ</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-dash-circle text-danger me-2"></i>BHYT</td>
                            <td class="text-end text-danger fw-semibold"><?php echo e(number_format($payroll->health_insurance, 0, ',', '.')); ?>đ</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-dash-circle text-danger me-2"></i>BHTN</td>
                            <td class="text-end text-danger fw-semibold"><?php echo e(number_format($payroll->unemployment_insurance, 0, ',', '.')); ?>đ</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-dash-circle text-danger me-2"></i>Thuế TNCN</td>
                            <td class="text-end text-danger fw-semibold"><?php echo e(number_format($payroll->personal_income_tax, 0, ',', '.')); ?>đ</td>
                        </tr>
                    </table>
                    <?php else: ?>
                    <p class="text-muted small mb-0">Part-time không đóng bảo hiểm và thuế</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Calculation -->
            <?php
                $salaryType = $payroll->user->profile->salaryGrade->salary_type ?? 'monthly';
                $baseSalary = $payroll->user->profile->base_salary_override ?? $payroll->user->profile->salaryGrade->base_salary ?? 0;
            ?>
            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold">
                        Lương cơ bản
                        <?php if($salaryType === 'hourly'): ?>
                            (theo giờ)
                        <?php else: ?>
                            (theo tháng)
                        <?php endif; ?>
                    </span>
                    <span class="fw-semibold">
                        <?php echo e(number_format($baseSalary, 0, ',', '.')); ?>đ
                        <?php if($salaryType === 'hourly'): ?>
                            /giờ
                        <?php endif; ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <small class="text-muted">
                        Lương cơ bản
                        <?php if($salaryType === 'hourly' && $hoursPerDay > 0): ?>
                            (<?php echo e(number_format($hoursPerDay, 1)); ?> giờ/ngày × <?php echo e(number_format($payroll->working_days, 1)); ?> ngày × <?php echo e(number_format($baseSalary, 0, ',', '.')); ?>đ/giờ)
                        <?php else: ?>
                            (theo số ngày đi làm: <?php echo e(number_format($payroll->working_days, 1)); ?> ngày)
                        <?php endif; ?>
                    </small>
                    <small class="text-muted"><?php echo e(number_format($payroll->basic_salary, 0, ',', '.')); ?>đ</small>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-success"><i class="bi bi-plus-circle me-2"></i>Phụ cấp</span>
                    <span class="text-success">+<?php echo e(number_format($payroll->allowances, 0, ',', '.')); ?>đ</span>
                </div>
                <?php if($payroll->ot_hours > 0 && $salaryType === 'monthly'): ?>
                <?php
                    // Chỉ tính làm thêm cho full-time
                    // Full-time: Tính lương giờ từ lương tháng
                    // Giả sử 1 tháng có 22 ngày làm việc, mỗi ngày 8 giờ
                    $standardWorkingDays = 22;
                    $hourlyRate = $baseSalary > 0 && $standardWorkingDays > 0 
                        ? $baseSalary / ($standardWorkingDays * 8) 
                        : 0;
                    $overtimeAmount = $hourlyRate > 0 
                        ? round($payroll->ot_hours * $hourlyRate * 1.5, 0) 
                        : 0;
                ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-success"><i class="bi bi-plus-circle me-2"></i>Làm thêm giờ (<?php echo e($payroll->ot_hours); ?>h x 1.5)</span>
                    <span class="text-success">+<?php echo e(number_format($overtimeAmount, 0, ',', '.')); ?>đ</span>
                </div>
                <?php endif; ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold">Tổng lương</span>
                    <span class="fw-semibold"><?php echo e(number_format($payroll->gross_salary, 0, ',', '.')); ?>đ</span>
                </div>
                <hr>
                <?php if($payroll->late_count_under_30 > 0 || $payroll->late_count_half_day > 0): ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-warning">
                        <i class="bi bi-dash-circle me-2"></i>
                        Phạt đi muộn
                        <?php if($payroll->late_count_under_30 > 0): ?>
                            <small class="d-block text-muted ms-4">(<?php echo e($payroll->late_count_under_30); ?> lần < 30p × 50.000đ)</small>
                        <?php endif; ?>
                        <?php if($payroll->late_count_half_day > 0): ?>
                            <small class="d-block text-muted ms-4">(<?php echo e($payroll->late_count_half_day); ?> lần ≥ 30p × 500.000đ)</small>
                        <?php endif; ?>
                    </span>
                    <span class="text-warning">
                        -<?php echo e(number_format(($payroll->late_count_under_30 * 50000) + ($payroll->late_count_half_day * 500000), 0, ',', '.')); ?>đ
                    </span>
                </div>
                <?php endif; ?>
                <?php if($payroll->early_leave_count_under_30 > 0 || $payroll->early_leave_count_half_day > 0): ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-warning">
                        <i class="bi bi-dash-circle me-2"></i>
                        Phạt về sớm
                        <?php if($payroll->early_leave_count_under_30 > 0): ?>
                            <small class="d-block text-muted ms-4">(<?php echo e($payroll->early_leave_count_under_30); ?> lần < 30p × 50.000đ)</small>
                        <?php endif; ?>
                        <?php if($payroll->early_leave_count_half_day > 0): ?>
                            <small class="d-block text-muted ms-4">(<?php echo e($payroll->early_leave_count_half_day); ?> lần ≥ 30p × 500.000đ)</small>
                        <?php endif; ?>
                    </span>
                    <span class="text-warning">
                        -<?php echo e(number_format(($payroll->early_leave_count_under_30 * 50000) + ($payroll->early_leave_count_half_day * 500000), 0, ',', '.')); ?>đ
                    </span>
                </div>
                <?php endif; ?>
                <?php if($salaryType === 'monthly'): ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-danger"><i class="bi bi-dash-circle me-2"></i>Tổng khấu trừ</span>
                    <span class="text-danger">
                        -<?php echo e(number_format($payroll->social_insurance + $payroll->health_insurance + $payroll->unemployment_insurance + $payroll->personal_income_tax, 0, ',', '.')); ?>đ
                    </span>
                </div>
                <?php endif; ?>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Thực lĩnh</h5>
                    <h4 class="fw-bold mb-0 text-success"><?php echo e(number_format($payroll->net_pay, 0, ',', '.')); ?>đ</h4>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
            <h5 class="text-muted mt-3">Chưa có bảng lương nào</h5>
            <p class="text-muted">Bảng lương sẽ được cập nhật khi quản lý tạo</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    document.getElementById('monthFilter')?.addEventListener('change', function() {
        const month = this.value;
        window.location.href = `<?php echo e(route('payroll.my-payroll')); ?>?month=${month}`;
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Pham Thien\Documents\GitHub\QLNV_KLTN\resources\views/payroll/my-payroll.blade.php ENDPATH**/ ?>