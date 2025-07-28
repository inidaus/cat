<?php echo $this->include('layouts/header'); ?>

<style>
.laporan-header {
  background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 15px;
  padding: 1.5rem;
  margin-bottom: 2rem;
}

.statistik-card {
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 10px;
  padding: 1rem;
  text-align: center;
  margin-bottom: 1rem;
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

.hasil-table {
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 15px;
  overflow: hidden;
}

.table-custom {
  margin-bottom: 0;
}

.table-custom th {
  background: rgba(255,255,255,0.2);
  color: white;
  border: none;
  padding: 1rem 0.75rem;
  font-weight: 600;
}

.table-custom td {
  background: rgba(255,255,255,0.05);
  color: white;
  border: 1px solid rgba(255,255,255,0.1);
  padding: 0.75rem;
}

.table-custom tbody tr:hover td {
  background: rgba(255,255,255,0.1);
}

.nilai-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 600;
}

.nilai-lulus { background: #28a745; color: white; }
.nilai-tidak-lulus { background: #dc3545; color: white; }
.nilai-kosong { background: #6c757d; color: white; }

.btn-export {
  background: linear-gradient(135deg, #17a2b8, #138496);
  border: none;
  border-radius: 25px;
  padding: 0.5rem 1.5rem;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-export:hover {
  transform: scale(1.05);
  color: white;
}
</style>

<div class="glass-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
      <i class="fas fa-chart-line me-2"></i> Detail Hasil Ujian (Admin)
    </h2>
    <div>
      <a href="<?= base_url('admin/laporan/export/' . $ujian['id']) ?>" 
         class="btn btn-export me-2">
        <i class="fas fa-download me-1"></i> Export CSV
      </a>
      <a href="<?= base_url('admin/laporan') ?>" class="btn btn-outline-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
      </a>
    </div>
  </div>

  <!-- Header Ujian -->
  <div class="laporan-header">
    <div class="row">
      <div class="col-md-8">
        <h4 class="text-white mb-2"><?= esc($ujian['judul']) ?></h4>
        <div class="row">
          <div class="col-sm-4">
            <small class="text-white-50">Waktu Mulai</small>
            <div class="text-white">
              <i class="fas fa-calendar me-1"></i>
              <?= date('d M Y, H:i', strtotime($ujian['mulai'])) ?>
            </div>
          </div>
          <div class="col-sm-4">
            <small class="text-white-50">Durasi</small>
            <div class="text-white">
              <i class="fas fa-clock me-1"></i>
              <?= $ujian['waktu_menit'] ?> menit
            </div>
          </div>
          <div class="col-sm-4">
            <small class="text-white-50">Passing Grade</small>
            <div class="text-white">
              <i class="fas fa-target me-1"></i>
              <?= $ujian['passing_grade'] ?>%
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistik -->
  <div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-6">
      <div class="statistik-card">
        <div class="statistik-number"><?= $statistik['total_peserta'] ?></div>
        <div class="statistik-label">Total Peserta</div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
      <div class="statistik-card">
        <div class="statistik-number"><?= $statistik['peserta_selesai'] ?></div>
        <div class="statistik-label">Selesai</div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
      <div class="statistik-card">
        <div class="statistik-number"><?= $statistik['peserta_lulus'] ?></div>
        <div class="statistik-label">Lulus</div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
      <div class="statistik-card">
        <div class="statistik-number"><?= $statistik['rata_rata'] ?>%</div>
        <div class="statistik-label">Rata-rata</div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
      <div class="statistik-card">
        <div class="statistik-number"><?= $statistik['nilai_tertinggi'] ?></div>
        <div class="statistik-label">Tertinggi</div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
      <div class="statistik-card">
        <div class="statistik-number"><?= $statistik['nilai_terendah'] ?></div>
        <div class="statistik-label">Terendah</div>
      </div>
    </div>
  </div>

  <!-- Persentase Kelulusan -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="statistik-card">
        <h5 class="text-white mb-3">Tingkat Kelulusan</h5>
        <div class="progress" style="height: 20px; background: rgba(255,255,255,0.2);">
          <div class="progress-bar bg-success" role="progressbar" 
               style="width: <?= $statistik['persentase_lulus'] ?>%">
            <?= $statistik['persentase_lulus'] ?>%
          </div>
        </div>
        <small class="text-white-50 mt-2 d-block">
          <?= $statistik['peserta_lulus'] ?> dari <?= $statistik['peserta_selesai'] ?> peserta lulus
        </small>
      </div>
    </div>
  </div>

  <!-- Tabel Hasil -->
  <div class="hasil-table">
    <div class="table-responsive">
      <table class="table table-custom">
        <thead>
          <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama Lengkap</th>
            <th>Status</th>
            <th>Benar/Salah/Kosong</th>
            <th>Nilai Real</th>
            <th>Durasi</th>
            <th>Detail Hasil</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($hasil_ujian)): ?>
            <tr>
              <td colspan="9" class="text-center">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada peserta yang terdaftar
              </td>
            </tr>
          <?php else: ?>
            <?php $no = 1; foreach ($hasil_ujian as $hasil): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($hasil['nim']) ?></td>
                <td><?= esc($hasil['nama_lengkap']) ?></td>
                <td>
                  <?php
                    $badgeClass = 'warning';
                    $statusText = ucfirst(str_replace('_', ' ', $hasil['status']));

                    if ($hasil['status'] === 'selesai') {
                      $badgeClass = 'success';
                    } elseif ($hasil['status'] === 'timeout') {
                      $badgeClass = 'warning';
                      $statusText = 'Waktu Habis';
                    }
                  ?>
                  <span class="badge bg-<?= $badgeClass ?>">
                    <?= $statusText ?>
                  </span>
                </td>
                <td>
                  <?php if (in_array($hasil['status'], ['selesai', 'timeout'])): ?>
                    <div style="font-size: 0.85rem;">
                      <span class="text-success">✓ <?= $hasil['jawaban_benar'] ?></span> •
                      <span class="text-danger">✗ <?= $hasil['jawaban_salah'] ?></span> •
                      <span class="text-warning">○ <?= $hasil['tidak_dijawab'] ?></span>
                    </div>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($hasil['nilai_real'] !== null): ?>
                    <span class="nilai-badge <?= $hasil['nilai_real'] >= $ujian['passing_grade'] ? 'nilai-lulus' : 'nilai-tidak-lulus' ?>">
                      <?= $hasil['nilai_real'] ?>%
                    </span>
                  <?php else: ?>
                    <span class="nilai-badge nilai-kosong">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php
                    if ($hasil['mulai'] && $hasil['selesai']) {
                      $waktuMulai = strtotime($hasil['mulai']);
                      $waktuSelesai = strtotime($hasil['selesai']);
                      $durasi = round(($waktuSelesai - $waktuMulai) / 60, 1);
                      echo $durasi . ' menit';
                    } else {
                      echo '-';
                    }
                  ?>
                </td>
                <td>
                  <?php if (in_array($hasil['status'], ['selesai', 'timeout']) && !empty($hasil['hasil_per_mata_pelajaran'])): ?>
                    <button class="btn btn-sm btn-outline-light" 
                            onclick="showCustomModal(<?= $hasil['peserta_id'] ?>)">
                      <i class="fas fa-eye me-1"></i>Detail
                    </button>
                    
                    <!-- Hidden Modal Data -->
                    <div id="modalData<?= $hasil['peserta_id'] ?>" style="display: none;">
                      <div class="modal-title-data"><?= esc($hasil['nama_lengkap']) ?></div>
                      <div class="modal-content-data">
                        <?php if (!empty($hasil['hasil_per_mata_pelajaran'])): ?>
                          <?php foreach ($hasil['hasil_per_mata_pelajaran'] as $hasilMP): ?>
                            <div class="mb-3 p-3 bg-secondary bg-opacity-25 rounded">
                              <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-white"><?= esc($hasilMP['nama_kategori']) ?></strong>
                                <span class="badge bg-<?= $hasilMP['status'] === 'LULUS' ? 'success' : 'danger' ?>">
                                  <?= $hasilMP['status'] ?>
                                </span>
                              </div>
                              <div class="row text-center">
                                <div class="col-2">
                                  <div class="text-white-50 small">Soal</div>
                                  <div class="text-white fw-bold"><?= $hasilMP['jumlah_soal'] ?></div>
                                </div>
                                <div class="col-2">
                                  <div class="text-success small">Benar</div>
                                  <div class="text-success fw-bold"><?= $hasilMP['benar'] ?></div>
                                </div>
                                <div class="col-2">
                                  <div class="text-danger small">Salah</div>
                                  <div class="text-danger fw-bold"><?= $hasilMP['salah'] ?></div>
                                </div>
                                <div class="col-2">
                                  <div class="text-warning small">Kosong</div>
                                  <div class="text-warning fw-bold"><?= $hasilMP['tidak_dijawab'] ?></div>
                                </div>
                                <div class="col-2">
                                  <div class="text-white-50 small">Grade</div>
                                  <div class="text-white fw-bold"><?= $hasilMP['grade'] ?>%</div>
                                </div>
                                <div class="col-2">
                                  <div class="text-white-50 small">Min</div>
                                  <div class="text-white fw-bold"><?= $hasilMP['passing_grade'] ?>%</div>
                                </div>
                              </div>
                              <div class="mt-2">
                                <div class="progress" style="height: 6px;">
                                  <div class="progress-bar bg-<?= $hasilMP['grade'] >= $hasilMP['passing_grade'] ? 'success' : 'danger' ?>"
                                       style="width: <?= $hasilMP['grade'] ?>%"></div>
                                </div>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <p class="text-white-50">Tidak ada data hasil per mata pelajaran</p>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (in_array($hasil['status'], ['selesai', 'timeout']) && $hasil['nilai_real'] !== null): ?>
                    <span class="badge bg-<?= $hasil['nilai_real'] >= $ujian['passing_grade'] ? 'success' : 'danger' ?>">
                      <?= $hasil['nilai_real'] >= $ujian['passing_grade'] ? 'LULUS' : 'TIDAK LULUS' ?>
                    </span>
                  <?php else: ?>
                    <span class="badge bg-secondary">-</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Custom Modal Overlay -->
<div id="customModalOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; backdrop-filter: blur(5px);">
  <div style="display: flex; justify-content: center; align-items: center; height: 100%; padding: 20px;">
    <div id="customModalContent" style="background: #2c3e50; border: 1px solid rgba(255,255,255,0.2); border-radius: 15px; max-width: 800px; width: 100%; max-height: 80vh; overflow-y: auto; position: relative;">
      <!-- Modal Header -->
      <div style="padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.2); display: flex; justify-content: between; align-items: center;">
        <h5 id="customModalTitle" class="text-white mb-0">Detail Hasil</h5>
        <button onclick="closeCustomModal()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; margin-left: auto;">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <!-- Modal Body -->
      <div id="customModalBody" style="padding: 20px;">
        <!-- Content will be inserted here -->
      </div>
      
      <!-- Modal Footer -->
      <div style="padding: 20px; border-top: 1px solid rgba(255,255,255,0.2); text-align: right;">
        <button onclick="closeCustomModal()" class="btn btn-secondary">
          <i class="fas fa-times me-1"></i>Tutup
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// Custom Modal Functions - Simple and Reliable
function showCustomModal(pesertaId) {
  // Get modal data
  const modalData = document.getElementById('modalData' + pesertaId);
  if (!modalData) {
    alert('Data tidak ditemukan');
    return;
  }
  
  // Get title and content
  const title = modalData.querySelector('.modal-title-data').textContent;
  const content = modalData.querySelector('.modal-content-data').innerHTML;
  
  // Set modal content
  document.getElementById('customModalTitle').textContent = 'Detail Hasil - ' + title;
  document.getElementById('customModalBody').innerHTML = content;
  
  // Show modal
  const overlay = document.getElementById('customModalOverlay');
  overlay.style.display = 'block';
  
  // Prevent body scroll
  document.body.style.overflow = 'hidden';
  
  // Add escape key listener
  document.addEventListener('keydown', handleEscapeKey);
}

function closeCustomModal() {
  // Hide modal
  const overlay = document.getElementById('customModalOverlay');
  overlay.style.display = 'none';
  
  // Restore body scroll
  document.body.style.overflow = '';
  
  // Remove escape key listener
  document.removeEventListener('keydown', handleEscapeKey);
}

function handleEscapeKey(e) {
  if (e.key === 'Escape') {
    closeCustomModal();
  }
}

// Close modal when clicking outside content
document.addEventListener('DOMContentLoaded', function() {
  const overlay = document.getElementById('customModalOverlay');
  overlay.addEventListener('click', function(e) {
    if (e.target === overlay) {
      closeCustomModal();
    }
  });
});
</script>

<?= $this->include('layouts/footer'); ?>
