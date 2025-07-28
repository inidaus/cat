<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home');
$routes->get('login', 'Auth::index');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');



// Legacy routes for backward compatibility
$routes->get('php81-check', 'PHPMultiVersionCheck::index');
$routes->get('php81-check/api', 'PHPMultiVersionCheck::api');
$routes->get('php81-check/requirements', 'PHPMultiVersionCheck::requirements');

$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->get('pembimbing/dashboard', 'Pembimbing::dashboard');

$routes->group('admin', ['filter' => 'adminFilter'], function($routes) {
    $routes->get('pembimbing', 'Admin\Pembimbing::index');
    $routes->post('pembimbing/save', 'Admin\Pembimbing::save');
    $routes->post('pembimbing/update', 'Admin\Pembimbing::update');
    $routes->post('pembimbing/delete/(:num)', 'Admin\Pembimbing::delete/$1');
    
    $routes->get('peserta', 'Admin\Peserta::index');
    $routes->get('peserta/create', 'Admin\Peserta::create');
    $routes->post('peserta/save', 'Admin\Peserta::save');
    $routes->post('peserta/toggle/(:num)', 'Admin\Peserta::toggle/$1');
    
    $routes->get('setting', 'Admin\Setting::index');
    $routes->post('setting/saveAdmin', 'Admin\Setting::saveAdmin');
    $routes->post('setting/updateAdmin', 'Admin\Setting::updateAdmin');
    $routes->post('setting/deleteAdmin/(:num)', 'Admin\Setting::deleteAdmin/$1');


    $routes->post('peserta/update', 'Admin\Peserta::update');
    $routes->post('peserta/delete/(:num)', 'Admin\Peserta::delete/$1');
    $routes->post('peserta/import', 'Admin\Peserta::import');
    $routes->get('peserta/export', 'Admin\Peserta::export');
    $routes->get('peserta/template', 'Admin\Peserta::downloadTemplate');

    $routes->get('soal', 'Admin\Soal::index');
    $routes->get('soal/create', 'Admin\Soal::create');
    $routes->post('soal/save', 'Admin\Soal::save');
    $routes->post('soal/update', 'Admin\Soal::update');
    $routes->post('soal/delete/(:num)', 'Admin\Soal::delete/$1');
    $routes->get('soal/edit/(:num)', 'Admin\Soal::edit/$1');
    $routes->get('soal/preview/(:num)', 'Admin\Soal::preview/$1');
    $routes->get('soal/duplicate/(:num)', 'Admin\Soal::duplicate/$1');

    $routes->post('soal/import', 'Admin\Soal::import');
    $routes->get('soal/template', 'Admin\Soal::downloadTemplate');
    $routes->get('soal/export', 'Admin\Soal::export');
    $routes->get('soal/export', 'Admin\Soal::export');

    // Admin Laporan routes
    $routes->get('laporan', 'Admin\Laporan::index');
    $routes->get('laporan/detail/(:num)', 'Admin\Laporan::detail/$1');
    $routes->get('laporan/export/(:num)', 'Admin\Laporan::export/$1');
});

$routes->group('admin', function($routes) {
    $routes->get('kategori', 'Admin\Kategori::index');
    $routes->post('kategori/save', 'Admin\Kategori::save');
    $routes->post('kategori/update', 'Admin\Kategori::update');
    $routes->post('kategori/delete/(:num)', 'Admin\Kategori::delete/$1');
});

$routes->group('pembimbing', ['filter' => 'pembimbingFilter'], function ($routes) {
    $routes->get('soal', 'Pembimbing\\Soal::index');
    $routes->get('soal/create', 'Pembimbing\\Soal::create');
    $routes->post('soal/save', 'Pembimbing\\Soal::save');
    $routes->post('soal/delete/(:num)', 'Pembimbing\\Soal::delete/$1');
    $routes->get('soal/edit/(:num)', 'Pembimbing\\Soal::edit/$1');
    $routes->post('soal/update', 'Pembimbing\\Soal::update');
    $routes->get('soal/preview/(:num)', 'Pembimbing\\Soal::preview/$1');
    $routes->get('soal/duplicate/(:num)', 'Pembimbing\\Soal::duplicate/$1');
    $routes->post('soal/import', 'Pembimbing\Soal::import');
    $routes->get('soal/template', 'Pembimbing\Soal::downloadTemplate');
    $routes->get('soal/export', 'Pembimbing\Soal::export');

    // Kategori routes
    $routes->get('kategori', 'Pembimbing\Kategori::index');
    $routes->post('kategori/store', 'Pembimbing\Kategori::store');
    $routes->post('kategori/update', 'Pembimbing\Kategori::update');
    $routes->post('kategori/delete/(:num)', 'Pembimbing\Kategori::delete/$1');

    // Peserta routes
    $routes->get('peserta', 'Pembimbing\Peserta::index');
    $routes->post('peserta/save', 'Pembimbing\Peserta::save');
    $routes->post('peserta/update', 'Pembimbing\Peserta::update');
    $routes->post('peserta/delete/(:num)', 'Pembimbing\Peserta::delete/$1');
    $routes->post('peserta/toggle/(:num)', 'Pembimbing\Peserta::toggle/$1');
    $routes->post('peserta/import', 'Pembimbing\Peserta::import');
    $routes->get('peserta/export', 'Pembimbing\Peserta::export');
    $routes->get('peserta/template', 'Pembimbing\Peserta::downloadTemplate');
    
    $routes->get('ujian', 'Pembimbing\Ujian::index');
    $routes->post('ujian/save', 'Pembimbing\Ujian::save');
    $routes->get('ujian/create', 'Pembimbing\Ujian::create');
    $routes->get('ujian/edit/(:num)', 'Pembimbing\Ujian::edit/$1');
    $routes->post('ujian/update', 'Pembimbing\Ujian::update');
    $routes->post('ujian/refreshToken/(:num)', 'Pembimbing\Ujian::refreshToken/$1');
    $routes->post('ujian/delete/(:num)', 'Pembimbing\Ujian::delete/$1');
    $routes->get('ujian/peserta/(:num)', 'Pembimbing\Ujian::peserta/$1');
    $routes->post('ujian/savePeserta/(:num)', 'Pembimbing\Ujian::savePeserta/$1');
    $routes->post('ujian/copyPeserta/(:num)', 'Pembimbing\Ujian::copyPeserta/$1');
    $routes->post('ujian/getJumlahSoal', 'Pembimbing\Ujian::getJumlahSoal');

    // Pembimbing Peserta routes
    $routes->get('peserta', 'Pembimbing\Peserta::index');
    $routes->post('peserta/save', 'Pembimbing\Peserta::save');
    $routes->post('peserta/update', 'Pembimbing\Peserta::update');
    $routes->post('peserta/delete/(:num)', 'Pembimbing\Peserta::delete/$1');
    $routes->post('peserta/toggle/(:num)', 'Pembimbing\Peserta::toggle/$1');

    // Monitoring routes
    $routes->get('monitoring', 'Pembimbing\Monitoring::index');
    $routes->get('monitoring/detail/(:num)', 'Pembimbing\Monitoring::detail/$1');
    $routes->get('monitoring/realtime/(:num)', 'Pembimbing\Monitoring::realtime/$1');

    // Laporan routes
    $routes->get('laporan', 'Pembimbing\Laporan::index');
    $routes->get('laporan/detail/(:num)', 'Pembimbing\Laporan::detail/$1');
    $routes->get('laporan/export/(:num)', 'Pembimbing\Laporan::export/$1');
    $routes->get('laporan/debug/(:num)', 'Pembimbing\Laporan::debugDetail/$1');
});

// PERBAIKAN: Tambahkan route yang hilang untuk sinkronisasi jawaban
$routes->group('peserta', ['filter' => 'pesertaFilter'], function($routes) {
    $routes->get('dashboard', 'Peserta\Dashboard::index');
    $routes->get('ujian/(:num)', 'Peserta\Ujian::index/$1');
    $routes->post('ujian/verify-token', 'Peserta\Ujian::verifyToken');
    $routes->post('ujian/simpan-jawaban', 'Peserta\Ujian::simpanJawaban');
    $routes->post('ujian/selesai', 'Peserta\Ujian::selesai');
    $routes->post('ujian/timeout', 'Peserta\Ujian::timeout');
    // ROUTE YANG HILANG - TAMBAHKAN INI:
    $routes->post('ujian/sinkronisasi-jawaban', 'Peserta\Ujian::sinkronisasiJawaban');

    // Hasil ujian routes
    $routes->get('hasil', 'Peserta\Hasil::index');
    $routes->get('hasil/detail/(:num)', 'Peserta\Hasil::detail/$1');
    $routes->get('hasil/sertifikat/(:num)', 'Peserta\Hasil::sertifikat/$1');

    // Debug route (development only)
    $routes->get('debug', 'Peserta\Dashboard::debug');
    $routes->get('fix-data', 'Peserta\Dashboard::fixData');
    $routes->get('clean-all-duplicates', 'Peserta\Dashboard::cleanAllDuplicates');
    $routes->get('register-to-ujian/(:num)', 'Peserta\Dashboard::registerToUjian/$1');
    $routes->get('fix-ujian2-time', 'Peserta\Dashboard::fixUjian2Time');

    $routes->get('check-kategori-soal', 'Peserta\Dashboard::checkKategoriSoal');
    $routes->get('set-ujian-time', 'Peserta\Dashboard::setUjianTime');
    $routes->get('debug-ujian-mendatang', 'Peserta\Dashboard::debugUjianMendatang');
    $routes->get('auto-register-all', 'Peserta\Dashboard::autoRegisterAll');
    $routes->get('debug-hasil-ujian', 'Peserta\Dashboard::debugHasilUjian');
    $routes->get('debug-dashboard-data', 'Peserta\Dashboard::debugDashboardData');
    $routes->get('reset-ujian-4', 'Peserta\Dashboard::resetUjian4');
    $routes->get('cek-database', 'Peserta\Dashboard::cekDatabase');
    $routes->get('debug-get-ujian-mendatang', 'Peserta\Dashboard::debugGetUjianMendatang');
    $routes->get('update-waktu-ujian', 'Peserta\Dashboard::updateWaktuUjian');
});

// API Routes untuk AJAX yang bersih (tanpa error display)
$routes->group('api', function ($routes) {
    $routes->get('ujian/test', 'Api\UjianApi::test');
    $routes->post('ujian/sinkronisasi-jawaban', 'Api\UjianApi::sinkronisasiJawaban');
    $routes->post('ujian/timeout', 'Api\UjianApi::timeout');
});

// Upload Routes (untuk future use)
$routes->post('upload/image', 'Upload::uploadImage');
$routes->post('upload/image-base64', 'Upload::uploadImageBase64');