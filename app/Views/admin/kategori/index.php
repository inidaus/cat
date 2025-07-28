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
    <h2 class="glass-title"><i class="fas fa-layer-group me-2"></i> Kategori Soal</h2>
    <div>
      <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-1"></i> Tambah Kategori
      </button>
      <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
      </a>
    </div>
  </div>

  <!-- Info Pagination dan Dropdown Per Page -->
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="pagination-info">
      <small>
        <i class="fas fa-info-circle me-1"></i>
        Menampilkan <strong><?= (($currentPage - 1) * $perPage) + 1 ?></strong> - <strong><?= min($currentPage * $perPage, $total) ?></strong>
        dari <strong><?= $total ?></strong> kategori
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
            <?= $option ?> kategori
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-light table-bordered table-striped align-middle">
      <thead class="table-secondary">
        <tr>
          <th style="width: 10%;">#</th>
          <th style="width: 60%;">Nama Kategori</th>
          <th style="width: 30%;" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($kategori)): ?>
        <tr>
          <td colspan="3" class="text-center text-muted py-4">
            <i class="fas fa-inbox fa-2x mb-2"></i><br>
            Belum ada kategori yang ditambahkan
          </td>
        </tr>
        <?php else: ?>
          <?php foreach ($kategori as $i => $k): ?>
          <tr>
            <td><?= (($currentPage - 1) * $perPage) + $i + 1 ?></td>
            <td>
              <strong><?= esc($k['nama_kategori']) ?></strong>
            </td>
            <td class="text-center">
              <div class="btn-group" role="group">
                <button class="btn btn-warning btn-sm btn-edit" data-bs-toggle="modal" data-bs-target="#modalEdit"
                  data-id="<?= $k['id'] ?>" data-nama="<?= esc($k['nama_kategori']) ?>" title="Edit Kategori">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $k['id'] ?>, '<?= esc($k['nama_kategori']) ?>')" title="Hapus Kategori">
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('admin/kategori/save') ?>" method="post">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Tambah Kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama_kategori" class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" placeholder="Masukkan nama kategori" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('admin/kategori/update') ?>" method="post">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <div class="mb-3">
            <label for="edit-nama" class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="edit-nama" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Update
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

// Confirm Delete Function
function confirmDelete(id, nama) {
  if (confirm(`Yakin ingin menghapus kategori "${nama}"?`)) {
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `<?= base_url('admin/kategori/delete/') ?>${id}`;
    document.body.appendChild(form);
    form.submit();
  }
}

// Change Per Page Function
function changePerPage(perPage) {
  const url = new URL(window.location);
  url.searchParams.set('per_page', perPage);
  url.searchParams.delete('page'); // Reset ke halaman 1
  window.location.href = url.toString();
}

// Edit modal and Toast initialization
document.addEventListener('DOMContentLoaded', function() {
  // Edit modal event listeners
  document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function () {
      document.getElementById('edit-id').value = this.dataset.id;
      document.getElementById('edit-nama').value = this.dataset.nama;
    });
  });

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
