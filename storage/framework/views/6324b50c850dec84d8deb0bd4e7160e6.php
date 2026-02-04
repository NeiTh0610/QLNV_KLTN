<?php $__env->startSection('title', 'Quản lý lương'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-cash-stack me-2"></i>
                Quản lý lương
            </h2>
            <p class="text-muted mb-0">Quản lý bảng lương nhân viên</p>
        </div>
        <button type="button" class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#generateModal">
            <i class="bi bi-plus-circle me-2"></i>
            Tạo bảng lương
        </button>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('payroll.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tìm kiếm</label>
                        <input type="text" 
                               name="search" 
                               value="<?php echo e(request('search')); ?>"
                               class="form-control" 
                               placeholder="Tên, mã NV, email..."
                               style="border-radius: 12px;">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tháng</label>
                        <input type="month" name="month" value="<?php echo e($month); ?>" class="form-control" style="border-radius: 12px;">
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="status" class="form-select" style="border-radius: 12px;">
                            <option value="">Tất cả</option>
                            <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Nháp</option>
                            <option value="confirmed" <?php echo e(request('status') == 'confirmed' ? 'selected' : ''); ?>>Đã xác nhận</option>
                            <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Đã thanh toán</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-gradient flex-fill">
                                <i class="bi bi-search me-2"></i>
                                Tìm kiếm
                            </button>
                            <a href="<?php echo e(route('payroll.export', request()->all())); ?>" class="btn btn-success" title="Xuất Excel">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <a href="<?php echo e(route('payroll.index', ['month' => $month])); ?>" class="btn btn-outline-secondary w-100" title="Xóa bộ lọc">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payroll Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="py-3 px-4 fw-semibold">Mã NV</th>
                            <th class="py-3 px-4 fw-semibold">Họ tên</th>
                            <th class="py-3 px-4 fw-semibold">Phòng ban</th>
                            <th class="py-3 px-4 fw-semibold text-end">Lương cơ bản</th>
                            <th class="py-3 px-4 fw-semibold text-end">Thực lĩnh</th>
                            <th class="py-3 px-4 fw-semibold text-center">Trạng thái</th>
                            <th class="py-3 px-4 fw-semibold text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $payrolls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payroll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-3 px-4 fw-semibold"><?php echo e($payroll->user->code); ?></td>
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                        <?php echo e(strtoupper(substr($payroll->user->name, 0, 1))); ?>

                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?php echo e($payroll->user->name); ?></div>
                                        <small class="text-muted"><?php echo e($payroll->user->email); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4"><?php echo e($payroll->user->profile->department->name ?? '-'); ?></td>
                            <td class="py-3 px-4 text-end fw-semibold">
                                <?php
                                    $baseSalary = $payroll->user->profile->base_salary_override ?? $payroll->user->profile->salaryGrade->base_salary ?? 0;
                                    $salaryType = $payroll->user->profile->salaryGrade->salary_type ?? 'monthly';
                                ?>
                                <?php echo e(number_format($baseSalary, 0, ',', '.')); ?>đ
                                <?php if($salaryType === 'hourly'): ?>
                                    <small class="text-muted d-block">/giờ</small>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-end">
                                <div class="fw-bold text-success"><?php echo e(number_format($payroll->net_pay, 0, ',', '.')); ?>đ</div>
                                <small class="text-muted">
                                    <?php echo e(number_format($payroll->working_days, 1)); ?> ngày
                                </small>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <?php if($payroll->status === 'draft'): ?>
                                    <span class="badge bg-secondary">Nháp</span>
                                <?php elseif($payroll->status === 'confirmed'): ?>
                                    <span class="badge bg-primary">Đã xác nhận</span>
                                <?php else: ?>
                                    <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">Đã thanh toán</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-end">
                                <a href="<?php echo e(route('payroll.show', $payroll)); ?>" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if($payroll->status === 'draft'): ?>
                                <div class="btn-group btn-group-sm ms-1">
                                    <form action="<?php echo e(route('payroll.update-status', $payroll)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-outline-success" style="border-radius: 8px;">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                </div>
                                <?php elseif($payroll->status === 'confirmed'): ?>
                                <div class="btn-group btn-group-sm ms-1">
                                    <form action="<?php echo e(route('payroll.update-status', $payroll)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" class="btn btn-outline-success" style="border-radius: 8px;">
                                            <i class="bi bi-cash"></i>
                                        </button>
                                    </form>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Chưa có bảng lương nào</p>
                                <button type="button" class="btn btn-gradient mt-2" data-bs-toggle="modal" data-bs-target="#generateModal">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Tạo bảng lương
                                </button>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($payrolls->hasPages()): ?>
            <div class="p-4 border-top">
                <?php echo e($payrolls->links('pagination::bootstrap-5')); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Generate Payroll Modal -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-calculator me-2"></i>
                    Tạo bảng lương
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('payroll.generate')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Từ ngày <span class="text-danger">*</span></label>
                        <input type="date" name="period_start" value="<?php echo e(now()->startOfMonth()->format('Y-m-d')); ?>" class="form-control" style="border-radius: 12px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Đến ngày <span class="text-danger">*</span></label>
                        <input type="date" name="period_end" value="<?php echo e(now()->endOfMonth()->format('Y-m-d')); ?>" class="form-control" style="border-radius: 12px;" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Hệ thống sẽ tự động tính lương dựa trên dữ liệu chấm công và thang lương</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="bi bi-check-circle me-2"></i>
                        Tạo bảng lương
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Admin\Documents\GitHub\QLNV_KLTN\resources\views/payroll/index.blade.php ENDPATH**/ ?>