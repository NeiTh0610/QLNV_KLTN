

<?php $__env->startSection('title', 'Thêm phòng ban'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-building me-2"></i>
                    Thêm phòng ban mới
                </h2>
                <p class="text-muted mb-0">Điền thông tin để thêm phòng ban vào hệ thống</p>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="<?php echo e(route('departments.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Tên phòng ban <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="name" 
                                       value="<?php echo e(old('name')); ?>" 
                                       class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       style="border-radius: 12px;" 
                                       placeholder="Ví dụ: Phòng Nhân sự"
                                       required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Mô tả</label>
                                <textarea name="description" 
                                          class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          style="border-radius: 12px;" 
                                          rows="3"
                                          placeholder="Mô tả về phòng ban..."><?php echo e(old('description')); ?></textarea>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Trưởng phòng</label>
                                <select name="manager_id" class="form-select <?php $__errorArgs = ['manager_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="border-radius: 12px;">
                                    <option value="">Chọn trưởng phòng (tùy chọn)</option>
                                    <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($manager->id); ?>" <?php echo e(old('manager_id') == $manager->id ? 'selected' : ''); ?>>
                                            <?php echo e($manager->name); ?> (<?php echo e($manager->code); ?>) - <?php echo e($manager->email); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['manager_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">Chỉ hiển thị người dùng có vai trò admin hoặc manager</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo e(route('departments.index')); ?>" class="btn btn-outline-secondary" style="border-radius: 12px;">
                                <i class="bi bi-arrow-left me-2"></i>
                                Quay lại
                            </a>
                            <button type="submit" class="btn btn-gradient" style="border-radius: 12px;">
                                <i class="bi bi-check-circle me-2"></i>
                                Thêm phòng ban
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Documents\GitHub\QLNV_KLTN\resources\views/departments/create.blade.php ENDPATH**/ ?>