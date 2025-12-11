<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả chấm công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        .result-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        .icon-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 3rem;
        }
        .success-icon {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        .error-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .message {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .user-name {
            color: #667eea;
            font-weight: 600;
            margin-top: 1rem;
        }
        .time {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="result-card">
        <div class="icon-wrapper <?php echo e($success ? 'success-icon' : 'error-icon'); ?>">
            <i class="bi bi-<?php echo e($success ? 'check-circle-fill' : 'x-circle-fill'); ?>"></i>
        </div>
        <h2 class="message <?php echo e($success ? 'text-success' : 'text-danger'); ?>">
            <?php echo e($message); ?>

        </h2>
        <?php if(isset($user_name)): ?>
        <div class="user-name">
            <i class="bi bi-person me-2"></i>
            <?php echo e($user_name); ?>

        </div>
        <?php endif; ?>
        <?php if(isset($time)): ?>
        <div class="time">
            <i class="bi bi-clock me-2"></i>
            <?php echo e($time); ?>

        </div>
        <?php endif; ?>
        <div class="mt-4">
            <button onclick="window.close()" class="btn btn-primary w-100" style="border-radius: 12px;">
                <i class="bi bi-check-lg me-2"></i>
                Đóng
            </button>
        </div>
    </div>
</body>
</html>

<?php /**PATH C:\Users\Pham Thien\Documents\GitHub\QLNV_KLTN\resources\views/attendance/qr-result.blade.php ENDPATH**/ ?>