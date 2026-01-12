# 📦 INSTALLATION GUIDE - Bisnisku Web App

Panduan instalasi lengkap untuk Bisnisku Web Application.

---

## 📋 Table of Contents
1. [System Requirements](#system-requirements)
2. [Installation Methods](#installation-methods)
3. [Database Setup](#database-setup)
4. [Configuration](#configuration)
5. [Web Server Setup](#web-server-setup)
6. [First Run](#first-run)
7. [Optional Features](#optional-features)
8. [Troubleshooting](#troubleshooting)

---

## 💻 System Requirements

### Minimum Requirements
- **OS:** Windows 10/11, Linux, macOS
- **PHP:** 8.0 or higher
- **MySQL:** 8.0 or higher
- **Web Server:** Apache 2.4+ atau Nginx
- **RAM:** 2GB minimum
- **Storage:** 500MB free space

### PHP Extensions Required
```
- pdo
- pdo_mysql
- mbstring
- json
- fileinfo
- gd (optional, for image processing)
- zip (optional, for backups)
```

### Check PHP Version & Extensions
```bash
php -v
php -m | grep -E "pdo|mysql|mbstring|json"
```

---

## 🚀 Installation Methods

### Method 1: XAMPP (Windows - Recommended)

#### Step 1: Install XAMPP
1. Download XAMPP from [apachefriends.org](https://www.apachefriends.org)
2. Install dengan PHP 8.0+
3. Start Apache & MySQL dari XAMPP Control Panel

#### Step 2: Copy Project
```bash
# Copy project ke htdocs
copy "D:\Semester 5\Project Pemrograman mobile 2\Project Flutter\bisnisku-web-app" "C:\xampp\htdocs\bisnisku-web-app"
```

#### Step 3: Setup Database
```bash
# Buka MySQL dari XAMPP Shell
mysql -u root -p

# Jalankan commands di database setup section
```

---

### Method 2: PHP Built-in Server (Development)

```bash
cd "D:\Semester 5\Project Pemrograman mobile 2\Project Flutter\bisnisku-web-app\public"
php -S localhost:8000
```

Access: `http://localhost:8000`

---

### Method 3: Docker (Advanced)

Create `docker-compose.yml`:

```yaml
version: '3.8'

services:
  web:
    image: php:8.1-apache
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www/html
    depends_on:
      - db
      
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: bisnisku_db
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

Run:
```bash
docker-compose up -d
```

---

## 🗄️ Database Setup

### Option A: Via phpMyAdmin (Easy)

1. Buka `http://localhost/phpmyadmin`
2. Login (user: root, password: kosong atau sesuai setup)
3. Klik "New" untuk create database
4. Nama database: `bisnisku_db`
5. Collation: `utf8mb4_unicode_ci`
6. Klik tab "Import"
7. Choose file: `bisnisku-web-app/database.sql`
8. Klik "Go"

### Option B: Via MySQL Command Line

```bash
# Login ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE bisnisku_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Exit
exit;

# Import SQL file
mysql -u root -p bisnisku_db < "D:\Semester 5\Project Pemrograman mobile 2\Project Flutter\bisnisku-web-app\database.sql"
```

### Option C: Via MySQL Workbench

1. Buka MySQL Workbench
2. Connect ke localhost
3. File > Run SQL Script
4. Select `database.sql`
5. Execute

### Verify Database Installation

```sql
-- Login ke MySQL
mysql -u root -p

-- Gunakan database
USE bisnisku_db;

-- Cek tables
SHOW TABLES;

-- Cek user default
SELECT * FROM users;
```

Expected output: 13 tables created, 1 admin user exists.

---

## ⚙️ Configuration

### 1. Environment Configuration

Edit: `config/env.php`

```php
<?php
// ============================================
// DATABASE CONFIGURATION
// ============================================
define('DB_HOST', 'localhost');           // MySQL host
define('DB_NAME', 'bisnisku_db');         // Database name
define('DB_USER', 'root');                 // MySQL username
define('DB_PASS', '');                     // MySQL password (jika ada)
define('DB_CHARSET', 'utf8mb4');

// ============================================
// APPLICATION CONFIGURATION
// ============================================
define('APP_NAME', 'Bisnisku');
define('APP_ENV', 'development');          // development atau production
define('APP_DEBUG', true);                 // false untuk production
define('APP_URL', 'http://localhost/bisnisku-web-app/public');  // Sesuaikan URL

// ============================================
// SESSION CONFIGURATION
// ============================================
define('SESSION_LIFETIME', 7200);          // 2 hours (dalam detik)

// ============================================
// UPLOAD CONFIGURATION
// ============================================
define('MAX_UPLOAD_SIZE', 5242880);        // 5MB dalam bytes
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx']);

// ============================================
// OTHER SETTINGS
// ============================================
define('ITEMS_PER_PAGE', 10);              // Pagination
date_default_timezone_set('Asia/Jakarta'); // Timezone
```

### 2. File Permissions (Linux/Mac)

```bash
chmod -R 755 bisnisku-web-app/
chmod -R 777 bisnisku-web-app/storage/
```

### 3. File Permissions (Windows)

```cmd
icacls "storage\uploads" /grant Users:F /T
icacls "storage\exports" /grant Users:F /T
```

---

## 🌐 Web Server Setup

### Apache Configuration

#### Option A: Basic Setup (htdocs)

1. Copy project ke `C:\xampp\htdocs\bisnisku-web-app`
2. Access via: `http://localhost/bisnisku-web-app/public`

#### Option B: Virtual Host (Recommended)

**Step 1:** Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

```apache
<VirtualHost *:80>
    ServerName bisnisku.local
    ServerAlias www.bisnisku.local
    DocumentRoot "D:/Semester 5/Project Pemrograman mobile 2/Project Flutter/bisnisku-web-app/public"
    
    <Directory "D:/Semester 5/Project Pemrograman mobile 2/Project Flutter/bisnisku-web-app/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/bisnisku-error.log"
    CustomLog "logs/bisnisku-access.log" common
</VirtualHost>
```

**Step 2:** Edit `C:\Windows\System32\drivers\etc\hosts` (Run as Administrator)

```
127.0.0.1    bisnisku.local
127.0.0.1    www.bisnisku.local
```

**Step 3:** Restart Apache

Access via: `http://bisnisku.local`

### Nginx Configuration

Create: `/etc/nginx/sites-available/bisnisku`

```nginx
server {
    listen 80;
    server_name bisnisku.local;
    root /path/to/bisnisku-web-app/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/bisnisku /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 🎯 First Run

### 1. Access Application

Open browser and navigate to:
- Basic: `http://localhost/bisnisku-web-app/public`
- Virtual Host: `http://bisnisku.local`
- PHP Server: `http://localhost:8000`

### 2. Login with Default Credentials

```
Email: admin@bisnisku.com
Password: admin123
```

### 3. First-Time Setup Checklist

After login, complete these steps:

- [ ] **Change Admin Password**
  - Go to Profile
  - Click "Change Password"
  - Use strong password

- [ ] **Update Company Settings**
  - Go to Settings
  - Update company name, email, phone
  - Set tax rate if applicable

- [ ] **Add Product Categories**
  - Go to Inventory
  - Add relevant categories for your business

- [ ] **Add First Product**
  - Go to Inventory > Add Product
  - Fill in product details

- [ ] **Add Employee Data**
  - Go to HR > Employees
  - Add employee records

- [ ] **Test All Features**
  - Create a test order
  - Record a transaction
  - Check dashboard analytics

---

## 🎨 Optional Features

### Install Export Libraries (PDF/Excel)

```bash
cd bisnisku-web-app

# Install Composer (if not installed)
# Download from: https://getcomposer.org/

# Install dependencies
composer install
```

This installs:
- `tecnickcom/tcpdf` - For PDF generation
- `phpoffice/phpspreadsheet` - For Excel exports

### Enable Advanced Features

Edit `config/env.php`:

```php
// Enable advanced logging
define('ENABLE_LOGGING', true);

// Enable email notifications
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your@email.com');
define('SMTP_PASS', 'your-password');
```

---

## 🐛 Troubleshooting

### Issue 1: "Database connection failed"

**Diagnosis:**
```bash
# Test MySQL connection
mysql -u root -p -e "SELECT 1"

# Check MySQL service
# Windows (XAMPP):
# Check XAMPP Control Panel, MySQL should be green

# Linux:
sudo systemctl status mysql
```

**Solutions:**
1. Verify MySQL is running
2. Check `config/env.php` credentials
3. Ensure database `bisnisku_db` exists
4. Test connection:
```php
php -r "new PDO('mysql:host=localhost;dbname=bisnisku_db', 'root', '');"
```

### Issue 2: "404 Not Found" on all pages

**Solutions:**

**Apache:**
1. Enable mod_rewrite:
```bash
# Edit httpd.conf
LoadModule rewrite_module modules/mod_rewrite.so
```

2. Ensure `.htaccess` exists in `public/` folder

3. Check AllowOverride:
```apache
<Directory />
    AllowOverride All
</Directory>
```

4. Restart Apache

### Issue 3: "Permission denied" errors

**Windows:**
```cmd
# Give full access to storage
icacls "storage" /grant Users:F /T
```

**Linux/Mac:**
```bash
sudo chmod -R 775 storage/
sudo chown -R www-data:www-data storage/
```

### Issue 4: "Class not found"

**Solutions:**
1. Check file naming (must match class name exactly)
2. Verify autoload in `public/index.php`
3. Clear PHP opcache:
```bash
service php8.1-fpm reload
```

### Issue 5: Charts not displaying

**Solutions:**
1. Check internet connection (CDN required for Chart.js)
2. Clear browser cache (Ctrl + Shift + Delete)
3. Check browser console for errors (F12)

### Issue 6: File uploads not working

**Solutions:**
1. Check PHP upload settings in `php.ini`:
```ini
file_uploads = On
upload_max_filesize = 10M
post_max_size = 10M
```

2. Create upload directory:
```bash
mkdir -p storage/uploads
chmod 777 storage/uploads
```

### Issue 7: Session expires too quickly

Edit `config/env.php`:
```php
define('SESSION_LIFETIME', 28800);  // 8 hours
```

---

## 🔒 Security Recommendations

### For Production Deployment

1. **Disable Debug Mode**
```php
define('APP_DEBUG', false);
define('APP_ENV', 'production');
```

2. **Use Strong Database Password**
```sql
ALTER USER 'root'@'localhost' IDENTIFIED BY 'StrongP@ssw0rd!';
```

3. **Change Default Admin Credentials**
- Login as admin
- Go to Profile
- Change password immediately

4. **Setup SSL/HTTPS**
- Use Let's Encrypt for free SSL
- Redirect HTTP to HTTPS

5. **Regular Backups**
```bash
# Backup database daily
mysqldump -u root -p bisnisku_db > backup_$(date +%Y%m%d).sql
```

6. **File Permissions**
```bash
# Restrict access
chmod 644 config/env.php
chmod 600 sensitive_files
```

---

## 📊 Performance Optimization

### Enable PHP OPcache

Edit `php.ini`:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
```

### MySQL Optimization

```sql
-- Add indexes for faster queries (already in schema)
-- Monitor slow queries
SET GLOBAL slow_query_log = 'ON';
```

### Apache Optimization

Enable compression in `.htaccess`:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css application/javascript
</IfModule>
```

---

## 📞 Support

### Get Help

1. **Documentation:** Read `README.md` for detailed info
2. **Quick Start:** See `QUICK_START.md` for fast setup
3. **Database:** Check `database.sql` for schema details

### Report Issues

- Check existing issues first
- Provide error messages
- Include PHP & MySQL versions
- Describe steps to reproduce

---

## ✅ Verification Checklist

After installation, verify:

- [ ] Can access login page
- [ ] Can login with default credentials
- [ ] Dashboard loads with charts
- [ ] Can create new product
- [ ] Can create new order
- [ ] Can record transaction
- [ ] Can view reports
- [ ] File upload works
- [ ] All menu items accessible
- [ ] No errors in browser console

---

## 🎉 Installation Complete!

Aplikasi siap digunakan! 

**Next Steps:**
1. Read `QUICK_START.md` for usage guide
2. Explore all features
3. Customize according to your needs
4. Start managing your business! 🚀

---

**Need help? Contact support@bisnisku.com**
