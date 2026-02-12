

<?php $__env->startSection('title', 'Hồ sơ của tôi'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Hồ sơ của tôi</h2>
                    <p class="text-muted mb-0">Thông tin cá nhân và hợp đồng</p>
                </div>
                <div class="d-flex gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-employees')): ?>
                    <a href="<?php echo e(route('employee-profiles.edit', $user)); ?>" class="btn btn-gradient">
                        <i class="bi bi-pencil me-2"></i>Chỉnh sửa hồ sơ
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(url()->previous()); ?>" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <?php if($user->avatar_path): ?>
                                <img src="<?php echo e(asset('storage/' . $user->avatar_path)); ?>" alt="avatar" class="img-fluid rounded mb-3" style="max-width:180px;">
                            <?php else: ?>
                                <div class="user-avatar mx-auto mb-3" style="width:180px;height:180px;border-radius:12px;font-size:56px;display:flex;align-items:center;justify-content:center;">
                                    <?php echo e(strtoupper(substr($user->name,0,1))); ?>

                                </div>
                            <?php endif; ?>

                            <h5 class="fw-bold mb-1"><?php echo e($user->name); ?></h5>
                            <div class="text-muted mb-3"><?php echo e($user->email); ?></div>

                            <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="mb-3">
                                    <input type="file" name="avatar" class="form-control">
                                    <div class="form-text">Hỗ trợ: jpeg, jpg, png, gif, webp. Tối đa 5MB.</div>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-gradient">Cập nhật ảnh</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="card h-100">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-4"><i class="bi bi-person-badge me-2"></i>Thông tin cá nhân</h5>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Mã nhân viên</small><h6 class="fw-semibold mb-0"><?php echo e($user->code ?? '-'); ?></h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">CMND/CCCD</small><h6 class="fw-semibold mb-0"><?php echo e($user->profile->id_number ?? '-'); ?></h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Ngày sinh</small><h6 class="fw-semibold mb-0"><?php echo e($user->profile->date_of_birth ? $user->profile->date_of_birth->format('d/m/Y') : '-'); ?></h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Giới tính</small><h6 class="fw-semibold mb-0"><?php if($user->profile->gender): ?><?php echo e($user->profile->gender === 'male' ? 'Nam' : ($user->profile->gender === 'female' ? 'Nữ' : 'Khác')); ?><?php else: ?> - <?php endif; ?></h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Địa chỉ</small><h6 class="fw-semibold mb-0"><?php echo e($user->profile->address ?? '-'); ?></h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Số điện thoại</small><h6 class="fw-semibold mb-0"><?php echo e($user->phone ?? ($user->profile->phone ?? '-')); ?></h6></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card h-100">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-4"><i class="bi bi-mortarboard me-2"></i>Học vấn & Kinh nghiệm</h5>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Trình độ</small><h6 class="fw-semibold mb-0"><?php echo e($user->profile->education_level ?? '-'); ?></h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Chuyên ngành</small><h6 class="fw-semibold mb-0"><?php echo e($user->profile->major ?? '-'); ?></h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Trường</small><h6 class="fw-semibold mb-0"><?php echo e($user->profile->university ?? '-'); ?></h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Kinh nghiệm</small><h6 class="fw-semibold mb-0"><?php echo e($user->profile->years_of_experience ?? 0); ?> năm</h6></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-4"><i class="bi bi-file-earmark-text me-2"></i>Hợp đồng</h5>
                                    <?php if($user->contracts && $user->contracts->count() > 0): ?>
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $user->contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($contract->contract_number); ?></td>
                                                    <td><?php echo e($contract->contract_type); ?></td>
                                                    <td><?php echo e($contract->start_date?->format('d/m/Y')); ?></td>
                                                    <td><?php echo e($contract->end_date?->format('d/m/Y') ?? '-'); ?></td>
                                                    <td><?php echo e($contract->salary ? number_format($contract->salary,0,',','.') . 'đ' : '-'); ?></td>
                                                    <td><?php echo e($contract->status); ?></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <div class="text-muted">Không có hợp đồng.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Documents\GitHub\QLNV_KLTN\resources\views/profile/edit.blade.php ENDPATH**/ ?>