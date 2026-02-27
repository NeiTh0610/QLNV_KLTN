<?php $__env->startSection('title', 'Quản lý hồ sơ nhân viên'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-file-person me-2"></i>
                Quản lý hồ sơ nhân viên
            </h2>
            <p class="text-muted mb-0">Quản lý thông tin hồ sơ chi tiết của nhân viên</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('employee-profiles.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" 
                               name="search" 
                               value="<?php echo e(request('search')); ?>"
                               class="form-control" 
                               placeholder="Tìm kiếm theo tên, mã NV, email, CMND..."
                               style="border-radius: 12px; border: 2px solid #e2e8f0;">
                    </div>
                    <div class="col-md-3">
                        <select name="department_id" class="form-select" style="border-radius: 12px; border: 2px solid #e2e8f0;">
                            <option value="">Tất cả phòng ban</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dept->id); ?>" <?php echo e(request('department_id') == $dept->id ? 'selected' : ''); ?>>
                                    <?php echo e($dept->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="bi bi-search me-2"></i>
                            Tìm kiếm
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="<?php echo e(route('employee-profiles.index')); ?>" class="btn btn-outline-secondary w-100" title="Xóa bộ lọc">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="py-3 px-4 fw-semibold">Mã NV</th>
                            <th class="py-3 px-4 fw-semibold">Họ tên</th>
                            <th class="py-3 px-4 fw-semibold">CMND/CCCD</th>
                            <th class="py-3 px-4 fw-semibold">Ngày sinh</th>
                            <th class="py-3 px-4 fw-semibold">Phòng ban</th>
                            <th class="py-3 px-4 fw-semibold">Trình độ</th>
                            <th class="py-3 px-4 fw-semibold text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-3 px-4 fw-semibold"><?php echo e($employee->code); ?></td>
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                        <?php echo e(strtoupper(substr($employee->name, 0, 1))); ?>

                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?php echo e($employee->name); ?></div>
                                        <small class="text-muted"><?php echo e($employee->email); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4"><?php echo e($employee->profile->id_number ?? '-'); ?></td>
                            <td class="py-3 px-4"><?php echo e($employee->profile->date_of_birth ? $employee->profile->date_of_birth->format('d/m/Y') : '-'); ?></td>
                            <td class="py-3 px-4"><?php echo e($employee->profile->department->name ?? '-'); ?></td>
                            <td class="py-3 px-4"><?php echo e($employee->profile->education_level ?? '-'); ?></td>
                            <td class="py-3 px-4 text-end">
                                <a href="<?php echo e(route('employee-profiles.show', $employee)); ?>" class="btn btn-sm btn-outline-primary me-1" style="border-radius: 8px;">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                                <a href="<?php echo e(route('employee-profiles.edit', $employee)); ?>" class="btn btn-sm btn-outline-success" style="border-radius: 8px;">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Không tìm thấy nhân viên nào</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($employees->hasPages()): ?>
            <div class="p-4 border-top">
                <?php echo e($employees->links('pagination::bootstrap-5')); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Documents\GitHub\QLNV_KLTN\resources\views/employee-profiles/index.blade.php ENDPATH**/ ?>