<?= $this->include('layouts/header') ?>

<style>
/* Toast Notification Styles */
.toast-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
}

.toast {
  min-width: 300px;
  border: none;
  border-radius: 10px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(10px);
  margin-bottom: 10px;
}

.toast.success {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
}

.toast.error {
  background: linear-gradient(135deg, #dc3545, #fd7e14);
  color: white;
}

.toast.warning {
  background: linear-gradient(135deg, #ffc107, #fd7e14);
  color: white;
}

.toast.info {
  background: linear-gradient(135deg, #17a2b8, #6f42c1);
  color: white;
}

.toast-header {
  background: transparent;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  color: inherit;
}

.toast-body {
  font-weight: 500;
}

.btn-close-white {
  filter: invert(1) grayscale(100%) brightness(200%);
}

/* Pagination Styles */
.pagination {
  justify-content: center;
}

.page-link {
  color: #495057;
  background-color: #fff;
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
  margin: 0 2px;
}

.page-link:hover {
  color: #0056b3;
  background-color: #e9ecef;
  border-color: #dee2e6;
}

.page-item.active .page-link {
  background-color: #007bff;
  border-color: #007bff;
  color: white;
}

.page-item.disabled .page-link {
  color: #6c757d;
  background-color: #fff;
  border-color: #dee2e6;
}

/* Per Page Dropdown Styles */
#perPageSelect {
  min-width: 120px;
  font-size: 0.875rem;
  border: 1px solid #ced4da;
  background-color: #fff;
}

.per-page-container {
  display: flex;
  align-items: center;
  gap: 8px;
  background-color: rgba(255, 255, 255, 0.8);
  padding: 8px 12px;
  border-radius: 6px;
  border: 1px solid rgba(0, 0, 0, 0.1);
}

.per-page-label {
  white-space: nowrap;
  font-weight: 600;
  color: #495057 !important;
}

/* Pagination Info Styles */
.pagination-info {
  background-color: rgba(255, 255, 255, 0.9);
  padding: 8px 12px;
  border-radius: 6px;
  border: 1px solid rgba(0, 0, 0, 0.1);
  color: #495057 !important;
}

.pagination-info small {
  color: #495057 !important;
  font-weight: 500;
}

.pagination-info strong {
  color: #212529 !important;
  font-weight: 700;
}

.pagination-info i {
  color: #007bff;
}

/* Search and Filter Styles */
.search-filter-container {
  background-color: rgba(255, 255, 255, 0.9);
  padding: 15px;
  border-radius: 8px;
  border: 1px solid rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.search-input {
  max-width: 300px;
}

/* Analisa Badge Styles */
.analisa-badge {
  font-size: 0.75rem;
  padding: 2px 6px;
  border-radius: 4px;
  margin: 1px;
  display: inline-block;
}

.analisa-dipakai {
  background-color: #e3f2fd;
  color: #1976d2;
}

.analisa-benar {
  background-color: #e8f5e8;
  color: #2e7d32;
}

.analisa-salah {
  background-color: #ffebee;
  color: #c62828;
}

/* Responsive Styles */
@media (max-width: 768px) {
  .pagination-info,
  .per-page-container {
    width: 100%;
    justify-content: center;
    text-align: center;
  }
  
  .pagination-info {
    margin-bottom: 8px;
  }
  
  #perPageSelect {
    min-width: 140px;
  }
  
  .search-input {
    max-width: 100%;
  }
}

@media (max-width: 576px) {
  .pagination-info small {
    font-size: 0.75rem;
  }
  
  .per-page-label small {
    font-size: 0.75rem;
  }
}
</style>

<div class="glass-wrapper">
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="glass-title"><i class="fas fa-question me-2"></i> Bank Soal Saya</h2>
  <div class="d-flex gap-2 flex-wrap">
    <a href="<?= base_url('pembimbing/soal/create') ?>" class="btn btn-success btn-sm">
      <i class="fas fa-plus me-1"></i> Tambah Soal
    </a>
    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
      <i class="fas fa-file-import me-1"></i> Import Soal
    </button>
    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalExport">
      <i class="fas fa-file-export me-1"></i> Export Soal
    </button>
    <a href="<?= base_url('pembimbing/dashboard') ?>" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
  </div>
</div>

  <!-- Search and Filter Form -->
  <div class="search-filter-container">
    <form method="GET" action="<?= base_url('pembimbing/soal') ?>">
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label for="search" class="form-label">
            <i class="fas fa-search me-1"></i>Cari Soal
          </label>
          <input type="text" id="search" name="search" class="form-control search-input" 
                 placeholder="Cari pertanyaan atau kategori..." 
                 value="<?= esc($search) ?>">
        </div>
        <div class="col-md-3">
          <label for="kategori" class="form-label">
            <i class="fas fa-filter me-1"></i>Filter Kategori
          </label>
          <select name="kategori" id="kategori" class="form-select">
            <option value="">Semua Kategori</option>
            <?php foreach ($kategori as $k): ?>
              <option value="<?= $k['id'] ?>" <?= $kategoriFilter == $k['id'] ? 'selected' : '' ?>>
                <?= esc($k['nama_kategori']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-search me-1"></i>Cari
          </button>
        </div>
        <div class="col-md-2">
          <a href="<?= base_url('pembimbing/soal') ?>" class="btn btn-outline-secondary w-100">
            <i class="fas fa-times me-1"></i>Reset
          </a>
        </div>
        <div class="col-md-1">
          <button type="button" class="btn btn-outline-info w-100" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i>
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Info Pagination dan Dropdown Per Page -->
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="pagination-info">
      <small>
        <i class="fas fa-info-circle me-1"></i>
        Menampilkan <strong><?= (($currentPage - 1) * $perPage) + 1 ?></strong> - <strong><?= min($currentPage * $perPage, $total) ?></strong> 
        dari <strong><?= $total ?></strong> soal
        <?php if ($pager->getPageCount() > 1): ?>
          (Halaman <strong><?= $currentPage ?></strong> dari <strong><?= $pager->getPageCount() ?></strong>)
        <?php endif; ?>
      </small>
    </div>
    <div class="per-page-container">
      <label for="perPageSelect" class="per-page-label mb-0">
        <small><i class="fas fa-list me-1"></i>Tampilkan:</small>
      </label>
      <select id="perPageSelect" class="form-select form-select-sm" onchange="changePerPage(this.value)">
        <?php foreach ($allowedPerPage as $option): ?>
          <option value="<?= $option ?>" <?= $option == $perPage ? 'selected' : '' ?>>
            <?= $option ?> soal
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-light table-bordered table-striped align-middle">
      <thead class="table-secondary">
        <tr>
          <th style="width: 5%;">#</th>
          <th style="width: 40%;">Pertanyaan</th>
          <th style="width: 15%;">Kategori</th>
          <th style="width: 8%;">Bobot</th>
          <th style="width: 17%;">Analisa</th>
          <th style="width: 15%;" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($soal)): ?>
        <tr>
          <td colspan="6" class="text-center text-muted py-4">
            <i class="fas fa-inbox fa-2x mb-2"></i><br>
            <?php if (!empty($search) || !empty($kategoriFilter)): ?>
              Tidak ada soal yang sesuai dengan pencarian atau filter
            <?php else: ?>
              Belum ada soal yang Anda buat
            <?php endif; ?>
          </td>
        </tr>
        <?php else: ?>
          <?php foreach ($soal as $i => $s): ?>
          <tr>
            <td><?= (($currentPage - 1) * $perPage) + $i + 1 ?></td>
            <td>
              <div class="fw-bold">
                <?= esc(strlen(strip_tags($s['pertanyaan'])) > 100 ? substr(strip_tags($s['pertanyaan']), 0, 100) . '...' : strip_tags($s['pertanyaan'])) ?>
              </div>
              <small class="text-muted">Kunci: <?= $s['kunci_jawaban'] ?></small>
            </td>
            <td>
              <span class="badge bg-primary"><?= esc($s['kategori']) ?></span>
            </td>
            <td class="text-center">
              <span class="badge bg-info"><?= esc($s['bobot']) ?></span>
            </td>
            <td>
              <?php
              $analisaData = $analisa[$s['id']] ?? null;
              if ($analisaData):
              ?>
                <div class="d-flex flex-wrap gap-1">
                  <span class="analisa-badge analisa-dipakai" title="Jumlah dipakai">
                    <i class="fas fa-chart-bar"></i> <?= $analisaData['jumlah_dipakai'] ?>
                  </span>
                  <span class="analisa-badge analisa-benar" title="Jawaban benar">
                    <i class="fas fa-check"></i> <?= $analisaData['jumlah_benar'] ?>
                  </span>
                  <span class="analisa-badge analisa-salah" title="Jawaban salah">
                    <i class="fas fa-times"></i> <?= $analisaData['jumlah_salah'] ?>
                  </span>
                </div>
              <?php else: ?>
                <small class="text-muted">Belum digunakan</small>
              <?php endif; ?>
            </td>
            <td class="text-center">
              <div class="btn-group" role="group">
                <button class="btn btn-info btn-sm" onclick="previewSoal(<?= $s['id'] ?>)" title="Preview Soal">
                  <i class="fas fa-eye"></i>
                </button>
                <a href="<?= base_url('pembimbing/soal/edit/' . $s['id']) ?>" class="btn btn-warning btn-sm" title="Edit Soal">
                  <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-success btn-sm" onclick="duplicateSoal(<?= $s['id'] ?>)" title="Duplikat Soal">
                  <i class="fas fa-copy"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $s['id'] ?>, '<?= esc(substr(strip_tags($s['pertanyaan']), 0, 50)) ?>...')" title="Hapus Soal">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($pager->getPageCount() > 1): ?>
  <div class="d-flex justify-content-center mt-4">
    <?= $pager->links('default', 'custom_pagination') ?>
  </div>
  <?php endif; ?>

</div>

<!-- Modal Import Soal -->
<div class="modal fade" id="modalImport" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="<?= base_url('pembimbing/soal/import') ?>" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-upload me-2"></i> Import Soal dari Excel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info">
            <h6><i class="fas fa-info-circle me-2"></i>Petunjuk Import:</h6>
            <ul class="mb-0">
              <li><strong>Download template</strong> terlebih dahulu untuk format yang benar</li>
              <li><strong>Kategori</strong>: Gunakan nama kategori yang sudah ada di sistem</li>
              <li><strong>Pembimbing</strong>: Otomatis diisi sesuai akun yang login</li>
              <li><strong>Kunci Jawaban</strong>: Harus berupa A, B, C, D, atau E</li>
              <li><strong>Pilihan E</strong>: Boleh dikosongkan (opsional)</li>
            </ul>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="file_excel" class="form-label">
                  <i class="fas fa-file-excel me-1"></i>File Excel (.xlsx atau .xls)
                </label>
                <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx,.xls" required>
                <div class="form-text">
                  Maksimal ukuran file: 10MB
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">
                  <i class="fas fa-list me-1"></i>Kategori Tersedia:
                </label>
                <div class="border rounded p-2" style="max-height: 120px; overflow-y: auto;">
                  <?php foreach ($kategori as $k): ?>
                    <span class="badge bg-primary me-1 mb-1"><?= esc($k['nama_kategori']) ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="<?= base_url('pembimbing/soal/template') ?>" class="btn btn-outline-info me-auto" target="_blank">
            <i class="fas fa-download me-1"></i> Download Template
          </a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-upload me-1"></i>Import Soal
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Export Soal -->
<div class="modal fade" id="modalExport" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('pembimbing/soal/export') ?>" method="get">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-download me-2"></i> Export Soal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="kategori_export" class="form-label">Pilih Kategori untuk Export</label>
            <select name="kategori_id" id="kategori_export" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              <?php foreach ($kategori as $k): ?>
                <option value="<?= $k['id'] ?>"><?= esc($k['nama_kategori']) ?></option>
              <?php endforeach; ?>
            </select>
            <div class="form-text">
              <i class="fas fa-info-circle me-1"></i>
              Export akan mengunduh semua soal Anda dalam kategori yang dipilih dalam format Excel (.xlsx)
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-file-export me-1"></i>Export Excel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Toast Notification Functions
function showToast(message, type = 'success', duration = 5000) {
  const toastContainer = document.getElementById('toast-container');
  const toastId = 'toast-' + Date.now();

  const icons = {
    success: 'fas fa-check-circle',
    error: 'fas fa-exclamation-circle',
    warning: 'fas fa-exclamation-triangle',
    info: 'fas fa-info-circle'
  };

  const titles = {
    success: 'Berhasil!',
    error: 'Error!',
    warning: 'Peringatan!',
    info: 'Informasi'
  };

  const toastHTML = `
    <div id="${toastId}" class="toast ${type}" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="${icons[type]} me-2"></i>
        <strong class="me-auto">${titles[type]}</strong>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        ${message}
      </div>
    </div>
  `;

  toastContainer.insertAdjacentHTML('beforeend', toastHTML);

  const toastElement = document.getElementById(toastId);
  const toast = new bootstrap.Toast(toastElement, {
    autohide: true,
    delay: duration
  });

  toast.show();

  // Remove toast element after it's hidden
  toastElement.addEventListener('hidden.bs.toast', function() {
    this.remove();
  });
}

// Change Per Page Function
function changePerPage(perPage) {
  const url = new URL(window.location);
  url.searchParams.set('per_page', perPage);
  url.searchParams.delete('page'); // Reset ke halaman 1
  window.location.href = url.toString();
}

// Confirm Delete Function
function confirmDelete(id, pertanyaan) {
  if (confirm(`Yakin ingin menghapus soal "${pertanyaan}"?`)) {
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `<?= base_url('pembimbing/soal/delete/') ?>${id}`;
    document.body.appendChild(form);
    form.submit();
  }
}

// Preview Soal Function
function previewSoal(id) {
  window.open(`<?= base_url('pembimbing/soal/preview/') ?>${id}`, '_blank', 'width=800,height=600,scrollbars=yes');
}

// Duplicate Soal Function
function duplicateSoal(id) {
  if (confirm('Yakin ingin menduplikat soal ini?')) {
    window.location.href = `<?= base_url('pembimbing/soal/duplicate/') ?>${id}`;
  }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
  // Check for flash messages and show toasts
  <?php if (session()->getFlashdata('success')): ?>
    showToast('<?= addslashes(session()->getFlashdata('success')) ?>', 'success');
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    showToast('<?= addslashes(session()->getFlashdata('error')) ?>', 'error');
  <?php endif; ?>

  <?php if (session()->getFlashdata('warning')): ?>
    showToast('<?= addslashes(session()->getFlashdata('warning')) ?>', 'warning');
  <?php endif; ?>

  <?php if (session()->getFlashdata('info')): ?>
    showToast('<?= addslashes(session()->getFlashdata('info')) ?>', 'info');
  <?php endif; ?>
});
</script>

<!-- Toast Container -->
<div id="toast-container" class="toast-container"></div>

<?= $this->include('layouts/footer') ?>
