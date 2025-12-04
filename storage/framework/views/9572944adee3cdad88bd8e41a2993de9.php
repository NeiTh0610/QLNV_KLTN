

<?php $__env->startSection('title', 'Chi tiết hồ sơ nhân viên'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-file-person me-2"></i>
                        Hồ sơ: <?php echo e($employee->name); ?>

                    </h2>
                    <p class="text-muted mb-0">Mã NV: <?php echo e($employee->code); ?></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('employee-profiles.edit', $employee)); ?>" class="btn btn-gradient">
                        <i class="bi bi-pencil me-2"></i>
                        Sửa hồ sơ
                    </a>
                    <a href="<?php echo e(route('employee-profiles.index')); ?>" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>
                        Quay lại
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <!-- Thông tin cá nhân -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-person-badge me-2"></i>
                                Thông tin cá nhân
                            </h5>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">CMND/CCCD</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->profile->id_number ?? '-'); ?></h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Ngày sinh</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->profile->date_of_birth ? $employee->profile->date_of_birth->format('d/m/Y') : '-'); ?></h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Giới tính</small>
                                <h6 class="fw-semibold mb-0">
                                    <?php if($employee->profile->gender): ?>
                                        <?php echo e($employee->profile->gender === 'male' ? 'Nam' : ($employee->profile->gender === 'female' ? 'Nữ' : 'Khác')); ?>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Địa chỉ</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->profile->address ?? '-'); ?></h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Email cá nhân</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->profile->personal_email ?? '-'); ?></h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Liên hệ khẩn cấp</small>
                                <h6 class="fw-semibold mb-0">
                                    <?php echo e($employee->profile->emergency_contact_name ?? '-'); ?>

                                    <?php if($employee->profile->emergency_contact_phone): ?>
                                        <br><small class="text-muted"><?php echo e($employee->profile->emergency_contact_phone); ?></small>
                                    <?php endif; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Học vấn & Kinh nghiệm -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-mortarboard me-2"></i>
                                Học vấn & Kinh nghiệm
                            </h5>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Trình độ học vấn</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->profile->education_level ?? '-'); ?></h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Chuyên ngành</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->profile->major ?? '-'); ?></h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Trường đại học</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->profile->university ?? '-'); ?></h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Số năm kinh nghiệm</small>
                                <h6 class="fw-semibold mb-0"><?php echo e($employee->profile->years_of_experience ?? 0); ?> năm</h6>
                            </div>
                            <?php if($employee->profile->skills): ?>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Kỹ năng</small>
                                <p class="mb-0"><?php echo e($employee->profile->skills); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if($employee->profile->certifications): ?>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Chứng chỉ</small>
                                <p class="mb-0"><?php echo e($employee->profile->certifications); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if($employee->profile->previous_work): ?>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Công việc trước đây</small>
                                <p class="mb-0"><?php echo e($employee->profile->previous_work); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Hợp đồng -->
                <?php if($employee->contracts->count() > 0): ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                Hợp đồng
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Số hợp đồng</th>
                                            <th>Loại</th>
                                            <th>Ngày bắt đầu</th>
                                            <th>Ngày kết thúc</th>
                                            <th>Lương</th>
                                            <th>Trạng thái</th>
                                            <th class="text-end">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $employee->contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($contract->contract_number); ?></td>
                                            <td>
                                                <?php if($contract->contract_type === 'full_time'): ?>
                                                    <span class="badge bg-primary">Chính thức</span>
                                                <?php elseif($contract->contract_type === 'part_time'): ?>
                                                    <span class="badge bg-info">Part-time</span>
                                                <?php elseif($contract->contract_type === 'intern'): ?>
                                                    <span class="badge bg-warning">Thực tập</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Tạm thời</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($contract->start_date->format('d/m/Y')); ?></td>
                                            <td><?php echo e($contract->end_date ? $contract->end_date->format('d/m/Y') : 'Không xác định'); ?></td>
                                            <td><?php echo e(number_format($contract->salary, 0, ',', '.')); ?>đ</td>
                                            <td>
                                                <?php if($contract->status === 'active'): ?>
                                                    <span class="badge bg-success">Đang hiệu lực</span>
                                                <?php elseif($contract->status === 'expired'): ?>
                                                    <span class="badge bg-warning">Hết hạn</span>
                                                <?php elseif($contract->status === 'terminated'): ?>
                                                    <span class="badge bg-danger">Chấm dứt</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Chờ duyệt</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?php echo e(route('contracts.show', $contract)); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KLTN_QLNV\resources\views/employee-profiles/show.blade.php ENDPATH**/ ?>