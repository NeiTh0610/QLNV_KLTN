

<?php $__env->startSection('title', 'Tạo đơn xin nghỉ'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-file-earmark-plus me-2"></i>
                    Tạo đơn xin nghỉ
                </h2>
                <p class="text-muted mb-0">Điền thông tin để gửi đơn xin nghỉ</p>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="<?php echo e(route('leave-requests.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Loại đơn <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" style="border-radius: 12px;" required>
                                <option value="leave">Nghỉ phép</option>
                                <option value="late">Xin đi muộn</option>
                                <option value="early">Xin về sớm</option>
                                <option value="remote">Làm việc từ xa</option>
                                <option value="other">Khác</option>
                            </select>
                            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Bắt đầu <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_at" value="<?php echo e(old('start_at')); ?>" class="form-control" style="border-radius: 12px;" required>
                                <?php $__errorArgs = ['start_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kết thúc <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="end_at" value="<?php echo e(old('end_at')); ?>" class="form-control" style="border-radius: 12px;" required>
                                <?php $__errorArgs = ['end_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Lý do <span class="text-danger">*</span></label>
                            <textarea name="reason" rows="5" class="form-control" style="border-radius: 12px;" placeholder="Nhập lý do xin nghỉ..." required><?php echo e(old('reason')); ?></textarea>
                            <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="<?php echo e(route('leave-requests.index')); ?>" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-2"></i>
                                Hủy
                            </a>
                            <button type="submit" class="btn btn-gradient px-4">
                                <i class="bi bi-send me-2"></i>
                                Gửi đơn
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Pham Thien\Documents\GitHub\QLNV_KLTN\resources\views/leave-requests/create.blade.php ENDPATH**/ ?>