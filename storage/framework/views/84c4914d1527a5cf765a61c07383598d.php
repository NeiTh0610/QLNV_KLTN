<?php $__env->startSection('title', 'Thêm nhân viên'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-person-plus me-2"></i>
                    Thêm nhân viên mới
                </h2>
                <p class="text-muted mb-0">Điền thông tin để thêm nhân viên vào hệ thống</p>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="<?php echo e(route('employees.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-person-badge me-2"></i>
                            Thông tin cơ bản
                        </h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mã nhân viên <span class="text-danger">*</span></label>
                                <input type="text" name="code" value="<?php echo e(old('code')); ?>" class="form-control" style="border-radius: 12px;" required>
                                <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control" style="border-radius: 12px;" required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control" style="border-radius: 12px;" required>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số điện thoại</label>
                                <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control" style="border-radius: 12px;">
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" style="border-radius: 12px;" required>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày vào làm</label>
                                <input type="date" name="hired_at" value="<?php echo e(old('hired_at', now()->format('Y-m-d'))); ?>" class="form-control" style="border-radius: 12px;">
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-briefcase me-2"></i>
                            Thông tin công việc
                        </h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phòng ban</label>
                                <select name="department_id" class="form-select" style="border-radius: 12px;">
                                    <option value="">Chọn phòng ban</option>
                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($dept->id); ?>" <?php echo e(old('department_id') == $dept->id ? 'selected' : ''); ?>>
                                            <?php echo e($dept->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Chức vụ</label>
                                <input type="text" name="position" value="<?php echo e(old('position')); ?>" class="form-control" style="border-radius: 12px;">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Vai trò <span class="text-danger">*</span></label>
                                <select name="role_id" class="form-select" style="border-radius: 12px;" required>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role->id); ?>" <?php echo e(old('role_id') == $role->id ? 'selected' : ''); ?>>
                                            <?php echo e($role->display_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" style="border-radius: 12px;" required>
                                    <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>Đang làm việc</option>
                                    <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>Nghỉ việc</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Thang lương</label>
                                <select name="salary_grade_id" class="form-select" style="border-radius: 12px;">
                                    <option value="">Chọn thang lương</option>
                                    <?php $__currentLoopData = $salaryGrades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($grade->id); ?>" <?php echo e(old('salary_grade_id') == $grade->id ? 'selected' : ''); ?>>
                                            <?php echo e($grade->name); ?> - <?php echo e(number_format($grade->base_salary, 0, ',', '.')); ?>đ
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Lương cơ bản (ghi đè)</label>
                                <input type="number" name="base_salary_override" value="<?php echo e(old('base_salary_override')); ?>" step="1000" class="form-control" style="border-radius: 12px;">
                                <small class="text-muted">Để trống nếu dùng lương từ thang lương</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-2"></i>
                                Hủy
                            </a>
                            <button type="submit" class="btn btn-gradient px-4">
                                <i class="bi bi-check-circle me-2"></i>
                                Thêm nhân viên
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Documents\GitHub\QLNV_KLTN\resources\views/employees/create.blade.php ENDPATH**/ ?>