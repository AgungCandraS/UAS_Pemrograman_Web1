# 🏪 Bisnisku Web Application

Modern Business Management System berbasis PHP Native dengan MySQL Database.

## 📋 Daftar Isi
- [Fitur Utama](#fitur-utama)
- [Teknologi](#teknologi)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Struktur Project](#struktur-project)
- [Modul & Fitur](#modul--fitur)
- [Screenshot](#screenshot)
- [API Documentation](#api-documentation)
- [Troubleshooting](#troubleshooting)

---

## ✨ Fitur Utama

### 1. **Dashboard Analytics**
- Real-time statistics & metrics
- Interactive charts (Chart.js & ApexCharts)
- Monthly sales trends
- Low stock alerts

### 2. **Inventory Management**
- Product CRUD operations
- Category management
- Stock tracking & movements
- Low stock notifications
- SKU generator
- Export to PDF/Excel

### 3. **Finance Module**
- Income & expense tracking
- Profit/Loss reports
- Transaction management
- Monthly financial reports
- Visual charts & analytics
- Export financial reports

### 4. **HR Management**
- Employee management
- Attendance tracking
- Payroll system
- Department management
- Employee performance

### 5. **Orders Management**
- Order creation & tracking
- Customer management
- Order status workflow
- Invoice generation
- Payment tracking

### 6. **Export Reports** 📊
- PDF reports (TCPDF)
- Excel exports (PhpSpreadsheet)
- Custom date ranges
- Multiple format support
- **Inventory Export** - Excel & PDF dengan search filter
- **Finance Export** - Excel & PDF dengan date range & type filter
- **Orders Export** - Excel & PDF dengan status filter
- **HR Export** - Excel & PDF untuk data karyawan

### 7. **Footer Copyright**
- Footer sesuai ketentuan UAS: `@Copyright by NPM_NAMA_KELAS_UASWEB1`
- Muncul di semua halaman (Dashboard & Guest pages)

---

## 🛠 Teknologi

- **Backend**: PHP 8.0+ (Native)
- **Database**: MySQL 8.0+
- **Frontend**: Tailwind CSS 3.x
- **Charts**: Chart.js, ApexCharts
- **Icons**: Font Awesome 6
- **Architecture**: MVC Pattern

---

## 📋 Persyaratan Sistem

- PHP >= 8.0
- MySQL >= 8.0
- Apache/Nginx Web Server
- Composer (optional, for libraries)
- Git

**PHP Extensions Required:**
- PDO
- pdo_mysql
- mbstring
- json
- fileinfo

---

## 🚀 Instalasi

### 1. Clone/Download Project

```bash
cd "D:\Semester 5\Project Pemrograman mobile 2\Project Flutter"
# Project sudah ada di folder bisnisku-web-app
```

### 2. Setup Database

```bash
# Buat database MySQL
mysql -u root -p

# Di MySQL console:
CREATE DATABASE bisnisku_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# Import schema
mysql -u root -p bisnisku_db < database.sql
```

### 3. Konfigurasi Environment

Edit file `config/env.php`:

```php
// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'bisnisku_db');
define('DB_USER', 'root');        // Sesuaikan
define('DB_PASS', '');             // Sesuaikan
define('DB_CHARSET', 'utf8mb4');

// Application URL
define('APP_URL', 'http://localhost/bisnisku-web-app/public');
```

### 4. Setup Web Server

#### **Option A: Apache (XAMPP/WAMP)**

1. Copy project ke folder `htdocs` atau buat virtual host
2. Akses: `http://localhost/bisnisku-web-app/public`

**Virtual Host Setup (Recommended):**

Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    ServerName bisnisku.local
    DocumentRoot "D:/Semester 5/Project Pemrograman mobile 2/Project Flutter/bisnisku-web-app/public"
    <Directory "D:/Semester 5/Project Pemrograman mobile 2/Project Flutter/bisnisku-web-app/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Edit `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1  bisnisku.local
```

Restart Apache, akses: `http://bisnisku.local`

#### **Option B: PHP Built-in Server (Development)**

```bash
cd public
php -S localhost:8000
```

Akses: `http://localhost:8000`

### 5. Install Dependencies (Optional)

Untuk fitur export PDF/Excel, install via Composer:

```bash
cd bisnisku-web-app
composer require tecnickcom/tcpdf
composer require phpoffice/phpspreadsheet
```

---

## ⚙️ Konfigurasi

### Database Connection

File: `config/env.php`

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'bisnisku_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### Upload Settings

```php
define('MAX_UPLOAD_SIZE', 5242880);  // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf']);
```

### Session Settings

```php
define('SESSION_LIFETIME', 7200);  // 2 hours
```

---

## 📁 Struktur Project

```
bisnisku-web-app/
├── app/
│   ├── controllers/         # Controller files
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── InventoryController.php
│   │   ├── FinanceController.php
│   │   ├── HRController.php
│   │   ├── OrderController.php
│   │   ├── AIController.php
│   │   └── ProfileController.php
│   ├── models/              # Model files
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Transaction.php
│   │   ├── Employee.php
│   │   └── Order.php
│   ├── views/               # View files
│   │   ├── layouts/
│   │   │   ├── base.php
│   │   │   └── app.php
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   └── register.php
│   │   ├── dashboard/
│   │   ├── inventory/
│   │   ├── finance/
│   │   ├── hr/
│   │   ├── orders/
│   │   └── ai/
│   └── middleware/
│       └── AuthMiddleware.php
├── assets/
│   ├── css/                 # Custom CSS
│   ├── js/                  # Custom JavaScript
│   └── images/              # Images
├── config/
│   └── env.php              # Environment config
├── core/
│   ├── Router.php           # Routing system
│   ├── Database.php         # Database handler
│   └── helpers.php          # Helper functions
├── public/                  # Public directory (Document Root)
│   ├── index.php            # Entry point
│   └── .htaccess            # Apache rewrite rules
├── storage/
│   ├── uploads/             # User uploads
│   └── exports/             # Generated reports
├── database.sql             # Database schema
├── routes.php               # Route definitions
├── composer.json            # Composer dependencies
└── README.md                # Documentation
```

---

## 🎯 Modul & Fitur

### 1. Authentication Module

**Routes:**
- `GET /login` - Login page
- `POST /login` - Login process
- `GET /register` - Register page
- `POST /register` - Register process
- `GET /logout` - Logout

**Default Login:**
- Email: `admin@bisnisku.com`
- Password: `admin123`

### 2. Dashboard Module

**Features:**
- Total revenue statistics
- Orders count
- Products inventory count
- Employee count
- Monthly sales chart
- Recent orders list
- Low stock alerts

**Routes:**
- `GET /dashboard` - Main dashboard
- `GET /dashboard/stats` - Get statistics (AJAX)

### 3. Inventory Module

**Features:**
- Product list with pagination
- Add new product
- Edit product
- Delete product
- Category management
- Stock movement tracking
- Low stock notifications
- Export to PDF/Excel

**Routes:**
- `GET /inventory` - Product list
- `GET /inventory/create` - Add product form
- `POST /inventory/store` - Save product
- `GET /inventory/edit/{id}` - Edit form
- `POST /inventory/update/{id}` - Update product
- `POST /inventory/delete/{id}` - Delete product
- `GET /inventory/export` - Export report

### 4. Finance Module

**Features:**
- Income tracking
- Expense tracking
- Profit/Loss calculation
- Monthly reports
- Transaction categories
- Payment methods
- Visual charts
- Export reports

**Routes:**
- `GET /finance` - Finance dashboard
- `GET /finance/income` - Income list
- `GET /finance/expense` - Expense list
- `POST /finance/transaction/store` - Add transaction
- `POST /finance/transaction/delete/{id}` - Delete
- `GET /finance/export` - Export report

### 5. HR Module

**Features:**
- Employee management
- Attendance system
- Payroll calculation
- Department management
- Employee documents
- Performance tracking

**Routes:**
- `GET /hr` - HR dashboard
- `GET /hr/employees` - Employee list
- `GET /hr/employee/create` - Add employee
- `POST /hr/employee/store` - Save employee
- `GET /hr/attendance` - Attendance page
- `POST /hr/attendance/record` - Record attendance
- `GET /hr/payroll` - Payroll page
- `GET /hr/export` - Export report

### 6. Orders Module

**Features:**
- Create new order
- Order tracking
- Customer management
- Order status workflow
- Payment tracking
- Invoice generation
- Order history

**Routes:**
- `GET /orders` - Order list
- `GET /orders/create` - Create order
- `POST /orders/store` - Save order
- `GET /orders/detail/{id}` - Order detail
- `POST /orders/update-status/{id}` - Update status
- `GET /orders/export` - Export report

### 7. AI Assistant Module

**Features:**
- Business insights chatbot
- Sales predictions
- Inventory recommendations
- Financial analysis
- Automated reports

**Routes:**
- `GET /ai-assistant` - AI chat interface
- `POST /ai-assistant/chat` - Send message
- `GET /ai-assistant/insights` - Get insights
- `GET /ai-assistant/recommendations` - Get recommendations

---

## 🔧 Helper Functions

File: `core/helpers.php`

```php
// Redirect
redirect(base_url('dashboard'));

// Flash messages
flash('success', 'Data berhasil disimpan');
flash('error', 'Terjadi kesalahan');

// Get POST data
$name = post('name');

// Check authentication
if (is_authenticated()) { }

// Format currency
echo format_currency(150000); // Rp 150.000

// Format date
echo format_date('2024-01-01'); // 01 Jan 2024

// Upload file
$filename = upload_file($_FILES['image']);

// JSON response
json_response(['status' => 'success', 'data' => $data]);
```

---

## 📊 Database Schema

### Main Tables:

1. **users** - User accounts
2. **categories** - Product categories
3. **products** - Inventory products
4. **stock_movements** - Stock history
5. **customers** - Customer data
6. **orders** - Order headers
7. **order_items** - Order details
8. **employees** - Employee data
9. **attendance** - Attendance records
10. **payroll** - Salary records
11. **transactions** - Financial transactions
12. **ai_conversations** - AI chat history
13. **settings** - App settings

### Views:

- `daily_sales` - Daily sales summary
- `product_stock_status` - Product stock status
- `monthly_finance` - Monthly financial summary

### Stored Procedures:

- `generate_order_number()` - Auto generate order number
- `calculate_monthly_salary()` - Calculate employee salary

---

## 📸 Screenshot

*(Screenshots would go here in actual deployment)*

---

## 🔌 API Documentation

### Authentication API

**Login**
```http
POST /login
Content-Type: application/x-www-form-urlencoded

email=admin@bisnisku.com
password=admin123
```

### Dashboard API

**Get Statistics**
```http
GET /dashboard/stats

Response:
{
  "revenue": 10000000,
  "orders": 150,
  "products": 89,
  "employees": 12
}
```

---

## 🐛 Troubleshooting

### Error: Database Connection Failed

**Solution:**
1. Check MySQL service is running
2. Verify database credentials in `config/env.php`
3. Ensure database `bisnisku_db` exists

### Error: 404 Not Found

**Solution:**
1. Check `.htaccess` file exists in `public/` folder
2. Enable Apache `mod_rewrite` module
3. Set `AllowOverride All` in Apache config

### Error: Permission Denied (Uploads)

**Solution:**
```bash
# Windows
icacls storage\uploads /grant Users:F

# Linux/Mac
chmod -R 755 storage/
```

### PHP Error: Class not found

**Solution:**
1. Check autoload in `public/index.php`
2. Verify file naming matches class name
3. Clear PHP cache if using opcache

---

## 👨‍💻 Development

### Adding New Module

1. Create Controller in `app/controllers/`
2. Create Model in `app/models/`
3. Create Views in `app/views/`
4. Add routes in `routes.php`

**Example:**

```php
// Controller: app/controllers/ProductController.php
class ProductController {
    public function index() {
        require_auth();
        // Your code
    }
}

// Route: routes.php
$router->get('/products', 'ProductController@index');
```

---

## 📝 Export Libraries

### Install Export Libraries

```bash
composer require tecnickcom/tcpdf
composer require phpoffice/phpspreadsheet
```

### PDF Export Example

```php
require_once 'vendor/autoload.php';

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->Write(0, 'Hello PDF', '', 0, 'L', true, 0, false, false, 0);
$pdf->Output('report.pdf', 'I');
```

### Excel Export Example

```php
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello Excel');

$writer = new Xlsx($spreadsheet);
$writer->save('report.xlsx');
```

---

## 🔒 Security Features

- Password hashing with bcrypt
- CSRF token protection
- SQL injection prevention (PDO prepared statements)
- XSS protection (sanitize inputs)
- Session security
- File upload validation

---

## 📈 Performance Tips

1. Enable PHP opcache
2. Use database indexes
3. Implement caching (Redis/Memcached)
4. Optimize images
5. Minify CSS/JS in production
6. Use CDN for libraries

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📄 License

This project is open source and available under the MIT License.

---

## 👤 Author

**Bisnisku Development Team**

- Website: [bisnisku.com](http://bisnisku.com)
- Email: info@bisnisku.com

---

## 🎯 Roadmap

- [ ] Multi-language support
- [ ] Mobile app integration (API)
- [ ] Advanced analytics & reporting
- [ ] WhatsApp integration
- [ ] Email notifications
- [ ] Barcode scanner
- [ ] POS system
- [ ] Multi-branch support

---

## 🙏 Acknowledgments

- Tailwind CSS
- Font Awesome
- Chart.js
- ApexCharts
- PHP Community

---

**Happy Coding! 🚀**
