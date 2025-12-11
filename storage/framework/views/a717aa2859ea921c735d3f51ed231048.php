

<?php $__env->startSection('title', 'Quản lý hợp đồng'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-file-earmark-text me-2"></i>
                Quản lý hợp đồng
            </h2>
            <p class="text-muted mb-0">Quản lý hợp đồng lao động của nhân viên</p>
        </div>
        <a href="<?php echo e(route('contracts.create')); ?>" class="btn btn-gradient">
            <i class="bi bi-plus-circle me-2"></i>
            Tạo hợp đồng mới
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('contracts.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" 
                               name="search" 
                               value="<?php echo e(request('search')); ?>"
                               class="form-control" 
                               placeholder="Tìm kiếm..."
                               style="border-radius: 12px; border: 2px solid #e2e8f0;">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select" style="border-radius: 12px; border: 2px solid #e2e8f0;">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Đang hiệu lực</option>
                            <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Hết hạn</option>
                            <option value="terminated" <?php echo e(request('status') == 'terminated' ? 'selected' : ''); ?>>Chấm dứt</option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Chờ duyệt</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="contract_type" class="form-select" style="border-radius: 12px; border: 2px solid #e2e8f0;">
                            <option value="">Tất cả loại</option>
                            <option value="full_time" <?php echo e(request('contract_type') == 'full_time' ? 'selected' : ''); ?>>Chính thức</option>
                            <option value="part_time" <?php echo e(request('contract_type') == 'part_time' ? 'selected' : ''); ?>>Part-time</option>
                            <option value="intern" <?php echo e(request('contract_type') == 'intern' ? 'selected' : ''); ?>>Thực tập</option>
                            <option value="temporary" <?php echo e(request('contract_type') == 'temporary' ? 'selected' : ''); ?>>Tạm thời</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="user_id" class="form-select" style="border-radius: 12px; border: 2px solid #e2e8f0;">
                            <option value="">Tất cả nhân viên</option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($emp->id); ?>" <?php echo e(request('user_id') == $emp->id ? 'selected' : ''); ?>>
                                    <?php echo e($emp->name); ?> (<?php echo e($emp->code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="<?php echo e(route('contracts.index')); ?>" class="btn btn-outline-secondary w-100" title="Xóa bộ lọc">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Contracts Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="py-3 px-4 fw-semibold">Số hợp đồng</th>
                            <th class="py-3 px-4 fw-semibold">Nhân viên</th>
                            <th class="py-3 px-4 fw-semibold">Loại</th>
                            <th class="py-3 px-4 fw-semibold">Ngày bắt đầu</th>
                            <th class="py-3 px-4 fw-semibold">Ngày kết thúc</th>
                            <th class="py-3 px-4 fw-semibold">Lương</th>
                            <th class="py-3 px-4 fw-semibold">Trạng thái</th>
                            <th class="py-3 px-4 fw-semibold text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-3 px-4 fw-semibold"><?php echo e($contract->contract_number); ?></td>
                            <td class="py-3 px-4">
                                <div class="fw-semibold"><?php echo e($contract->user->name); ?></div>
                                <small class="text-muted"><?php echo e($contract->user->code); ?></small>
                            </td>
                            <td class="py-3 px-4">
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
                            <td class="py-3 px-4"><?php echo e($contract->start_date->format('d/m/Y')); ?></td>
                            <td class="py-3 px-4"><?php echo e($contract->end_date ? $contract->end_date->format('d/m/Y') : 'Không xác định'); ?></td>
                            <td class="py-3 px-4"><?php echo e(number_format($contract->salary, 0, ',', '.')); ?>đ</td>
                            <td class="py-3 px-4">
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
                            <td class="py-3 px-4 text-end">
                                <a href="<?php echo e(route('contracts.show', $contract)); ?>" class="btn btn-sm btn-outline-primary me-1" style="border-radius: 8px;">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo e(route('contracts.edit', $contract)); ?>" class="btn btn-sm btn-outline-success me-1" style="border-radius: 8px;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="<?php echo e(route('contracts.destroy', $contract)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa hợp đồng này?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Không tìm thấy hợp đồng nào</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($contracts->hasPages()): ?>
            <div class="p-4 border-top">
                <?php echo e($contracts->links('pagination::bootstrap-5')); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Pham Thien\Documents\GitHub\QLNV_KLTN\resources\views/contracts/index.blade.php ENDPATH**/ ?>