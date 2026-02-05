

````md
# 🏪 Bisnisku Web Application  
Modern Business Management System berbasis **PHP Native** dengan **MySQL Database**  
untuk membantu UMKM mengelola **inventori, penjualan, keuangan, dan pengguna** secara terintegrasi.

---

## 📋 Fitur Utama

- 🔐 **Autentikasi User**
  - Login & Logout berbasis session
  - Hak akses Admin & User
- 📊 **Dashboard Admin**
  - Ringkasan data produk & transaksi
- 📦 **Inventory Management**
  - CRUD Produk
  - Stok & harga tersimpan di database
- 🧾 **Orders / Transaksi**
  - Pencatatan transaksi penjualan
  - Riwayat transaksi
- 📤 **Export Laporan**
  - PDF
  - Excel (CSV)
- 🧪 **Session & Cookie Testing**
  - Cocok untuk shared hosting
- 🧾 **Footer sesuai ketentuan UAS**

---

## 🛠 Teknologi

- **Backend** : PHP Native (≥ 7.4)
- **Database** : MySQL / MariaDB
- **Frontend** : Bootstrap 5 + Custom CSS
- **Export** : PDF & CSV
- **Arsitektur** : MVC Sederhana

---

## 📋 Persyaratan Sistem

- PHP ≥ 7.4
- MySQL / MariaDB
- Apache / Nginx
- mod_rewrite aktif
- XAMPP / LAMP / Shared Hosting

---

## 🚀 Instalasi

### 1️⃣ Clone / Extract Project
```bash
# Extract ke
htdocs/bisnisku-web-app
````

### 2️⃣ Setup Database

Buat database:

```sql
CREATE DATABASE bisnisku;
```

Import database:

```bash
mysql -u root -p bisnisku < storage/sql/database.sql
```

---

### 3️⃣ Konfigurasi Database

Edit file:

```
config/env.php
```

Sesuaikan:

```php
DB_HOST=localhost
DB_NAME=bisnisku
DB_USER=root
DB_PASS=your_password
```

---

### 4️⃣ Jalankan Aplikasi

Akses melalui browser:

```
http://localhost/bisnisku-web-app/public/
```

---

## 🔑 Login Default

**Admin**

* Email : `admin@bisnisku.com`
* Password : `admin123`

---

## 🌐 Demo & Akses Online

* 🔗 **Hosting / Production**
  👉 [https://acas.my.id](https://acas.my.id)

* 🎥 **Video Demo Aplikasi**
  👉 [https://github.com/user-attachments/assets/67d2c738-a5a3-47e4-855d-c2c89a50e60b
)

---

## 🖼️ Screenshot Aplikasi

---

### 1 Halaman Login

Halaman autentikasi untuk user dan admin menggunakan email dan password.

![Login](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/edf2248b-ef3f-45e5-8f91-571c84a5640e" />
)

---

### 2 Halaman Register

Halaman register untuk Pendaftaran
![Login](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/4cd4c8da-60c9-4499-8f6a-786a57ac1722" />

)
---


### 3 Dashboard Admin

Dashboard utama admin yang menampilkan ringkasan data sistem.

![Dashboard Admin](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/1acd8360-683c-4de0-9ab2-accc93b4ce50" />
)

---

### 4 Data Produk / Inventori

Daftar produk yang tersimpan di database lengkap dengan harga dan stok.

![Inventori](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/c8a8278f-71d1-40f7-bec0-0ac87a9fed61" />
)

---

### 5 Tambah Produk

Form input untuk menambahkan produk baru ke sistem.

![Tambah Produk](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/5408e258-827c-473b-a4ac-704de5437114" />
)

---

### 6 Edit Produk

Admin dapat mengubah data produk yang sudah tersimpan.

![Edit Produk](s<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/7cc4279e-17ea-4282-a8ae-91ac1b791de5" />
)

---

### 7 Hapus Produk

Produk dapat dihapus dan langsung terhapus dari database.

![Hapus Produk](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/87c54699-edd5-4459-b2bc-5a60263e3381" />
)

---

### 8 Keuangan

Menampilkan daftar uang masukdankeluar

![Transaksi](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/f1f15244-0f4a-4661-bda5-0f01c628189d" />
)

---

### 9 Tambah transaksi Keuangan

Tambah Transaksi Keuangan

![Transaksi](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/67168818-dc19-498b-a306-735644183e89" />

)

---
### 10 Export Laporan

fitur karyawan bisa tambah karyawan, absensi karyawan, pencatatan produksi karyawan sampai penggajian

![HR Karyawan](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/e3d694a4-3885-4185-827e-cc0a24510282" />
)

---
### 11 Penjualan

Fitur Penjualan, untuk mencatat penjualan online maupun offline beserta potongan admin fees platorm jika online yang dapat diatur disetting penjualan

![Penjualan](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/5ecf3cfa-6720-4fd4-a2f7-23b90a8f4844" />

)

---
### 12 AI Asistant

Fitur chatbot sederhana untuk membantu admin dalam pengecekan cepat

![AI Asistant](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/e0cbef3f-4c2a-4a49-a2ee-8f4fa185d3c6" />

)

---
### 13 Profil

Fitur Profil untuk mengatur profil dan ganti pasword serta cek informasi akun

![Profil](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/e0cbef3f-4c2a-4a49-a2ee-8f4fa185d3c6" />

)

---
### 14 Pengaturan

Fitur Pengaturan akun

![Pengaturan](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/2471202f-08ad-40dd-b888-4903384ca043" />


)

---
### 15 Export PDF

tampilan export pdf

![Export PDF](<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/dadf517d-8565-4f7d-a13c-22c59d08264a" />



)

---
### 16 Export Excel

tampilan export Excel

![Export Excel](<img width="1029" height="535" alt="image" src="https://github.com/user-attachments/assets/87de83d9-b463-4b9c-afc6-792c725ec6a3" />



)

---
## 📁 Struktur Project

```txt
bisnisku-web-app/
├── app/                    # Controller, Model, View
├── core/                   # Router & Database
├── config/                 # Konfigurasi environment
├── public/                 # Entry point aplikasi
│   ├── index.php
│   └── .htaccess
├── assets/                 # CSS, JS, Images
├── storage/
│   ├── uploads/
│   ├── exports/
│   └── sql/
├── routes.php
└── README.md
```



## 📝 Catatan:

Aplikasi ini dikembangkan untuk memenuhi **UAS Pemrograman Web**, dengan penerapan:

* CRUD
* Autentikasi & Session
* Database Relasional
* Export Data
* Struktur project terorganisir

---

## 📜 License

© 2026
**Bisnisku Web Application**
Project UAS Pemrograman Web

