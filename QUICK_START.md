# 🚀 Quick Start Guide - Bisnisku Web App

Panduan cepat untuk menjalankan project Bisnisku Web Application dalam 5 menit!

---

## ⚡ Instalasi Cepat

### 1. Persyaratan
- ✅ XAMPP/WAMP/LAMP (dengan PHP 8.0+)
- ✅ MySQL 8.0+
- ✅ Browser modern

### 2. Setup Database (2 menit)

**Langkah 1:** Buka MySQL
```bash
# Windows - Buka XAMPP Control Panel, start Apache & MySQL
# Atau via CMD:
mysql -u root -p
```

**Langkah 2:** Import Database
```sql
-- Di MySQL console:
CREATE DATABASE bisnisku_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

-- Import via CMD (ganti path sesuai lokasi):
mysql -u root -p bisnisku_db < "D:\Semester 5\Project Pemrograman mobile 2\Project Flutter\bisnisku-web-app\database.sql"
```

### 3. Konfigurasi (1 menit)

Edit file: `config/env.php`

```php
// Sesuaikan credentials MySQL Anda
define('DB_HOST', 'localhost');
define('DB_NAME', 'bisnisku_db');
define('DB_USER', 'root');          // Username MySQL
define('DB_PASS', '');               // Password MySQL (kosongkan jika tidak ada)

// Sesuaikan URL aplikasi
define('APP_URL', 'http://localhost/bisnisku-web-app/public');
```

### 4. Jalankan Aplikasi

**Option A: Via XAMPP (Recommended)**
1. Copy folder `bisnisku-web-app` ke `C:\xampp\htdocs\`
2. Buka browser: `http://localhost/bisnisku-web-app/public`

**Option B: Via PHP Built-in Server**
```bash
cd "D:\Semester 5\Project Pemrograman mobile 2\Project Flutter\bisnisku-web-app\public"
php -S localhost:8000
```
Buka browser: `http://localhost:8000`

---

## 🔐 Login Pertama Kali

**Akun Admin Default:**
- **Email:** admin@bisnisku.com
- **Password:** admin123

⚠️ **Penting:** Segera ganti password setelah login pertama!

---

## 🎯 Setelah Login

Anda akan melihat **Dashboard** dengan fitur lengkap:

### ✨ Modul yang Tersedia:

1. **📊 Dashboard**
   - Lihat statistik bisnis real-time
   - Chart penjualan 6 bulan terakhir
   - Alert stok menipis

2. **📦 Inventory**
   - Tambah/Edit/Hapus produk
   - Kelola kategori
   - Monitor stok
   - Export laporan

3. **💰 Keuangan**
   - Catat pemasukan/pengeluaran
   - Laporan profit/loss
   - Visualisasi keuangan
   - Export laporan Excel/PDF

4. **👥 HR Management**
   - Data karyawan
   - Absensi
   - Payroll (coming soon)

5. **🛒 Pesanan**
   - Buat order baru
   - Track status pesanan
   - Cetak invoice

6. **🤖 AI Assistant**
   - Chat dengan AI
   - Business insights
   - Rekomendasi otomatis

---

## 📝 Contoh Penggunaan Cepat

### Tambah Produk Baru
1. Klik **Inventory** di sidebar
2. Klik tombol **+ Tambah Produk**
3. Isi form:
   - SKU: AUTO-001
   - Nama: Laptop ASUS
   - Kategori: Elektronik
   - Harga: 8.500.000
   - Stok: 10
4. Klik **Simpan**

### Buat Pesanan
1. Klik **Pesanan** di sidebar
2. Klik **Buat Pesanan Baru**
3. Isi data customer
4. Tambah produk ke keranjang
5. Pilih metode pembayaran
6. Klik **Proses Pesanan**

### Cek Laporan Keuangan
1. Klik **Keuangan** di sidebar
2. Pilih range tanggal
3. Lihat summary & chart
4. Klik **Export** untuk download

---

## 🔧 Troubleshooting

### ❌ Error: "Database connection failed"
**Solusi:**
1. Pastikan MySQL service running
2. Cek credentials di `config/env.php`
3. Pastikan database `bisnisku_db` sudah dibuat

### ❌ Error: "404 Page Not Found"
**Solusi:**
1. Pastikan akses via folder `public/`
2. Enable `mod_rewrite` di Apache:
```bash
# Edit httpd.conf, uncomment:
LoadModule rewrite_module modules/mod_rewrite.so
```
3. Restart Apache

### ❌ Error: "Permission denied" saat upload
**Solusi (Windows):**
```cmd
icacls "storage\uploads" /grant Users:F /T
icacls "storage\exports" /grant Users:F /T
```

### ❌ Chart tidak muncul
**Solusi:**
- Pastikan internet aktif (Chart.js dari CDN)
- Clear browser cache (Ctrl + Shift + Delete)

---

## 🎨 Kustomisasi

### Ubah Logo & Warna
Edit `app/views/layouts/app.php`:
```php
// Ganti warna gradient
.gradient-bg {
    background: linear-gradient(135deg, #YOUR_COLOR1, #YOUR_COLOR2);
}
```

### Tambah Menu Baru
Edit `app/views/layouts/app.php` (sidebar):
```php
<a href="<?= base_url('new-menu') ?>" class="sidebar-item ...">
    <i class="fas fa-icon"></i>
    <span>Menu Baru</span>
</a>
```

---

## 📦 Install Export Libraries (Optional)

Untuk fitur export PDF/Excel yang advanced:

```bash
cd bisnisku-web-app
composer install
```

Ini akan install:
- TCPDF (PDF generation)
- PhpSpreadsheet (Excel export)

---

## 🚀 Production Checklist

Sebelum deploy ke production server:

- [ ] Ganti password admin default
- [ ] Set `APP_DEBUG` = false di `config/env.php`
- [ ] Set `APP_ENV` = 'production'
- [ ] Backup database secara berkala
- [ ] Setup SSL certificate (HTTPS)
- [ ] Batasi akses folder `storage/` dari public
- [ ] Enable gzip compression
- [ ] Setup firewall rules

---

## 📚 Resources

- **Full Documentation:** Baca `README.md`
- **Database Schema:** Lihat `database.sql`
- **API Routes:** Lihat `routes.php`

---

## 💡 Tips & Tricks

### Shortcut Keyboard
- `Ctrl + K` - Global search (coming soon)
- `Ctrl + /` - Toggle sidebar (coming soon)

### Best Practices
1. **Backup rutin** - Export database setiap hari
2. **Update stok** - Selalu update setelah transaksi
3. **Cek laporan** - Review keuangan minimal seminggu sekali
4. **Gunakan AI** - Manfaatkan AI Assistant untuk insights

---

## 🆘 Butuh Bantuan?

**Ada masalah?**
1. Cek file `README.md` untuk dokumentasi lengkap
2. Review error di browser console (F12)
3. Cek PHP error log di `xampp/logs/`

**Contact:**
- Email: support@bisnisku.com
- GitHub Issues: [github.com/bisnisku/web-app/issues](https://github.com)

---

## 🎉 Selamat!

Aplikasi Bisnisku sudah siap digunakan! 🚀

**Next Steps:**
1. ✅ Ganti password default
2. ✅ Tambah data produk
3. ✅ Setup karyawan
4. ✅ Mulai catat transaksi
5. ✅ Monitor dashboard setiap hari

---

**Happy Business Management! 💼✨**
