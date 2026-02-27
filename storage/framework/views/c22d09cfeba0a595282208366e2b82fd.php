<?php $__env->startSection('title', 'Chi tiết nhân viên'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <div style="width:64px;height:64px;overflow:hidden;border-radius:8px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                        <?php if($employee->avatar_path): ?>
                            <img src="<?php echo e(asset('storage/'.$employee->avatar_path)); ?>" alt="" style="width:100%;height:100%;object-fit:cover;" />
                        <?php else: ?>
                            <span style="color:#9ca3af;font-weight:600;"><?php echo e(strtoupper(substr($employee->name,0,1))); ?></span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-1">
                            <i class="bi bi-person-circle me-2"></i>
                            <?php echo e($employee->name); ?>

                        </h2>
                        <p class="text-muted mb-0">Mã NV: <?php echo e($employee->code); ?></p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('employees.edit', $employee)); ?>" class="btn btn-gradient">
                        <i class="bi bi-pencil me-2"></i>
                        Sửa
                    </a>
                    <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>
                        Quay lại
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <!-- Basic Info -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-person-badge me-2"></i>
                                Thông tin cơ bản
                            </h5>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Họ tên</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->name); ?></h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Email</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->email); ?></h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Số điện thoại</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->phone ?? '-'); ?></h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Trạng thái</small>
                                <?php if($employee->status === 'active'): ?>
                                    <span class="badge badge-custom" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                                        <i class="bi bi-check-circle me-1"></i>Đang làm việc
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Nghỉ việc
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Work Info -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-briefcase me-2"></i>
                                Thông tin công việc
                            </h5>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Phòng ban</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->profile->department->name ?? '-'); ?></h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Chức vụ</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->profile->position ?? '-'); ?></h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Vai trò</small>
                                <div>
                                    <?php $__currentLoopData = $employee->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge badge-custom d-inline-flex align-items-center" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; gap:8px;">
                                            <?php if($role->avatar): ?>
                                                <img src="<?php echo e(asset('storage/'.$role->avatar)); ?>" alt="" style="width:20px;height:20px;object-fit:cover;border-radius:4px;" />
                                            <?php endif; ?>
                                            <?php echo e($role->display_name); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Ngày vào làm</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->hired_at?->format('d/m/Y') ?? '-'); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Documents\GitHub\QLNV_KLTN\resources\views/employees/show.blade.php ENDPATH**/ ?>