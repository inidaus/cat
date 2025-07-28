<?= $this->include('layouts/header') ?>

<div class="glass-wrapper">

  <!-- Header Desktop -->
  <div class="d-none d-md-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
        <i class="fas fa-chalkboard-teacher me-2"></i> Dashboard Pembimbing
      </h2>
      <p class="mb-0" style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7); font-size: 1rem; opacity: 0.9;">
        Selamat datang, <strong><?= session('nama_lengkap') ?></strong>
      </p>
    </div>
    <div class="d-flex align-items-center gap-3">
      <div id="clock-global"></div>
      <a href="<?= base_url('logout') ?>" class="btn btn-logout btn-sm">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </div>

  <!-- Header Mobile -->
  <div class="d-md-none mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <h2 style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
          <i class="fas fa-chalkboard-teacher me-2"></i> Dashboard Pembimbing
        </h2>
        <p class="mb-0" style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7); font-size: 0.9rem; opacity: 0.9;">
          Selamat datang, <strong><?= session('nama_lengkap') ?></strong>
        </p>
      </div>
      <a href="<?= base_url('logout') ?>" class="btn btn-logout btn-sm">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
    <div class="text-center">
      <div id="clock-global-mobile" style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7); font-size: 1rem;"></div>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <!-- Baris Pertama -->
    <div class="col-md-4">
      <div class="glass-card text-center">
        <h4><i class="fas fa-question-circle me-2 text-primary"></i> Bank Soal</h4>
        <p>Kelola soal ujian Anda</p>
        <a href="<?= base_url('pembimbing/soal') ?>" class="btn btn-primary btn-sm">
          <i class="fas fa-edit me-1"></i>Kelola Soal
        </a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="glass-card text-center">
        <h4><i class="fas fa-folder me-2 text-warning"></i> Kategori Soal</h4>
        <p>Kelola kategori soal ujian</p>
        <a href="<?= base_url('pembimbing/kategori') ?>" class="btn btn-warning btn-sm">
          <i class="fas fa-folder-open me-1"></i>Kelola Kategori
        </a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="glass-card text-center">
        <h4><i class="fas fa-tasks me-2 text-danger"></i> Ujian</h4>
        <p>Buat dan kelola ujian</p>
        <a href="<?= base_url('pembimbing/ujian') ?>" class="btn btn-danger btn-sm">
          <i class="fas fa-clipboard-list me-1"></i>Kelola Ujian
        </a>
      </div>
    </div>

    <!-- Baris Kedua -->
    <div class="col-md-4">
      <div class="glass-card text-center">
        <h4><i class="fas fa-users me-2 text-info"></i> Data Peserta</h4>
        <p>Kelola data peserta ujian</p>
        <a href="<?= base_url('pembimbing/peserta') ?>" class="btn btn-info btn-sm">
          <i class="fas fa-user-graduate me-1"></i>Kelola Peserta
        </a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="glass-card text-center">
        <h4><i class="fas fa-chart-bar me-2 text-success"></i> Hasil Ujian</h4>
        <p>Analisa hasil peserta</p>
        <a href="<?= base_url('pembimbing/laporan') ?>" class="btn btn-success btn-sm">
          <i class="fas fa-chart-line me-1"></i>Lihat Laporan
        </a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="glass-card text-center">
        <h4><i class="fas fa-user-check me-2 text-secondary"></i> Monitoring</h4>
        <p>Pantau perkembangan peserta</p>
        <a href="<?= base_url('pembimbing/monitoring') ?>" class="btn btn-secondary btn-sm">
          <i class="fas fa-eye me-1"></i>Pantau
        </a>
      </div>
    </div>
  </div>

  <div class="text-center text-white-50 mb-4">
    <p><i class="fas fa-info-circle"></i> Selamat datang di dashboard pembimbing. Silakan pilih menu di atas untuk mulai bekerja.</p>
  </div>

  <div id="quote-slider" class="carousel slide w-100" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active text-center text-white">
        <h5>"Pendidikan adalah senjata paling ampuh untuk mengubah dunia." <br><small>- Nelson Mandela</small></h5>
      </div>
      <div class="carousel-item text-center text-white">
        <h5>"Sukses adalah hasil dari persiapan, kerja keras, dan belajar dari kegagalan." <br><small>- Colin Powell</small></h5>
      </div>
      <div class="carousel-item text-center text-white">
        <h5>"Setiap anak adalah artis. Tantangannya adalah tetap menjadi artis ketika kita tumbuh dewasa." <br><small>- Pablo Picasso</small></h5>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#quote-slider" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#quote-slider" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

</div>

<?= $this->include('layouts/footer') ?>
