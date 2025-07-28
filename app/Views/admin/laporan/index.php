<?php echo $this->include('layouts/header'); ?>

<style>
.laporan-card {
  background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 15px;
  padding: 1.5rem;
  transition: all 0.3s ease;
  margin-bottom: 1rem;
}

.laporan-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.2);
  border-color: rgba(255,255,255,0.3);
}

.statistik-item {
  text-align: center;
  padding: 0.75rem;
  background: rgba(255,255,255,0.1);
  border-radius: 8px;
  margin-bottom: 0.5rem;
}

.statistik-number {
  font-size: 1.5rem;
  font-weight: bold;
  color: #fff;
  text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.statistik-label {
  font-size: 0.75rem;
  color: rgba(255,255,255,0.8);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.btn-laporan {
  background: linear-gradient(135deg, #28a745, #20c997);
  border: none;
  border-radius: 25px;
  padding: 0.5rem 1.5rem;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-laporan:hover {
  transform: scale(1.05);
  box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
  color: white;
}

.btn-export {
  background: linear-gradient(135deg, #17a2b8, #138496);
  border: none;
  border-radius: 25px;
  padding: 0.4rem 1rem;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
  font-size: 0.875rem;
}

.btn-export:hover {
  transform: scale(1.05);
  color: white;
}

.pembimbing-info {
  background: rgba(255,255,255,0.1);
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.85rem;
  color: rgba(255,255,255,0.9);
  margin-bottom: 0.5rem;
}
</style>

<div class="glass-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
      <i class="fas fa-chart-bar me-2"></i> Hasil Ujian (Admin)
    </h2>
    <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-light btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <?php if (empty($ujian_list)): ?>
    <div class="laporan-card text-center">
      <i class="fas fa-chart-line text-white-50 mb-3" style="font-size: 3rem;"></i>
      <h4 class="text-white mb-2">Belum Ada Laporan</h4>
      <p class="text-white-50 mb-3">Belum ada ujian yang dibuat oleh pembimbing</p>
    </div>
  <?php else: ?>
    <div class="row">
      <?php foreach ($ujian_list as $ujian): ?>
        <div class="col-lg-6 col-md-12 mb-4">
          <div class="laporan-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="flex-grow-1">
                <h5 class="text-white mb-1"><?= esc($ujian['judul']) ?></h5>
                <div class="pembimbing-info">
                  <i class="fas fa-user-tie me-1"></i>
                  Pembimbing: <?= esc($ujian['nama_pembimbing']) ?>
                </div>
                <small class="text-white-50">
                  <i class="fas fa-calendar me-1"></i>
                  <?= date('d M Y, H:i', strtotime($ujian['mulai'])) ?>
                </small>
              </div>
              <a href="<?= base_url('admin/laporan/export/' . $ujian['id']) ?>" 
                 class="btn-export" title="Export CSV">
                <i class="fas fa-download me-1"></i> Export
              </a>
            </div>

            <!-- Statistik Singkat -->
            <div class="row mb-3">
              <div class="col-4">
                <div class="statistik-item">
                  <div class="statistik-number"><?= $ujian['total_peserta'] ?></div>
                  <div class="statistik-label">Total Peserta</div>
                </div>
              </div>
              <div class="col-4">
                <div class="statistik-item">
                  <div class="statistik-number"><?= $ujian['peserta_selesai'] ?></div>
                  <div class="statistik-label">Selesai</div>
                </div>
              </div>
              <div class="col-4">
                <div class="statistik-item">
                  <div class="statistik-number"><?= $ujian['rata_rata'] ?>%</div>
                  <div class="statistik-label">Rata-rata</div>
                </div>
              </div>
            </div>

            <!-- Info Tambahan -->
            <div class="row mb-3">
              <div class="col-6">
                <small class="text-white-50">Durasi</small>
                <div class="text-white">
                  <i class="fas fa-clock me-1"></i><?= $ujian['waktu_menit'] ?> menit
                </div>
              </div>
              <div class="col-6">
                <small class="text-white-50">Passing Grade</small>
                <div class="text-white">
                  <i class="fas fa-target me-1"></i><?= $ujian['passing_grade'] ?>%
                </div>
              </div>
            </div>

            <div class="text-center">
              <a href="<?= base_url('admin/laporan/detail/' . $ujian['id']) ?>" 
                 class="btn btn-laporan">
                <i class="fas fa-chart-line me-1"></i>Lihat Detail
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?= $this->include('layouts/footer'); ?>
