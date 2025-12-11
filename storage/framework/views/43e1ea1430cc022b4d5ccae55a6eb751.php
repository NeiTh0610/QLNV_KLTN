

<?php $__env->startSection('title', 'Chấm công'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="card card-gradient mb-4">
        <div class="card-body p-4">
            <h1 class="display-6 fw-bold mb-2">
                <i class="bi bi-fingerprint me-2"></i>
                Chấm công
            </h1>
            <p class="lead mb-0 opacity-75">Quản lý thời gian làm việc của bạn</p>
        </div>
    </div>

    <!-- Check-in/Check-out Cards -->
    <div class="row g-4 mb-4">
        <!-- Check-in Card -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <div class="stat-icon mx-auto mb-3" style="width: 96px; height: 96px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <i class="bi bi-box-arrow-in-right text-white" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="fw-bold mb-2">Check-in</h3>
                        <p class="text-muted">Bắt đầu ngày làm việc</p>
                    </div>

                    <?php if($todayAttendance && $todayAttendance->check_in_at): ?>
                    <div class="alert alert-success d-flex align-items-center justify-content-between mb-4">
                        <div class="text-start">
                            <small class="d-block">Đã check-in lúc</small>
                            <h2 class="fw-bold mb-0"><?php echo e(\Carbon\Carbon::parse($todayAttendance->check_in_at)->format('H:i')); ?></h2>
                            <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($todayAttendance->check_in_at)->format('d/m/Y')); ?></small>
                        </div>
                        <div>
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-lg" disabled>
                        <i class="bi bi-check-circle me-2"></i>
                        Đã check-in
                    </button>
                    <?php else: ?>
                    <form method="POST" action="<?php echo e(route('attendance.check-in')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; color: white; padding: 1rem;">
                            <i class="bi bi-fingerprint me-2" style="font-size: 1.5rem;"></i>
                            <span class="fw-bold">Check-in ngay</span>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Check-out Card -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <div class="stat-icon mx-auto mb-3" style="width: 96px; height: 96px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="bi bi-box-arrow-right text-white" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="fw-bold mb-2">Check-out</h3>
                        <p class="text-muted">Kết thúc ngày làm việc</p>
                    </div>

                    <?php if($todayAttendance && $todayAttendance->check_out_at): ?>
                    <div class="alert alert-info d-flex align-items-center justify-content-between mb-4">
                        <div class="text-start">
                            <small class="d-block">Đã check-out lúc</small>
                            <h2 class="fw-bold mb-0"><?php echo e(\Carbon\Carbon::parse($todayAttendance->check_out_at)->format('H:i')); ?></h2>
                            <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($todayAttendance->check_out_at)->format('d/m/Y')); ?></small>
                        </div>
                        <div>
                            <i class="bi bi-check-circle-fill text-info" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-lg" disabled>
                        <i class="bi bi-check-circle me-2"></i>
                        Đã check-out
                    </button>
                    <?php elseif($todayAttendance && $todayAttendance->check_in_at): ?>
                    <form method="POST" action="<?php echo e(route('attendance.check-out')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; color: white; padding: 1rem;">
                            <i class="bi bi-fingerprint me-2" style="font-size: 1.5rem;"></i>
                            <span class="fw-bold">Check-out ngay</span>
                        </button>
                    </form>
                    <?php else: ?>
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Vui lòng check-in trước
                    </div>
                    <button class="btn btn-secondary btn-lg" disabled>
                        <i class="bi bi-lock me-2"></i>
                        Chưa thể check-out
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Section -->
    <div class="row g-4 mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">
                        <i class="bi bi-qr-code me-2"></i>
                        Quét QR để chấm công
                    </h4>
                    <?php if($todayAttendance && $todayAttendance->check_in_at && !$todayAttendance->check_out_at): ?>
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Lưu ý:</strong> Mã QR này dùng để check-out. Quét lại để kết thúc ngày làm việc.
                    </div>
                    <?php elseif(!$todayAttendance || !$todayAttendance->check_in_at): ?>
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Lưu ý:</strong> Mã QR này dùng để check-in. Quét để bắt đầu ngày làm việc.
                    </div>
                    <?php elseif($todayAttendance && $todayAttendance->check_out_at): ?>
                    <div class="alert alert-success mb-3">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Hoàn thành:</strong> Bạn đã check-in và check-out hôm nay.
                    </div>
                    <?php endif; ?>
                    <div class="row align-items-center">
                        <div class="col-lg-6 text-center">
                            <div class="p-4 rounded-3 d-inline-block" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
                                <?php
                                    $qrData = auth()->user()->id . '|' . now()->format('Y-m-d') . '|' . csrf_token();
                                    $qrUrl = ($qrBaseUrl ?? url('/')) . '/attendance/qr-scan?data=' . urlencode($qrData);
                                    // Sử dụng Google Charts API để tạo QR code
                                    $qrCodeImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=256x256&data=' . urlencode($qrUrl);
                                ?>
                                <div id="qrcode-container" style="display: inline-block; min-height: 256px; min-width: 256px; position: relative;">
                                    <img id="qrcode" 
                                         src="<?php echo e($qrCodeImageUrl); ?>" 
                                         alt="QR Code" 
                                         style="max-width: 256px; max-height: 256px; display: block; margin: 0 auto;"
                                         onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=256x256&data=' + encodeURIComponent('<?php echo e($qrUrl); ?>');">
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshQRCode()" id="refresh-qr-btn">
                                        <i class="bi bi-arrow-clockwise me-1"></i>
                                        Tạo lại QR code
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="p-4">
                                <h5 class="fw-bold mb-3">Hướng dẫn sử dụng</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="bi bi-1-circle-fill text-primary me-2"></i>
                                        Mở ứng dụng camera trên điện thoại
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-2-circle-fill text-primary me-2"></i>
                                        Hướng camera vào mã QR
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-3-circle-fill text-primary me-2"></i>
                                        Nhấn vào thông báo để chấm công
                                    </li>
                                </ul>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <small>Mã QR có hiệu lực trong ngày hôm nay</small>
                                </div>
                                <?php
                                    $qrBaseUrl = $qrBaseUrl ?? url('/');
                                    $serverUrl = $qrBaseUrl;
                                    // Trích xuất IP từ URL
                                    $parsedUrl = parse_url($serverUrl);
                                    $currentIp = $parsedUrl['host'] ?? 'localhost';
                                    // Kiểm tra xem URL có chứa 0.0.0.0 hoặc localhost không
                                    $isInvalidIp = strpos($serverUrl, '0.0.0.0') !== false || strpos($serverUrl, 'localhost') !== false || strpos($serverUrl, '127.0.0.1') !== false;
                                ?>
                                <?php if($isInvalidIp): ?>
                                <div class="alert alert-warning mt-3">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <small>
                                        <strong>Lưu ý:</strong> Hệ thống không thể tự động phát hiện IP. Vui lòng kiểm tra cấu hình.<br>
                                        <strong>URL hiện tại:</strong> <code><?php echo e($serverUrl); ?></code><br>
                                        <small class="text-muted">
                                            <strong>Hướng dẫn:</strong><br>
                                            1. Mở Command Prompt và chạy: <code>ipconfig | findstr /i "IPv4"</code><br>
                                            2. Tìm IP của bạn (thường là 192.168.x.x hoặc 10.x.x.x)<br>
                                            3. Chạy Laravel server: <code>php artisan serve --host=0.0.0.0 --port=8000</code><br>
                                            4. Reload trang này để hệ thống tự động cập nhật IP<br>
                                            5. Đảm bảo điện thoại và máy tính cùng mạng WiFi
                                        </small>
                                    </small>
                                </div>
                                <?php else: ?>
                                <div class="alert alert-info mt-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <small>
                                        <strong>Thông tin kết nối:</strong><br>
                                        <strong>IP hiện tại:</strong> <code><?php echo e($currentIp); ?></code><br>
                                        <strong>Server URL:</strong> <code><?php echo e($serverUrl); ?></code><br>
                                        <small class="text-muted">
                                            <strong>Hướng dẫn:</strong><br>
                                            1. Đảm bảo điện thoại và máy tính cùng mạng WiFi<br>
                                            2. Chạy Laravel server: <code>php artisan serve --host=0.0.0.0 --port=8000</code><br>
                                            3. Truy cập từ điện thoại: <code><?php echo e($serverUrl); ?></code> để kiểm tra kết nối<br>
                                            4. Nếu đổi mạng, reload trang này để cập nhật IP tự động<br>
                                            5. Nếu không kết nối được, kiểm tra Firewall Windows
                                        </small>
                                    </small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Info -->
    <?php if($todayAttendance): ?>
    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Thông tin hôm nay
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #11998e15 0%, #38ef7d15 100%);">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                    <span class="badge badge-custom" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">Trạng thái</span>
                                </div>
                                <h5 class="fw-bold mb-1">
                                    <?php if($todayAttendance->status === 'on_time'): ?>
                                        <span class="text-success">Đúng giờ</span>
                                    <?php elseif($todayAttendance->status === 'late'): ?>
                                        <span class="text-warning">Đi muộn</span>
                                    <?php elseif($todayAttendance->status === 'early_leave'): ?>
                                        <span class="text-danger">Về sớm</span>
                                    <?php else: ?>
                                        <span class="text-muted">Chưa xác định</span>
                                    <?php endif; ?>
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%);">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <i class="bi bi-phone text-info" style="font-size: 2rem;"></i>
                                    <span class="badge badge-custom" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">Phương thức</span>
                                </div>
                                <h5 class="fw-bold mb-1">
                                    <?php echo e($todayAttendance->check_in_method === 'qr' ? 'QR Code' : ($todayAttendance->check_in_method === 'mobile' ? 'Mobile' : 'Thủ công')); ?>

                                </h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%);">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <i class="bi bi-hourglass-split text-danger" style="font-size: 2rem;"></i>
                                    <span class="badge badge-custom" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">Thời gian</span>
                                </div>
                                <h5 class="fw-bold mb-1">
                                    <?php if($todayAttendance->check_in_at && $todayAttendance->check_out_at): ?>
                                        <?php echo e(\Carbon\Carbon::parse($todayAttendance->check_in_at)->diffInHours(\Carbon\Carbon::parse($todayAttendance->check_out_at))); ?>h 
                                        <?php echo e(\Carbon\Carbon::parse($todayAttendance->check_in_at)->diffInMinutes(\Carbon\Carbon::parse($todayAttendance->check_out_at)) % 60); ?>m
                                    <?php else: ?>
                                        <span class="text-muted">Đang tính...</span>
                                    <?php endif; ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    // Hàm refresh QR code
    function refreshQRCode() {
        const qrImg = document.getElementById("qrcode");
        const refreshBtn = document.getElementById("refresh-qr-btn");
        
        if (!qrImg) {
            console.error('Không tìm thấy QR code image');
            return;
        }
        
        if (refreshBtn) {
            refreshBtn.disabled = true;
            refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Đang tạo...';
        }
        
        // Lấy URL hiện tại và thêm timestamp để force reload
        const currentSrc = qrImg.src.split('&t=')[0];
        const newSrc = currentSrc + '&t=' + new Date().getTime();
        
        // Reload image
        qrImg.onload = function() {
            if (refreshBtn) {
                refreshBtn.disabled = false;
                refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Tạo lại QR code';
            }
            console.log('QR Code đã được refresh');
        };
        
        qrImg.onerror = function() {
            console.error('Lỗi khi load QR code image');
            if (refreshBtn) {
                refreshBtn.disabled = false;
                refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Tạo lại QR code';
            }
        };
        
        qrImg.src = newSrc;
    }
    
    // Đảm bảo QR code được load khi trang load
    window.addEventListener('load', function() {
        const qrImg = document.getElementById("qrcode");
        if (qrImg) {
            qrImg.onerror = function() {
                console.error('Lỗi khi load QR code, thử lại...');
                // Thử lại sau 1 giây
                setTimeout(function() {
                    const currentSrc = qrImg.src.split('&t=')[0];
                    qrImg.src = currentSrc + '&t=' + new Date().getTime();
                }, 1000);
            };
            
            // Kiểm tra xem image đã load chưa
            if (!qrImg.complete || qrImg.naturalHeight === 0) {
                console.log('QR code đang được tải...');
            } else {
                console.log('QR code đã được load thành công');
            }
        }
    });

    // Auto-reload khi có thay đổi chấm công từ QR code
    (function() {
        // Lưu trạng thái hiện tại
        let currentState = {
            has_check_in: <?php echo e($todayAttendance && $todayAttendance->check_in_at ? 'true' : 'false'); ?>,
            has_check_out: <?php echo e($todayAttendance && $todayAttendance->check_out_at ? 'true' : 'false'); ?>,
            check_in_at: <?php echo e($todayAttendance && $todayAttendance->check_in_at ? "'" . $todayAttendance->check_in_at->toDateTimeString() . "'" : 'null'); ?>,
            check_out_at: <?php echo e($todayAttendance && $todayAttendance->check_out_at ? "'" . $todayAttendance->check_out_at->toDateTimeString() . "'" : 'null'); ?>

        };

        let pollInterval = null;
        let fastPollInterval = null;
        let isReloading = false;
        let lastCheckTime = Date.now();

        // Hàm kiểm tra trạng thái mới
        function checkAttendanceStatus(forceCheck = false) {
            // Tránh gọi nhiều lần cùng lúc (trừ khi force)
            if (isReloading && !forceCheck) {
                return;
            }

            // Thêm timestamp để tránh cache
            const url = '<?php echo e(route("attendance.check-status")); ?>?t=' + Date.now();
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                },
                credentials: 'same-origin',
                cache: 'no-store'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                lastCheckTime = Date.now();
                
                // Chuẩn hóa giá trị null
                const normalizeValue = (val) => {
                    if (val === null || val === '' || val === undefined) return null;
                    return String(val);
                };
                
                const newCheckIn = normalizeValue(data.check_in_at);
                const newCheckOut = normalizeValue(data.check_out_at);
                const oldCheckIn = normalizeValue(currentState.check_in_at);
                const oldCheckOut = normalizeValue(currentState.check_out_at);

                // Log để debug (chỉ log mỗi 5 lần để không spam console)
                if (Math.random() < 0.2 || forceCheck) {
                    console.log('🔍 Kiểm tra trạng thái:', {
                        old: currentState,
                        new: {
                            has_check_in: data.has_check_in,
                            has_check_out: data.has_check_out,
                            check_in_at: newCheckIn,
                            check_out_at: newCheckOut
                        }
                    });
                }

                // So sánh với trạng thái cũ
                const hasChanged = (
                    data.has_check_in !== currentState.has_check_in || 
                    data.has_check_out !== currentState.has_check_out ||
                    newCheckIn !== oldCheckIn ||
                    newCheckOut !== oldCheckOut
                );

                if (hasChanged) {
                    console.log('✅ PHÁT HIỆN THAY ĐỔI CHẤM CÔNG!', {
                        old: currentState,
                        new: data,
                        timestamp: new Date().toLocaleTimeString()
                    });
                    
                    // Dừng tất cả polling
                    if (pollInterval) {
                        clearInterval(pollInterval);
                    }
                    if (fastPollInterval) {
                        clearInterval(fastPollInterval);
                    }
                    
                    isReloading = true;
                    
                    // Reload trang ngay lập tức
                    console.log('🔄 Đang reload trang...');
                    window.location.reload();
                } else {
                    // Cập nhật trạng thái hiện tại
                    currentState.has_check_in = data.has_check_in;
                    currentState.has_check_out = data.has_check_out;
                    currentState.check_in_at = newCheckIn;
                    currentState.check_out_at = newCheckOut;
                }
            })
            .catch(error => {
                console.error('❌ Lỗi khi kiểm tra trạng thái:', error);
                // Không dừng polling khi có lỗi, sẽ thử lại lần sau
            });
        }

        // Kiểm tra ngay lập tức
        checkAttendanceStatus(true);

        // Polling nhanh mỗi 1 giây trong 30 giây đầu (để phát hiện nhanh khi vừa quét QR)
        let fastPollCount = 0;
        fastPollInterval = setInterval(function() {
            fastPollCount++;
            checkAttendanceStatus();
            // Sau 30 lần (30 giây), chuyển sang polling chậm hơn
            if (fastPollCount >= 30) {
                clearInterval(fastPollInterval);
                fastPollInterval = null;
            }
        }, 1000);

        // Polling bình thường mỗi 2 giây (sau khi đã qua 30 giây đầu)
        pollInterval = setInterval(checkAttendanceStatus, 2000);

        // Kiểm tra ngay khi trang được focus lại (người dùng có thể quét QR trên điện thoại)
        let isPageVisible = true;
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden && isPageVisible) {
                // Trang được focus lại, kiểm tra ngay
                console.log('👁️ Trang được focus lại, kiểm tra trạng thái ngay...');
                checkAttendanceStatus(true);
            }
            isPageVisible = !document.hidden;
        });

        // Kiểm tra khi window được focus lại
        window.addEventListener('focus', function() {
            console.log('👁️ Window được focus, kiểm tra trạng thái ngay...');
            checkAttendanceStatus(true);
        });

        // Kiểm tra khi người dùng quay lại tab
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                console.log('📄 Trang được restore từ cache, kiểm tra trạng thái...');
                checkAttendanceStatus(true);
            }
        });

        // Dọn dẹp khi rời khỏi trang
        window.addEventListener('beforeunload', function() {
            if (pollInterval) {
                clearInterval(pollInterval);
            }
            if (fastPollInterval) {
                clearInterval(fastPollInterval);
            }
        });

        // Hiển thị thông báo auto-reload đang hoạt động
        console.log('🔄 Auto-reload đã được kích hoạt!');
        console.log('📊 Trạng thái ban đầu:', currentState);
        console.log('⚡ Polling nhanh: 1 giây/lần trong 30 giây đầu');
        console.log('⏱️ Polling bình thường: 2 giây/lần sau đó');
    })();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Pham Thien\Documents\GitHub\QLNV_KLTN\resources\views/attendance/check-in.blade.php ENDPATH**/ ?>