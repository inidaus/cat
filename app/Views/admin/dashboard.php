<?= $this->include('layouts/header') ?>

<div class="glass-wrapper">

  <!-- Header Desktop -->
  <div class="d-none d-md-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="glass-title"><i class="fas fa-user-shield me-2"></i> Dashboard Admin</h2>
      <p class="glass-title mb-0" style="font-size: 1rem; opacity: 0.9;">
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
        <h2 class="glass-title"><i class="fas fa-user-shield me-2"></i> Dashboard Admin</h2>
        <p class="glass-title mb-0" style="font-size: 0.9rem; opacity: 0.9;">
          Selamat datang, <strong><?= session('nama_lengkap') ?></strong>
        </p>
      </div>
      <a href="<?= base_url('logout') ?>" class="btn btn-logout btn-sm">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
    <div class="text-center">
      <div id="clock-global-mobile" class="glass-title" style="font-size: 1rem;"></div>
    </div>
  </div>

<div class="row g-4">

  <div class="col-md-4">
    <div class="glass-card">
      <h4 class="mb-2"><i class="fas fa-user-graduate me-2"></i> Data Peserta</h4>
      <p>Kelola data peserta ujian</p>
      <a href="<?= base_url('admin/peserta') ?>" class="btn btn-primary btn-sm">Kelola</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="glass-card">
      <h4><i class="fas fa-chalkboard-teacher me-2"></i> Data Pembimbing</h4>
      <p>Kelola akun pembimbing</p>
      <a href="<?= base_url('admin/pembimbing') ?>" class="btn btn-primary btn-sm">Kelola</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="glass-card">
      <h4><i class="fas fa-layer-group me-2"></i> Kategori Soal</h4>
      <p>Manajemen mata pelajaran / kategori soal</p>
      <a href="<?= base_url('admin/kategori') ?>" class="btn btn-primary btn-sm">Kelola</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="glass-card">
      <h4><i class="fas fa-question me-2"></i> Bank Soal</h4>
      <p>Manajemen soal CAT</p>
      <a href="<?= base_url('admin/soal') ?>" class="btn btn-primary btn-sm">Kelola</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="glass-card">
      <h4><i class="fas fa-chart-bar me-2"></i> Hasil Ujian</h4>
      <p>Rekap nilai & performa peserta</p>
      <a href="<?= base_url('admin/laporan') ?>" class="btn btn-primary btn-sm">
        <i class="fas fa-chart-line me-1"></i> Lihat Laporan
      </a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="glass-card">
      <h4><i class="fas fa-cog me-2"></i> Setting</h4>
      <p>Pengaturan aplikasi & konfigurasi</p>
      <a href="<?= base_url('admin/setting') ?>" class="btn btn-primary btn-sm">Buka</a>
    </div>
  </div>

</div>
</div>

<?= $this->include('layouts/footer') ?>
