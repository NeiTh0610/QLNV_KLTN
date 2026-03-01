<?php $__env->startSection('title', 'Tạo bảng lương'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('payroll.index')); ?>">Quản lý lương</a></li>
                    <li class="breadcrumb-item active">Tạo bảng lương</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-1"><i class="bi bi-calculator me-2"></i>Tạo bảng lương</h2>
            <p class="text-muted mb-0">Chọn kỳ lương để hệ thống tính lương theo chấm công và thang lương.</p>
        </div>
        <a href="<?php echo e(route('payroll.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
    </div>

    <div class="card" style="border-radius: 16px;">
        <div class="card-body p-4">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger mb-4">
                    <strong>Có lỗi:</strong>
                    <ul class="mb-0 mt-1"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('payroll.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Từ ngày <span class="text-danger">*</span></label>
                        <input type="date" name="period_start" value="<?php echo e(old('period_start', now()->startOfMonth()->format('Y-m-d'))); ?>"
                               class="form-control form-control-lg <?php $__errorArgs = ['period_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="border-radius: 12px; max-width: 280px;" required>
                        <?php $__errorArgs = ['period_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Đến ngày <span class="text-danger">*</span></label>
                        <input type="date" name="period_end" value="<?php echo e(old('period_end', now()->endOfMonth()->format('Y-m-d'))); ?>"
                               class="form-control form-control-lg <?php $__errorArgs = ['period_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="border-radius: 12px; max-width: 280px;" required>
                        <?php $__errorArgs = ['period_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>Hệ thống tính lương cho nhân viên đang hoạt động có thang lương, dựa trên chấm công trong kỳ.
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gradient btn-lg"><i class="bi bi-check-circle me-2"></i>Tạo bảng lương</button>
                    <a href="<?php echo e(route('payroll.index')); ?>" class="btn btn-light btn-lg">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Documents\GitHub\QLNV_KLTN\resources\views/payroll/create.blade.php ENDPATH**/ ?>