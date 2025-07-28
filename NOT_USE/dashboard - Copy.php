<?= $this->include('layouts/header') ?>

<div class="glass-wrapper">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
      <i class="fas fa-user-graduate me-2"></i> Dashboard Peserta
    </h2>
    <a href="<?= base_url('/logout') ?>" class="btn btn-logout btn-sm">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>

  <div class="row g-4">
    <div class="col-md-6">
      <div class="glass-card">
        <h4><i class="fas fa-book-open me-2"></i> Ujian</h4>
        <p>Kerjakan ujian yang tersedia</p>
        <a href="#" class="btn btn-success btn-sm">Mulai Ujian</a>
      </div>
    </div>
    <div class="col-md-6">
      <div class="glass-card">
        <h4><i class="fas fa-file-alt me-2"></i> Hasil Ujian</h4>
        <p>Lihat nilai dan status ujian</p>
        <a href="#" class="btn btn-info btn-sm">Lihat Hasil</a>
      </div>
    </div>
  </div>

</div>

<?= $this->include('layouts/footer') ?>
