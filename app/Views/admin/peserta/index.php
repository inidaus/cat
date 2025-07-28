<?php echo $this->include('layouts/header') ?>

<style>
/* Enhanced Card Styling */
.peserta-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 15px;
  transition: all 0.3s ease;
  overflow: hidden;
  position: relative;
}

.peserta-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
}

.peserta-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
}

.peserta-card .card-body {
  position: relative;
  z-index: 1;
}

.peserta-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: bold;
  font-size: 1.2rem;
  margin: 0 auto 10px;
}

.status-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  border: 2px solid white;
}

.status-active {
  background: #4ecdc4;
  box-shadow: 0 0 10px rgba(78, 205, 196, 0.5);
}

.status-inactive {
  background: #ff6b6b;
  box-shadow: 0 0 10px rgba(255, 107, 107, 0.5);
}

/* Enhanced Table Styling */
.table-modern {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 15px;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.table-modern thead {
  background: linear-gradient(135deg, #667eea, #764ba2);
}

.table-modern tbody tr {
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
}

.table-modern tbody tr:hover {
  background: rgba(255, 255, 255, 0.1);
  transform: scale(1.01);
}

/* Enhanced Modal Styling */
.modal-modern .modal-content {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-modern .modal-header {
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  background: rgba(255, 255, 255, 0.1);
  border-radius: 20px 20px 0 0;
}

.modal-modern .modal-body {
  padding: 2rem;
}

.form-floating-modern {
  position: relative;
  margin-bottom: 1.5rem;
}

.form-floating-modern input {
  background: rgba(255, 255, 255, 0.1);
  border: 2px solid rgba(255, 255, 255, 0.2);
  border-radius: 10px;
  color: white;
  padding: 15px;
  transition: all 0.3s ease;
}

.form-floating-modern input:focus {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.5);
  box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
  outline: none;
}

.form-floating-modern label {
  color: rgba(255, 255, 255, 0.8);
  font-weight: 500;
}

/* Action Buttons */
.btn-action {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: none;
  transition: all 0.3s ease;
  margin: 0 2px;
}

.btn-action:hover {
  transform: scale(1.1);
}

.btn-edit {
  background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
  color: #2d3436;
}

.btn-delete {
  background: linear-gradient(135deg, #ff7675, #e84393);
  color: white;
}

/* Import/Export Buttons */
.btn-import {
  background: linear-gradient(135deg, #00b894, #00cec9);
  border: none;
  border-radius: 10px;
  color: white;
  transition: all 0.3s ease;
}

.btn-import:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 184, 148, 0.3);
  color: white;
}

.btn-export {
  background: linear-gradient(135deg, #0984e3, #74b9ff);
  border: none;
  border-radius: 10px;
  color: white;
  transition: all 0.3s ease;
}

.btn-export:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(9, 132, 227, 0.3);
  color: white;
}

/* Pagination Styling */
.pagination-modern {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 15px;
  padding: 15px;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.per-page-select {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 8px;
  color: white;
  padding: 8px 12px;
}

.per-page-select:focus {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.5);
  color: white;
  box-shadow: none;
}

.per-page-select option {
  background: #2d3436;
  color: white;
}
</style>

<div class="glass-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
      <h2 class="glass-title">
        <i class="fas fa-user-graduate me-2"></i> Data Peserta
      </h2>
      <p class="text-white-50 mb-0">
        <i class="fas fa-info-circle me-1"></i>
        Kelola data peserta ujian
      </p>
    </div>

    <div class="d-flex flex-wrap gap-2 align-items-center">
      <!-- Search -->
      <div class="input-group" style="width: 250px;">
        <span class="input-group-text bg-transparent border-white-50 text-white">
          <i class="fas fa-search"></i>
        </span>
        <input type="text" id="searchInput" class="form-control bg-transparent border-white-50 text-white"
               placeholder="Cari nama atau NIM..." style="border-left: none;">
      </div>

      <!-- View Toggle -->
      <div class="btn-group" role="group">
        <button class="btn btn-outline-light active" id="btnCard" type="button">
          <i class="fas fa-th-large me-1"></i>Card
        </button>
        <button class="btn btn-outline-light" id="btnTable" type="button">
          <i class="fas fa-table me-1"></i>Table
        </button>
      </div>

      <!-- Import/Export -->
      <div class="btn-group" role="group">
        <button class="btn btn-import btn-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
          <i class="fas fa-file-import me-1"></i>Import
        </button>
        <a href="<?= base_url('admin/peserta/export') ?>" class="btn btn-export btn-sm">
          <i class="fas fa-file-export me-1"></i>Export
        </a>
        <a href="<?= base_url('admin/peserta/template') ?>" class="btn btn-outline-light btn-sm">
          <i class="fas fa-download me-1"></i>Template
        </a>
      </div>

      <!-- Actions -->
      <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-1"></i>Tambah Peserta
      </button>
      <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Kembali
      </a>
    </div>
  </div>

  <!-- Pagination Info -->
  <div class="pagination-modern mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="pagination-info text-white">
        <i class="fas fa-info-circle me-1"></i>
        Menampilkan <strong><?= (($currentPage - 1) * $perPage) + 1 ?></strong> -
        <strong><?= min($currentPage * $perPage, $total) ?></strong>
        dari <strong><?= $total ?></strong> peserta
        <?php if ($pager->getPageCount() > 1): ?>
          (Halaman <strong><?= $currentPage ?></strong> dari <strong><?= $pager->getPageCount() ?></strong>)
        <?php endif; ?>
      </div>
      <div class="d-flex align-items-center gap-2">
        <label class="text-white mb-0">
          <i class="fas fa-list me-1"></i>Tampilkan:
        </label>
        <select class="per-page-select" onchange="changePerPage(this.value)">
          <?php foreach ($allowedPerPage as $option): ?>
            <option value="<?= $option ?>" <?= $option == $perPage ? 'selected' : '' ?>>
              <?= $option ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <!-- Card View -->
  <div id="cardView" class="row g-4">
    <?php foreach ($peserta as $p): ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2 peserta-item">
        <div class="card peserta-card text-white">
          <div class="status-badge <?= $p['aktif'] ? 'status-active' : 'status-inactive' ?>"></div>
          <div class="card-body text-center p-3">
            <div class="peserta-avatar">
              <?= strtoupper(substr($p['nama_lengkap'], 0, 1)) ?>
            </div>
            <h6 class="card-title mb-2 nama-peserta fw-bold">
              <?= esc($p['nama_lengkap']) ?>
            </h6>
            <div class="mb-2">
              <small class="d-block">
                <i class="fas fa-id-card me-1"></i>
                <span class="nim-peserta"><?= esc($p['username']) ?></span>
              </small>
              <small class="d-block">
                <i class="fas fa-calendar me-1"></i>
                Angkatan <?= esc($p['angkatan']) ?>
              </small>
            </div>
            <div class="d-flex justify-content-center align-items-center gap-2 mt-3">
              <form action="<?= base_url('admin/peserta/toggle/' . $p['id']) ?>" method="post" class="d-inline">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox"
                         <?= $p['aktif'] ? 'checked' : '' ?>
                         onchange="this.form.submit()">
                  <label class="form-check-label text-white-50" style="font-size: 0.75rem;">
                    <?= $p['aktif'] ? 'Aktif' : 'Nonaktif' ?>
                  </label>
                </div>
              </form>
            </div>
            <div class="d-flex justify-content-center gap-1 mt-3">
              <button class="btn btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#modalEdit"
                data-id="<?= $p['id'] ?>"
                data-nama="<?= esc($p['nama_lengkap']) ?>"
                data-nim="<?= esc($p['username']) ?>"
                data-angkatan="<?= esc($p['angkatan']) ?>"
                title="Edit Peserta">
                <i class="fas fa-edit"></i>
              </button>
              <form action="<?= base_url('admin/peserta/delete/' . $p['id']) ?>" method="post"
                    onsubmit="return confirm('Yakin ingin menghapus peserta <?= esc($p['nama_lengkap']) ?>?')"
                    class="d-inline">
                <button class="btn btn-action btn-delete" title="Hapus Peserta">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>

  <!-- Table View -->
  <div id="tableView" class="d-none">
    <div class="table-responsive">
      <table class="table table-modern text-white">
        <thead>
          <tr>
            <th class="text-center" style="width: 60px;">
              <i class="fas fa-hashtag"></i>
            </th>
            <th>
              <i class="fas fa-user me-2"></i>Nama Lengkap
            </th>
            <th>
              <i class="fas fa-id-card me-2"></i>NIM
            </th>
            <th>
              <i class="fas fa-calendar me-2"></i>Angkatan
            </th>
            <th class="text-center">
              <i class="fas fa-toggle-on me-2"></i>Status
            </th>
            <th class="text-center" style="width: 120px;">
              <i class="fas fa-cogs me-2"></i>Aksi
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($peserta as $i => $p): ?>
          <tr class="peserta-item">
            <td class="text-center fw-bold">
              <?= (($currentPage - 1) * $perPage) + $i + 1 ?>
            </td>
            <td>
              <div class="d-flex align-items-center">
                <div class="peserta-avatar me-2" style="width: 35px; height: 35px; font-size: 0.9rem;">
                  <?= strtoupper(substr($p['nama_lengkap'], 0, 1)) ?>
                </div>
                <div>
                  <div class="nama-peserta fw-bold"><?= esc($p['nama_lengkap']) ?></div>
                  <small class="text-white-50">Peserta Ujian</small>
                </div>
              </div>
            </td>
            <td>
              <span class="nim-peserta badge bg-info"><?= esc($p['username']) ?></span>
            </td>
            <td>
              <span class="badge bg-secondary"><?= esc($p['angkatan']) ?></span>
            </td>
            <td class="text-center">
              <form action="<?= base_url('admin/peserta/toggle/' . $p['id']) ?>" method="post" class="d-inline">
                <div class="form-check form-switch d-flex justify-content-center">
                  <input class="form-check-input" type="checkbox"
                         <?= $p['aktif'] ? 'checked' : '' ?>
                         onchange="this.form.submit()">
                </div>
              </form>
              <small class="text-white-50">
                <?= $p['aktif'] ? 'Aktif' : 'Nonaktif' ?>
              </small>
            </td>
            <td class="text-center">
              <div class="d-flex justify-content-center gap-1">
                <button class="btn btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#modalEdit"
                  data-id="<?= $p['id'] ?>"
                  data-nama="<?= esc($p['nama_lengkap']) ?>"
                  data-nim="<?= esc($p['username']) ?>"
                  data-angkatan="<?= esc($p['angkatan']) ?>"
                  title="Edit Peserta">
                  <i class="fas fa-edit"></i>
                </button>
                <form action="<?= base_url('admin/peserta/delete/' . $p['id']) ?>" method="post"
                      onsubmit="return confirm('Yakin ingin menghapus peserta <?= esc($p['nama_lengkap']) ?>?')"
                      class="d-inline">
                  <button class="btn btn-action btn-delete" title="Hapus Peserta">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  <?php if ($pager->getPageCount() > 1): ?>
    <div class="d-flex justify-content-center mt-4">
      <?= $pager->links('default', 'custom_pagination') ?>
    </div>
  <?php endif; ?>
</div>

<!-- Modal Tambah -->
<div class="modal fade modal-modern" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white">
          <i class="fas fa-user-plus me-2"></i>Tambah Peserta Baru
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('admin/peserta/save') ?>" method="post">
        <div class="modal-body">
          <div class="form-floating-modern">
            <input type="text" name="nama_lengkap" class="form-control" id="nama_lengkap" placeholder="Nama Lengkap" required>
            <label for="nama_lengkap">
              <i class="fas fa-user me-2"></i>Nama Lengkap
            </label>
          </div>
          <div class="form-floating-modern">
            <input type="text" name="nim" class="form-control" id="nim" placeholder="NIM" required>
            <label for="nim">
              <i class="fas fa-id-card me-2"></i>NIM
            </label>
          </div>
          <div class="form-floating-modern">
            <input type="text" name="angkatan" class="form-control" id="angkatan" placeholder="Angkatan" required>
            <label for="angkatan">
              <i class="fas fa-calendar me-2"></i>Angkatan
            </label>
          </div>
          <div class="text-center mt-3">
            <small class="text-white-50">
              <i class="fas fa-info-circle me-1"></i>
              Password default akan sama dengan NIM
            </small>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i>Simpan Peserta
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade modal-modern" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white">
          <i class="fas fa-user-edit me-2"></i>Edit Peserta
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('admin/peserta/update') ?>" method="post">
        <input type="hidden" name="id" id="edit-id">
        <div class="modal-body">
          <div class="form-floating-modern">
            <input type="text" name="nama_lengkap" id="edit-nama" class="form-control" placeholder="Nama Lengkap" required>
            <label for="edit-nama">
              <i class="fas fa-user me-2"></i>Nama Lengkap
            </label>
          </div>
          <div class="form-floating-modern">
            <input type="text" name="nim" id="edit-nim" class="form-control" placeholder="NIM" required>
            <label for="edit-nim">
              <i class="fas fa-id-card me-2"></i>NIM
            </label>
          </div>
          <div class="form-floating-modern">
            <input type="text" name="angkatan" id="edit-angkatan" class="form-control" placeholder="Angkatan" required>
            <label for="edit-angkatan">
              <i class="fas fa-calendar me-2"></i>Angkatan
            </label>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-warning">
            <i class="fas fa-save me-1"></i>Update Peserta
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Import -->
<div class="modal fade modal-modern" id="modalImport" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white">
          <i class="fas fa-file-import me-2"></i>Import Data Peserta
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('admin/peserta/import') ?>" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="text-center mb-4">
            <div class="import-icon mb-3">
              <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: rgba(255,255,255,0.7);"></i>
            </div>
            <h6 class="text-white">Upload File CSV</h6>
            <p class="text-white-50 small">
              Format: NIM, Nama Lengkap, Angkatan
            </p>
          </div>

          <div class="form-floating-modern">
            <input type="file" name="file" class="form-control" id="fileImport" accept=".csv" required>
            <label for="fileImport">
              <i class="fas fa-file-csv me-2"></i>Pilih File CSV
            </label>
          </div>

          <div class="alert alert-info bg-transparent border-info text-white mt-3">
            <h6><i class="fas fa-info-circle me-2"></i>Petunjuk Import:</h6>
            <ul class="mb-0 small">
              <li>File harus berformat CSV</li>
              <li>Kolom: NIM, Nama Lengkap, Angkatan</li>
              <li>Password default sama dengan NIM</li>
              <li>NIM yang sudah ada akan dilewati</li>
            </ul>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-import">
            <i class="fas fa-upload me-1"></i>Import Data
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Change per page function
function changePerPage(value) {
  const url = new URL(window.location);
  url.searchParams.set('per_page', value);
  url.searchParams.delete('page'); // Reset to first page
  window.location.href = url.toString();
}

// View toggle functionality
document.getElementById('btnCard').addEventListener('click', function() {
  document.getElementById('cardView').classList.remove('d-none');
  document.getElementById('tableView').classList.add('d-none');
  this.classList.add('active');
  document.getElementById('btnTable').classList.remove('active');
});

document.getElementById('btnTable').addEventListener('click', function() {
  document.getElementById('tableView').classList.remove('d-none');
  document.getElementById('cardView').classList.add('d-none');
  this.classList.add('active');
  document.getElementById('btnCard').classList.remove('active');
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
  const searchTerm = this.value.toLowerCase();
  const items = document.querySelectorAll('.peserta-item');

  items.forEach(item => {
    const nama = item.querySelector('.nama-peserta').textContent.toLowerCase();
    const nim = item.querySelector('.nim-peserta').textContent.toLowerCase();

    if (nama.includes(searchTerm) || nim.includes(searchTerm)) {
      item.style.display = '';
    } else {
      item.style.display = 'none';
    }
  });
});

// Edit modal functionality
document.querySelectorAll('.btn-edit').forEach(btn => {
  btn.addEventListener('click', function() {
    document.getElementById('edit-id').value = this.dataset.id;
    document.getElementById('edit-nama').value = this.dataset.nama;
    document.getElementById('edit-nim').value = this.dataset.nim;
    document.getElementById('edit-angkatan').value = this.dataset.angkatan;
  });
});

// Toggle functionality now uses form submit - no JavaScript needed

// File input styling
document.getElementById('fileImport').addEventListener('change', function() {
  const fileName = this.files[0]?.name || 'Pilih File CSV';
  this.nextElementSibling.innerHTML = `<i class="fas fa-file-csv me-2"></i>${fileName}`;
});
</script>

<?= $this->include('layouts/footer') ?>
