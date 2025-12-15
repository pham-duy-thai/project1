# Hệ Thống Quản Lý Ký Túc Xá (Dormitory Management System)

![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

Hệ thống quản lý ký túc xá toàn diện được xây dựng bằng Laravel Framework, hỗ trợ quản lý phòng, sinh viên, hợp đồng, thanh toán, bảo trì và nhiều tính năng khác.

## 📋 Mục Lục

- [Tính Năng](#-tính-năng)
- [Yêu Cầu Hệ Thống](#-yêu-cầu-hệ-thống)
- [Cài Đặt](#-cài-đặt)
- [Cấu Trúc Database](#-cấu-trúc-database)
- [Hướng Dẫn Sử Dụng](#-hướng-dẫn-sử-dụng)
- [API Routes](#-api-routes)

## ✨ Tính Năng

### 🏢 Quản Lý Cơ Sở Vật Chất
- ✅ **Quản lý Ký túc xá**: Thêm, sửa, xóa các khu ký túc xá
- ✅ **Quản lý Tòa nhà**: Phân chia theo tòa nhà, tầng lầu
- ✅ **Quản lý Phòng**: 
  - Phân loại phòng theo số người (2, 4, 6, 8 người)
  - Trạng thái phòng (còn trống, đã đầy, bảo trì, đã đặt)
  - Quản lý tiện nghi trong phòng
  - Upload hình ảnh phòng

### 👥 Quản Lý Sinh Viên
- ✅ Hồ sơ sinh viên đầy đủ (CMND, địa chỉ, phụ huynh, khoa/ngành)
- ✅ Theo dõi trạng thái (đang ở, đã tốt nghiệp, đình chỉ)
- ✅ Xem lịch sử thuê phòng
- ✅ Dashboard cá nhân cho sinh viên

### 📝 Quản Lý Hợp Đồng
- ✅ Tạo hợp đồng thuê phòng
- ✅ Ký điện tử hợp đồng
- ✅ Gia hạn/Chấm dứt hợp đồng
- ✅ Theo dõi thời hạn hợp đồng
- ✅ Quản lý tiền cọc

### 💰 Quản Lý Tài Chính
- ✅ **Hóa đơn tự động**: 
  - Tiền thuê phòng
  - Tiền điện, nước
  - Phí dịch vụ
- ✅ **Thanh toán đa dạng**:
  - Tiền mặt
  - Chuyển khoản ngân hàng
  - VNPay, MoMo, ZaloPay
- ✅ In biên lai thanh toán
- ✅ Theo dõi công nợ
- ✅ Thống kê doanh thu

### 🔧 Quản Lý Bảo Trì
- ✅ Sinh viên gửi yêu cầu sửa chữa
- ✅ Phân loại theo mức độ ưu tiên
- ✅ Gán nhiệm vụ cho nhân viên
- ✅ Theo dõi tiến độ xử lý
- ✅ Tính toán chi phí sửa chữa

### ⚠️ Quản Lý Vi Phạm
- ✅ Ghi nhận vi phạm nội quy
- ✅ Phân loại mức độ nghiêm trọng
- ✅ Quản lý tiền phạt
- ✅ Lưu bằng chứng (hình ảnh, file)
- ✅ Theo dõi lịch sử vi phạm

### 📢 Thông Báo & Công Bố
- ✅ Đăng thông báo cho sinh viên
- ✅ Phân loại theo loại (chung, bảo trì, thanh toán, sự kiện)
- ✅ Đặt mức độ ưu tiên
- ✅ Gửi email tự động
- ✅ Lên lịch xuất bản

### 🔐 Phân Quyền & Bảo Mật
- ✅ **3 vai trò**:
  - Admin: Toàn quyền quản lý
  - Manager: Quản lý hàng ngày
  - Student: Xem và quản lý thông tin cá nhân
- ✅ Xác thực người dùng (Laravel Breeze)
- ✅ Bảo mật dữ liệu sinh viên

## 💻 Yêu Cầu Hệ Thống

- **PHP**: >= 8.2
- **Composer**: >= 2.0
- **Node.js**: >= 18.x
- **Database**: MySQL 8.0+
- **Web Server**: Apache/Nginx

## 🚀 Cài Đặt

### Bước 1: Clone & Setup

```bash
git clone https://github.com/your-repo/dormitory_laravel.git
cd dormitory_laravel
composer install
npm install
```

### Bước 2: Cấu Hình Environment

```bash
cp .env.example .env
php artisan key:generate
```

Cập nhật file `.env`:

```env
APP_NAME="Dormitory Management System"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dormitory_db
DB_USERNAME=root
DB_PASSWORD=

# VNPay Configuration (nếu sử dụng thanh toán online)
VNPAY_TMN_CODE=your_tmn_code
VNPAY_HASH_SECRET=your_hash_secret
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_RETURN_URL="${APP_URL}/payment/vnpay/callback"

# Dormitory Settings
DORMITORY_MAX_ROOM_CAPACITY=8
DORMITORY_MIN_CONTRACT_MONTHS=3
DORMITORY_MAX_CONTRACT_MONTHS=12
DORMITORY_PAYMENT_DUE_DAYS=7
```

### Bước 3: Chạy Migration & Seeder

```bash
php artisan migrate:fresh
php artisan db:seed
```

### Bước 4: Build Assets & Chạy Server

```bash
npm run dev

# Terminal mới
php artisan serve
```

Truy cập: `http://localhost:8000`

### Tài Khoản Mặc Định

- **Admin**: 
  - Email: `admin@dormitory.com`
  - Password: `password`
  
- **Manager**: 
  - Email: `manager@dormitory.com`
  - Password: `password`

## 📊 Cấu Trúc Database

### Core Tables

#### 1. `dormitories` - Ký Túc Xá
```sql
- id, name, code, address
- phone, email, description
- total_buildings, total_rooms, total_capacity
- is_active, timestamps
```

#### 2. `buildings` - Tòa Nhà
```sql
- id, dormitory_id
- name, code, floors, total_rooms
- gender (male/female/mixed)
- is_active, timestamps
```

#### 3. `room_types` - Loại Phòng
```sql
- id, name, code, capacity
- price_per_month, price_per_semester, price_per_year
- amenities (JSON), description
- is_active, timestamps
```

#### 4. `rooms` - Phòng
```sql
- id, building_id, room_type_id
- room_number, floor, capacity, current_occupancy
- status (available/occupied/maintenance/reserved)
- area, images (JSON), notes
- is_active, timestamps
```

#### 5. `students` - Sinh Viên
```sql
- id, user_id, student_code
- full_name, date_of_birth, gender
- phone, email, citizen_id, citizen_id_date, citizen_id_place
- permanent_address, current_address
- parent_name, parent_phone, parent_relationship
- faculty, major, class, academic_year
- status (active/inactive/graduated/suspended)
- timestamps
```

#### 6. `contracts` - Hợp Đồng
```sql
- id, contract_number, student_id, room_id
- start_date, end_date
- monthly_rent, deposit_amount, total_amount
- payment_cycle (monthly/semester/yearly)
- signed_at, signed_by_student, signed_by_manager
- status (pending/active/expired/terminated/cancelled)
- notes, timestamps
```

#### 7. `invoices` - Hóa Đơn
```sql
- id, invoice_number, contract_id, student_id
- invoice_type, billing_period_start/end
- room_price, electricity_usage, electricity_cost
- water_usage, water_cost, service_cost, other_costs
- total_amount, paid_amount, remaining_amount
- due_date, payment_status
- notes, timestamps
```

#### 8. `payments` - Thanh Toán
```sql
- id, payment_code, invoice_id, student_id
- amount, payment_method, payment_date
- transaction_id, vnpay_data (JSON)
- status (pending/completed/failed/cancelled/refunded)
- notes, timestamps
```

#### 9. `maintenance_requests` - Yêu Cầu Bảo Trì
```sql
- id, request_code, room_id, student_id
- title, description
- category (plumbing/electrical/furniture/air_conditioning/internet/other)
- priority (low/medium/high/urgent)
- status (pending/approved/in_progress/completed/rejected/cancelled)
- assigned_to, scheduled_date, completed_at
- cost, notes, timestamps
```

#### 10. `amenities` & `room_amenities` - Tiện Nghi
```sql
amenities: id, name, code, description, icon
room_amenities: room_id, amenity_id, quantity, condition, notes
```

#### 11. `violations` - Vi Phạm
```sql
- id, violation_code, student_id, room_id
- title, description
- severity (minor/moderate/serious/critical)
- violation_date, fine_amount
- fine_status (unpaid/paid/waived)
- reported_by, evidence (JSON)
- status (pending/confirmed/appealed/resolved)
- resolved_at, timestamps
```

#### 12. `announcements` - Thông Báo
```sql
- id, title, content
- type (general/maintenance/payment/event/emergency/rule)
- priority (low/normal/high/urgent)
- created_by, published_at, expires_at
- is_published, send_email, send_notification
- target_audience (JSON), timestamps
```

### Relationships

```
dormitories → buildings → rooms → contracts
students → contracts → invoices → payments
rooms → maintenance_requests
students → violations
users → announcements (created_by)
rooms → amenities (many-to-many)
```

## 📖 Hướng Dẫn Sử Dụng

### Dành Cho Admin/Manager

#### 1. Quản Lý Phòng
```
Dashboard → Rooms Management
- Thêm phòng mới
- Cập nhật trạng thái phòng
- Upload hình ảnh phòng
- Thêm tiện nghi
```

#### 2. Đăng Ký Sinh Viên Vào Phòng
```
Students → Add New Student
- Nhập thông tin sinh viên
- Chọn phòng còn trống
- Tạo hợp đồng
- Tính tiền đặt cọc
```

#### 3. Quản Lý Hóa Đơn
```
Invoices → Create Invoice
- Tự động tạo hóa đơn theo chu kỳ
- Nhập chỉ số điện/nước
- Tính tổng tiền
- Gửi thông báo cho sinh viên
```

#### 4. Xử Lý Thanh Toán
```
Payments → View Payments
- Xác nhận thanh toán tiền mặt
- Theo dõi thanh toán online
- In biên lai
```

#### 5. Xử Lý Bảo Trì
```
Maintenance → View Requests
- Xem yêu cầu mới
- Phân công nhân viên
- Cập nhật tiến độ
- Nhập chi phí
```

### Dành Cho Sinh Viên

#### 1. Xem Thông Tin Phòng
```
Dashboard → My Room
- Xem thông tin phòng hiện tại
- Danh sách bạn cùng phòng
- Tiện nghi trong phòng
```

#### 2. Thanh Toán Hóa Đơn
```
My Invoices → Unpaid Invoices
- Chọn hóa đơn cần thanh toán
- Chọn phương thức (VNPay/MoMo/ZaloPay)
- Thanh toán online
```

#### 3. Gửi Yêu Cầu Bảo Trì
```
Maintenance → Create Request
- Mô tả vấn đề
- Upload hình ảnh
- Chọn mức độ khẩn cấp
- Gửi yêu cầu
```

#### 4. Xem Thông Báo
```
Announcements
- Đọc thông báo mới
- Lọc theo loại thông báo
- Tìm kiếm thông báo cũ
```

## 🔌 API Routes

### Authentication
```php
POST   /login              // Đăng nhập
POST   /register           // Đăng ký
POST   /logout             // Đăng xuất
```

### Dormitories (Admin/Manager)
```php
GET    /dormitories        // Danh sách ký túc xá
POST   /dormitories        // Tạo mới
GET    /dormitories/{id}   // Chi tiết
PUT    /dormitories/{id}   // Cập nhật
DELETE /dormitories/{id}   // Xóa
```

### Rooms (Admin/Manager)
```php
GET    /rooms              // Danh sách phòng
POST   /rooms              // Tạo phòng mới
GET    /rooms/{id}         // Chi tiết phòng
PUT    /rooms/{id}         // Cập nhật phòng
GET    /rooms/available    // Phòng còn trống
```

### Students
```php
GET    /students           // Danh sách sinh viên (Admin/Manager)
POST   /students           // Thêm sinh viên (Admin/Manager)
GET    /students/{id}      // Thông tin sinh viên
PUT    /students/{id}      // Cập nhật (Admin/Manager/Own)
```

### Contracts
```php
GET    /contracts          // Danh sách hợp đồng
POST   /contracts          // Tạo hợp đồng (Admin/Manager)
GET    /contracts/{id}     // Chi tiết hợp đồng
PUT    /contracts/{id}     // Gia hạn/Chấm dứt (Admin/Manager)
POST   /contracts/{id}/sign // Ký hợp đồng
```

### Invoices
```php
GET    /invoices           // Danh sách hóa đơn
POST   /invoices           // Tạo hóa đơn (Admin/Manager)
GET    /invoices/{id}      // Chi tiết hóa đơn
PUT    /invoices/{id}      // Cập nhật hóa đơn (Admin/Manager)
GET    /invoices/my        // Hóa đơn của tôi (Student)
```

### Payments
```php
GET    /payments           // Danh sách thanh toán
POST   /payments/{invoice} // Thanh toán hóa đơn
GET    /payments/{id}      // Chi tiết thanh toán
GET    /payment/vnpay/callback // VNPay callback
```

### Maintenance Requests
```php
GET    /maintenance        // Danh sách yêu cầu
POST   /maintenance        // Tạo yêu cầu
GET    /maintenance/{id}   // Chi tiết
PUT    /maintenance/{id}   // Cập nhật (Admin/Manager)
DELETE /maintenance/{id}   // Xóa
```

### Announcements
```php
GET    /announcements      // Danh sách thông báo
POST   /announcements      // Tạo thông báo (Admin/Manager)
GET    /announcements/{id} // Chi tiết
PUT    /announcements/{id} // Cập nhật (Admin/Manager)
DELETE /announcements/{id} // Xóa (Admin/Manager)
```

## 🛠️ Công Nghệ Sử Dụng

- **Backend**: Laravel 12.0
- **Frontend**: Blade Templates, TailwindCSS 3.x, Alpine.js 3.x
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Breeze
- **Payment Gateway**: VNPay, MoMo, ZaloPay
- **Build Tool**: Vite 7.x

## 📝 Todo List

- [ ] Implement controller logic cho tất cả features
- [ ] Tạo views (Blade templates) cho UI
- [ ] Implement CheckRole middleware
- [ ] Tạo Gates & Policies cho authorization
- [ ] Tạo seeders cho dữ liệu mẫu
- [ ] Xóa các file CloudBox cũ
- [ ] Viết tests (Unit & Feature)
- [ ] Tối ưu hóa queries (eager loading)
- [ ] Thêm export Excel cho báo cáo
- [ ] Thêm API documentation (Swagger)

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
- [Cấu Hình](#-cấu-hình)
- [Sử Dụng](#-sử-dụng)
- [Cấu Trúc Thư Mục](#-cấu-trúc-thư-mục)
- [API Routes](#-api-routes)
- [Công Nghệ Sử Dụng](#-công-nghệ-sử-dụng)
- [Bảo Mật](#-bảo-mật)
- [Đóng Góp](#-đóng-góp)
- [Giấy Phép](#-giấy-phép)

## ✨ Tính Năng

### Quản Lý File & Thư Mục
- ✅ **Upload File**: Tải lên nhiều file cùng lúc với drag & drop
- ✅ **Upload Thư Mục**: Tải lên cả cấu trúc thư mục từ máy tính
- ✅ **Tạo Thư Mục**: Tổ chức file theo thư mục và thư mục con không giới hạn
- ✅ **Xem File**: Xem trước file trực tiếp trên trình duyệt (hình ảnh, PDF, văn bản)
- ✅ **Tải Xuống**: Download file hoặc toàn bộ thư mục
- ✅ **Sửa/Xóa**: Đổi tên, chỉnh sửa thông tin, xóa file và thư mục
- ✅ **Tìm Kiếm**: Tìm kiếm nhanh file và thư mục

### Chia Sẻ File
- 🔗 **Chia Sẻ Qua Email**: Chia sẻ file/thư mục cho người dùng khác qua địa chỉ email
- 🔗 **Link Công Khai**: Tạo link chia sẻ công khai với token bảo mật
- ⏰ **Hạn Chia Sẻ**: Đặt thời gian hết hạn cho link chia sẻ
- 🔐 **Quyền Truy Cập**: Kiểm soát quyền xem và tải xuống cho người nhận

### Quản Lý Nâng Cao
- ⭐ **Yêu Thích**: Đánh dấu file/thư mục quan trọng vào danh sách yêu thích
- 🕒 **Gần Đây**: Xem lại file đã truy cập gần đây
- 🗑️ **Thùng Rác**: Khôi phục hoặc xóa vĩnh viễn file/thư mục đã xóa
- 📊 **Dashboard**: Thống kê dung lượng, số lượng file, thư mục
- 🎨 **Màu Sắc Thư Mục**: Tùy chỉnh màu sắc cho từng thư mục để dễ phân biệt
- 📝 **Mô Tả**: Thêm mô tả chi tiết cho file và thư mục

### Xử Lý Xung Đột
- 🔄 **Phát Hiện Trùng Lặp**: Tự động kiểm tra file trùng lặp khi upload
- ⚡ **Xử Lý Thông Minh**: 
  - Replace: Thay thế file cũ
  - Merge: Giữ cả hai với tên tự động đánh số (1), (2), ...
  - Skip: Bỏ qua file trùng

### Bảo Mật & Quản Lý
- 🔒 **Xác Thực Người Dùng**: Hệ thống đăng nhập/đăng ký an toàn với Laravel Breeze
- 👤 **Quản Lý Tài Khoản**: Mỗi người dùng có không gian lưu trữ riêng
- 🔐 **Bảo Mật File**: File được lưu trữ với tên ngẫu nhiên, bảo vệ khỏi truy cập trái phép
- 🛡️ **Phân Quyền**: Kiểm soát quyền truy cập cho từng file/thư mục

## 💻 Yêu Cầu Hệ Thống

### Phần Mềm Yêu Cầu
- **PHP**: >= 8.2
- **Composer**: >= 2.0
- **Node.js**: >= 18.x
- **NPM** hoặc **Yarn**: Phiên bản mới nhất
- **Database**: MySQL 8.0+ hoặc PostgreSQL 13+ hoặc SQLite 3.x

### PHP Extensions
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML

### Khuyến Nghị
- **Web Server**: Apache 2.4+ hoặc Nginx 1.18+
- **RAM**: Tối thiểu 1GB (khuyến nghị 2GB+)
- **Disk Space**: Tối thiểu 500MB cho ứng dụng + dung lượng lưu trữ file

## 🚀 Cài Đặt

### Bước 1: Clone Repository

```bash
git clone https://github.com/pqchien1905/CloudBox.git
cd CloudBox
```

### Bước 2: Cài Đặt Dependencies

```bash
# Cài đặt PHP dependencies
composer install

# Cài đặt JavaScript dependencies
npm install
```

### Bước 3: Cấu Hình Môi Trường

```bash
# Sao chép file .env.example
cp .env.example .env

# Tạo application key
php artisan key:generate
```

### Bước 4: Cấu Hình Database

Mở file `.env` và cấu hình thông tin database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cloudbox
DB_USERNAME=root
DB_PASSWORD=
```

**Lưu ý**: Đảm bảo database đã được tạo trước khi chạy migration.

### Bước 5: Chạy Migration

```bash
# Tạo các bảng trong database
php artisan migrate

# (Tùy chọn) Seed dữ liệu mẫu
php artisan db:seed
```

### Bước 6: Tạo Symbolic Link cho Storage

```bash
php artisan storage:link
```

### Bước 7: Tạo Thư Mục Cache

```bash
# Windows (PowerShell)
New-Item -Path "storage/framework/views" -ItemType Directory -Force
New-Item -Path "storage/framework/cache" -ItemType Directory -Force
New-Item -Path "storage/framework/sessions" -ItemType Directory -Force

# Linux/Mac
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
```

### Bước 8: Build Assets

```bash
# Build cho production
npm run build

# Hoặc chạy development server
npm run dev
```

### Bước 9: Khởi Chạy Server

```bash
# Khởi động Laravel development server
php artisan serve

# Ứng dụng sẽ chạy tại: http://127.0.0.1:8000
```

### Bước 10: Truy Cập Ứng Dụng

Mở trình duyệt và truy cập:
```
http://127.0.0.1:8000
```

## ⚙️ Cấu Hình

### Cấu Hình Email (Chia Sẻ File)

Để sử dụng tính năng chia sẻ file qua email, cấu hình SMTP trong `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Cấu Hình File Upload

Chỉnh sửa `php.ini` để tăng giới hạn upload:

```ini
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
memory_limit = 256M
```

### Cấu Hình Storage

File được lưu trong thư mục `storage/app/public/uploads`. Đảm bảo thư mục có quyền ghi:

```bash
# Linux/Mac
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Windows: Không cần thiết
```

### Cấu Hình Queue (Tùy Chọn)

Để xử lý tác vụ nền (email, notification), cấu hình queue:

```env
QUEUE_CONNECTION=database
```

Chạy queue worker:

```bash
php artisan queue:work
```

## 📖 Sử Dụng

### Đăng Ký & Đăng Nhập

1. Truy cập trang chủ
2. Click "Sign Up" để tạo tài khoản mới
3. Hoặc "Sign In" nếu đã có tài khoản

### Upload File

**Cách 1: Drag & Drop**
1. Kéo file từ máy tính vào vùng upload
2. File sẽ tự động được tải lên

**Cách 2: Chọn File**
1. Click nút "Upload Files"
2. Chọn file từ máy tính
3. Click "Upload"

**Cách 3: Upload Thư Mục**
1. Click nút "Upload Folder"
2. Chọn thư mục từ máy tính
3. Toàn bộ cấu trúc thư mục sẽ được tải lên

### Tạo Thư Mục

1. Click nút "Create Folder"
2. Nhập tên thư mục
3. (Tùy chọn) Chọn màu sắc, thêm mô tả
4. Click "Create"

### Chia Sẻ File/Thư Mục

**Chia sẻ qua Email:**
1. Click nút "Share" trên file/thư mục
2. Nhập email người nhận
3. (Tùy chọn) Đặt thời gian hết hạn
4. Click "Share"

**Chia sẻ qua Link:**
1. File/thư mục được chia sẻ sẽ có link dạng: `/shared/{token}`
2. Copy link và gửi cho người cần chia sẻ
3. Người nhận có thể xem và tải xuống mà không cần đăng nhập

### Quản Lý File

- **Xem**: Click vào file để xem preview
- **Tải xuống**: Click nút Download
- **Đổi tên**: Click "Edit" → Nhập tên mới
- **Xóa**: Click "Delete" → File chuyển vào Thùng Rác
- **Yêu thích**: Click biểu tượng sao để đánh dấu
- **Khôi phục**: Vào Thùng Rác → Click "Restore"
- **Xóa vĩnh viễn**: Vào Thùng Rác → Click "Delete Forever"

### Tìm Kiếm

1. Sử dụng thanh tìm kiếm ở đầu trang
2. Nhập tên file/thư mục
3. Kết quả hiển thị ngay lập tức

## 📁 Cấu Trúc Thư Mục

```
cloudbox-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                 # Controllers xác thực
│   │   │   ├── DashboardController.php
│   │   │   ├── FileController.php    # Quản lý file listing
│   │   │   ├── FileUploadController.php  # Upload & operations
│   │   │   ├── FolderController.php  # Quản lý thư mục
│   │   │   ├── FileShareController.php   # Chia sẻ file
│   │   │   └── FolderShareController.php # Chia sẻ thư mục
│   │   └── Middleware/
│   └── Models/
│       ├── User.php
│       ├── File.php
│       ├── Folder.php
│       ├── FileShare.php
│       └── FolderShare.php
├── database/
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── public/
│   ├── index.php               # Entry point
│   └── storage/                # Symbolic link to storage/app/public
├── resources/
│   ├── views/
│   │   ├── pages/              # Blade templates
│   │   │   ├── dashboard.blade.php
│   │   │   ├── files.blade.php
│   │   │   ├── folders.blade.php
│   │   │   ├── folder-view.blade.php
│   │   │   ├── shared.blade.php
│   │   │   ├── recent.blade.php
│   │   │   ├── favorites.blade.php
│   │   │   ├── trash.blade.php
│   │   │   └── file-shared.blade.php
│   │   ├── partials/           # Partial views
│   │   │   ├── sidebar.blade.php
│   │   │   ├── upload-modal.blade.php
│   │   │   └── upload-folder-modal.blade.php
│   │   └── components/         # Blade components
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php                 # Web routes
│   └── console.php             # Console routes
├── storage/
│   ├── app/
│   │   └── public/
│   │       └── uploads/        # Uploaded files
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/              # Compiled views
│   └── logs/
├── tests/                      # Unit & Feature tests
├── .env                        # Environment configuration
├── composer.json               # PHP dependencies
├── package.json                # Node dependencies
└── README.md                   # This file
```

## 🔌 API Routes

### Authenticated Routes (Prefix: `/cloudbox`)

| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | `/` | Dashboard | Trang tổng quan |
| GET | `/files` | Files Index | Danh sách file |
| GET | `/folders` | Folders Index | Danh sách thư mục |
| GET | `/folders/{id}` | Folder View | Xem nội dung thư mục |
| GET | `/shared` | Shared Files | File được chia sẻ |
| GET | `/recent` | Recent Files | File truy cập gần đây |
| GET | `/favorites` | Favorites | File yêu thích |
| GET | `/trash` | Trash | Thùng rác |
| POST | `/files/upload` | Upload File | Tải lên file |
| POST | `/files/check-duplicates` | Check Duplicates | Kiểm tra trùng lặp |
| POST | `/files/bulk-delete` | Bulk Delete Files | Xóa nhiều file |
| GET | `/files/{id}/view` | View File | Xem file |
| GET | `/files/{id}/download` | Download File | Tải xuống file |
| PUT | `/files/{id}` | Update File | Cập nhật file |
| DELETE | `/files/{id}` | Delete File | Xóa file |
| POST | `/files/{id}/favorite` | Toggle Favorite | Đánh dấu yêu thích |
| POST | `/files/{id}/restore` | Restore File | Khôi phục file |
| DELETE | `/files/{id}/force` | Force Delete File | Xóa vĩnh viễn file |
| POST | `/files/{id}/share` | Share File | Chia sẻ file |
| POST | `/folders` | Create Folder | Tạo thư mục |
| PUT | `/folders/{id}` | Update Folder | Cập nhật thư mục |
| DELETE | `/folders/{id}` | Delete Folder | Xóa thư mục |
| POST | `/folders/bulk-delete` | Bulk Delete Folders | Xóa nhiều thư mục |
| POST | `/folders/{id}/restore` | Restore Folder | Khôi phục thư mục |
| DELETE | `/folders/{id}/force` | Force Delete Folder | Xóa vĩnh viễn thư mục |
| POST | `/folders/upload` | Upload Folder | Tải lên thư mục |
| POST | `/folders/check-duplicates` | Check Folder Duplicates | Kiểm tra thư mục trùng |
| POST | `/folders/{id}/share` | Share Folder | Chia sẻ thư mục |
| POST | `/trash/cleanup` | Cleanup Trash | Dọn sạch thùng rác |

### Public Routes

| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | `/shared/{token}` | Public Shared View | Xem file chia sẻ công khai |
| GET | `/shared/{token}/download` | Public Download | Tải xuống file chia sẻ |

## 🛠️ Công Nghệ Sử Dụng

### Backend
- **Laravel 12.0**: PHP Framework
- **PHP 8.2+**: Server-side language
- **MySQL/PostgreSQL/SQLite**: Database
- **Laravel Breeze**: Authentication scaffolding

### Frontend
- **Blade Templates**: Server-side templating
- **TailwindCSS 3.x**: Utility-first CSS framework
- **Alpine.js 3.x**: Lightweight JavaScript framework
- **Vite 7.x**: Frontend build tool
- **Axios**: HTTP client

### Dependencies
- **Laravel Tinker**: REPL for Laravel
- **Faker**: Generate fake data
- **PHPUnit**: PHP testing framework

## 🔒 Bảo Mật

### Best Practices Implemented

1. **Authentication**: Laravel Breeze với bcrypt password hashing
2. **CSRF Protection**: Token validation trên tất cả form
3. **SQL Injection**: Eloquent ORM và prepared statements
4. **XSS Protection**: Blade template escaping
5. **File Security**: 
   - Rename file với tên ngẫu nhiên
   - Validate file type và size
   - Lưu file ngoài public directory
6. **Share Token**: Random secure token cho link chia sẻ
7. **Authorization**: Middleware kiểm tra quyền truy cập

### Khuyến Nghị Bảo Mật

- ✅ Luôn cập nhật dependencies
- ✅ Sử dụng HTTPS trong production
- ✅ Đặt `APP_DEBUG=false` trong production
- ✅ Backup database thường xuyên
- ✅ Giới hạn file upload size
- ✅ Cấu hình rate limiting
- ✅ Sử dụng environment variables cho thông tin nhạy cảm

## 🧪 Testing

Chạy tests:

```bash
# Chạy tất cả tests
php artisan test

# Chạy test cụ thể
php artisan test --filter=FileUploadTest

# Test với coverage
php artisan test --coverage
```

## 🚀 Deployment

### Production Setup

1. **Set Environment**
```env
APP_ENV=production
APP_DEBUG=false
```

2. **Optimize**
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

3. **Set Permissions**
```bash
chmod -R 755 storage bootstrap/cache
```

4. **Setup Cron** (cho scheduled tasks)
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

5. **Setup Queue Worker** (production)
```bash
# Sử dụng supervisor hoặc systemd
php artisan queue:work --daemon
```

## 🤝 Đóng Góp

Chúng tôi hoan nghênh mọi đóng góp! Để đóng góp:

1. Fork repository
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Mở Pull Request

### Coding Standards

- Follow PSR-12 coding standards
- Viết unit tests cho features mới
- Cập nhật documentation khi cần
- Comment code khi cần thiết (tiếng Việt)

## 📝 Changelog

### Version 1.0.0 (2025-10-27)
- ✨ Initial release
- ✨ File upload/download
- ✨ Folder management
- ✨ File sharing
- ✨ Favorites & Recent
- ✨ Trash management
- ✨ User authentication

## 🐛 Bug Report

Nếu phát hiện lỗi, vui lòng:
1. Kiểm tra [Issues](https://github.com/pqchien1905/CloudBox/issues) hiện có
2. Tạo Issue mới với thông tin chi tiết:
   - Mô tả lỗi
   - Các bước tái hiện
   - Kết quả mong đợi vs thực tế
   - Screenshots (nếu có)
   - Môi trường (OS, PHP version, browser)

## 📞 Liên Hệ

- **Developer**: pqchien1905
- **Repository**: [https://github.com/pqchien1905/CloudBox](https://github.com/pqchien1905/CloudBox)
- **Issues**: [https://github.com/pqchien1905/CloudBox/issues](https://github.com/pqchien1905/CloudBox/issues)

## 📄 Giấy Phép

Project này được phân phối dưới giấy phép MIT License. Xem file [LICENSE](LICENSE) để biết thêm chi tiết.

---

**CloudBox** - Quản lý file trên cloud một cách dễ dàng và an toàn! 🚀

Made with ❤️ by pqchien1905
