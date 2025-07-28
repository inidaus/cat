<?= $this->include('layouts/header') ?>

<style>
.detail-card {
  background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 15px;
  padding: 2rem;
  margin-bottom: 1.5rem;
}

.info-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 0;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}

.info-item:last-child {
  border-bottom: none;
}

.info-label {
  color: rgba(255,255,255,0.8);
  font-weight: 500;
}

.info-value {
  color: white;
  font-weight: 600;
}

.score-circle {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  font-weight: bold;
  color: white;
  margin: 0 auto 1rem;
}

.score-lulus {
  background: linear-gradient(135deg, #28a745, #20c997);
  box-shadow: 0 0 30px rgba(40, 167, 69, 0.3);
}

.score-tidak-lulus {
  background: linear-gradient(135deg, #dc3545, #fd7e14);
  box-shadow: 0 0 30px rgba(220, 53, 69, 0.3);
}

.status-badge {
  padding: 0.5rem 1.5rem;
  border-radius: 25px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.badge-lulus {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
}

.badge-tidak-lulus {
  background: linear-gradient(135deg, #dc3545, #fd7e14);
  color: white;
}

.progress-bar-custom {
  height: 8px;
  border-radius: 4px;
  background: rgba(255,255,255,0.2);
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  border-radius: 4px;
  transition: width 0.3s ease;
}

.progress-lulus {
  background: linear-gradient(90deg, #28a745, #20c997);
}

.progress-tidak-lulus {
  background: linear-gradient(90deg, #dc3545, #fd7e14);
}

.btn-back {
  background: linear-gradient(135deg, #6c757d, #495057);
  border: none;
  border-radius: 25px;
  padding: 0.75rem 1.5rem;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-back:hover {
  transform: scale(1.05);
  box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
  color: white;
}

.btn-sertifikat {
  background: linear-gradient(135deg, #28a745, #20c997);
  border: none;
  border-radius: 25px;
  padding: 0.75rem 1.5rem;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-sertifikat:hover {
  transform: scale(1.05);
  box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
  color: white;
}

/* Progress Bar Animated */
.progress-bar-animated {
  width: 100%;
  height: 20px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 10px;
  overflow: hidden;
  position: relative;
}

.progress-fill-animated {
  height: 100%;
  width: 0%;
  border-radius: 10px;
  position: relative;
  transition: width 2s ease-in-out;
  animation: progressShine 2s infinite;
}

.progress-fill-animated.progress-lulus {
  background: linear-gradient(90deg, #28a745, #20c997);
}

.progress-fill-animated.progress-tidak-lulus {
  background: linear-gradient(90deg, #dc3545, #c82333);
}

@keyframes progressShine {
  0% {
    box-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
  }
  50% {
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.6);
  }
  100% {
    box-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
  }
}

/* Progress Bar Pulse Animation */
.progress-fill-animated::after {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
  animation: progressSlide 3s infinite;
}

@keyframes progressSlide {
  0% {
    left: -100%;
  }
  100% {
    left: 100%;
  }
}
</style>

<div class="glass-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">
      <i class="fas fa-chart-line me-2"></i>Detail Hasil Ujian
    </h2>
    <a href="<?= base_url('peserta/hasil') ?>" class="btn btn-back">
      <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
  </div>

  <div class="row">
    <!-- Informasi Ujian -->
    <div class="col-md-8">
      <div class="detail-card">
        <h4 class="text-white mb-4">
          <i class="fas fa-info-circle me-2"></i>Informasi Ujian
        </h4>
        
        <div class="info-item">
          <span class="info-label">
            <i class="fas fa-book me-2"></i>Judul Ujian
          </span>
          <span class="info-value"><?= esc($detail['judul']) ?></span>
        </div>
        
        <div class="info-item">
          <span class="info-label">
            <i class="fas fa-folder me-2"></i>Mata Pelajaran
          </span>
          <span class="info-value"><?= esc(isset($detail['nama_kategori_multiple']) ? $detail['nama_kategori_multiple'] : $detail['nama_kategori']) ?></span>
        </div>
        
        <div class="info-item">
          <span class="info-label">
            <i class="fas fa-clock me-2"></i>Durasi
          </span>
          <span class="info-value"><?= $detail['durasi'] ?> menit</span>
        </div>

        <?php if (isset($detail['kategori_data_parsed']) && is_array($detail['kategori_data_parsed'])): ?>
        <div class="info-item">
          <span class="info-label">
            <i class="fas fa-list me-2"></i>Detail Mata Pelajaran
          </span>
          <div class="info-value">
            <?php foreach ($detail['kategori_data_parsed'] as $index => $kd): ?>
              <?php
                $kategoriModel = new \App\Models\KategoriModel();
                $kategori = $kategoriModel->find($kd['kategori_id']);
                $namaKategori = $kategori ? $kategori['nama_kategori'] : 'Unknown';
              ?>
              <div class="mb-2 p-2 bg-dark bg-opacity-25 rounded">
                <strong><?= esc($namaKategori) ?></strong><br>
                <small class="text-white-50">
                  <?= $kd['jumlah_soal'] ?> soal •
                  Passing Grade: <?= $kd['passing_grade'] ?>% •
                  <?= $kd['waktu_menit'] ?> menit
                </small>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($detail['hasil_per_mata_pelajaran'])): ?>
        <div class="info-item">
          <span class="info-label">
            <i class="fas fa-chart-bar me-2"></i>Hasil per Mata Pelajaran
          </span>
          <div class="info-value">
            <?php foreach ($detail['hasil_per_mata_pelajaran'] as $hasil): ?>
              <div class="mb-3 p-3 bg-dark bg-opacity-25 rounded">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <strong class="text-white"><?= esc($hasil['nama_kategori']) ?></strong>
                  <span class="badge bg-<?= $hasil['status'] === 'LULUS' ? 'success' : 'danger' ?> px-2 py-1">
                    <?= $hasil['status'] ?>
                  </span>
                </div>
                <div class="row text-center">
                  <div class="col-3">
                    <div class="text-white-50 small">Jumlah Soal</div>
                    <div class="text-white fw-bold"><?= $hasil['jumlah_soal'] ?></div>
                  </div>
                  <div class="col-2">
                    <div class="text-success small">Benar</div>
                    <div class="text-success fw-bold"><?= $hasil['benar'] ?></div>
                  </div>
                  <div class="col-2">
                    <div class="text-danger small">Salah</div>
                    <div class="text-danger fw-bold"><?= $hasil['salah'] ?></div>
                  </div>
                  <div class="col-2">
                    <div class="text-warning small">Kosong</div>
                    <div class="text-warning fw-bold"><?= $hasil['tidak_dijawab'] ?></div>
                  </div>
                  <div class="col-3">
                    <div class="text-white-50 small">Grade</div>
                    <div class="text-white fw-bold"><?= $hasil['grade'] ?>%</div>
                  </div>
                </div>
                <div class="mt-2">
                  <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-<?= $hasil['grade'] >= $hasil['passing_grade'] ? 'success' : 'danger' ?>"
                         style="width: <?= $hasil['grade'] ?>%"></div>
                  </div>
                  <small class="text-white-50">Passing Grade: <?= $hasil['passing_grade'] ?>%</small>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="info-item">
          <span class="info-label">
            <i class="fas fa-calendar-alt me-2"></i>Tanggal Ujian
          </span>
          <span class="info-value">
            <?php if (!empty($detail['mulai']) && $detail['mulai'] !== '0000-00-00 00:00:00'): ?>
              <?= date('d M Y, H:i', strtotime($detail['mulai'])) ?>
            <?php else: ?>
              <span class="text-muted">Tidak tersedia</span>
            <?php endif; ?>
          </span>
        </div>

        <div class="info-item">
          <span class="info-label">
            <i class="fas fa-hourglass-end me-2"></i>Waktu Selesai
          </span>
          <span class="info-value">
            <?php if (!empty($detail['selesai']) && $detail['selesai'] !== '0000-00-00 00:00:00'): ?>
              <?= date('d M Y, H:i', strtotime($detail['selesai'])) ?>
            <?php else: ?>
              <span class="text-muted">Tidak tersedia</span>
            <?php endif; ?>
          </span>
        </div>
        


        <div class="info-item">
          <span class="info-label">
            <i class="fas fa-target me-2"></i>Passing Grade
          </span>
          <span class="info-value"><?= $detail['passing_grade'] ?? 70 ?>%</span>
        </div>
      </div>
    </div>

    <!-- Hasil dan Status -->
    <div class="col-md-4">
      <div class="detail-card text-center">
        <h4 class="text-white mb-4">
          <i class="fas fa-trophy me-2"></i>Hasil Ujian
        </h4>
        
        <!-- Score Circle -->
        <?php
        $passingGrade = $detail['passing_grade'] ?? 70;

        // PERBAIKAN: Gunakan data yang sudah diperbaiki dari controller
        $rataRataPencapaian = $detail['rata_rata_pencapaian'] ?? $detail['nilai'];
        $isLulus = $detail['is_lulus'] ?? false;
        ?>
        <div class="score-circle <?= $isLulus ? 'score-lulus' : 'score-tidak-lulus' ?>">
          <?= $detail['nilai'] ?>
        </div>

        <!-- Status Badge -->
        <div class="mb-4">
          <span class="status-badge <?= $isLulus ? 'badge-lulus' : 'badge-tidak-lulus' ?>">
            <?= $isLulus ? 'LULUS' : 'TIDAK LULUS' ?>
          </span>
        </div>



        <!-- Passing Grade Total -->
        <div class="mb-3">
          <div class="text-center">
            <div class="text-white-50 small">Passing Grade Total</div>
            <div class="text-white h6"><?= $passingGrade ?>%</div>
          </div>
        </div>

        <!-- Summary Passing Grade per Mata Pelajaran -->
        <?php if (!empty($detail['hasil_per_mata_pelajaran'])): ?>
        <div class="mb-3">
          <div class="text-center">
            <div class="text-white-50 small mb-2">Hasil per Mata Pelajaran</div>
            <?php
            $totalGrade = 0;
            $jumlahMapel = count($detail['hasil_per_mata_pelajaran']);
            ?>
            <?php foreach ($detail['hasil_per_mata_pelajaran'] as $hasil): ?>
              <?php
              $totalGrade += $hasil['grade']; // PERBAIKAN: Langsung gunakan grade
              ?>
              <div class="d-flex justify-content-between text-white-50 small mb-1">
                <span><strong><?= esc($hasil['nama_kategori']) ?>:</strong></span>
                <span><?= $hasil['grade'] ?>% (PG: <?= $hasil['passing_grade'] ?>%)</span>
              </div>
            <?php endforeach; ?>
            <hr class="my-2 border-secondary">
            <div class="text-white small">
              <strong>Rata-rata: <?= $jumlahMapel > 0 ? round($totalGrade / $jumlahMapel, 2) : 0 ?>%</strong>
            </div>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Progress Bar dengan Animasi -->
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-2">
            <span class="text-white-50 small">Progress Nilai</span>
            <span class="text-white small"><strong><?= round($rataRataPencapaian, 0) ?>% / <?= $passingGrade ?>%</strong></span>
          </div>
          <div class="progress-bar-animated">
            <div class="progress-fill-animated <?= $isLulus ? 'progress-lulus' : 'progress-tidak-lulus' ?>"
                 data-width="<?= min(100, ($rataRataPencapaian / max(1, $passingGrade)) * 100) ?>%"></div>
          </div>
        </div>
        
        <!-- Action Buttons -->
        <?php if ($isLulus): ?>
          <div class="text-center mb-3">
            <div class="text-white-50 small mb-2">
              <i class="fas fa-check-circle me-1"></i>
              Sertifikat tersedia
            </div>
            <button class="btn btn-sertifikat w-100" onclick="showSertifikatNotification()">
              <i class="fas fa-certificate me-1"></i>Download Sertifikat
            </button>
          </div>
        <?php else: ?>
          <div class="text-center mb-3">
            <div class="text-white-50 small mb-2">
              <i class="fas fa-times-circle me-1"></i>
              Sertifikat tidak tersedia
            </div>
            <button class="btn btn-sertifikat w-100" disabled style="opacity: 0.5; cursor: not-allowed;">
              <i class="fas fa-certificate me-1"></i>Download Sertifikat
            </button>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
// Animate progress bar on page load
document.addEventListener('DOMContentLoaded', function() {
  const progressBar = document.querySelector('.progress-fill-animated');
  if (progressBar) {
    const targetWidth = progressBar.getAttribute('data-width');
    setTimeout(() => {
      progressBar.style.width = targetWidth;
    }, 500);
  }
});

// Beautiful notification for certificate download
function showSertifikatNotification() {
  // Create notification overlay
  const notificationOverlay = document.createElement('div');
  notificationOverlay.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.95), rgba(32, 201, 151, 0.95));
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    backdrop-filter: blur(15px);
    opacity: 0;
    transition: opacity 0.3s ease;
  `;

  notificationOverlay.innerHTML = `
    <div style="
      background: white;
      border-radius: 25px;
      padding: 50px;
      max-width: 600px;
      width: 90%;
      text-align: center;
      box-shadow: 0 25px 80px rgba(0,0,0,0.3);
      transform: scale(0.8);
      opacity: 0;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      position: relative;
      overflow: hidden;
    " id="sertifikat-notification">

      <!-- Background Animation -->
      <div style="
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(40, 167, 69, 0.1), transparent);
        animation: rotateBackground 3s linear infinite;
        pointer-events: none;
      "></div>

      <!-- Certificate Icon with Animation -->
      <div style="
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #28a745, #20c997);
        border-radius: 50%;
        margin: 0 auto 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: certificatePulse 2s ease-in-out infinite;
        position: relative;
        z-index: 1;
      ">
        <i class="fas fa-certificate" style="color: white; font-size: 4rem; animation: certificateRotate 4s linear infinite;"></i>
      </div>

      <!-- Title -->
      <h2 style="
        color: #2c3e50;
        margin-bottom: 20px;
        font-weight: 700;
        font-size: 2rem;
        position: relative;
        z-index: 1;
      ">
        🎉 Selamat!
      </h2>

      <!-- Message -->
      <p style="
        color: #6c757d;
        font-size: 1.2rem;
        margin-bottom: 30px;
        line-height: 1.6;
        position: relative;
        z-index: 1;
      ">
        Anda telah <strong>LULUS</strong> ujian ini!<br>
        Fitur download sertifikat akan segera tersedia.
      </p>

      <!-- Features Coming Soon -->
      <div style="
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        position: relative;
        z-index: 1;
      ">
        <h4 style="color: #495057; margin-bottom: 15px; font-size: 1.1rem;">
          🚀 Fitur yang Akan Datang:
        </h4>
        <div style="text-align: left; color: #6c757d;">
          <div style="margin-bottom: 8px;">
            <i class="fas fa-check-circle" style="color: #28a745; margin-right: 10px;"></i>
            Sertifikat PDF dengan desain profesional
          </div>
          <div style="margin-bottom: 8px;">
            <i class="fas fa-check-circle" style="color: #28a745; margin-right: 10px;"></i>
            QR Code untuk verifikasi keaslian
          </div>
          <div style="margin-bottom: 8px;">
            <i class="fas fa-check-circle" style="color: #28a745; margin-right: 10px;"></i>
            Tanda tangan digital otomatis
          </div>
          <div>
            <i class="fas fa-check-circle" style="color: #28a745; margin-right: 10px;"></i>
            Integrasi dengan LinkedIn dan media sosial
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div style="display: flex; gap: 15px; justify-content: center; position: relative; z-index: 1;">
        <button onclick="closeSertifikatNotification()" style="
          background: linear-gradient(135deg, #28a745, #20c997);
          color: white;
          border: none;
          padding: 15px 35px;
          border-radius: 30px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
          box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
          font-size: 1rem;
        " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 35px rgba(40, 167, 69, 0.4)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(40, 167, 69, 0.3)'">
          <i class="fas fa-thumbs-up me-2"></i>Mengerti
        </button>

        <button onclick="shareAchievement()" style="
          background: linear-gradient(135deg, #007bff, #0056b3);
          color: white;
          border: none;
          padding: 15px 35px;
          border-radius: 30px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
          box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
          font-size: 1rem;
        " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 35px rgba(0, 123, 255, 0.4)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(0, 123, 255, 0.3)'">
          <i class="fas fa-share me-2"></i>Bagikan
        </button>
      </div>
    </div>
  `;

  document.body.appendChild(notificationOverlay);

  // Add CSS animations
  const style = document.createElement('style');
  style.textContent = `
    @keyframes certificatePulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    @keyframes certificateRotate {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @keyframes rotateBackground {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  `;
  document.head.appendChild(style);

  // Animate notification in
  setTimeout(() => {
    notificationOverlay.style.opacity = '1';
    const modal = document.getElementById('sertifikat-notification');
    modal.style.transform = 'scale(1)';
    modal.style.opacity = '1';
  }, 100);

  // Global functions for buttons
  window.closeSertifikatNotification = function() {
    notificationOverlay.style.opacity = '0';
    setTimeout(() => {
      document.body.removeChild(notificationOverlay);
      document.head.removeChild(style);
    }, 300);
  };

  window.shareAchievement = function() {
    if (navigator.share) {
      navigator.share({
        title: 'Saya Lulus Ujian!',
        text: 'Saya berhasil lulus ujian dengan hasil yang memuaskan! 🎉',
        url: window.location.href
      });
    } else {
      // Fallback for browsers that don't support Web Share API
      const text = 'Saya berhasil lulus ujian dengan hasil yang memuaskan! 🎉 ' + window.location.href;
      navigator.clipboard.writeText(text).then(() => {
        alert('Link berhasil disalin ke clipboard!');
      });
    }
  };
}
</script>

<?= $this->include('layouts/footer') ?>
