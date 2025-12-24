<?php $__env->startSection('title', 'Đăng ký làm thêm giờ'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-clock-history me-2"></i>
                Đăng ký làm thêm giờ
            </h2>
            <p class="text-muted mb-0">Quản lý đăng ký làm thêm giờ</p>
        </div>
        <?php if(!auth()->user()->canManageEmployees()): ?>
        <a href="<?php echo e(route('overtime-requests.create')); ?>" class="btn btn-gradient">
            <i class="bi bi-plus-circle me-2"></i>
            Đăng ký mới
        </a>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('overtime-requests.index')); ?>">
                <div class="row g-3 align-items-end">
                    <?php if(auth()->user()->canManageEmployees()): ?>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nhân viên</label>
                        <select name="user_id" class="form-select" style="border-radius: 12px;">
                            <option value="">Tất cả nhân viên</option>
                            <?php $__currentLoopData = \App\Models\User::where('status', 'active')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="status" class="form-select" style="border-radius: 12px;">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Chờ duyệt</option>
                            <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Đã duyệt</option>
                            <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Từ chối</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tháng</label>
                        <input type="month" name="month" class="form-control" value="<?php echo e(request('month', now()->format('Y-m'))); ?>" style="border-radius: 12px;">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="bi bi-funnel me-2"></i>
                            Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Overtime Requests Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <?php if(auth()->user()->canManageEmployees()): ?>
                            <th class="py-3 px-4 fw-semibold">Nhân viên</th>
                            <?php endif; ?>
                            <th class="py-3 px-4 fw-semibold">Ngày</th>
                            <th class="py-3 px-4 fw-semibold">Thời gian</th>
                            <th class="py-3 px-4 fw-semibold">Số giờ</th>
                            <th class="py-3 px-4 fw-semibold">Lý do</th>
                            <th class="py-3 px-4 fw-semibold">Trạng thái</th>
                            <th class="py-3 px-4 fw-semibold text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $overtimeRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <?php if(auth()->user()->canManageEmployees()): ?>
                            <td class="py-3 px-4"><?php echo e($request->user->name); ?></td>
                            <?php endif; ?>
                            <td class="py-3 px-4"><?php echo e($request->date->format('d/m/Y')); ?></td>
                            <td class="py-3 px-4">
                                <?php echo e(\Carbon\Carbon::parse($request->start_time)->format('H:i')); ?> - 
                                <?php echo e(\Carbon\Carbon::parse($request->end_time)->format('H:i')); ?>

                            </td>
                            <td class="py-3 px-4 fw-semibold"><?php echo e(number_format($request->hours, 2)); ?>h</td>
                            <td class="py-3 px-4">
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="<?php echo e($request->reason); ?>">
                                    <?php echo e($request->reason ?? '-'); ?>

                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <?php if($request->status === 'approved'): ?>
                                    <span class="badge bg-success">Đã duyệt</span>
                                <?php elseif($request->status === 'rejected'): ?>
                                    <span class="badge bg-danger">Từ chối</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Chờ duyệt</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-end">
                                <a href="<?php echo e(route('overtime-requests.show', $request)); ?>" class="btn btn-sm btn-outline-info me-2">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if($request->status === 'pending'): ?>
                                    <?php if(auth()->user()->canManageEmployees()): ?>
                                        <form action="<?php echo e(route('overtime-requests.approve', $request)); ?>" method="POST" class="d-inline me-2">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Bạn có chắc chắn muốn duyệt đăng ký này?');">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo e($request->id); ?>">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    <?php elseif($request->user_id === auth()->id()): ?>
                                        <a href="<?php echo e(route('overtime-requests.edit', $request)); ?>" class="btn btn-sm btn-outline-primary me-2">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?php echo e(route('overtime-requests.destroy', $request)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đăng ký này?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        
                        <!-- Reject Modal -->
                        <?php if(auth()->user()->canManageEmployees() && $request->status === 'pending'): ?>
                        <div class="modal fade" id="rejectModal<?php echo e($request->id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="<?php echo e(route('overtime-requests.reject', $request)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Từ chối đăng ký làm thêm giờ</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Lý do từ chối (tùy chọn)</label>
                                                <textarea name="rejection_reason" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-danger">Từ chối</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="<?php echo e(auth()->user()->canManageEmployees() ? '7' : '6'); ?>" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Chưa có đăng ký làm thêm giờ nào</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($overtimeRequests->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($overtimeRequests->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Documents\GitHub\QLNV_KLTN\resources\views/overtime-requests/index.blade.php ENDPATH**/ ?>