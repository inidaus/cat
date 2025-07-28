<?php echo $this->include('layouts/header'); ?>

<style>
/* Enhanced Dashboard Styling */
.dashboard-card {
  background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 15px;
  padding: 1.5rem;
  transition: all 0.3s ease;
  min-height: 300px; /* Ubah dari height: 100% ke min-height agar bisa expand */
}

.dashboard-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.2);
  border-color: rgba(255,255,255,0.3);
}

.card-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  margin-bottom: 1rem;
  color: white;
}

.icon-ujian { background: linear-gradient(135deg, #667eea, #764ba2); }
.icon-mendatang { background: linear-gradient(135deg, #f093fb, #f5576c); }
.icon-hasil { background: linear-gradient(135deg, #4facfe, #00f2fe); }

.ujian-item {
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 10px;
  padding: 1rem;
  margin-bottom: 0.75rem;
  transition: all 0.3s ease;
  position: relative;
  display: block;
  width: 100%;
}

.ujian-item:hover {
  background: rgba(255,255,255,0.15);
  transform: translateX(5px);
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

.btn-ujian {
  background: linear-gradient(135deg, #28a745, #20c997);
  border: none;
  border-radius: 25px;
  padding: 0.5rem 1.5rem;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-ujian:hover {
  transform: scale(1.05);
  box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
  color: white;
}

.btn-hasil {
  background: linear-gradient(135deg, #007bff, #6610f2);
  border: none;
  border-radius: 25px;
  padding: 0.5rem 1.5rem;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-hasil:hover {
  transform: scale(1.05);
  box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
  color: white;
}

/* Student Info Styling */
.student-info {
  display: flex;
  justify-content: center;
  gap: 20px;
  flex-wrap: wrap;
  margin-top: 15px;
}

.info-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 15px;
  padding: 12px 20px;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.info-card:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.info-card i {
  color: #74b9ff;
  font-size: 1.1rem;
}

.info-label {
  color: rgba(255, 255, 255, 0.8);
  font-size: 0.9rem;
  font-weight: 500;
}

.info-value {
  color: #ffffff;
  font-weight: 600;
  font-size: 1rem;
}

@media (max-width: 768px) {
  .student-info {
    flex-direction: column;
    align-items: center;
  }

  .info-card {
    width: 100%;
    max-width: 250px;
    justify-content: center;
  }
}

.statistik-item {
  text-align: center;
  padding: 1rem;
  background: rgba(255,255,255,0.1);
  border-radius: 10px;
  margin-bottom: 0.5rem;
}

.statistik-number {
  font-size: 2rem;
  font-weight: bold;
  color: #fff;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.statistik-label {
  font-size: 0.875rem;
  color: rgba(255,255,255,0.8);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
</style>

<div class="glass-wrapper">
  <!-- Header Desktop -->
  <div class="d-none d-md-flex justify-content-between align-items-center mb-4">
    <h2 style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
      <i class="fas fa-user-graduate me-2"></i> Dashboard Peserta
    </h2>
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
      <h2 style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
        <i class="fas fa-user-graduate me-2"></i> Dashboard Peserta
      </h2>
      <a href="<?= base_url('logout') ?>" class="btn btn-logout btn-sm">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
    <div class="text-center">
      <div id="clock-global-mobile" style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7); font-size: 1rem;"></div>
    </div>
  </div>

  <div class="text-center mb-4">
    <h4 class="text-white mb-2">Selamat Datang, <strong><?= session('nama_lengkap') ?></strong>!</h4>
    <div class="student-info">
      <div class="info-card">
        <i class="fas fa-id-card me-2"></i>
        <span class="info-label">NIM:</span>
        <span class="info-value"><?= $data_peserta['username'] ?? session('username') ?></span>
      </div>
      <?php if (!empty($data_peserta['angkatan'])): ?>
      <div class="info-card">
        <i class="fas fa-graduation-cap me-2"></i>
        <span class="info-label">Angkatan:</span>
        <span class="info-value"><?= $data_peserta['angkatan'] ?></span>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <!-- Ujian Aktif -->
    <div class="col-lg-4 col-md-6">
      <div class="dashboard-card">
        <div class="card-icon icon-ujian mx-auto">
          <i class="fas fa-play-circle"></i>
        </div>
        <h4 class="text-white text-center mb-3">Ujian Aktif</h4>

        <?php if (!empty($ujian_aktif)): ?>
          <?php foreach ($ujian_aktif as $u): ?>
            <div class="ujian-item">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <h6 class="text-white mb-1"><?= esc($u['judul']) ?></h6>
                  <small class="text-white-50">
                    <i class="fas fa-folder me-1"></i><?= esc(isset($u['nama_kategori_multiple']) ? $u['nama_kategori_multiple'] : $u['nama_kategori']) ?>
                  </small>
                </div>
                <span class="status-badge status-aktif">
                  <i class="fas fa-circle me-1"></i>Aktif
                </span>
              </div>
              <div class="mb-2">
                <small class="text-white-50">
                  <i class="fas fa-clock me-1"></i>
                  <?php
                    $waktuMulai = strtotime($u['mulai']);
                    $waktuSelesai = $waktuMulai + ($u['waktu_menit'] * 60);
                  ?>
                  <?= date('d M Y, H:i', $waktuMulai) ?> - <?= date('H:i', $waktuSelesai) ?>
                </small>
              </div>
              <div class="text-center">
                <a href="<?= base_url('peserta/ujian/' . $u['id']) ?>" class="btn btn-ujian">
                  <i class="fas fa-play me-1"></i>Mulai Ujian
                </a>
              </div>
            </div>
          <?php endforeach ?>
        <?php else: ?>
          <div class="text-center py-4">
            <i class="fas fa-info-circle text-white-50 mb-2" style="font-size: 2rem;"></i>
            <p class="text-white-50 mb-0">Tidak ada ujian aktif saat ini</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Ujian Mendatang -->
    <div class="col-lg-4 col-md-6">
      <div class="dashboard-card">
        <div class="card-icon icon-mendatang mx-auto">
          <i class="fas fa-calendar-alt"></i>
        </div>
        <h4 class="text-white text-center mb-3">Ujian Mendatang</h4>



        <?php if (!empty($ujian_mendatang)): ?>
          <?php foreach ($ujian_mendatang as $u): ?>

            <div class="ujian-item">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <h6 class="text-white mb-1"><?= esc($u['judul']) ?></h6>
                  <small class="text-white-50">
                    <i class="fas fa-folder me-1"></i><?= esc(isset($u['nama_kategori_multiple']) ? $u['nama_kategori_multiple'] : $u['nama_kategori']) ?>
                  </small>
                </div>
                <span class="status-badge status-mendatang">
                  <i class="fas fa-clock me-1"></i>Mendatang
                </span>
              </div>
              <div class="mb-2">
                <small class="text-white-50">
                  <i class="fas fa-calendar me-1"></i>
                  <?= date('d M Y, H:i', strtotime($u['mulai'])) ?>
                </small>
              </div>
              <div class="mb-2">
                <small class="text-white-50">
                  <i class="fas fa-hourglass-half me-1"></i>
                  Durasi: <?= $u['waktu_menit'] ?> menit
                </small>
              </div>
            </div>
          <?php endforeach ?>
        <?php else: ?>
          <div class="text-center py-4">
            <i class="fas fa-calendar-times text-white-50 mb-2" style="font-size: 2rem;"></i>
            <p class="text-white-50 mb-0">Tidak ada ujian mendatang</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Hasil Ujian -->
    <div class="col-lg-4 col-md-12">
      <div class="dashboard-card">
        <div class="card-icon icon-hasil mx-auto">
          <i class="fas fa-chart-line"></i>
        </div>
        <h4 class="text-white text-center mb-3">Hasil Ujian</h4>

        <?php if (!empty($statistik_hasil['hasil_ujian'])): ?>
          <!-- Statistik Singkat -->
          <div class="row mb-4">
            <div class="col-6">
              <div class="statistik-item">
                <div class="statistik-number"><?= $statistik_hasil['total_ujian'] ?></div>
                <div class="statistik-label">Total Ujian</div>
              </div>
            </div>
            <div class="col-6">
              <div class="statistik-item">
                <div class="statistik-number"><?= $statistik_hasil['rata_rata'] ?>%</div>
                <div class="statistik-label">Rata-rata</div>
              </div>
            </div>
          </div>

          <!-- Tombol Lihat Detail -->
          <div class="text-center">
            <a href="<?= base_url('peserta/hasil') ?>" class="btn btn-hasil btn-lg">
              <i class="fas fa-chart-line me-2"></i>Lihat Semua Hasil
            </a>
          </div>
        <?php else: ?>
          <div class="text-center py-4">
            <i class="fas fa-chart-bar text-white-50 mb-2" style="font-size: 2rem;"></i>
            <p class="text-white-50 mb-0">Belum ada hasil ujian</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>



<?= $this->include('layouts/footer'); ?>
