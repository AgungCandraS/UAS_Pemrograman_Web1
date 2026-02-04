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
