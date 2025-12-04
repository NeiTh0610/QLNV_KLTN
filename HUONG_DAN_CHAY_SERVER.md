# Hướng dẫn chạy server Laravel

## Vấn đề
Khi truy cập bằng IP (ví dụ: `http://192.168.5.175`), vẫn hiện trang XAMPP thay vì Laravel.

## Giải pháp: Sử dụng Laravel Serve

### Bước 1: Tắt Apache trong XAMPP (nếu đang chạy)
1. Mở XAMPP Control Panel
2. Stop Apache (nếu đang chạy)

### Bước 2: Chạy Laravel server
Mở Command Prompt hoặc PowerShell trong thư mục dự án và chạy:

```bash
cd C:\xampp\htdocs\KLTN_QLNV
php artisan serve --host=0.0.0.0 --port=8000
```

### Bước 3: Truy cập ứng dụng
- **Từ máy tính:** `http://localhost:8000`
- **Từ điện thoại (cùng mạng WiFi):** `http://192.168.5.175:8000` (thay bằng IP hiện tại của bạn)

### Lưu ý
- QR code sẽ tự động dùng IP thực tế (192.168.5.175 hoặc IP hiện tại)
- Khi đổi mạng, reload trang để cập nhật IP tự động
- Không cần cấu hình Apache nữa

## Cách 2: Sử dụng Apache (nếu muốn dùng port 80)

Nếu muốn dùng Apache và truy cập qua port 80 (không cần :8000):

### Bước 1: Cấu hình DocumentRoot trong Apache
1. Mở XAMPP Control Panel → Config (Apache) → `httpd.conf`
2. Tìm dòng `DocumentRoot` và sửa thành:
```apache
DocumentRoot "C:/xampp/htdocs/KLTN_QLNV/public"
```
3. Tìm dòng `<Directory "C:/xampp/htdocs">` và sửa thành:
```apache
<Directory "C:/xampp/htdocs/KLTN_QLNV/public">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

### Bước 2: Khởi động lại Apache
- Stop và Start lại Apache trong XAMPP Control Panel

### Bước 3: Truy cập
- **Từ máy tính:** `http://localhost`
- **Từ điện thoại:** `http://192.168.5.175` (thay bằng IP hiện tại)

**Lưu ý:** Với cách này, bạn cần cập nhật code để QR code không thêm port 80.

