<?php echo $this->include('layouts/header'); ?>

<style>
.monitoring-header {
  background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 15px;
  padding: 1.5rem;
  margin-bottom: 2rem;
}

.peserta-card {
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 10px;
  padding: 1rem;
  margin-bottom: 0.75rem;
  transition: all 0.3s ease;
}

.peserta-card:hover {
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

.status-success { background: #28a745; color: white; }
.status-warning { background: #ffc107; color: #212529; }
.status-secondary { background: #6c757d; color: white; }

.progress-bar-custom {
  height: 8px;
  border-radius: 4px;
  background: rgba(255,255,255,0.2);
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #28a745, #20c997);
  border-radius: 4px;
  transition: width 0.3s ease;
}

.refresh-btn {
  background: linear-gradient(135deg, #17a2b8, #138496);
  border: none;
  border-radius: 25px;
  padding: 0.5rem 1rem;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}

.refresh-btn:hover {
  transform: scale(1.05);
  color: white;
}
</style>

<div class="glass-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
      <i class="fas fa-desktop me-2"></i> Monitoring Detail
    </h2>
    <div>
      <button onclick="refreshData()" class="refresh-btn me-2">
        <i class="fas fa-sync-alt me-1"></i> Refresh
      </button>
      <a href="<?= base_url('pembimbing/monitoring') ?>" class="btn btn-outline-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
      </a>
    </div>
  </div>

  <!-- Header Ujian -->
  <div class="monitoring-header">
    <div class="row">
      <div class="col-md-8">
        <h4 class="text-white mb-2"><?= esc($ujian['judul']) ?></h4>
        <div class="row">
          <div class="col-sm-6">
            <small class="text-white-50">Waktu Mulai</small>
            <div class="text-white">
              <i class="fas fa-calendar me-1"></i>
              <?= date('d M Y, H:i', strtotime($ujian['mulai'])) ?>
            </div>
          </div>
          <div class="col-sm-6">
            <small class="text-white-50">Durasi</small>
            <div class="text-white">
              <i class="fas fa-clock me-1"></i>
              <?= $ujian['waktu_menit'] ?> menit
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 text-md-end">
        <div class="mb-2">
          <small class="text-white-50">Total Peserta</small>
          <div class="text-white h5 mb-0">
            <i class="fas fa-users me-1"></i>
            <?= count($peserta_ujian) ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Daftar Peserta -->
  <div class="row">
    <div class="col-12">
      <h5 class="text-white mb-3">
        <i class="fas fa-list me-2"></i> Status Peserta
        <small class="text-white-50 ms-2" id="last-update">
          Terakhir update: <?= date('H:i:s') ?>
        </small>
      </h5>
      
      <div id="peserta-list">
        <?php if (empty($peserta_ujian)): ?>
          <div class="peserta-card text-center">
            <i class="fas fa-info-circle text-white-50 mb-2" style="font-size: 2rem;"></i>
            <p class="text-white-50 mb-0">Belum ada peserta yang terdaftar</p>
          </div>
        <?php else: ?>
          <?php foreach ($peserta_ujian as $peserta): ?>
            <div class="peserta-card">
              <div class="row align-items-center">
                <div class="col-md-3">
                  <h6 class="text-white mb-1"><?= esc($peserta['nama_lengkap']) ?></h6>
                  <small class="text-white-50">
                    <i class="fas fa-id-card me-1"></i>
                    <?= esc($peserta['nim']) ?>
                  </small>
                </div>
                <div class="col-md-2">
                  <?php
                    if ($peserta['status'] === 'sedang_ujian') {
                      $statusClass = 'success';
                      $statusText = 'Sedang Ujian';
                    } elseif ($peserta['status'] === 'selesai') {
                      $statusClass = 'secondary';
                      $statusText = 'Selesai';
                    } else {
                      $statusClass = 'warning';
                      $statusText = 'Belum Mulai';
                    }
                  ?>
                  <span class="status-badge status-<?= $statusClass ?>">
                    <?= $statusText ?>
                  </span>
                </div>

                <?php if ($peserta['status'] === 'selesai'): ?>
                  <!-- Tampilan untuk peserta yang sudah selesai -->
                  <div class="col-md-2">
                    <small class="text-white-50">Benar/Salah</small>
                    <div class="text-white">
                      <i class="fas fa-check text-success me-1"></i><?= $peserta['jawaban_benar'] ?>
                      <i class="fas fa-times text-danger ms-2 me-1"></i><?= $peserta['jawaban_salah'] ?>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <small class="text-white-50">Durasi</small>
                    <div class="text-white">
                      <i class="fas fa-stopwatch me-1"></i>
                      <?= $peserta['durasi_menit'] ?> menit
                    </div>
                  </div>
                  <div class="col-md-2">
                    <small class="text-white-50">Nilai Real</small>
                    <div class="text-white h6 mb-0">
                      <span class="badge bg-<?= $peserta['nilai_real'] >= $ujian['passing_grade'] ? 'success' : 'danger' ?>">
                        <?= $peserta['nilai_real'] ?>%
                      </span>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <small class="text-white-50">Status</small>
                    <div class="text-white">
                      <?php if ($peserta['nilai_real'] >= $ujian['passing_grade']): ?>
                        <i class="fas fa-check-circle text-success"></i>
                      <?php else: ?>
                        <i class="fas fa-times-circle text-danger"></i>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php else: ?>
                  <!-- Tampilan untuk peserta yang belum selesai -->
                  <div class="col-md-2">
                    <small class="text-white-50">Jawaban</small>
                    <div class="text-white">
                      <i class="fas fa-check-circle me-1"></i>
                      <?= $peserta['total_jawaban'] ?>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <small class="text-white-50">Durasi</small>
                    <div class="text-white">
                      <i class="fas fa-stopwatch me-1"></i>
                      <?= $peserta['durasi_menit'] ?> menit
                    </div>
                  </div>
                  <div class="col-md-3">
                    <small class="text-white-50">Status</small>
                    <div class="text-white-50">
                      <?= $peserta['status'] === 'sedang_ujian' ? 'Sedang mengerjakan...' : 'Belum mulai' ?>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
let refreshInterval;

function refreshData() {
  const ujianId = <?= $ujian['id'] ?>;
  
  fetch(`<?= base_url('pembimbing/monitoring/realtime/') ?>${ujianId}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        updatePesertaList(data.data);
        document.getElementById('last-update').textContent = 
          `Terakhir update: ${new Date().toLocaleTimeString()}`;
      }
    })
    .catch(error => {
      console.error('Error refreshing data:', error);
    });
}

function updatePesertaList(pesertaData) {
  const container = document.getElementById('peserta-list');
  
  if (pesertaData.length === 0) {
    container.innerHTML = `
      <div class="peserta-card text-center">
        <i class="fas fa-info-circle text-white-50 mb-2" style="font-size: 2rem;"></i>
        <p class="text-white-50 mb-0">Belum ada peserta yang terdaftar</p>
      </div>
    `;
    return;
  }
  
  let html = '';
  pesertaData.forEach(peserta => {
    html += `
      <div class="peserta-card">
        <div class="row align-items-center">
          <div class="col-md-4">
            <h6 class="text-white mb-1">${peserta.nama_lengkap}</h6>
            <small class="text-white-50">
              <i class="fas fa-id-card me-1"></i>
              ${peserta.nim}
            </small>
          </div>
          <div class="col-md-2">
            <span class="status-badge status-${peserta.status_class}">
              ${peserta.status_display}
            </span>
          </div>
          <div class="col-md-2">
            <small class="text-white-50">Jawaban</small>
            <div class="text-white">
              <i class="fas fa-check-circle me-1"></i>
              ${peserta.total_jawaban}
            </div>
          </div>
          <div class="col-md-2">
            <small class="text-white-50">Durasi</small>
            <div class="text-white">
              <i class="fas fa-stopwatch me-1"></i>
              ${peserta.durasi_menit} menit
            </div>
          </div>
          <div class="col-md-2">
            ${peserta.status === 'selesai' && peserta.nilai_real !== undefined ? `
              <small class="text-white-50">Nilai Real</small>
              <div class="text-white h6 mb-0">
                <span class="badge bg-${peserta.nilai_real >= <?= $ujian['passing_grade'] ?> ? 'success' : 'danger'}">
                  ${peserta.nilai_real}%
                </span>
              </div>
            ` : `
              <small class="text-white-50">Nilai</small>
              <div class="text-white-50">-</div>
            `}
          </div>
        </div>
      </div>
    `;
  });
  
  container.innerHTML = html;
}

// Auto refresh setiap 10 detik
document.addEventListener('DOMContentLoaded', function() {
  refreshInterval = setInterval(refreshData, 10000);
});

// Stop auto refresh saat halaman ditutup
window.addEventListener('beforeunload', function() {
  if (refreshInterval) {
    clearInterval(refreshInterval);
  }
});
</script>

<?= $this->include('layouts/footer'); ?>
