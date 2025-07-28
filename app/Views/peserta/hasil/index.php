<?= $this->include('layouts/header') ?>

<style>
.hasil-card {
  background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 15px;
  padding: 1.5rem;
  transition: all 0.3s ease;
  margin-bottom: 1rem;
}

.hasil-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
  border-color: rgba(255,255,255,0.3);
}

.statistik-card {
  background: linear-gradient(135deg, #667eea, #764ba2);
  border-radius: 15px;
  padding: 1.5rem;
  color: white;
  text-align: center;
  margin-bottom: 1rem;
}

.stat-number {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.stat-label {
  font-size: 0.875rem;
  opacity: 0.9;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.nilai-badge {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.1rem;
  color: white;
}

.nilai-lulus { background: linear-gradient(135deg, #28a745, #20c997); }
.nilai-tidak-lulus { background: linear-gradient(135deg, #dc3545, #fd7e14); }

.btn-detail {
  background: linear-gradient(135deg, #007bff, #6610f2);
  border: none;
  border-radius: 25px;
  padding: 0.5rem 1rem;
  color: white;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-detail:hover {
  transform: scale(1.05);
  box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
  color: white;
}

.btn-sertifikat {
  background: linear-gradient(135deg, #28a745, #20c997);
  border: none;
  border-radius: 25px;
  padding: 0.5rem 1rem;
  color: white;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-sertifikat:hover {
  transform: scale(1.05);
  box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
  color: white;
}
</style>

<div class="glass-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">
      <i class="fas fa-chart-line me-2"></i>Hasil Ujian
    </h2>
    <a href="<?= base_url('peserta/dashboard') ?>" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
  </div>

  <!-- Statistik -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="statistik-card">
        <div class="stat-number"><?= $statistik['total_ujian'] ?></div>
        <div class="stat-label">Total Ujian</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="statistik-card">
        <div class="stat-number"><?= $statistik['lulus'] ?></div>
        <div class="stat-label">Lulus</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="statistik-card">
        <div class="stat-number"><?= $statistik['rata_rata'] ?>%</div>
        <div class="stat-label">Rata-rata</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="statistik-card">
        <div class="stat-number"><?= $statistik['tertinggi'] ?>%</div>
        <div class="stat-label">Tertinggi</div>
      </div>
    </div>
  </div>

  <!-- Daftar Hasil Ujian -->
  <div class="row">
    <div class="col-12">
      <?php if (!empty($hasil_ujian)): ?>
        <?php foreach ($hasil_ujian as $hasil): ?>
          <div class="hasil-card">
            <div class="row align-items-center">
              <div class="col-md-1">
                <?php
                // Gunakan data yang sudah dihitung dari controller
                $isLulus = $hasil['is_lulus'] ?? false;
                ?>
                <div class="nilai-badge <?= $isLulus ? 'nilai-lulus' : 'nilai-tidak-lulus' ?>">
                  <?= $hasil['nilai'] ?>
                </div>
              </div>
              <div class="col-md-6">
                <h5 class="text-white mb-1"><?= esc($hasil['judul']) ?></h5>
                <p class="text-white-50 mb-1">
                  <i class="fas fa-folder me-1"></i><?= esc(isset($hasil['nama_kategori_multiple']) ? $hasil['nama_kategori_multiple'] : $hasil['nama_kategori']) ?>
                </p>
                <small class="text-white-50">
                  <i class="fas fa-calendar me-1"></i>
                  <?php if (!empty($hasil['selesai']) && $hasil['selesai'] !== '0000-00-00 00:00:00'): ?>
                    <?= date('d M Y, H:i', strtotime($hasil['selesai'])) ?>
                  <?php else: ?>
                    Tidak tersedia
                  <?php endif; ?>
                </small>
              </div>
              <div class="col-md-2">
                <div class="text-center">
                  <div class="text-white-50 small">Durasi</div>
                  <div class="text-white"><?= $hasil['durasi'] ?> menit</div>
                </div>
              </div>
              <div class="col-md-1">
                <div class="text-center">
                  <div class="text-white-50 small">Passing</div>
                  <div class="text-white"><?= $hasil['passing_grade'] ?? 70 ?>%</div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="text-center">
                  <span class="badge bg-<?= $isLulus ? 'success' : 'danger' ?> px-3 py-2">
                    <?= $isLulus ? 'LULUS' : 'TIDAK LULUS' ?>
                  </span>
                </div>
              </div>
              <div class="col-md-1">
                <div class="d-flex flex-column gap-1">
                  <a href="<?= base_url('peserta/hasil/detail/' . $hasil['ujian_id']) ?>" 
                     class="btn btn-detail btn-sm" title="Lihat Detail">
                    <i class="fas fa-eye"></i>
                  </a>
                  <?php if ($isLulus): ?>
                    <a href="<?= base_url('peserta/hasil/sertifikat/' . $hasil['ujian_id']) ?>"
                       class="btn btn-sertifikat btn-sm" title="Sertifikat" target="_blank">
                      <i class="fas fa-certificate"></i>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="text-center py-5">
          <i class="fas fa-chart-bar text-white-50 mb-3" style="font-size: 4rem;"></i>
          <h4 class="text-white-50">Belum Ada Hasil Ujian</h4>
          <p class="text-white-50">Anda belum mengikuti ujian apapun.</p>
          <a href="<?= base_url('peserta/dashboard') ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Dashboard
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?= $this->include('layouts/footer') ?>
