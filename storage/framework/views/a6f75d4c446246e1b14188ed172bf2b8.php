

<?php $__env->startSection('title', 'Tạo hợp đồng mới'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-plus-circle me-2"></i>
                    Tạo hợp đồng mới
                </h2>
                <p class="text-muted mb-0">Thêm hợp đồng lao động cho nhân viên</p>
            </div>

            <form method="POST" action="<?php echo e(route('contracts.store')); ?>">
                <?php echo csrf_field(); ?>

                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-person-badge me-2"></i>
                            Thông tin cơ bản
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nhân viên <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-select" required>
                                    <option value="">Chọn nhân viên</option>
                                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($emp->id); ?>" <?php echo e(old('user_id') == $emp->id ? 'selected' : ''); ?>>
                                            <?php echo e($emp->name); ?> (<?php echo e($emp->code); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Loại hợp đồng <span class="text-danger">*</span></label>
                                <select name="contract_type" class="form-select" required>
                                    <option value="">Chọn loại</option>
                                    <option value="full_time" <?php echo e(old('contract_type') == 'full_time' ? 'selected' : ''); ?>>Chính thức</option>
                                    <option value="part_time" <?php echo e(old('contract_type') == 'part_time' ? 'selected' : ''); ?>>Part-time</option>
                                    <option value="intern" <?php echo e(old('contract_type') == 'intern' ? 'selected' : ''); ?>>Thực tập</option>
                                    <option value="temporary" <?php echo e(old('contract_type') == 'temporary' ? 'selected' : ''); ?>>Tạm thời</option>
                                </select>
                                <?php $__errorArgs = ['contract_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" value="<?php echo e(old('start_date')); ?>" class="form-control" required>
                                <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày kết thúc</label>
                                <input type="date" name="end_date" value="<?php echo e(old('end_date')); ?>" class="form-control">
                                <small class="text-muted">Để trống nếu không xác định</small>
                                <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mức lương <span class="text-danger">*</span></label>
                                <input type="number" name="salary" value="<?php echo e(old('salary')); ?>" class="form-control" min="0" step="1000" required>
                                <?php $__errorArgs = ['salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Chức vụ <span class="text-danger">*</span></label>
                                <input type="text" name="position" value="<?php echo e(old('position')); ?>" class="form-control" required>
                                <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày ký</label>
                                <input type="date" name="signed_date" value="<?php echo e(old('signed_date')); ?>" class="form-control">
                                <?php $__errorArgs = ['signed_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="pending" <?php echo e(old('status') == 'pending' ? 'selected' : ''); ?>>Chờ duyệt</option>
                                    <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>Đang hiệu lực</option>
                                    <option value="expired" <?php echo e(old('status') == 'expired' ? 'selected' : ''); ?>>Hết hạn</option>
                                    <option value="terminated" <?php echo e(old('status') == 'terminated' ? 'selected' : ''); ?>>Chấm dứt</option>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-file-text me-2"></i>
                            Chi tiết hợp đồng
                        </h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Mô tả công việc</label>
                                <textarea name="job_description" class="form-control" rows="4"><?php echo e(old('job_description')); ?></textarea>
                                <?php $__errorArgs = ['job_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Phúc lợi</label>
                                <textarea name="benefits" class="form-control" rows="4"><?php echo e(old('benefits')); ?></textarea>
                                <?php $__errorArgs = ['benefits'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Ghi chú</label>
                                <textarea name="notes" class="form-control" rows="3"><?php echo e(old('notes')); ?></textarea>
                                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('contracts.index')); ?>" class="btn btn-light">Hủy</a>
                    <button type="submit" class="btn btn-gradient">
                        <i class="bi bi-check-circle me-2"></i>
                        Tạo hợp đồng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KLTN_QLNV\resources\views/contracts/create.blade.php ENDPATH**/ ?>