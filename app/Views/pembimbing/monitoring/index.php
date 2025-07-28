<?php echo $this->include('layouts/header'); ?>

<style>
.monitoring-card {
  background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 12px;
  padding: 1rem;
  transition: all 0.3s ease;
  margin-bottom: 0.75rem;
  height: 200px;
}

.monitoring-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.2);
  border-color: rgba(255,255,255,0.3);
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.status-aktif { background: #28a745; color: white; }
.status-selesai { background: #6c757d; color: white; }
.status-mendatang { background: #ffc107; color: #212529; }

.btn-monitor {
  background: linear-gradient(135deg, #667eea, #764ba2);
  border: none;
  border-radius: 25px;
  padding: 0.5rem 1.5rem;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-monitor:hover {
  transform: scale(1.05);
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
  color: white;
}
</style>

<div class="glass-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
      <i class="fas fa-desktop me-2"></i> Monitoring Ujian
    </h2>
    <a href="<?= base_url('pembimbing/dashboard') ?>" class="btn btn-outline-light btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <?php if (empty($ujian_list)): ?>
    <div class="monitoring-card text-center">
      <i class="fas fa-info-circle text-white-50 mb-3" style="font-size: 3rem;"></i>
      <h4 class="text-white mb-2">Belum Ada Ujian</h4>
      <p class="text-white-50 mb-3">Anda belum membuat ujian apapun untuk dimonitor</p>
      <a href="<?= base_url('pembimbing/ujian/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Buat Ujian Baru
      </a>
    </div>
  <?php else: ?>
    <div class="row">
      <?php foreach ($ujian_list as $ujian): ?>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
          <div class="monitoring-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div class="flex-grow-1">
                <h6 class="text-white mb-1" style="font-size: 0.9rem;"><?= esc($ujian['judul']) ?></h6>
                <small class="text-white-50" style="font-size: 0.75rem;">
                  <i class="fas fa-calendar me-1"></i>
                  <?= date('d/m H:i', strtotime($ujian['mulai'])) ?>
                </small>
              </div>
              <?php
                $now = time();
                $mulai = strtotime($ujian['mulai']);
                $selesai = $mulai + ($ujian['waktu_menit'] * 60) + ($ujian['toleransi_menit'] * 60);

                // Cek status berdasarkan waktu dan status ujian
                if ($ujian['status'] == 0) { // Status ujian tidak aktif
                  $status = 'selesai';
                  $statusText = 'Selesai';
                } elseif ($now < $mulai) {
                  $status = 'mendatang';
                  $statusText = 'Mendatang';
                } elseif ($now >= $mulai && $now <= $selesai) {
                  $status = 'aktif';
                  $statusText = 'Aktif';
                } else {
                  $status = 'selesai';
                  $statusText = 'Selesai';
                }
              ?>
              <span class="status-badge status-<?= $status ?>">
                <i class="fas fa-circle me-1"></i><?= $statusText ?>
              </span>
            </div>

            <div class="row mb-2">
              <div class="col-6">
                <small class="text-white-50" style="font-size: 0.7rem;">Durasi</small>
                <div class="text-white" style="font-size: 0.8rem;">
                  <i class="fas fa-clock me-1"></i><?= $ujian['waktu_menit'] ?>m
                </div>
              </div>
              <div class="col-6">
                <small class="text-white-50" style="font-size: 0.7rem;">Passing</small>
                <div class="text-white" style="font-size: 0.8rem;">
                  <i class="fas fa-target me-1"></i><?= $ujian['passing_grade'] ?>%
                </div>
              </div>
            </div>

            <div class="text-center">
              <a href="<?= base_url('pembimbing/monitoring/detail/' . $ujian['id']) ?>"
                 class="btn btn-monitor btn-sm">
                <i class="fas fa-eye me-1"></i>Monitor
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?= $this->include('layouts/footer'); ?>
