# 🛠️ Utilities & Helper Scripts

File-file utility dan maintenance scripts untuk development & production.

## 📁 Files

### 🔄 Session Management
- **cleanup_sessions.php** - Cleanup expired sessions dari database
  - Run manual: `php utilities/cleanup_sessions.php`
  - Setup cron: `0 */1 * * * php /path/to/utilities/cleanup_sessions.php`
  - Fungsi: Hapus sessions > 24 jam, hapus expired tokens

### 🔐 User Management
- **update_password.php** - Update password user secara manual
  - Gunakan jika lupa password admin
  - Edit file → set email & password baru → run sekali
  - Hapus setelah digunakan (security)

### 💰 Payroll Maintenance
- **update_payroll_structure.php** - Update struktur payroll
  - Migrasi struktur lama ke baru
  - Hanya sekali pakai (maintenance)
  - Sudah tidak diperlukan lagi

## 🚀 Cara Menggunakan

### 1. Session Cleanup (Recommended Cron Job)

**Manual Run:**
```bash
php utilities/cleanup_sessions.php
```

**Output:**
```
[2026-02-04 10:00:00] Cleaned 45 expired sessions
[2026-02-04 10:00:00] Cleaned 12 expired remember tokens
```

**Setup Cron Job (Linux/Mac):**
```bash
# Edit crontab
crontab -e

# Add line (run every hour)
0 * * * * php /home/acas9288/public_html/utilities/cleanup_sessions.php >> /path/logs/cleanup.log 2>&1
```

**Setup Task Scheduler (Windows):**
1. Open Task Scheduler
2. Create Basic Task → "Bisnisku Session Cleanup"
3. Trigger: Daily, repeat every 1 hour
4. Action: Start program
   - Program: `C:\xampp\php\php.exe`
   - Arguments: `C:\xampp\htdocs\bisnisku-web-app\utilities\cleanup_sessions.php`

### 2. Update Password

**Cara Pakai:**
```php
// Edit file update_password.php
$email = 'admin@bisnisku.com';  // Email user
$newPassword = 'newpassword123'; // Password baru

// Run sekali
php utilities/update_password.php

// Hapus file setelah selesai (security!)
```

### 3. Payroll Structure Update

**Sudah tidak diperlukan** - Hanya untuk maintenance history.

## ⚠️ Security Notes

### Development:
- ✅ File utilities boleh ada
- ✅ Gunakan untuk maintenance
- ✅ Test dengan aman

### Production (Hosting):
- ❌ **Hapus file ini di production!**
- ❌ Jangan upload ke public_html
- ✅ Kecuali: cleanup_sessions.php (untuk cron)
- ✅ Set permissions 600 untuk file PHP

## 📋 Production Checklist

Sebelum upload ke hosting:
- [ ] Upload hanya cleanup_sessions.php
- [ ] Hapus update_password.php (security risk!)
- [ ] Hapus update_payroll_structure.php (tidak perlu)
- [ ] Set cleanup_sessions.php permission 600
- [ ] Setup cron job di cPanel

## 🔒 Protect Files

Via .htaccess di utilities/:
```apache
# Deny access to all files
<Files "*">
    Order allow,deny
    Deny from all
</Files>

# Allow only cron job access
<Files "cleanup_sessions.php">
    Allow from 127.0.0.1
</Files>
```

## 📞 Support

Untuk pertanyaan tentang utilities, lihat dokumentasi:
- Session: `dokumentasi/SESSION_DATABASE_INTEGRATION.md`
- Deployment: `dokumentasi/HOSTING_RUMAHWEB_GUIDE.md`
