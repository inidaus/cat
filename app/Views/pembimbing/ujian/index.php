<?= $this->include('layouts/header') ?>

<style>
/* Fix text visibility issues */
.glass-title {
  color: #ffffff !important;
  font-weight: 600;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
}

.table-light {
  background-color: #f8f9fa !important;
}

.table-light th {
  background-color: #e9ecef !important;
  color: #333 !important;
  font-weight: 600;
}

.table-light td {
  color: #333 !important;
  background-color: #fff !important;
}

.table-striped > tbody > tr:nth-of-type(odd) > td {
  background-color: #f8f9fa !important;
}

.alert-success {
  color: #155724 !important;
  background-color: #d4edda !important;
  border-color: #c3e6cb !important;
}

/* Modal styling */
.modal-content {
  color: #333 !important;
}

.modal-content label {
  color: #333 !important;
  font-weight: 600 !important;
  margin-bottom: 0.5rem !important;
}

.form-control, .form-select {
  color: #333 !important;
  background-color: #fff !important;
}

/* Token styling */
.token-container {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.token-code {
  font-family: 'Courier New', monospace;
  font-weight: bold;
  letter-spacing: 1px;
  border-radius: 6px;
  transition: all 0.3s ease;
}

.token-code:hover {
  transform: scale(1.05);
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.btn-token {
  transition: all 0.3s ease;
  border-radius: 6px;
}

.btn-token:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.btn-token:disabled {
  opacity: 0.7;
  transform: none;
}

/* Mata pelajaran container styling */
.mata-pelajaran-item {
  border: 1px solid #dee2e6;
  border-radius: 0.5rem;
  padding: 1rem;
  margin-bottom: 1rem;
  background-color: #f8f9fa;
}

.mata-pelajaran-item:last-child {
  margin-bottom: 0;
}

.btn-remove-mapel {
  background-color: #dc3545;
  border-color: #dc3545;
  color: white;
}

.btn-remove-mapel:hover {
  background-color: #c82333;
  border-color: #bd2130;
}

.soal-info {
  font-size: 0.875rem;
  color: #6c757d;
  margin-top: 0.25rem;
}

.soal-warning {
  color: #dc3545 !important;
  font-weight: 600;
}

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

/* Mata Pelajaran Item Styling */
.mata-pelajaran-item {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1) !important;
  transition: all 0.3s ease;
}

.mata-pelajaran-item:hover {
  background: rgba(255, 255, 255, 0.08);
  border-color: rgba(255, 255, 255, 0.2) !important;
}

.mata-pelajaran-item .btn-remove-mapel {
  background: #dc3545;
  border-color: #dc3545;
  color: white;
}

.mata-pelajaran-item .btn-remove-mapel:hover {
  background: #c82333;
  border-color: #bd2130;
}

/* Time picker styles are now handled globally by time-picker-24h.css */
</style>

<div class="glass-wrapper">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="glass-title"><i class="fas fa-file-alt me-2"></i> Daftar Ujian</h2>
    <div>
      <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus"></i> Buat Ujian
      </button>
      <a href="<?= base_url('pembimbing/dashboard') ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>
  </div>



  <div class="table-responsive">
    <table class="table table-hover table-bordered table-light align-middle">
      <thead class="table-secondary text-center">
        <tr>
          <th style="width: 5%;">No</th>
          <th style="width: 30%;">Nama Tes</th>
          <th style="width: 20%;">Mata Pelajaran</th>
          <th style="width: 8%;">Soal</th>
          <th style="width: 12%;">Waktu</th>
          <th style="width: 8%;">Acak</th>
          <th style="width: 8%;">Grade</th>
          <th style="width: 9%;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($ujian as $i => $u): ?>
        <tr>
          <td class="text-center"><?= $i + 1 ?></td>
          <td>
            <div class="mb-2">
              <strong><?= esc($u['judul']) ?></strong>
            </div>
            <div class="token-container">
              <?php
              // Load token helper
              helper('token');
              // Generate token menggunakan helper function
              $token = generateUjianToken($u['id'], $u['updated_at'], $u['created_at']);
              ?>
              <span class="text-muted small me-2">Token:</span>
              <code id="token-display-<?= $u['id'] ?>" class="token-code bg-primary text-white px-2 py-1 me-2" style="font-size: 0.85rem; font-family: 'Courier New', monospace; font-weight: bold; letter-spacing: 2px;">
                <?= $token ?>
              </code>
              <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-outline-success btn-token" onclick="copyToken('<?= $token ?>', <?= $u['id'] ?>)" title="Copy Token">
                  <i class="fas fa-copy"></i>
                </button>
                <button class="btn btn-outline-secondary btn-token" onclick="refreshToken(<?= $u['id'] ?>)" title="Refresh Token">
                  <i class="fas fa-sync-alt"></i>
                </button>
              </div>
            </div>
          </td>
          <td>
            <?php
            if (!empty($u['kategori_data'])) {
              $kategoriData = json_decode($u['kategori_data'], true);
              if ($kategoriData && is_array($kategoriData) && count($kategoriData) > 0) {
                foreach ($kategoriData as $index => $kd) {
                  // Cari nama kategori berdasarkan ID
                  $namaKategori = 'Unknown';
                  foreach ($kategori as $k) {
                    if ($k['id'] == $kd['kategori_id']) {
                      $namaKategori = $k['nama_kategori'];
                      break;
                    }
                  }
                  echo '<div class="mb-1">';
                  echo '<strong>' . esc($namaKategori) . '</strong><br>';
                  echo '<small class="text-muted">' . (isset($kd['jumlah_soal']) ? $kd['jumlah_soal'] : '0') . ' soal, Grade: ' . (isset($kd['passing_grade']) ? $kd['passing_grade'] : $u['passing_grade']) . ', ' . (isset($kd['waktu_menit']) ? $kd['waktu_menit'] : '0') . ' menit</small>';
                  echo '</div>';
                  if ($index < count($kategoriData) - 1) echo '<hr class="my-1">';
                }
              } else {
                // Fallback ke single kategori
                echo '<strong>' . esc($u['nama_kategori']) . '</strong><br>';
                echo '<small class="text-muted">' . $u['jumlah_soal'] . ' soal, Grade: ' . $u['passing_grade'] . ', ' . $u['waktu_menit'] . ' menit</small>';
              }
            } else {
              // Fallback ke single kategori
              echo '<strong>' . esc($u['nama_kategori']) . '</strong><br>';
              echo '<small class="text-muted">' . $u['jumlah_soal'] . ' soal, Grade: ' . $u['passing_grade'] . ', ' . $u['waktu_menit'] . ' menit</small>';
            }
            ?>
          </td>
          <td class="text-center"><?= $u['jumlah_soal'] ?></td>
          <td>
            <?= date('d M Y H:i', strtotime($u['mulai'])) ?><br>
            <small class="text-muted">(<?= $u['waktu_menit'] ?> menit)</small>
          </td>
          <td class="text-center"><?= $u['acak_soal'] ? '✅' : '❌' ?></td>
          <td class="text-center"><?= $u['passing_grade'] ?></td>
          <td class="text-center">
  <a href="<?= base_url('pembimbing/ujian/peserta/' . $u['id']) ?>" class="btn btn-sm btn-info">
    <i class="fas fa-users"></i>
  </a>
  <button class="btn btn-sm btn-warning btn-edit" data-bs-toggle="modal" data-bs-target="#modalEdit"
    data-id="<?= $u['id'] ?>"
    data-judul="<?= esc($u['judul']) ?>"
    data-tanggal="<?= date('Y-m-d', strtotime($u['mulai'])) ?>"
    data-jam="<?= date('H:i', strtotime($u['mulai'])) ?>"
    data-toleransi="<?= $u['toleransi_menit'] ?>"
    data-passing="<?= $u['passing_grade'] ?>"
    data-passing-total="<?= $u['passing_grade'] ?>"
    data-acak="<?= $u['acak_soal'] ?>"
    data-kategori='<?= isset($u['kategori_data']) ? $u['kategori_data'] : '' ?>'>
    <i class="fas fa-edit"></i>
  </button>
  <form action="<?= base_url('pembimbing/ujian/delete/' . $u['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus ujian ini?')">
    <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
  </form>
</td>

        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>

</div>

<!-- Modal Tambah Ujian -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form action="<?= base_url('pembimbing/ujian/save') ?>" method="post" id="formTambahUjian">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-plus me-2"></i> Buat Ujian</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label>Nama Ujian</label>
              <input type="text" name="judul" class="form-control" required>
            </div>
            <div class="col-md-3">
              <label>Tanggal Mulai</label>
              <input type="date" name="tanggal_mulai" class="form-control" required>
            </div>
            <div class="col-md-3">
              <label>Jam Mulai</label>
              <input type="time" name="jam_mulai" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label>Toleransi Terlambat</label>
              <div class="input-group">
                <input type="number" name="toleransi_menit" class="form-control" value="5">
                <span class="input-group-text">menit</span>
              </div>
            </div>

            <div class="col-md-4">
              <label>Acak Soal</label>
              <select name="acak_soal" class="form-select">
                <option value="1">Soal Diacak</option>
                <option value="0">Soal Urut</option>
              </select>
            </div>
          </div>

          <hr>
          <h6><i class="fas fa-percentage me-2"></i> Passing Grade Total</h6>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label>Passing Grade Total (%)</label>
              <input type="number" name="passing_grade_total" class="form-control" value="70" min="0" max="100" required>
              <small class="text-muted">Passing grade untuk keseluruhan ujian (menentukan LULUS/TIDAK LULUS)</small>
            </div>
            <div class="col-md-6">
              <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Catatan:</strong> Passing Grade Total berbeda dengan Passing Grade per mata pelajaran.
                Ini adalah nilai minimum untuk dinyatakan LULUS secara keseluruhan.
              </div>
            </div>
          </div>

          <hr>
          <h6><i class="fas fa-book me-2"></i> Mata Pelajaran, Jumlah Soal, Passing Grade & Waktu Ujian</h6>

          <div id="mata-pelajaran-container">
            <div class="mata-pelajaran-item border rounded p-3 mb-3">
              <div class="row g-3">
                <div class="col-md-3">
                  <label>Mata Pelajaran 1</label>
                  <select name="kategori_id[]" class="form-select kategori-select" required onchange="checkJumlahSoal(this)">
                    <option value="">- Pilih Mata Pelajaran -</option>
                    <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id'] ?>"><?= esc($k['nama_kategori']) ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Jumlah Soal</label>
                  <input type="number" name="jumlah_soal[]" class="form-control jumlah-soal-input" min="1" required oninput="validateJumlahSoal(this)">
                  <div class="soal-info text-muted small"></div>
                </div>
                <div class="col-md-2">
                  <label>Passing Grade</label>
                  <input type="number" name="passing_grade[]" class="form-control" value="0" min="0" max="100" required>
                </div>
                <div class="col-md-3">
                  <label>Waktu (menit)</label>
                  <input type="number" name="waktu_menit[]" class="form-control" min="1" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                  <button type="button" class="btn btn-danger btn-sm w-100" onclick="this.parentElement.parentElement.parentElement.remove(); updateRemoveButtons();" style="display: none;">
                    <i class="fas fa-trash"></i> Hapus
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="text-center mt-3">
            <button type="button" class="btn btn-success btn-sm" onclick="addSimpleMataPelajaran()">
              <i class="fas fa-plus"></i> Tambah Mata Pelajaran
            </button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Ujian</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Ujian -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form action="<?= base_url('pembimbing/ujian/update') ?>" method="post" id="formEditUjian">
        <input type="hidden" name="id" id="edit-id">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Ujian</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label>Nama Ujian</label>
              <input type="text" name="judul" id="edit-judul" class="form-control" required>
            </div>
            <div class="col-md-3">
              <label>Tanggal Mulai</label>
              <input type="date" name="tanggal_mulai" id="edit-tanggal" class="form-control" required>
            </div>
            <div class="col-md-3">
              <label>Jam Mulai</label>
              <input type="time" name="jam_mulai" id="edit-jam" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label>Toleransi Terlambat</label>
              <div class="input-group">
                <input type="number" name="toleransi_menit" id="edit-toleransi" class="form-control" value="5">
                <span class="input-group-text">menit</span>
              </div>
            </div>

            <div class="col-md-4">
              <label>Acak Soal</label>
              <select name="acak_soal" id="edit-acak" class="form-select">
                <option value="1">Soal Diacak</option>
                <option value="0">Soal Urut</option>
              </select>
            </div>
          </div>

          <hr>
          <h6><i class="fas fa-percentage me-2"></i> Passing Grade Total</h6>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label>Passing Grade Total (%)</label>
              <input type="number" name="passing_grade_total" id="edit-passing-total" class="form-control" value="70" min="0" max="100" required>
              <small class="text-muted">Passing grade untuk keseluruhan ujian (menentukan LULUS/TIDAK LULUS)</small>
            </div>
            <div class="col-md-6">
              <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Catatan:</strong> Passing Grade Total berbeda dengan Passing Grade per mata pelajaran.
              </div>
            </div>
          </div>

          <hr>
          <h6><i class="fas fa-book me-2"></i> Mata Pelajaran, Jumlah Soal, Passing Grade & Waktu Ujian</h6>

          <div id="edit-mata-pelajaran-container">
            <!-- Dynamic content will be loaded here -->
          </div>

          <div class="text-center mt-3">
            <button type="button" class="btn btn-success btn-sm" onclick="addEditMataPelajaran()">
              <i class="fas fa-plus"></i> Tambah Mata Pelajaran
            </button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Ujian</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
let mataPelajaranIndex = 0;
let availableKategori = <?= json_encode($kategori) ?>;

function confirmDelete(id) {
  if (confirm('Yakin ingin menghapus ujian ini?')) {
    window.location.href = '<?= base_url('pembimbing/ujian/delete/') ?>' + id;
  }
}

// Fungsi untuk menambah mata pelajaran dengan validasi
function addSimpleMataPelajaran() {
  const container = document.getElementById('mata-pelajaran-container');
  const itemCount = container.children.length + 1;

  const newItem = document.createElement('div');
  newItem.className = 'mata-pelajaran-item border rounded p-3 mb-3';

  let kategoriOptions = '<option value="">- Pilih Mata Pelajaran -</option>';
  availableKategori.forEach(k => {
    kategoriOptions += `<option value="${k.id}">${k.nama_kategori}</option>`;
  });

  newItem.innerHTML = `
    <div class="row g-3">
      <div class="col-md-3">
        <label>Mata Pelajaran ${itemCount}</label>
        <select name="kategori_id[]" class="form-select kategori-select" required onchange="checkJumlahSoal(this)">
          ${kategoriOptions}
        </select>
      </div>
      <div class="col-md-2">
        <label>Jumlah Soal</label>
        <input type="number" name="jumlah_soal[]" class="form-control jumlah-soal-input" min="1" required oninput="validateJumlahSoal(this)">
        <div class="soal-info text-muted small"></div>
      </div>
      <div class="col-md-2">
        <label>Passing Grade</label>
        <input type="number" name="passing_grade[]" class="form-control" value="0" min="0" max="100" required>
      </div>
      <div class="col-md-3">
        <label>Waktu (menit)</label>
        <input type="number" name="waktu_menit[]" class="form-control" min="1" required>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-danger btn-sm w-100" onclick="this.parentElement.parentElement.parentElement.remove(); updateRemoveButtons();">
          <i class="fas fa-trash"></i> Hapus
        </button>
      </div>
    </div>
  `;

  container.appendChild(newItem);
  updateRemoveButtons();
}

// Fungsi untuk mengatur visibilitas tombol hapus
function updateRemoveButtons() {
  const container = document.getElementById('mata-pelajaran-container');
  const items = container.querySelectorAll('.mata-pelajaran-item');

  items.forEach((item, index) => {
    const removeBtn = item.querySelector('.btn-danger');
    if (items.length <= 1) {
      removeBtn.style.display = 'none';
    } else {
      removeBtn.style.display = 'block';
    }
  });
}

// Fungsi untuk mengecek jumlah soal tersedia
function checkJumlahSoal(selectElement) {
  const kategoriId = selectElement.value;
  if (!kategoriId) return;

  const container = selectElement.closest('.mata-pelajaran-item');
  const soalInfo = container.querySelector('.soal-info');

  soalInfo.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengecek...';

  fetch('<?= base_url('pembimbing/ujian/getJumlahSoal') ?>', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `kategori_id=${kategoriId}`
  })
  .then(response => response.json())
  .then(data => {
    const jumlahSoalTersedia = data.jumlah_soal;
    soalInfo.innerHTML = `Soal tersedia: ${jumlahSoalTersedia}`;
    soalInfo.setAttribute('data-max-soal', jumlahSoalTersedia);

    const jumlahSoalInput = container.querySelector('.jumlah-soal-input');
    jumlahSoalInput.setAttribute('max', jumlahSoalTersedia);

    // Validate current input value
    validateJumlahSoal(jumlahSoalInput);
  })
  .catch(error => {
    console.error('Error:', error);
    soalInfo.innerHTML = '<span class="text-danger">Error mengecek soal</span>';
  });
}

// Fungsi untuk validasi input jumlah soal
function validateJumlahSoal(inputElement) {
  const container = inputElement.closest('.mata-pelajaran-item');
  const soalInfo = container.querySelector('.soal-info');
  const maxSoal = parseInt(soalInfo.getAttribute('data-max-soal')) || 0;
  const inputValue = parseInt(inputElement.value) || 0;

  if (maxSoal > 0 && inputValue > maxSoal) {
    soalInfo.innerHTML = `Soal tersedia: ${maxSoal} <span class="text-danger">⚠️ Jumlah melebihi yang tersedia!</span>`;
    soalInfo.className = 'soal-info text-danger small';
    inputElement.classList.add('is-invalid');
  } else if (maxSoal > 0) {
    soalInfo.innerHTML = `Soal tersedia: ${maxSoal}`;
    soalInfo.className = 'soal-info text-muted small';
    inputElement.classList.remove('is-invalid');
  }
}

// Fungsi-fungsi yang disederhanakan sudah tidak diperlukan

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
  console.log('Form loaded successfully');

  // Initialize remove buttons visibility
  updateRemoveButtons();

  // Validasi form sebelum submit
  document.getElementById('formTambahUjian').addEventListener('submit', function(e) {
    // Cek apakah ada input yang invalid
    const invalidInputs = this.querySelectorAll('.is-invalid');
    if (invalidInputs.length > 0) {
      e.preventDefault();
      showToast('Terdapat mata pelajaran dengan jumlah soal yang melebihi yang tersedia. Silakan periksa kembali.', 'error');
      return false;
    }

    // Cek duplikasi mata pelajaran
    const selectedKategori = [];
    const allSelects = this.querySelectorAll('select[name="kategori_id[]"]');

    for (let select of allSelects) {
      if (select.value) {
        if (selectedKategori.includes(select.value)) {
          e.preventDefault();
          showToast('Mata pelajaran tidak boleh duplikat. Silakan pilih mata pelajaran yang berbeda.', 'warning');
          return false;
        }
        selectedKategori.push(select.value);
      }
    }

    console.log('Form validation passed, submitting...');
    return true;
  });

  // Edit modal event listeners
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      const judul = this.dataset.judul;
      const tanggal = this.dataset.tanggal;
      const jam = this.dataset.jam;
      const toleransi = this.dataset.toleransi;
      const passing = this.dataset.passing;
      const passingTotal = this.dataset.passingTotal;
      const acak = this.dataset.acak;
      let kategoriData = [];
      try {
        console.log('Raw kategori data:', this.dataset.kategori); // Debug
        kategoriData = JSON.parse(this.dataset.kategori || '[]');
        if (!Array.isArray(kategoriData)) {
          kategoriData = [];
        }
        console.log('Parsed kategori data:', kategoriData); // Debug
      } catch (e) {
        console.warn('Invalid kategori data:', this.dataset.kategori);
        kategoriData = [];
      }

      // Fill form
      document.getElementById('edit-id').value = id;
      document.getElementById('edit-judul').value = judul;
      document.getElementById('edit-tanggal').value = tanggal;
      document.getElementById('edit-jam').value = jam;
      document.getElementById('edit-toleransi').value = toleransi;
      document.getElementById('edit-passing-total').value = passingTotal || passing || 70;
      document.getElementById('edit-acak').value = acak;

      // Load mata pelajaran
      loadEditMataPelajaran(kategoriData);
    });
  });
});

// Function to load mata pelajaran for edit
function loadEditMataPelajaran(kategoriData) {
  const container = document.getElementById('edit-mata-pelajaran-container');
  container.innerHTML = '';

  if (kategoriData && Array.isArray(kategoriData) && kategoriData.length > 0) {
    kategoriData.forEach((kd, index) => {
      // Pastikan kd memiliki properties yang diperlukan
      const kategoriId = kd.kategori_id || '';
      const jumlahSoal = kd.jumlah_soal || '';
      const passingGrade = kd.passing_grade || '0';
      const waktuMenit = kd.waktu_menit || '';
      addEditMataPelajaranItem(index, kategoriId, jumlahSoal, passingGrade, waktuMenit);
    });
  } else {
    // Jika tidak ada kategori_data (ujian lama), buat default item
    addEditMataPelajaranItem(0);
  }
  updateEditRemoveButtons();
}

// Function to add mata pelajaran item for edit
function addEditMataPelajaranItem(index, selectedKategori = '', jumlahSoal = '', passingGrade = '0', waktuMenit = '') {
  const container = document.getElementById('edit-mata-pelajaran-container');
  const newItem = document.createElement('div');
  newItem.className = 'mata-pelajaran-item border rounded p-3 mb-3';
  newItem.setAttribute('data-index', index);

  newItem.innerHTML = `
    <div class="row g-3">
      <div class="col-md-3">
        <label>Mata Pelajaran</label>
        <select name="kategori_id[]" class="form-select kategori-select" required>
          <option value="">- Pilih Mata Pelajaran -</option>
          <?php foreach ($kategori as $k): ?>
          <option value="<?= $k['id'] ?>"><?= esc($k['nama_kategori']) ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-md-2">
        <label>Jumlah Soal</label>
        <input type="number" name="jumlah_soal[]" class="form-control jumlah-soal-input" min="1" value="${jumlahSoal}" required>
        <div class="soal-info"></div>
      </div>
      <div class="col-md-2">
        <label>Passing Grade</label>
        <input type="number" name="passing_grade[]" class="form-control" value="${passingGrade}" min="0" max="100" required>
      </div>
      <div class="col-md-3">
        <label>Waktu Ujian</label>
        <div class="input-group">
          <input type="number" name="waktu_menit[]" class="form-control" min="1" value="${waktuMenit}" required>
          <span class="input-group-text">menit</span>
        </div>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-danger btn-remove-mapel btn-sm w-100" onclick="removeEditMataPelajaran(${index})">
          <i class="fas fa-trash"></i> Hapus
        </button>
      </div>
    </div>
  `;

  container.appendChild(newItem);

  // Set selected kategori
  if (selectedKategori) {
    const select = newItem.querySelector('.kategori-select');
    select.value = selectedKategori;
  }
}

// Function to add new mata pelajaran for edit
function addEditMataPelajaran() {
  const container = document.getElementById('edit-mata-pelajaran-container');
  const items = container.querySelectorAll('.mata-pelajaran-item');
  const newIndex = items.length;

  addEditMataPelajaranItem(newIndex, '', '', '0', '');
  updateEditRemoveButtons();
}

// Function to remove mata pelajaran for edit
function removeEditMataPelajaran(index) {
  const item = document.querySelector(`#edit-mata-pelajaran-container .mata-pelajaran-item[data-index="${index}"]`);
  if (item) {
    item.remove();
    updateEditRemoveButtons();
  }
}

// Function to update remove buttons for edit
function updateEditRemoveButtons() {
  const items = document.querySelectorAll('#edit-mata-pelajaran-container .mata-pelajaran-item');
  items.forEach((item, index) => {
    const removeBtn = item.querySelector('.btn-remove-mapel');
    if (items.length <= 1) {
      removeBtn.style.display = 'none';
    } else {
      removeBtn.style.display = 'block';
    }
  });
}

// Function to refresh token
function refreshToken(ujianId) {
  if (confirm('Yakin ingin refresh token ujian ini?')) {
    const tokenElement = document.getElementById(`token-display-${ujianId}`);
    const refreshButton = event.target.closest('button');
    const originalIcon = refreshButton.innerHTML;

    // Show loading state
    refreshButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    refreshButton.disabled = true;

    // Generate new random token (client-side untuk demo)
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let newToken = '';
    for (let i = 0; i < 6; i++) {
      newToken += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    // Simulate API call delay
    setTimeout(() => {
      if (tokenElement) {
        tokenElement.textContent = newToken;
        showToast('Token berhasil di-refresh: ' + newToken, 'success');

        // Update copy button with new token
        const copyButton = refreshButton.parentElement.querySelector('button[onclick*="copyToken"]');
        if (copyButton) {
          copyButton.setAttribute('onclick', `copyToken('${newToken}', ${ujianId})`);
        }
      } else {
        showToast('Gagal refresh token: Element tidak ditemukan', 'error');
      }

      // Reset button
      refreshButton.innerHTML = originalIcon;
      refreshButton.disabled = false;
    }, 1000);
  }
}

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

// Check for flash messages and show toasts
document.addEventListener('DOMContentLoaded', function() {
  // Check for success message
  <?php if (session()->getFlashdata('success')): ?>
    showToast('<?= addslashes(session()->getFlashdata('success')) ?>', 'success');
  <?php endif; ?>

  // Check for error message
  <?php if (session()->getFlashdata('error')): ?>
    showToast('<?= addslashes(session()->getFlashdata('error')) ?>', 'error');
  <?php endif; ?>

  // Check for warning message
  <?php if (session()->getFlashdata('warning')): ?>
    showToast('<?= addslashes(session()->getFlashdata('warning')) ?>', 'warning');
  <?php endif; ?>

  // Check for info message
  <?php if (session()->getFlashdata('info')): ?>
    showToast('<?= addslashes(session()->getFlashdata('info')) ?>', 'info');
  <?php endif; ?>
});

// Function to copy token to clipboard with visual feedback
function copyToken(token, ujianId) {
  console.log('copyToken called with:', token, ujianId);

  // Get the button that was clicked
  const button = event.target.closest('button');
  if (!button) {
    console.error('Button not found');
    return;
  }

  console.log('Button found:', button);
  const originalIcon = button.innerHTML;

  // Show loading state
  button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
  button.disabled = true;

  // Function to handle success
  function handleSuccess() {
    button.innerHTML = '<i class="fas fa-check text-success"></i>';
    showToast('Token berhasil disalin: ' + token, 'success');

    // Reset button after 2 seconds
    setTimeout(() => {
      button.innerHTML = originalIcon;
      button.disabled = false;
    }, 2000);
  }

  // Function to handle error
  function handleError(error) {
    console.error('Copy failed:', error);
    button.innerHTML = '<i class="fas fa-times text-danger"></i>';
    showToast('Gagal menyalin token: ' + error.message, 'error');

    // Reset button after 2 seconds
    setTimeout(() => {
      button.innerHTML = originalIcon;
      button.disabled = false;
    }, 2000);
  }

  // Try modern clipboard API first
  if (navigator.clipboard && window.isSecureContext) {
    navigator.clipboard.writeText(token)
      .then(handleSuccess)
      .catch(error => {
        console.log('Clipboard API failed, trying fallback:', error);
        // Try fallback method
        tryFallbackCopy(token, handleSuccess, handleError);
      });
  } else {
    // Use fallback method directly
    tryFallbackCopy(token, handleSuccess, handleError);
  }
}

// Fallback copy method for older browsers or non-HTTPS
function tryFallbackCopy(token, onSuccess, onError) {
  try {
    const textArea = document.createElement('textarea');
    textArea.value = token;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    const successful = document.execCommand('copy');
    document.body.removeChild(textArea);

    if (successful) {
      onSuccess();
    } else {
      onError(new Error('execCommand failed'));
    }
  } catch (err) {
    onError(err);
  }
}
</script>

<!-- Toast Container -->
<div id="toast-container" class="toast-container"></div>

<?= $this->include('layouts/footer') ?>
