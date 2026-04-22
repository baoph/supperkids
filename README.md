# SupperKids CRM - Hệ thống quản lý lớp học thêm

CRM quản lý trung tâm/lớp học thêm xây dựng bằng **Laravel 10 + Blade + Bootstrap + MySQL**.

## Tính năng chính

- Authentication chuẩn Laravel (Đăng ký/Đăng nhập/Đăng xuất)
- Quản lý Lớp học (CRUD)
- Quản lý Học sinh (CRUD)
- Quản lý Giáo viên (CRUD)
- Quản lý Học phí (CRUD + theo dõi đã thu/chưa thu/công nợ)
- Dashboard tổng quan:
  - Tổng học sinh/lớp học/giáo viên
  - Doanh thu tháng hiện tại
  - Biểu đồ doanh thu theo tháng
  - Danh sách học phí chưa thanh toán
- Module báo cáo:
  - Báo cáo doanh thu theo tháng/quý/năm
  - Báo cáo học phí theo trạng thái
  - Báo cáo học sinh theo trạng thái
  - Báo cáo hiệu suất giáo viên
- Seed dữ liệu mẫu để test nhanh
- Giao diện responsive với Bootstrap

---

## 1) Yêu cầu môi trường

- PHP >= 8.1
- Composer
- Node.js >= 18, npm
- MySQL >= 8.0 (hoặc MariaDB tương thích)

---

## 2) Cài đặt project

```bash
cd /home/ubuntu/supperkids_crm
composer install
npm install
npm run build
```

Copy biến môi trường:

```bash
cp .env.example .env
php artisan key:generate
```

Cấu hình DB trong `.env` (MySQL):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=supperkids
DB_USERNAME=root
DB_PASSWORD=your_password
```

Tạo database `supperkids`, sau đó chạy migrate + seed:

```bash
php artisan migrate --seed
```

Chạy ứng dụng:

```bash
php artisan serve
```

Mở: `http://127.0.0.1:8000`

> Ghi chú: Tài khoản seed mặc định:
>
> - Email: `admin@supperkids.vn`
> - Password: `password123`

---

## 3) Chạy test

Có thể chạy test với SQLite local:

```bash
touch database/database.sqlite
DB_CONNECTION=sqlite DB_DATABASE=/absolute/path/to/supperkids_crm/database/database.sqlite php artisan migrate:fresh --seed
DB_CONNECTION=sqlite DB_DATABASE=/absolute/path/to/supperkids_crm/database/database.sqlite php artisan test
```

---

## 4) Cấu trúc module chính

- `app/Models`: `Teacher`, `Student`, `SchoolClass`, `Payment`
- `app/Http/Controllers`:
  - `DashboardController`
  - `SchoolClassController`
  - `StudentController`
  - `TeacherController`
  - `PaymentController`
  - `ReportController`
- `resources/views`:
  - `dashboard/*`
  - `classes/*`
  - `students/*`
  - `teachers/*`
  - `payments/*`
  - `reports/*`

---

## 5) Lệnh hữu ích

```bash
php artisan route:list
php artisan migrate:fresh --seed
php artisan optimize:clear
```

---

## 6) GitHub

Repository mục tiêu: **supperkids**

Sau khi có quyền GitHub phù hợp:

```bash
git init
git add .
git commit -m "feat: initial supperkids CRM system"
git branch -M main
git remote add origin <github_repo_url>
git push -u origin main
```
