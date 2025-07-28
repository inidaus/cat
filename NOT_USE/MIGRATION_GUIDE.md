# Panduan Migrasi Web Root CBT System

## Ringkasan Perubahan

Web root telah berhasil dipindahkan dari folder `public` ke root directory untuk kemudahan akses dan konfigurasi yang lebih fleksibel.

### Sebelum dan Sesudah

**Sebelum:**
- URL Akses: `http://localhost/cbt/public/`
- Web Root: `public/` folder
- Assets: `public/assets/`

**Sesudah:**
- URL Akses: `http://192.168.1.200/cbt/` (atau sesuai konfigurasi)
- Web Root: Root directory
- Assets: `assets/` (di root)

## File yang Dipindahkan

Dari folder `public/` ke root directory:
- `index.php` (dengan penyesuaian path)
- `robots.txt`
- `favicon.ico`
- `assets/` (folder dan isinya)
- `.htaccess` (dengan penyesuaian RewriteBase)

## Konfigurasi Fleksibel

### File .env
Konfigurasi base URL sekarang dapat diatur dengan mudah di file `.env`:

```env
# Untuk localhost development
# app.baseURL = 'http://localhost/cbt/'

# Untuk production/server dengan IP
app.baseURL = 'http://192.168.1.200/cbt/'

# Untuk domain production
# app.baseURL = 'https://yourdomain.com/cbt/'
```

### Cara Mengganti Environment

1. **Development (localhost):**
   ```env
   app.baseURL = 'http://localhost/cbt/'
   ```

2. **Production dengan IP:**
   ```env
   app.baseURL = 'http://192.168.1.200/cbt/'
   ```

3. **Production dengan Domain:**
   ```env
   app.baseURL = 'https://yourdomain.com/cbt/'
   ```

## File yang Dimodifikasi

### 1. Konfigurasi Utama
- `app/Config/App.php` - Base URL default dan dokumentasi
- `.env` - Konfigurasi environment yang fleksibel
- `.htaccess` - RewriteBase untuk subfolder

### 2. File Bootstrap
- `index.php` - Path adjustment untuk root directory
- `spark` - FCPATH adjustment
- `preload.php` - FCPATH adjustment

### 3. File Controller
- `app/Controllers/Auth.php` - Perbaikan redirect logout
- `app/Controllers/Admin/Peserta.php` - Perbaikan redirect

### 4. File View
- `app/Views/auth/login.php` - Perbaikan form action URL

### 5. File Testing
- `phpunit.xml.dist` - PUBLICPATH adjustment

## Keamanan

File `.htaccess` telah ditambahkan untuk melindungi folder sensitif:
- `app/.htaccess` - Blokir akses ke folder aplikasi
- `writable/.htaccess` - Blokir akses ke folder writable
- `tests/.htaccess` - Blokir akses ke folder tests
- `vendor/.htaccess` - Blokir akses ke folder vendor

## Cara Deployment

### 1. Development ke Production
1. Edit file `.env`
2. Uncomment dan sesuaikan `app.baseURL`
3. Restart web server jika diperlukan

### 2. Pindah Hosting
1. Upload semua file ke web root hosting
2. Edit `.env` sesuai domain/IP baru
3. Pastikan mod_rewrite aktif di server

## Troubleshooting

### Masalah URL Masih Mengarah ke /public
- Periksa file `.env` apakah `app.baseURL` sudah benar
- Clear cache browser
- Restart web server

### Assets Tidak Muncul
- Pastikan folder `assets/` ada di root directory
- Periksa permission folder assets (755)

### Error 404 pada Route
- Pastikan `.htaccess` ada di root directory
- Pastikan mod_rewrite aktif di Apache
- Periksa `RewriteBase /cbt/` sesuai subfolder

## Catatan Penting

1. **Backup**: Selalu backup sebelum melakukan perubahan
2. **Testing**: Test semua fitur setelah migrasi
3. **Cache**: Clear cache browser dan server setelah perubahan
4. **Permission**: Pastikan permission file dan folder sesuai (644 untuk file, 755 untuk folder)

## Kontak Support

Jika mengalami masalah, hubungi tim development dengan menyertakan:
- Environment yang digunakan (development/production)
- Error message yang muncul
- Screenshot jika diperlukan
