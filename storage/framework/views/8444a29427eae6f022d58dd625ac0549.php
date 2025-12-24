<?php $__env->startSection('title', 'Cài đặt'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            <i class="bi bi-gear me-2"></i>
            Cài đặt hệ thống
        </h2>
        <p class="text-muted mb-0">Cấu hình các thông số hệ thống</p>
    </div>

    <!-- Working Hours Settings -->
    <div class="card mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-clock me-2"></i>
                Giờ làm việc
            </h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="<?php echo e(route('settings.update')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Giờ bắt đầu</label>
                        <input type="time" name="working_hours_start" value="<?php echo e($settings['working_hours_start']); ?>" class="form-control" style="border-radius: 12px;" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Giờ kết thúc</label>
                        <input type="time" name="working_hours_end" value="<?php echo e($settings['working_hours_end']); ?>" class="form-control" style="border-radius: 12px;" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ngưỡng đi muộn (phút)</label>
                        <input type="number" name="late_threshold" value="<?php echo e($settings['late_threshold']); ?>" class="form-control" style="border-radius: 12px;" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ngưỡng về sớm (phút)</label>
                        <input type="number" name="early_leave_threshold" value="<?php echo e($settings['early_leave_threshold']); ?>" class="form-control" style="border-radius: 12px;" required>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-gradient">
                        <i class="bi bi-check-circle me-2"></i>
                        Lưu cài đặt
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Holidays -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-calendar-event me-2"></i>
                Ngày lễ
            </h5>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#holidayModal" onclick="openHolidayModal()">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm ngày lễ
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 fw-semibold">Tên ngày lễ</th>
                            <th class="py-3 px-4 fw-semibold">Ngày</th>
                            <th class="py-3 px-4 fw-semibold">Lặp lại hàng năm</th>
                            <th class="py-3 px-4 fw-semibold">Nghỉ bù</th>
                            <th class="py-3 px-4 fw-semibold text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $holidays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $holiday): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-3 px-4 fw-semibold"><?php echo e($holiday->name); ?></td>
                            <td class="py-3 px-4">
                                <?php if($holiday->end_date && $holiday->end_date != $holiday->date): ?>
                                    <div><?php echo e($holiday->date->format('d/m/Y')); ?></div>
                                    <div class="text-muted small">đến <?php echo e($holiday->end_date->format('d/m/Y')); ?></div>
                                    <div class="badge bg-info mt-1">
                                        <?php echo e(\Carbon\Carbon::parse($holiday->date)->diffInDays($holiday->end_date) + 1); ?> ngày
                                    </div>
                                <?php else: ?>
                                    <?php echo e($holiday->date->format('d/m/Y')); ?>

                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4">
                                <?php if($holiday->is_recurring): ?>
                                    <span class="badge bg-success">Có</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Không</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4"><?php echo e($holiday->compensate_to ? $holiday->compensate_to->format('d/m/Y') : '-'); ?></td>
                            <td class="py-3 px-4 text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="editHoliday(<?php echo e($holiday->id); ?>, '<?php echo e($holiday->name); ?>', '<?php echo e($holiday->date->format('Y-m-d')); ?>', '<?php echo e($holiday->end_date ? $holiday->end_date->format('Y-m-d') : ''); ?>', <?php echo e($holiday->is_recurring ? 'true' : 'false'); ?>, '<?php echo e($holiday->compensate_to ? $holiday->compensate_to->format('Y-m-d') : ''); ?>')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="<?php echo e(route('settings.holidays.destroy', $holiday)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa ngày lễ này?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Chưa có ngày lễ nào</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Holiday Modal -->
    <div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="holidayModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <h5 class="modal-title fw-bold" id="holidayModalLabel">
                        <i class="bi bi-calendar-event me-2"></i>
                        <span id="modalTitle">Thêm ngày lễ</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="holidayForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <div id="methodField"></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tên ngày lễ <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="holidayName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="holidayDate" class="form-control" required onchange="updateEndDateMin()">
                            <small class="text-muted">Ngày bắt đầu của kỳ nghỉ lễ</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ngày kết thúc (tùy chọn)</label>
                            <input type="date" name="end_date" id="holidayEndDate" class="form-control" min="">
                            <small class="text-muted">Để trống nếu chỉ nghỉ 1 ngày. Chọn ngày kết thúc cho kỳ nghỉ lễ dài ngày</small>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_recurring" id="holidayRecurring" value="1">
                                <label class="form-check-label" for="holidayRecurring">
                                    Lặp lại hàng năm
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ngày nghỉ bù (tùy chọn)</label>
                            <input type="date" name="compensate_to" id="holidayCompensate" class="form-control">
                            <small class="text-muted">Nếu có nghỉ bù, chọn ngày nghỉ bù</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="bi bi-check-circle me-2"></i>
                            Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateEndDateMin() {
            const startDate = document.getElementById('holidayDate').value;
            const endDateInput = document.getElementById('holidayEndDate');
            if (startDate && endDateInput) {
                endDateInput.min = startDate;
                // Update Flatpickr instance if exists
                if (endDateInput._flatpickr) {
                    endDateInput._flatpickr.set('minDate', startDate);
                }
            }
        }

        function openHolidayModal() {
            document.getElementById('modalTitle').textContent = 'Thêm ngày lễ';
            document.getElementById('holidayForm').action = '<?php echo e(route("settings.holidays.store")); ?>';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('holidayName').value = '';
            
            const holidayDateInput = document.getElementById('holidayDate');
            const holidayEndDateInput = document.getElementById('holidayEndDate');
            const holidayCompensateInput = document.getElementById('holidayCompensate');
            
            if (holidayDateInput) {
                holidayDateInput.value = '';
                if (holidayDateInput._flatpickr) {
                    holidayDateInput._flatpickr.clear();
                }
            }
            
            if (holidayEndDateInput) {
                holidayEndDateInput.value = '';
                holidayEndDateInput.min = '';
                if (holidayEndDateInput._flatpickr) {
                    holidayEndDateInput._flatpickr.clear();
                    holidayEndDateInput._flatpickr.set('minDate', null);
                }
            }
            
            if (holidayCompensateInput) {
                holidayCompensateInput.value = '';
                if (holidayCompensateInput._flatpickr) {
                    holidayCompensateInput._flatpickr.clear();
                }
            }
            
            document.getElementById('holidayRecurring').checked = false;
        }

        function editHoliday(id, name, date, endDate, isRecurring, compensateTo) {
            document.getElementById('modalTitle').textContent = 'Sửa ngày lễ';
            document.getElementById('holidayForm').action = '<?php echo e(route("settings.holidays.update", ":id")); ?>'.replace(':id', id);
            document.getElementById('methodField').innerHTML = '<?php echo method_field("PUT"); ?>';
            document.getElementById('holidayName').value = name;
            
            const holidayDateInput = document.getElementById('holidayDate');
            const holidayEndDateInput = document.getElementById('holidayEndDate');
            const holidayCompensateInput = document.getElementById('holidayCompensate');
            
            if (holidayDateInput) {
                holidayDateInput.value = date;
                if (holidayDateInput._flatpickr) {
                    holidayDateInput._flatpickr.setDate(date, false);
                }
            }
            
            if (holidayEndDateInput) {
                holidayEndDateInput.value = endDate || '';
                if (endDate) {
                    holidayEndDateInput.min = date;
                }
                if (holidayEndDateInput._flatpickr) {
                    if (endDate) {
                        holidayEndDateInput._flatpickr.setDate(endDate, false);
                        holidayEndDateInput._flatpickr.set('minDate', date);
                    } else {
                        holidayEndDateInput._flatpickr.clear();
                        holidayEndDateInput._flatpickr.set('minDate', date);
                    }
                }
            }
            
            if (holidayCompensateInput) {
                holidayCompensateInput.value = compensateTo || '';
                if (holidayCompensateInput._flatpickr) {
                    if (compensateTo) {
                        holidayCompensateInput._flatpickr.setDate(compensateTo, false);
                    } else {
                        holidayCompensateInput._flatpickr.clear();
                    }
                }
            }
            
            document.getElementById('holidayRecurring').checked = isRecurring;
            
            const modal = new bootstrap.Modal(document.getElementById('holidayModal'));
            modal.show();
        }

        // Cập nhật min date khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            updateEndDateMin();
            
            // Reinitialize Flatpickr when modal is shown
            const holidayModal = document.getElementById('holidayModal');
            if (holidayModal) {
                holidayModal.addEventListener('shown.bs.modal', function() {
                    // Reinitialize Flatpickr for inputs in modal
                    const holidayDateInput = document.getElementById('holidayDate');
                    const holidayEndDateInput = document.getElementById('holidayEndDate');
                    const holidayCompensateInput = document.getElementById('holidayCompensate');
                    
                    [holidayDateInput, holidayEndDateInput, holidayCompensateInput].forEach(function(input) {
                        if (input && !input._flatpickr) {
                            const options = {
                                locale: 'vn',
                                dateFormat: 'Y-m-d',
                                altInput: true,
                                altFormat: 'd/m/Y',
                                allowInput: true,
                                clickOpens: true,
                                animate: true,
                                monthSelectorType: 'static',
                                static: true,
                                inline: false,
                                showMonths: 1,
                            };
                            
                            if (input.hasAttribute('min')) {
                                options.minDate = input.getAttribute('min');
                            }
                            
                            if (input.value) {
                                options.defaultDate = input.value;
                            }
                            
                            flatpickr(input, options);
                        }
                    });
                });
            }
        });
    </script>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Documents\GitHub\QLNV_KLTN\resources\views/settings/index.blade.php ENDPATH**/ ?>