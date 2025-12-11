<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Ứng dụng đang offline</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f766e">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #1f2933;
        }

        .offline-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.18);
            text-align: center;
        }

        .offline-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0f766e 0%, #22c55e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #ffffff;
            font-size: 2rem;
        }

        h1 {
            font-size: 1.4rem;
            margin-bottom: 0.75rem;
        }

        p {
            margin: 0.3rem 0;
            font-size: 0.95rem;
            color: #4b5563;
        }

        .hint {
            margin-top: 1.25rem;
            font-size: 0.85rem;
            color: #6b7280;
        }

        .btn-retry {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.6rem 1.6rem;
            border-radius: 999px;
            border: none;
            background: linear-gradient(135deg, #0f766e 0%, #22c55e 100%);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(16, 185, 129, 0.35);
        }

        .btn-retry:active {
            transform: translateY(1px);
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35);
        }
    </style>
</head>
<body>
    <div class="offline-card">
        <div class="offline-icon">⚡</div>
        <h1>Bạn đang offline</h1>
        <p>Kết nối mạng hiện tại không khả dụng.</p>
        <p>Vui lòng kiểm tra lại Wi-Fi/4G của bạn.</p>

        <p class="hint">
            Một số chức năng vẫn có thể sử dụng nhờ bộ nhớ đệm của ứng dụng.
        </p>

        <button class="btn-retry" onclick="window.location.reload()">
            Thử tải lại
        </button>
    </div>
</body>
</html>


