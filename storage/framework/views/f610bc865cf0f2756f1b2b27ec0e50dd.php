<?php $__env->startSection('title', 'Quản lý lương'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-cash-stack me-2"></i>Quản lý lương</h2>
            <p class="text-muted mb-0">Quản lý bảng lương nhân viên</p>
        </div>
        <a href="<?php echo e(route('payroll.create')); ?>" class="btn btn-gradient"><i class="bi bi-plus-circle me-2"></i>Tạo bảng lương</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('payroll.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tìm kiếm</label>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Tên, mã NV, email..." style="border-radius: 12px;">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tháng</label>
                        <input type="month" name="month" value="<?php echo e($month); ?>" class="form-control" style="border-radius: 12px;">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Phòng ban</label>
                        <select name="department_id" class="form-select" style="border-radius: 12px;">
                            <option value="">Tất cả</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d->id); ?>" <?php echo e(request('department_id') == $d->id ? 'selected' : ''); ?>><?php echo e($d->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <button type="submit" class="btn btn-gradient flex-fill"><i class="bi bi-search me-2"></i>Tìm</button>
                            <a href="<?php echo e(route('payroll.export', request()->all())); ?>" class="btn btn-success" title="Xuất CSV"><i class="bi bi-download"></i></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="py-3 px-4">Mã NV</th>
                            <th class="py-3 px-4">Họ tên</th>
                            <th class="py-3 px-4">Phòng ban</th>
                            <th class="py-3 px-4 text-end">Lương cơ bản</th>
                            <th class="py-3 px-4 text-end">Thực lĩnh</th>
                            <th class="py-3 px-4 text-center">Trạng thái</th>
                            <th class="py-3 px-4 text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $payrolls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payroll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-3 px-4 fw-semibold"><?php echo e($payroll->user->code); ?></td>
                            <td class="py-3 px-4">
                                <div class="fw-semibold"><?php echo e($payroll->user->name); ?></div>
                                <small class="text-muted"><?php echo e($payroll->user->email); ?></small>
                            </td>
                            <td class="py-3 px-4"><?php echo e($payroll->user->profile->department->name ?? '-'); ?></td>
                            <td class="py-3 px-4 text-end">
                                <?php
                                    $base = $payroll->user->profile->base_salary_override ?? $payroll->user->profile->salaryGrade->base_salary ?? 0;
                                    $st = $payroll->user->profile->salaryGrade->salary_type ?? 'monthly';
                                ?>
                                <?php echo e(number_format($base, 0, ',', '.')); ?>đ <?php if($st === 'hourly'): ?><small class="text-muted">/giờ</small><?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-end fw-bold text-success"><?php echo e(number_format($payroll->net_pay, 0, ',', '.')); ?>đ</td>
                            <td class="py-3 px-4 text-center">
                                <?php if($payroll->status === 'draft'): ?><span class="badge bg-secondary">Nháp</span>
                                <?php elseif($payroll->status === 'confirmed'): ?><span class="badge bg-primary">Đã xác nhận</span>
                                <?php else: ?><span class="badge bg-success">Đã thanh toán</span><?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-end">
                                <a href="<?php echo e(route('payroll.show', $payroll)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <?php if($payroll->status === 'draft'): ?>
                                    <form action="<?php echo e(route('payroll.update-status', $payroll)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                <?php elseif($payroll->status === 'confirmed'): ?>
                                    <form action="<?php echo e(route('payroll.update-status', $payroll)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-cash"></i></button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <p class="text-muted">Chưa có bảng lương nào.</p>
                                <a href="<?php echo e(route('payroll.create')); ?>" class="btn btn-gradient mt-2"><i class="bi bi-plus-circle me-2"></i>Tạo bảng lương</a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($payrolls->hasPages()): ?>
                <div class="p-4 border-top"><?php echo e($payrolls->links('pagination::bootstrap-5')); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Documents\GitHub\QLNV_KLTN\resources\views/payroll/index.blade.php ENDPATH**/ ?>