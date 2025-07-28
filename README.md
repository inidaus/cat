# 🧠 CAT - Computerized Adaptive Test System

Aplikasi **CAT (Computerized Adaptive Test)** berbasis web yang dirancang untuk efisiensi dan kecepatan dalam pelaksanaan ujian adaptif online.
Dibangun dengan framework **CodeIgniter 4** oleh **Mas Daus** dari [Langit Inovasi](https://inova.my.id), https://github.com/inidaus langsung dari **Warkop** penuh inspirasi.

## 🤝 Dibuat untuk

Untuk Tim **Scrab Team**
Didesain dan dikembangkan dengan 💻 & ☕ oleh **Mas Daus**,  [https://github.com/inidaus](https://github.com/inidaus)
dari **Langit Inovasi** → [https://inova.my.id](https://inova.my.id)

---

## 🚀 Fitur Unggulan

### 🔐 Sistem Multi-Role
- **Admin**: Kelola seluruh sistem, pengguna, dan laporan
- **Pembimbing**: Buat soal, ujian, dan monitor peserta
- **Peserta**: Ikuti ujian dengan antarmuka yang user-friendly

### 🎯 Ujian Adaptif
- Soal adaptif otomatis berdasarkan performa peserta
- Bank soal fleksibel dengan berbagai tipe (Pilihan Ganda, Essay)
- Sistem bobot soal dan randomisasi
- Timer otomatis dengan toleransi waktu

### 📊 Monitoring & Laporan
- Dashboard real-time untuk monitoring ujian
- Laporan hasil ujian yang detail
- Export laporan ke format PDF dan Excel
- Analisis statistik performa peserta

### 🌐 Teknologi Modern
- Responsive design untuk semua perangkat
- Interface yang intuitif dan mudah digunakan
- Sistem keamanan berlapis
- Database MySQL dengan optimasi performa

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: CodeIgniter 4 (PHP 8.0+)
- **Database**: MySQL/MariaDB
- **Frontend**: Bootstrap 5, jQuery, Font Awesome
- **Export**: PhpSpreadsheet untuk Excel
- **Server**: Apache/Nginx dengan mod_rewrite

---

## 📂 Struktur Aplikasi

```
📦 cat/
 ┣ 📁 app/
 ┃ ┣ 📁 Controllers/      # Logic aplikasi
 ┃ ┃ ┣ 📁 Admin/          # Controller admin
 ┃ ┃ ┣ 📁 Pembimbing/     # Controller pembimbing
 ┃ ┃ ┣ 📁 Peserta/        # Controller peserta
 ┃ ┃ ┗ 📁 Api/            # API endpoints
 ┃ ┣ 📁 Models/           # Model database
 ┃ ┣ 📁 Views/            # Template tampilan
 ┃ ┣ 📁 Database/         # Migrasi & seeder
 ┃ ┗ 📁 Config/           # Konfigurasi sistem
 ┣ 📁 public/             # Web root & assets
 ┣ 📁 writable/           # Upload, logs, cache
 ┣ 📁 vendor/             # Dependencies Composer
 ┣ 📄 .env                # Konfigurasi environment
 ┣ 📄 composer.json       # Dependencies PHP
 ┗ 📄 spark               # CLI CodeIgniter
```

---

## ⚙️ Instalasi & Konfigurasi

### 📋 Persyaratan Sistem

- **PHP**: 8.0 atau lebih tinggi
- **Database**: MySQL 5.7+ atau MariaDB 10.3+
- **Web Server**: Apache dengan mod_rewrite atau Nginx
- **Composer**: Untuk manajemen dependencies
- **Extensions PHP**:
  - `php-mysqli` atau `php-pdo`
  - `php-mbstring`
  - `php-json`
  - `php-xml`
  - `php-zip`

### 🚀 Langkah Instalasi

1. **Clone Repository:**
   ```bash
   git clone https://github.com/inidaus/cat.git
   cd cat
   ```

2. **Install Dependencies:**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment:**
   ```bash
   cp env .env
   ```

   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   database.default.hostname = localhost
   database.default.database = cbtcat
   database.default.username = your_username
   database.default.password = your_password
   database.default.DBDriver = MySQLi
   ```

4. **Buat Database:**
   ```sql
   CREATE DATABASE cbtcat CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```

5. **Jalankan Migrasi Database:**
   ```bash
   php spark migrate
   ```

6. **Jalankan Seeder (Opsional):**
   ```bash
   php spark db:seed
   ```

7. **Set Permissions:**
   ```bash
   chmod -R 755 writable/
   chmod -R 755 public/uploads/
   ```

8. **Jalankan Development Server:**
   ```bash
   php spark serve
   ```

   Akses aplikasi di: `http://localhost:8080`

---

## 🔍 Demo Online

🌐 [https://dmcat.inova.my.id](https://dmcat.inova.my.id)
🔑 *Username & password hubungi Mas Daus langsung*

---

## 🗄️ Struktur Database

### Tabel Utama

- **`users`**: Data pengguna (admin, pembimbing, peserta)
- **`ujian`**: Data ujian dan konfigurasi
- **`soal`**: Bank soal dengan berbagai kategori
- **`ujian_peserta`**: Relasi peserta dengan ujian
- **`jawaban_ujian`**: Jawaban peserta untuk setiap soal
- **`kategori`**: Kategori soal dan ujian

### Relasi Database
```
users (1) -----> (n) ujian_peserta (n) <----- (1) ujian
  |                                              |
  |                                              |
  v                                              v
soal (n) -----> (1) kategori (1) <------------- (n) ujian
  |
  |
  v
jawaban_ujian (n) -----> (1) ujian_peserta
```

---

## 👥 Panduan Penggunaan

### 🔑 Login Default

Setelah instalasi, gunakan akun default berikut:

| Role | Username | Password | Deskripsi |
|------|----------|----------|-----------|
| Admin | `admin` | `123456` | Akses penuh sistem |
| Pembimbing | `pembimbing` | `pembimbing123` | Kelola soal & ujian |
| Peserta | `peserta` | `peserta123` | Ikuti ujian |

> ⚠️ **Penting**: Segera ubah password default setelah login pertama!

### 📚 Alur Kerja Sistem

1. **Admin** membuat kategori dan mengelola pengguna
2. **Pembimbing** membuat bank soal dan konfigurasi ujian
3. **Peserta** login dan mengikuti ujian yang tersedia
4. **Sistem** otomatis menghitung nilai dan generate laporan
5. **Pembimbing/Admin** dapat melihat hasil dan export laporan

---

## 🎯 Fitur Detail

### 🔧 Panel Admin
- ✅ Manajemen pengguna (CRUD)
- ✅ Manajemen kategori soal
- ✅ Monitoring sistem secara real-time
- ✅ Laporan komprehensif semua ujian
- ✅ Export data ke Excel/PDF
- ✅ Pengaturan sistem global

### 👨‍🏫 Panel Pembimbing
- ✅ Buat dan kelola bank soal
- ✅ Konfigurasi ujian (waktu, jumlah soal, passing grade)
- ✅ Monitor peserta saat ujian berlangsung
- ✅ Analisis hasil ujian per kategori
- ✅ Export laporan peserta

### 👨‍🎓 Panel Peserta
- ✅ Dashboard ujian yang tersedia
- ✅ Interface ujian yang user-friendly
- ✅ Timer countdown real-time
- ✅ Auto-save jawaban
- ✅ Review hasil ujian
- ✅ Riwayat ujian yang pernah diikuti

---

## 🔧 Konfigurasi Lanjutan

### ⚙️ Environment Variables

Edit file `.env` untuk konfigurasi tambahan:

```env
# Database
database.default.hostname = localhost
database.default.database = cbtcat
database.default.username = root
database.default.password =

# App Settings
app.baseURL = 'http://localhost:8080'
app.forceGlobalSecureRequests = false
app.sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler'
app.sessionSavePath = null

# Security
security.csrfProtection = 'cookie'
security.tokenRandomize = true
security.tokenName = 'csrf_token_name'
security.headerName = 'X-CSRF-TOKEN'

# Upload Settings
upload.maxSize = 10485760  # 10MB
upload.allowedTypes = 'jpg,jpeg,png,gif,pdf,doc,docx'
```

### 🌐 Konfigurasi Web Server

#### Apache (.htaccess)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

---

## 🚀 Deployment ke Production

### 📋 Checklist Pre-Deployment

- [ ] Update semua dependencies: `composer update`
- [ ] Set environment ke production di `.env`
- [ ] Disable debug mode: `CI_ENVIRONMENT = production`
- [ ] Konfigurasi database production
- [ ] Set permissions yang tepat untuk folder `writable/`
- [ ] Backup database sebelum migrasi
- [ ] Test semua fitur di staging environment

### 🔒 Keamanan Production

```env
# Production Security Settings
CI_ENVIRONMENT = production
app.forceGlobalSecureRequests = true
security.csrfProtection = 'cookie'
security.tokenRandomize = true
```

### 📊 Monitoring & Maintenance

- Monitor log error di `writable/logs/`
- Backup database secara berkala
- Update dependencies secara rutin
- Monitor performa server dan database

---

## 🛠️ Troubleshooting

### ❗ Masalah Umum

#### Database Connection Error
```bash
# Periksa konfigurasi database di .env
# Pastikan MySQL service berjalan
sudo systemctl start mysql

# Test koneksi database
php spark db:table users
```

#### Permission Denied
```bash
# Set permission yang benar
sudo chown -R www-data:www-data writable/
sudo chmod -R 755 writable/
```

#### Composer Dependencies
```bash
# Clear cache dan reinstall
composer clear-cache
rm -rf vendor/
composer install
```

### 🔍 Debug Mode

Untuk debugging, aktifkan di `.env`:
```env
CI_ENVIRONMENT = development
app.forceGlobalSecureRequests = false
```

---

## 📞 Support & Kontribusi

### 🐛 Melaporkan Bug

1. Buka [Issues](https://github.com/inidaus/cat/issues)
2. Gunakan template bug report
3. Sertakan informasi environment dan langkah reproduksi

### 💡 Request Fitur

1. Diskusikan di [Discussions](https://github.com/inidaus/cat/discussions)
2. Buat proposal fitur yang detail
3. Tunggu feedback dari maintainer

### 🤝 Kontribusi Code

1. Fork repository ini
2. Buat branch fitur: `git checkout -b fitur-baru`
3. Commit perubahan: `git commit -m 'Tambah fitur baru'`
4. Push ke branch: `git push origin fitur-baru`
5. Buat Pull Request

---

## 📚 Dokumentasi Tambahan

- [📖 User Manual](NOT_USE/docs/USER_MANUAL.md)
- [🔧 API Documentation](NOT_USE/docs/API_DOCS.md)
- [🚀 Deployment Guide](NOT_USE/HOSTING_CHECKLIST.md)
- [🔄 Migration Guide](NOT_USE/MIGRATION_GUIDE.md)

---

## 🏆 Changelog

### v3.17.0 (2025-07-28) - Current Version
- 🚀 **Major Performance Boost**: Optimasi query database untuk ujian dengan ribuan peserta
- 🎯 **Smart Question Selection**: Algoritma adaptif yang lebih pintar berdasarkan IRT (Item Response Theory)
- 📊 **Advanced Analytics Dashboard**: Grafik real-time performa peserta dan analisis soal
- 🔐 **Enhanced Security**: Multi-factor authentication dan session management yang lebih aman
- 📱 **PWA Support**: Progressive Web App untuk pengalaman mobile yang lebih baik
- 🌐 **Multi-Language Support**: Dukungan bahasa Indonesia dan Inggris
- 🔄 **Auto-Backup System**: Backup otomatis database dan file ujian
- 📈 **Detailed Reporting**: Export laporan dengan format yang lebih lengkap (PDF, Excel, CSV)
- 🎨 **Modern UI/UX**: Interface yang lebih clean dan user-friendly
- 🛠️ **Admin Tools**: Tools untuk monitoring sistem dan maintenance
- 📝 **Question Bank Management**: Import/export soal dalam format Excel
- ⏱️ **Flexible Timer**: Timer yang dapat disesuaikan per peserta
- 🔍 **Search & Filter**: Pencarian dan filter yang lebih canggih di semua modul
- 🚨 **Alert System**: Notifikasi real-time untuk admin dan pembimbing
- 🔧 **Bug Fixes**: Perbaikan berbagai bug dan stabilitas sistem

### v2.0.0 (2025-01-28)
- ✨ Sistem adaptif testing yang lebih canggih
- 🔧 Optimasi performa database
- 🎨 UI/UX improvements
- 🔒 Enhanced security features
- 📊 Advanced reporting system

### v1.5.0 (2024-12-15)
- 📱 Mobile responsive improvements
- 🔄 Auto-save functionality
- 📈 Real-time monitoring
- 🎯 Better adaptive algorithm

### v1.0.0 (2024-06-15)
- 🎉 Initial release
- 🔐 Multi-role authentication
- 📝 Basic exam functionality
- 📊 Simple reporting

---

## 📜 Lisensi

Proyek ini menggunakan lisensi MIT – bebas digunakan, dimodifikasi, dan dikembangkan namun harus menyertakan sumber asli.

---

## 🙏 Acknowledgments

Terima kasih kepada:
- **Scrab Team** untuk kepercayaan dan kolaborasi
- **CodeIgniter Community** untuk framework yang luar biasa
- **Bootstrap Team** untuk UI framework yang responsive
- **PhpSpreadsheet** untuk fitur export yang powerful

---

**Dibuat dengan ❤️ di Indonesia**
© 2025 Mas Daus - Langit Inovasi
