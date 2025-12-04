

<?php $__env->startSection('title', 'Lịch sử chấm công'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-clock-history me-2"></i>
                Lịch sử chấm công
            </h2>
            <p class="text-muted mb-0">Xem lại thời gian làm việc của bạn</p>
        </div>
        <div>
            <input type="month" 
                   id="monthFilter" 
                   value="<?php echo e(request('month', now()->format('Y-m'))); ?>"
                   class="form-control"
                   style="border-radius: 12px; border: 2px solid #e2e8f0;">
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="py-3 px-4 fw-semibold">Ngày</th>
                            <th class="py-3 px-4 fw-semibold">Check-in</th>
                            <th class="py-3 px-4 fw-semibold">Check-out</th>
                            <th class="py-3 px-4 fw-semibold">Thời gian làm</th>
                            <th class="py-3 px-4 fw-semibold">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $attendances ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-3 px-4">
                                <div class="fw-semibold"><?php echo e(\Carbon\Carbon::parse($attendance->work_date)->format('d/m/Y')); ?></div>
                                <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($attendance->work_date)->locale('vi')->dayName); ?></small>
                            </td>
                            <td class="py-3 px-4">
                                <?php if($attendance->check_in_at): ?>
                                    <div class="fw-semibold"><?php echo e(\Carbon\Carbon::parse($attendance->check_in_at)->format('H:i')); ?></div>
                                    <small class="text-muted">
                                        <i class="bi bi-<?php echo e($attendance->check_in_method === 'qr' ? 'qr-code' : ($attendance->check_in_method === 'mobile' ? 'phone' : 'hand-index')); ?>"></i>
                                        <?php echo e($attendance->check_in_method === 'qr' ? 'QR' : ($attendance->check_in_method === 'mobile' ? 'Mobile' : 'Thủ công')); ?>

                                    </small>
                                <?php else: ?>
                                    <span class="text-danger">Chưa check-in</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4">
                                <?php if($attendance->check_out_at): ?>
                                    <div class="fw-semibold"><?php echo e(\Carbon\Carbon::parse($attendance->check_out_at)->format('H:i')); ?></div>
                                    <small class="text-muted">
                                        <i class="bi bi-<?php echo e($attendance->check_out_method === 'qr' ? 'qr-code' : ($attendance->check_out_method === 'mobile' ? 'phone' : 'hand-index')); ?>"></i>
                                        <?php echo e($attendance->check_out_method === 'qr' ? 'QR' : ($attendance->check_out_method === 'mobile' ? 'Mobile' : 'Thủ công')); ?>

                                    </small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4">
                                <?php if($attendance->check_in_at && $attendance->check_out_at): ?>
                                    <span class="fw-semibold">
                                        <?php echo e(\Carbon\Carbon::parse($attendance->check_in_at)->diffInHours(\Carbon\Carbon::parse($attendance->check_out_at))); ?>h
                                        <?php echo e(\Carbon\Carbon::parse($attendance->check_in_at)->diffInMinutes(\Carbon\Carbon::parse($attendance->check_out_at)) % 60); ?>m
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4">
                                <?php if($attendance->status === 'on_time'): ?>
                                    <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 0.5rem 1rem; border-radius: 8px;">
                                        <i class="bi bi-check-circle me-1"></i>Đúng giờ
                                    </span>
                                <?php elseif($attendance->status === 'late'): ?>
                                    <span class="badge bg-warning text-dark" style="padding: 0.5rem 1rem; border-radius: 8px;">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Đi muộn
                                    </span>
                                <?php elseif($attendance->status === 'early_leave'): ?>
                                    <span class="badge bg-danger" style="padding: 0.5rem 1rem; border-radius: 8px;">
                                        <i class="bi bi-clock me-1"></i>Về sớm
                                    </span>
                                <?php elseif($attendance->status === 'absent'): ?>
                                    <span class="badge bg-dark" style="padding: 0.5rem 1rem; border-radius: 8px;">
                                        <i class="bi bi-x-circle me-1"></i>Vắng mặt
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary" style="padding: 0.5rem 1rem; border-radius: 8px;">
                                        <i class="bi bi-question-circle me-1"></i>Chờ xử lý
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Chưa có dữ liệu chấm công</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('monthFilter').addEventListener('change', function() {
        const month = this.value;
        window.location.href = `<?php echo e(route('attendance.history')); ?>?month=${month}`;
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KLTN_QLNV\resources\views/attendance/history.blade.php ENDPATH**/ ?>