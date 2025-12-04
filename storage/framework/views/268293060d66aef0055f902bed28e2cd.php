

<?php $__env->startSection('title', 'Chi tiết đăng ký làm thêm giờ'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clock-history me-2"></i>
                        Chi tiết đăng ký làm thêm giờ
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Nhân viên</label>
                            <div class="fw-bold"><?php echo e($overtimeRequest->user->name); ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Ngày</label>
                            <div class="fw-bold"><?php echo e($overtimeRequest->date->format('d/m/Y')); ?></div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Giờ bắt đầu</label>
                            <div class="fw-bold"><?php echo e(\Carbon\Carbon::parse($overtimeRequest->start_time)->format('H:i')); ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Giờ kết thúc</label>
                            <div class="fw-bold"><?php echo e(\Carbon\Carbon::parse($overtimeRequest->end_time)->format('H:i')); ?></div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Số giờ làm thêm</label>
                            <div class="fw-bold text-primary fs-4"><?php echo e(number_format($overtimeRequest->hours, 2)); ?> giờ</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Trạng thái</label>
                            <div>
                                <?php if($overtimeRequest->status === 'approved'): ?>
                                    <span class="badge bg-success fs-6">Đã duyệt</span>
                                <?php elseif($overtimeRequest->status === 'rejected'): ?>
                                    <span class="badge bg-danger fs-6">Từ chối</span>
                                <?php else: ?>
                                    <span class="badge bg-warning fs-6">Chờ duyệt</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">Lý do</label>
                        <div class="p-3 bg-light rounded"><?php echo e($overtimeRequest->reason ?? '-'); ?></div>
                    </div>
                    
                    <?php if($overtimeRequest->status === 'rejected' && $overtimeRequest->rejection_reason): ?>
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">Lý do từ chối</label>
                        <div class="p-3 bg-danger bg-opacity-10 rounded text-danger"><?php echo e($overtimeRequest->rejection_reason); ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($overtimeRequest->approver): ?>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Người duyệt</label>
                            <div><?php echo e($overtimeRequest->approver->name); ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Thời gian duyệt</label>
                            <div><?php echo e($overtimeRequest->approved_at->format('d/m/Y H:i')); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('overtime-requests.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Quay lại
                        </a>
                        <?php if($overtimeRequest->status === 'pending' && $overtimeRequest->user_id === auth()->id()): ?>
                        <div>
                            <a href="<?php echo e(route('overtime-requests.edit', $overtimeRequest)); ?>" class="btn btn-primary me-2">
                                <i class="bi bi-pencil me-2"></i>
                                Sửa
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KLTN_QLNV\resources\views/overtime-requests/show.blade.php ENDPATH**/ ?>