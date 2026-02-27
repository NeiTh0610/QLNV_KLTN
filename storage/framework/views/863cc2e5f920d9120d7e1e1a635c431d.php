<?php $__env->startSection('title', 'Yêu cầu quên mật khẩu'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Yêu cầu quên mật khẩu</h2>
                    <p class="text-muted mb-0">Danh sách nhân viên yêu cầu hỗ trợ đặt lại mật khẩu</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nhân viên</th>
                                    <th>Email</th>
                                    <th>Trạng thái</th>
                                    <th>Ghi chú</th>
                                    <th>Thời gian</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($requests->firstItem() + $index); ?></td>
                                        <td>
                                            <?php if($request->user): ?>
                                                <strong><?php echo e($request->user->name); ?></strong><br>
                                                <small class="text-muted"><?php echo e($request->user->code); ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">Không tìm thấy người dùng</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($request->email); ?></td>
                                        <td>
                                            <?php
                                                $badgeClass = match($request->status) {
                                                    'processed' => 'badge bg-success',
                                                    'rejected' => 'badge bg-danger',
                                                    default => 'badge bg-warning text-dark',
                                                };
                                            ?>
                                            <span class="<?php echo e($badgeClass); ?>">
                                                <?php echo e($request->status === 'pending' ? 'Chờ xử lý' : ($request->status === 'processed' ? 'Đã xử lý' : 'Từ chối')); ?>

                                            </span>
                                        </td>
                                        <td style="max-width: 220px;">
                                            <small class="text-muted"><?php echo e($request->note); ?></small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo e($request->created_at->format('d/m/Y H:i')); ?>

                                            </small>
                                        </td>
                                        <td class="text-end">
                                            <form method="POST" action="<?php echo e(route('password-requests.update', $request)); ?>" class="d-inline-block text-start" style="min-width:260px;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-5">
                                                        <select name="status" class="form-select form-select-sm">
                                                            <option value="pending" <?php echo e($request->status === 'pending' ? 'selected' : ''); ?>>Chờ xử lý</option>
                                                            <option value="processed" <?php echo e($request->status === 'processed' ? 'selected' : ''); ?>>Đã xử lý</option>
                                                            <option value="rejected" <?php echo e($request->status === 'rejected' ? 'selected' : ''); ?>>Từ chối</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-5">
                                                        <input type="text" name="note" value="<?php echo e($request->note); ?>" class="form-control form-control-sm" placeholder="Ghi chú">
                                                    </div>
                                                    <div class="col-2 text-end">
                                                        <button class="btn btn-sm btn-primary">
                                                            Lưu
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Chưa có yêu cầu quên mật khẩu nào.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($requests->hasPages()): ?>
                    <div class="card-footer">
                        <?php echo e($requests->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Documents\GitHub\QLNV_KLTN\resources\views/password-requests/index.blade.php ENDPATH**/ ?>