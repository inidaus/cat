<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home');
$routes->get('login', 'Auth::index');
$routes->post('login', 'Auth::login'); // POST = untuk proses login
$routes->get('logout', 'Auth::logout');

$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->get('pembimbing/dashboard', 'Pembimbing::dashboard');
// $routes->get('peserta/dashboard', 'Peserta::dashboard');

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
    $routes->post('setting/deleteAdmin/(:num)', 'Admin\Setting::deleteAdmin/$1');
    $routes->post('admin/setting/saveTimezone', 'Admin\Setting::saveTimezone');
    $routes->post('setting/saveTimezone', 'Admin\Setting::saveTimezone'); // <- Tambahkan ini

    $routes->post('peserta/update', 'Admin\Peserta::update');
    $routes->post('peserta/delete/(:num)', 'Admin\Peserta::delete/$1');

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
    
    $routes->get('ujian', 'Pembimbing\Ujian::index');
    $routes->post('ujian/save', 'Pembimbing\Ujian::save');
    $routes->get('ujian/create', 'Pembimbing\Ujian::create');
    $routes->get('ujian/edit/(:num)', 'Pembimbing\Ujian::edit/$1');
    $routes->post('ujian/update/(:num)', 'Pembimbing\Ujian::update/$1');
    $routes->post('ujian/delete/(:num)', 'Pembimbing\Ujian::delete/$1');
    $routes->get('ujian/peserta/(:num)', 'Pembimbing\Ujian::peserta/$1');
    $routes->post('ujian/savePeserta/(:num)', 'Pembimbing\Ujian::savePeserta/$1');

});

$routes->group('peserta', ['filter' => 'pesertaFilter'], function($routes) {
    $routes->get('dashboard', 'Peserta\Dashboard::index');
    $routes->get('ujian/(:num)', 'Peserta\Ujian::index/$1');
    $routes->post('ujian/simpan-jawaban', 'Peserta\Ujian::simpanJawaban');
    $routes->post('ujian/selesai', 'Peserta\Ujian::selesai');
});


