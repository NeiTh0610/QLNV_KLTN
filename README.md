# Hệ thống Chấm công - Laravel

Hệ thống quản lý chấm công nhân viên với đầy đủ tính năng quản lý, báo cáo và tính lương.

## Tính năng chính

### Cho Nhân viên
- ✅ Đăng nhập bằng email/số điện thoại
- ✅ Chấm công nhanh (Check-in/Check-out)
- ✅ Chấm công bằng QR code
- ✅ Xem lịch sử chấm công theo ngày/tháng
- ✅ Xem bảng công và lương tạm tính
- ✅ Đăng ký nghỉ phép, đi muộn, về sớm
- ✅ Nhận thông báo

### Cho Quản lý/Admin
- ✅ Quản lý danh sách nhân viên (CRUD)
- ✅ Phân quyền theo vai trò
- ✅ Duyệt đơn xin nghỉ, đi muộn
- ✅ Xem báo cáo chấm công (ngày/tuần/tháng)
- ✅ Xuất file Excel/PDF
- ✅ Cấu hình giờ làm chuẩn
- ✅ Quy định đi muộn/về sớm
- ✅ Quản lý ngày lễ, nghỉ bù
- ✅ Tính lương tự động (BHXH, BHYT, Thuế TNCN)

## Yêu cầu hệ thống

- PHP >= 8.2
- Composer
- Node.js & NPM (để build frontend)
- MySQL/SQLite
- XAMPP (hoặc Apache/Nginx)

## Cài đặt

### 1. Clone repository
```bash
cd C:\xampp\htdocs\KLTN_QLNV
```

### 2. Cài đặt dependencies
```bash
composer install
npm install
```

### 3. Cấu hình môi trường
Copy file `.env.example` thành `.env` và cấu hình:
```bash
cp .env.example .env
php artisan key:generate
```

Cấu hình database trong `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=qlnv
```

Hoặc MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kltn_qlnv
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Chạy migrations và seeders
```bash
php artisan migrate:fresh --seed
```

### 5. Build frontend assets
```bash
npm run build
```

Hoặc chạy dev mode:
```bash
npm run dev
```

### 6. Tạo symbolic link cho storage
```bash
php artisan storage:link
```

### 7. Chạy server
```bash
 php artisan serve --host=0.0.0.0 --port=8000
```

Truy cập: http://127.0.0.1:8000/

## Tài khoản mặc định

### Admin
- **Email:** admin@goldenbown.com
- **Mật khẩu:** Admin@123

### Nhân viên mẫu
- **Email:** nguyenvana@example.com
- **Mật khẩu:** Password@123

## Cấu trúc dự án

```
KLTN_QLNV/
├── app/
│   ├── Console/Commands/      # Artisan commands
│   ├── Http/Controllers/      # Controllers
│   ├── Models/                # Eloquent models
│   ├── Services/              # Business logic services
│   └── Support/               # Helper classes
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   ├── views/                 # Blade templates
│   ├── css/                   # CSS files
│   └── js/                    # JavaScript files
├── routes/
│   └── web.php                # Web routes
└── public/                    # Public assets
```

## Các lệnh hữu ích

### Database
```bash
# Reset và seed lại database
php artisan migrate:fresh --seed

# Tạo migration mới
php artisan make:migration create_table_name

# Tạo seeder
php artisan make:seeder SeederName
```

### Tính lương
```bash
# Tạo bảng lương cho tháng
php artisan payroll:generate --start=2025-11-01 --end=2025-11-30
```

### Cache
```bash
# Clear tất cả cache
php artisan optimize:clear

# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache
```

## Tính năng nổi bật

### 1. Chấm công thông minh
- Tự động tính trạng thái (đúng giờ, đi muộn, về sớm)
- Hỗ trợ chấm công bằng QR code
- Lưu IP và phương thức chấm công

### 2. Tính lương tự động
- Tính BHXH (8%), BHYT (1.5%), BHTN (1%)
- Tính thuế thu nhập cá nhân theo biểu thuế lũy tiến
- Hỗ trợ thang lương theo cấp bậc
- Tính lương part-time theo giờ

### 3. Báo cáo chi tiết
- Báo cáo theo ngày/tuần/tháng
- Lọc theo phòng ban
- Xuất Excel/CSV
- Thống kê đầy đủ

### 4. Quản lý đơn xin nghỉ
- Tạo đơn online
- Duyệt/từ chối đơn
- Theo dõi trạng thái
- Tính thời gian nghỉ tự động

## Phân quyền

- **Admin:** Toàn quyền quản lý hệ thống
- **Manager:** Quản lý nhân viên, duyệt đơn, xem báo cáo
- **Employee:** Chấm công, xem lịch sử, tạo đơn xin nghỉ
- **Part-time:** Tương tự Employee, tính lương theo giờ

## Cấu hình

### Giờ làm việc
Vào **Cài đặt** → Cấu hình giờ bắt đầu/kết thúc

### Ngưỡng đi muộn/về sớm
Cấu hình số phút cho phép đi muộn/về sớm

### Thang lương
Quản lý thang lương trong database (bảng `salary_grades`)

## Troubleshooting

### Lỗi không tìm thấy route
```bash
php artisan route:clear
php artisan route:cache
```

### Lỗi view không hiển thị
```bash
php artisan view:clear
```

### Lỗi cache
```bash
php artisan optimize:clear
```

### Lỗi npm không tìm thấy
Cài đặt Node.js từ https://nodejs.org/

## License

MIT License

## Tác giả

Hệ thống chấm công - Laravel
