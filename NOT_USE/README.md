# Folder NOT_USE

Folder ini berisi file-file yang sudah tidak digunakan dalam aplikasi CBT tetapi disimpan untuk backup dan referensi.

## Daftar File yang Dipindahkan:

### Controllers (Development/Testing)
- `PHPMultiVersionCheck.php` - Controller untuk cek kompatibilitas PHP (development only)
- `TestTimezone.php` - Controller untuk test timezone (development only)
- `Ujian - Copy.php` - Copy file controller ujian peserta

### Views (Development/Testing)
- `dashboard - Copy.php` - Copy file dashboard peserta
- `php81_check.php` - View untuk cek PHP 8.1
- `php_multiversion_check.php` - View untuk cek multi-version PHP
- `php_multiversion_requirements.php` - View requirements PHP
- `test_timezone.php` - View untuk test timezone
- `index - Copy.php` - Copy file view ujian peserta
- `index - Copy (3).php` - Copy file view ujian peserta
- `index - Copy (claude).php` - Copy file view ujian peserta

### Config (Development/Testing)
- `PHPMultiVersion.php` - Config untuk multi-version PHP
- `Timezone.php` - Config timezone (development)
- `Routes - Copy.php` - Copy file routes

### Documentation & Build Files
- `PHP81_UPGRADE.md` - Dokumentasi upgrade PHP 8.1
- `PHP_MULTIVERSION_GUIDE.md` - Guide multi-version PHP
- `MIGRATION_GUIDE.md` - Guide migrasi
- `HOSTING_CHECKLIST.md` - Checklist hosting
- `deploy_to_hosting.md` - Guide deploy ke hosting
- `update_dependencies.sh` - Script update dependencies
- `phpunit.xml.dist` - Config PHPUnit testing
- `image-bg.png` - File gambar background yang tidak digunakan

### Folders
- `tests/` - Folder testing PHPUnit
- `public.bak/` - Backup folder public
- `uploads/` - Folder uploads duplikat di root
- `docs/` - Folder dokumentasi development
- `builds/` - Folder builds yang tidak digunakan

### Catatan Khusus:
- **Folder `assets/`** telah dikembalikan ke root karena masih digunakan di `app/Views/layouts/header.php` untuk background image (`bg-image.jpg`)

## Catatan:
- File-file ini dipindahkan pada tanggal: 28 Juli 2025
- Semua file masih bisa dikembalikan jika diperlukan
- Aplikasi CBT tetap berfungsi normal tanpa file-file ini
- File-file ini khusus untuk development dan testing, tidak diperlukan untuk production

## Cara Mengembalikan File:
Jika ada file yang ternyata masih diperlukan, cukup pindahkan kembali ke lokasi asalnya sesuai dengan struktur folder yang ada.
