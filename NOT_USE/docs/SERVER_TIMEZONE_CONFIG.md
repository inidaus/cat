# Konfigurasi Waktu Server - CBT Application

## Overview

Aplikasi CBT ini menggunakan **waktu server** sebagai acuan utama untuk semua operasi waktu. Ini berarti:

- ✅ **Waktu yang ditampilkan** mengikuti timezone server
- ✅ **Waktu ujian** menggunakan waktu server
- ✅ **Log dan database** menggunakan waktu server
- ✅ **Tidak terpengaruh** timezone client/browser user

## Konfigurasi Timezone Server

### 1. Deteksi Otomatis Timezone Server

```php
// Di app/Views/layouts/header.php
$serverTimezone = date_default_timezone_get(); // Ambil timezone server
$tzDB = $serverTimezone; // Gunakan timezone server
date_default_timezone_set($serverTimezone); // Set timezone PHP
```

### 2. Informasi Timezone Server

Aplikasi akan menampilkan informasi timezone server di console browser:

```javascript
console.log('=== SERVER TIMEZONE INFO ===');
console.log('Server Timezone:', serverInfo.server_timezone);
console.log('Server Time:', serverInfo.server_time);
console.log('Server Offset:', serverInfo.server_offset);
console.log('Server DST:', serverInfo.server_dst);
```

## Helper Functions

### Fungsi-fungsi yang Tersedia

```php
// Waktu server saat ini
server_time('Y-m-d H:i:s'); // 2024-01-15 14:30:45

// Timezone server
server_timezone(); // Asia/Jakarta

// Offset timezone server
server_timezone_offset(); // +07:00

// Format waktu untuk database
server_datetime_for_db(); // 2024-01-15 14:30:45
server_date_for_db(); // 2024-01-15

// Format waktu untuk display
server_time_for_display(); // Senin, 15 Januari 2024 | 14:30:45

// Informasi lengkap timezone server
server_timezone_info();
```

## Contoh Penggunaan

### 1. Di Controller

```php
class UjianController extends BaseController
{
    public function save()
    {
        // Input dari user
        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $jamMulai = $this->request->getPost('jam_mulai');
        $mulai = $tanggalMulai . ' ' . $jamMulai . ':00';
        
        // Konversi ke waktu server
        $mulaiTimestamp = strtotime($mulai);
        $mulaiServerTime = format_server_time($mulaiTimestamp, 'Y-m-d H:i:s');
        
        // Simpan ke database dengan waktu server
        $data = [
            'mulai' => $mulaiServerTime,
            'created_at' => server_datetime_for_db()
        ];
    }
}
```

### 2. Di View

```php
<!-- Tampilkan waktu server -->
<p>Waktu Server: <?= server_time_for_display() ?></p>
<p>Timezone: <?= server_timezone() ?> (<?= server_timezone_offset() ?>)</p>
```

### 3. Di JavaScript

```javascript
// Waktu akan mengikuti timezone server
const now = DateTime.now().setZone(serverTimezone);
const displayTime = now.toFormat("dd LLLL yyyy | HH:mm:ss");
```

## Skenario Penggunaan

### Skenario 1: Server di Indonesia (+7), User di Amerika (-5)

- **Server Timezone:** Asia/Jakarta (+07:00)
- **User Timezone:** America/New_York (-05:00)
- **Server Time:** 15 Jan 2024, 14:00 WIB
- **User Local Time:** 15 Jan 2024, 02:00 EST

**Yang Ditampilkan di Aplikasi:**
- ✅ **Waktu:** 15 Januari 2024 | 14:00:00 (waktu server)
- ✅ **Ujian dimulai:** 15 Jan 2024, 14:00 WIB
- ✅ **Konsisten** untuk semua user di seluruh dunia

### Skenario 2: Server di Singapura (+8), User di Indonesia (+7)

- **Server Timezone:** Asia/Singapore (+08:00)
- **User Timezone:** Asia/Jakarta (+07:00)
- **Server Time:** 15 Jan 2024, 15:00 SGT
- **User Local Time:** 15 Jan 2024, 14:00 WIB

**Yang Ditampilkan di Aplikasi:**
- ✅ **Waktu:** 15 Januari 2024 | 15:00:00 (waktu server)
- ✅ **Ujian dimulai:** 15 Jan 2024, 15:00 SGT
- ✅ **Semua user** melihat waktu yang sama

## Keuntungan Sistem Ini

### 1. Konsistensi Global
- Semua user melihat waktu yang sama
- Ujian dimulai bersamaan untuk semua peserta
- Tidak ada kebingungan timezone

### 2. Administrasi Mudah
- Admin hanya perlu mengatur waktu server
- Tidak perlu konversi timezone manual
- Satu sumber waktu untuk semua

### 3. Keamanan
- Tidak bisa dimanipulasi dari client
- Waktu ujian tidak bisa diubah user
- Server yang mengontrol penuh

## Debugging Timezone

### 1. Cek Console Browser

Buka Developer Tools (F12) dan lihat console:

```
=== SERVER TIMEZONE INFO ===
Server Timezone: Asia/Jakarta
Server Time: 2024-01-15 14:30:45
Server Offset: +07:00
Server DST: No
============================
```

### 2. Cek PHP Info

```php
// Tambahkan di controller untuk debugging
public function debugTimezone()
{
    $info = server_timezone_info();
    dd($info);
}
```

### 3. Cek Database

```sql
-- Cek waktu di database
SELECT NOW() as database_time;
SELECT created_at FROM ujian ORDER BY id DESC LIMIT 1;
```

## Troubleshooting

### Problem: Waktu tidak sesuai

**Solusi:**
1. Cek timezone server: `date_default_timezone_get()`
2. Set timezone server: `date_default_timezone_set('Asia/Jakarta')`
3. Restart web server
4. Clear browser cache

### Problem: Ujian dimulai di waktu yang salah

**Solusi:**
1. Pastikan input waktu menggunakan `format_server_time()`
2. Cek database apakah waktu tersimpan benar
3. Verifikasi timezone server

### Problem: User bingung dengan waktu

**Solusi:**
1. Tampilkan timezone server di UI
2. Berikan keterangan "Waktu Server"
3. Edukasi user tentang sistem waktu

## Best Practices

### 1. Selalu Gunakan Helper Functions

```php
// ✅ BENAR
$waktuSekarang = server_time();
$waktuUjian = format_server_time($input, 'Y-m-d H:i:s');

// ❌ SALAH
$waktuSekarang = date('Y-m-d H:i:s'); // Bisa berbeda timezone
$waktuUjian = $input; // Tidak dikonversi ke server time
```

### 2. Tampilkan Informasi Timezone

```php
<!-- ✅ BENAR - Berikan informasi jelas -->
<p>Waktu Server: <?= server_time_for_display() ?></p>
<p>Timezone: <?= server_timezone() ?></p>
<small>Semua waktu mengikuti waktu server</small>

<!-- ❌ SALAH - User tidak tahu timezone -->
<p>Waktu: <?= date('Y-m-d H:i:s') ?></p>
```

### 3. Konsisten di Seluruh Aplikasi

- Database: Gunakan `server_datetime_for_db()`
- Display: Gunakan `server_time_for_display()`
- JavaScript: Gunakan `serverTimezone`
- Log: Gunakan `server_time()`

## Kesimpulan

Sistem waktu server memastikan:
- ✅ **Konsistensi** waktu di seluruh aplikasi
- ✅ **Keamanan** dari manipulasi client
- ✅ **Kemudahan** administrasi
- ✅ **Kejelasan** untuk user

Semua waktu dalam aplikasi CBT mengikuti waktu server, memberikan pengalaman yang konsisten untuk semua user di manapun mereka berada.
