# CBT System - Deployment Guide untuk Hosting

## 🚀 Panduan Deploy ke Hosting

### 📋 Persiapan Sebelum Upload

#### 1. Update Dependencies Lokal
```bash
# Jalankan script update dependencies
chmod +x update_dependencies.sh
./update_dependencies.sh

# Atau manual:
rm -rf composer.lock vendor/
composer install --no-dev --optimize-autoloader
```

#### 2. Verifikasi Compatibility
```bash
# Cek PHP version yang dibutuhkan
php -v

# Test aplikasi lokal
php spark serve
```

### 📁 File yang Perlu Diupload

#### ✅ Upload Files:
```
/app/
/public/
/system/ (jika ada)
/vendor/
/writable/
.htaccess
index.php
spark
composer.json
composer.lock
```

#### ❌ Jangan Upload:
```
.env (buat baru di hosting)
.git/
tests/
.gitignore
update_dependencies.sh
deploy_to_hosting.md
```

### ⚙️ Konfigurasi di Hosting

#### 1. Setup Environment File
```bash
# Copy template ke .env
cp .env.hosting .env

# Edit .env dengan data hosting Anda:
nano .env
```

#### 2. Update .env dengan Data Hosting
```env
# Base URL
app.baseURL = 'https://dmcat.inova.my.id/'

# Database (sesuaikan dengan hosting)
database.default.hostname = localhost
database.default.database = dmcat_database
database.default.username = dmcat_user
database.default.password = your_password

# Environment
CI_ENVIRONMENT = production
```

#### 3. Set Permissions
```bash
# Set permission untuk writable directory
chmod -R 755 writable/
chmod -R 755 public/

# Jika perlu, set ownership
chown -R www-data:www-data writable/
```

### 🗄️ Database Setup

#### 1. Import Database
```sql
-- Upload file database.sql ke hosting
-- Atau jalankan migrations:
php spark migrate
```

#### 2. Seed Data (Opsional)
```bash
php spark db:seed UserSeeder
php spark db:seed UjianSeeder
```

### 🔧 Troubleshooting Hosting

#### Problem: Composer Dependencies Error
```bash
# Solusi 1: Update di hosting
composer install --no-dev --optimize-autoloader

# Solusi 2: Upload vendor/ yang sudah di-compile lokal
# (sudah termasuk dalam file upload)
```

#### Problem: PHP Version Mismatch
```bash
# Cek PHP version di hosting
php -v

# Jika hosting menggunakan multiple PHP versions:
# Gunakan .htaccess untuk force PHP version
```

#### Problem: File Permissions
```bash
# Set correct permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 755 writable/
```

#### Problem: URL Rewriting
```apache
# Pastikan .htaccess ada di root:
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

### 🧪 Testing di Hosting

#### 1. Basic Test
```bash
# Test homepage
curl -I https://dmcat.inova.my.id/

# Test PHP compatibility
https://dmcat.inova.my.id/php-multiversion-check
```

#### 2. Database Test
```bash
# Test database connection
https://dmcat.inova.my.id/login

# Test admin functions
https://dmcat.inova.my.id/admin/dashboard
```

#### 3. Feature Test
```bash
# Test ujian functionality
https://dmcat.inova.my.id/peserta/dashboard

# Test file upload
# Upload test file melalui admin panel
```

### 📊 Monitoring & Maintenance

#### 1. Log Monitoring
```bash
# Check error logs
tail -f writable/logs/log-*.php

# Check server logs (hosting panel)
```

#### 2. Performance Monitoring
```bash
# Enable OPcache di hosting
# Check PHP configuration
https://dmcat.inova.my.id/php-multiversion-check
```

#### 3. Security Checklist
```bash
# ✅ .env file tidak accessible dari web
# ✅ writable/ directory permissions correct
# ✅ Database credentials secure
# ✅ HTTPS enabled
# ✅ Error display disabled in production
```

### 🔄 Update Procedure

#### 1. Backup
```bash
# Backup database
mysqldump -u user -p database > backup.sql

# Backup files
tar -czf backup.tar.gz public/ app/ writable/
```

#### 2. Update
```bash
# Upload new files
# Update .env if needed
# Run migrations if any
php spark migrate
```

#### 3. Verify
```bash
# Test all functionality
# Check logs for errors
# Monitor performance
```

### 📞 Support

#### Hosting Requirements Check:
- **PHP**: 8.0+ ✅
- **MySQL**: 5.7+ ✅
- **Apache/Nginx**: mod_rewrite ✅
- **Memory**: 128MB+ ✅
- **Disk Space**: 100MB+ ✅

#### Common Hosting Issues:
1. **PHP Version**: Pastikan hosting support PHP 8.0+
2. **Composer**: Upload vendor/ jika composer tidak tersedia
3. **Permissions**: Set 755 untuk directories, 644 untuk files
4. **Database**: Pastikan credentials benar di .env
5. **URL Rewriting**: Pastikan .htaccess berfungsi

#### Contact Support:
- **Email**: support@langitinovasi.id
- **Documentation**: PHP_MULTIVERSION_GUIDE.md
- **Compatibility Check**: /php-multiversion-check

---

**© 2025 CBT System 3.0 - Ready for Production Hosting**
