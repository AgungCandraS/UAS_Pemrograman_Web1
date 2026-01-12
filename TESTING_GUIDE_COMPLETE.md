# 🧪 TESTING GUIDE - Bisnisku Web App

## ✅ Quick Test Checklist

### 1. 🚀 Setup & Installation Test

#### Database Connection
```bash
# Test 1: Check database connection
✅ Login ke aplikasi
✅ Dashboard load tanpa error
✅ Data statistics muncul
```

#### File Permissions
```bash
# Test 2: Check file permissions
✅ Upload gambar produk berhasil (storage/uploads writable)
✅ Export file berhasil (storage/exports writable)
```

---

### 2. 🔐 Authentication Test

#### Login
```
URL: http://localhost/bisnisku-web-app/public/login
Email: admin@bisnisku.com
Password: admin123

Expected Result:
✅ Login berhasil
✅ Redirect ke dashboard
✅ User info muncul di topbar
✅ Flash message "Login berhasil"
```

#### Register
```
URL: http://localhost/bisnisku-web-app/public/register

Test Data:
- Full Name: Test User
- Email: test@example.com
- Password: test123
- Phone: 08123456789

Expected Result:
✅ Registration berhasil
✅ Redirect ke login
✅ Flash message "Registrasi berhasil"
```

#### Logout
```
Click: Logout button di sidebar

Expected Result:
✅ Session cleared
✅ Redirect ke login
✅ Flash message "Logout berhasil"
```

---

### 3. 📦 Inventory CRUD Test

#### Create Product
```
URL: http://localhost/bisnisku-web-app/public/inventory/create

Test Data:
- Kategori: Elektronik
- SKU: TEST-001
- Nama: Test Product
- Harga Modal: 100000
- Harga Jual: 150000
- Stok: 50
- Min Stock: 10
- Unit: pcs
- Status: active
- Image: upload test.jpg

Expected Result:
✅ Produk berhasil ditambahkan
✅ Flash message "Produk berhasil ditambahkan"
✅ Redirect ke inventory list
✅ Produk baru muncul di list
```

#### Read/List Products
```
URL: http://localhost/bisnisku-web-app/public/inventory

Test:
✅ Semua produk ditampilkan
✅ Search produk berfungsi
✅ Pagination muncul (jika > 10 items)
✅ Image thumbnail muncul
✅ Badge status muncul
✅ Stock indicator berfungsi (red jika low stock)
```

#### Update Product
```
URL: http://localhost/bisnisku-web-app/public/inventory/edit/1

Test:
✅ Form pre-filled dengan data existing
✅ Image saat ini ditampilkan
✅ Update nama produk
✅ Update harga
✅ Update stok
✅ Upload image baru
✅ Flash message "Produk berhasil diupdate"
✅ Redirect ke inventory list
```

#### Delete Product
```
Action: Click delete button pada produk

Test:
✅ Confirmation dialog muncul
✅ Click OK
✅ Flash message "Produk berhasil dihapus"
✅ Produk hilang dari list
```

---

### 4. 📤 Export Test (CRITICAL!)

#### Inventory Export - Excel
```
URL: http://localhost/bisnisku-web-app/public/inventory/export?format=excel

Expected Result:
✅ File downloaded: inventory_YYYYMMDD_HHMMSS.xlsx
✅ File bisa dibuka di Excel/LibreOffice
✅ Header kolom: SKU, Nama, Kategori, Harga, Stok, Status
✅ Data sesuai dengan database
✅ Columns auto-sized
```

#### Inventory Export - PDF
```
URL: http://localhost/bisnisku-web-app/public/inventory/export?format=pdf

Expected Result:
✅ File downloaded: inventory_YYYYMMDD_HHMMSS.pdf
✅ File bisa dibuka di PDF reader
✅ Title: "Laporan Inventory"
✅ Table dengan border
✅ Data complete & readable
```

#### Finance Export - Excel
```
URL: http://localhost/bisnisku-web-app/public/finance/export?format=excel

Test with filters:
?format=excel&date_from=2024-01-01&date_to=2024-12-31
?format=excel&type=income

Expected Result:
✅ File downloaded: finance_YYYYMMDD_HHMMSS.xlsx
✅ Data filtered by date range
✅ Header: Tanggal, Tipe, Kategori, Nominal, Metode, Deskripsi
```

#### Finance Export - PDF
```
URL: http://localhost/bisnisku-web-app/public/finance/export?format=pdf

Expected Result:
✅ File downloaded: finance_YYYYMMDD_HHMMSS.pdf
✅ Period displayed: "Periode: YYYY-MM-DD s.d. YYYY-MM-DD"
✅ Formatted currency (Rp X.XXX.XXX)
```

#### Orders Export
```
Excel: http://localhost/bisnisku-web-app/public/orders/export?format=excel
PDF: http://localhost/bisnisku-web-app/public/orders/export?format=pdf

Expected Result:
✅ Download berhasil
✅ Header: No Pesanan, Pelanggan, Total, Status Bayar, Status Pesanan
✅ Data complete
```

#### HR Export
```
Excel: http://localhost/bisnisku-web-app/public/hr/export?format=excel
PDF: http://localhost/bisnisku-web-app/public/hr/export?format=pdf

Expected Result:
✅ Download berhasil
✅ Header: ID, Nama, Email, Telepon, Jabatan, Departemen, Gaji, Status
✅ Salary formatted as currency
```

---

### 5. 🦶 Footer Test

#### Dashboard Pages
```
Test pada:
✅ Dashboard (/dashboard)
✅ Inventory (/inventory)
✅ Finance (/finance)
✅ HR (/hr)
✅ Orders (/orders)
✅ AI Assistant (/ai-assistant)
✅ Profile (/profile)
✅ Settings (/settings)

Expected Result:
✅ Footer muncul di bottom
✅ Text: "@Copyright by NPM_NAMA_KELAS_UASWEB1"
✅ Center aligned
✅ Subtle color (gray)
✅ Border top visible
```

#### Guest Pages
```
Test pada:
✅ Login page (/login)
✅ Register page (/register)

Expected Result:
✅ Footer muncul di bottom
✅ Text: "@Copyright by NPM_NAMA_KELAS_UASWEB1"
✅ Styling consistent dengan dashboard
```

---

### 6. 🎨 UI/UX Test

#### Responsive Design
```
Test pada:
✅ Desktop (1920x1080)
✅ Tablet (768x1024)
✅ Mobile (375x667)

Expected Result:
✅ Layout tidak pecah
✅ Sidebar collapsible (mobile)
✅ Table responsive (horizontal scroll atau card layout)
✅ Buttons stack vertical (mobile)
✅ Forms full width (mobile)
```

#### Animations & Transitions
```
Test:
✅ Fade-in effect pada page load
✅ Button hover effects
✅ Card hover effects
✅ Smooth transitions
✅ Loading states
```

#### Colors & Typography
```
✅ Primary color: Purple (#667eea)
✅ Font: Poppins, Inter
✅ Readable contrast
✅ Consistent spacing
✅ Icons dari Font Awesome
```

---

### 7. 🔍 Error Handling Test

#### Invalid Input
```
Test 1: Create product dengan field kosong
Expected: ✅ Form validation error

Test 2: Upload file > 5MB
Expected: ✅ Error message "File terlalu besar"

Test 3: Upload file bukan gambar
Expected: ✅ Error message "Format file tidak valid"
```

#### Access Control
```
Test: Access dashboard tanpa login
URL: http://localhost/bisnisku-web-app/public/dashboard

Expected Result:
✅ Redirect ke login
✅ Flash message "Please login to continue"
```

#### 404 Error
```
Test: Access invalid URL
URL: http://localhost/bisnisku-web-app/public/invalid-page

Expected Result:
✅ 404 page muncul
✅ Styled error page
✅ Link back to dashboard
```

---

### 8. 🔧 Performance Test

#### Page Load Speed
```
Test dengan browser DevTools (Network tab):
✅ Dashboard load < 2s
✅ Inventory list < 1s
✅ CSS/JS from CDN cached
✅ No console errors
```

#### Database Queries
```
Test:
✅ Inventory pagination efficient (LIMIT/OFFSET)
✅ Search menggunakan prepared statements
✅ No N+1 query problem
```

#### Export Performance
```
Test:
✅ Export 100 records < 3s
✅ Export 1000 records < 10s
✅ Memory usage stable
✅ No timeout errors
```

---

## 🐛 Common Issues & Solutions

### Issue 1: Export Error "Class not found"
**Error:** `Fatal error: Uncaught Error: Class '\TCPDF' not found`

**Solution:**
```bash
cd c:\xampp\htdocs\bisnisku-web-app
composer install
```

### Issue 2: Upload Gagal
**Error:** Failed to move uploaded file

**Solution:**
```bash
# Check folder permissions
chmod 755 storage/uploads
chmod 755 storage/exports

# Or via File Manager
# Right-click folder > Properties > Permissions > 755
```

### Issue 3: Footer Tidak Muncul
**Solution:**
- Clear browser cache (Ctrl + F5)
- Check if layout file updated
- Inspect element untuk debug CSS

### Issue 4: Database Connection Error
**Error:** SQLSTATE[HY000] [1045] Access denied

**Solution:**
```php
// Edit config/env.php
define('DB_USER', 'root');     // Your MySQL username
define('DB_PASS', '');         // Your MySQL password
define('DB_NAME', 'bisnisku_db');
```

### Issue 5: Blank Page
**Solution:**
```php
// Enable error reporting
// Edit public/index.php (temporary)
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

## ✅ Final Checklist (UAS Requirements)

| Ketentuan | Test | Status |
|-----------|------|--------|
| Backend & Frontend Terintegrasi | Load semua pages | ✅ |
| Dashboard sebagai pusat informasi | View dashboard | ✅ |
| Fitur register & login | Test auth | ✅ |
| Laporan PDF & Excel | Export all modules | ✅ |
| CRUD data | Test Inventory CRUD | ✅ |
| Session/Cookie | Login & remember me | ✅ |
| Footer copyright | Check all pages | ✅ |
| Aplikasi dihosting | Deploy & test online | ⏳ |
| Link git & E-learning | Add to README | ⏳ |
| Screenshot & video | Capture & document | ⏳ |

---

## 📊 Test Report Template

```
=== TEST REPORT ===
Date: _______________
Tester: ______________

1. Authentication: ✅ / ❌
   - Login: ___
   - Register: ___
   - Logout: ___

2. Inventory CRUD: ✅ / ❌
   - Create: ___
   - Read: ___
   - Update: ___
   - Delete: ___

3. Export: ✅ / ❌
   - Inventory Excel: ___
   - Inventory PDF: ___
   - Finance Excel: ___
   - Finance PDF: ___
   - Orders Excel: ___
   - Orders PDF: ___
   - HR Excel: ___
   - HR PDF: ___

4. Footer: ✅ / ❌
   - Dashboard pages: ___
   - Guest pages: ___

5. Responsive: ✅ / ❌
   - Desktop: ___
   - Tablet: ___
   - Mobile: ___

Overall Status: ✅ PASS / ❌ FAIL

Notes:
_______________________
_______________________
```

---

## 🚀 Next Steps

1. ✅ Complete all tests above
2. ⏳ Deploy to hosting (InfinityFree, 000webhost, atau VPS)
3. ⏳ Take screenshots of all features
4. ⏳ Record demo video (5-10 minutes)
5. ⏳ Update README.md dengan:
   - Live URL
   - GitHub repository link
   - E-learning submission link
   - Screenshots gallery
   - Video embed/link
6. ⏳ Ganti placeholder footer dengan data real:
   - NPM: _____________
   - NAMA: _____________
   - KELAS: _____________

---

**Happy Testing! 🎉**

Jika ada error atau pertanyaan, dokumentasikan dan cari solusi di:
- `TROUBLESHOOTING.md`
- `INSTALLATION.md`
- Stack Overflow
- PHP Documentation
