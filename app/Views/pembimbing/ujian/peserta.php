<?= $this->include('layouts/header') ?>

<style>
/* Fix text visibility issues */
.glass-title {
  color: #ffffff !important;
  font-weight: 600;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
}

.glass-wrapper h5 {
  color: #ffffff !important;
  font-weight: 600;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
}

.glass-wrapper p {
  color: #e0e0e0 !important;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.glass-wrapper p i {
  color: #ffffff !important;
}
</style>

<div class="glass-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="glass-title"><i class="fas fa-users me-2"></i> Kelola Peserta Ujian</h2>
    <a href="<?= base_url('pembimbing/ujian') ?>" class="btn btn-secondary btn-sm">← Kembali</a>
  </div>

  <div class="mb-4">
    <h5>Ujian: <strong><?= esc($ujian['judul']) ?></strong></h5>
    <p><i class="fas fa-book-open me-2"></i><?= esc($ujian['nama_kategori']) ?> | <?= $ujian['jumlah_soal'] ?> soal</p>

    <!-- Token Ujian -->
    <div class="alert alert-info mt-3">
      <?php
      // Load token helper dan generate token yang sama dengan halaman index
      helper('token');
      $token = generateUjianToken($ujian['id'], $ujian['updated_at'], $ujian['created_at']);
      ?>
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1" style="color: #0c5460 !important; text-shadow: none !important;">
            <i class="fas fa-key me-2"></i>Token Ujian
          </h5>
          <p style="color: #0c5460 !important; text-shadow: none !important;">
            Berikan token ini kepada peserta untuk mengakses ujian
          </p>
        </div>
        <div class="text-center">
          <h3 class="mb-2" style="color: #0c5460 !important; font-weight: bold; letter-spacing: 3px; text-shadow: none !important; font-family: 'Courier New', monospace;">
            <?= $token ?>
          </h3>
          <button type="button" class="btn btn-sm btn-primary" onclick="copyToken('<?= $token ?>')">
            <i class="fas fa-copy me-1"></i>Salin Token
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Copy Peserta dari Ujian Lain -->
  <div class="mb-4">
    <div class="card bg-light">
      <div class="card-body">
        <h6 class="card-title text-dark"><i class="fas fa-copy me-2"></i>Salin Peserta dari Ujian Lain</h6>
        <form action="<?= base_url('pembimbing/ujian/copyPeserta/' . $ujian['id']) ?>" method="post" class="d-flex gap-2">
          <select name="ujian_sumber" class="form-select" required>
            <option value="">Pilih ujian sumber...</option>
            <?php
            // Get ujian lain dari pembimbing yang sama
            $ujianModel = new \App\Models\UjianModel();
            $ujianLain = $ujianModel
                ->where('ujian.pembimbing_id', session('id'))
                ->where('ujian.id !=', $ujian['id'])
                ->join('kategori_soal', 'kategori_soal.id = ujian.kategori_id')
                ->select('ujian.id, ujian.judul, kategori_soal.nama_kategori')
                ->findAll();

            foreach ($ujianLain as $u):
            ?>
              <option value="<?= $u['id'] ?>"><?= esc($u['judul']) ?> (<?= esc($u['nama_kategori']) ?>)</option>
            <?php endforeach; ?>
          </select>
          <button type="submit" class="btn btn-info btn-sm">
            <i class="fas fa-copy me-1"></i>Salin Peserta
          </button>
        </form>
        <small class="text-muted">Fitur ini akan menyalin semua peserta dari ujian yang dipilih ke ujian ini.</small>
      </div>
    </div>
  </div>

  <form action="<?= base_url('pembimbing/ujian/savePeserta/' . $ujian['id']) ?>" method="post">
    <div class="table-responsive">
      <table class="table table-bordered table-light align-middle">
        <thead class="table-secondary text-center">
          <tr>
            <th><input type="checkbox" id="checkAll"></th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Angkatan</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($peserta as $p): ?>
          <tr>
            <td class="text-center">
              <input type="checkbox" name="peserta_id[]" value="<?= $p['id'] ?>" <?= in_array($p['id'], $peserta_terpilih) ? 'checked' : '' ?>>
            </td>
            <td><?= esc($p['nama_lengkap']) ?></td>
            <td><?= esc($p['username']) ?></td>
            <td><?= esc($p['angkatan']) ?></td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>

    <div class="mt-3 text-end">
      <button class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
    </div>
  </form>
</div>

<script>
  // Centang semua
  document.getElementById('checkAll').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[name="peserta_id[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
  });

  // Function to copy token to clipboard with visual feedback
  function copyToken(token) {
    console.log('copyToken called with:', token);

    // Get the button that was clicked
    const button = event.target.closest('button');
    if (!button) {
      console.error('Button not found');
      return;
    }

    const originalContent = button.innerHTML;

    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyalin...';
    button.disabled = true;

    // Function to handle success
    function handleSuccess() {
      button.innerHTML = '<i class="fas fa-check text-success me-1"></i>Tersalin!';
      showToast('Token berhasil disalin: ' + token, 'success');

      // Reset button after 2 seconds
      setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;
      }, 2000);
    }

    // Function to handle error
    function handleError(error) {
      console.error('Copy failed:', error);
      button.innerHTML = '<i class="fas fa-times text-danger me-1"></i>Gagal';
      showToast('Gagal menyalin token: ' + error.message, 'error');

      // Reset button after 2 seconds
      setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;
      }, 2000);
    }

    // Try modern clipboard API first
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(token)
        .then(handleSuccess)
        .catch(error => {
          console.log('Clipboard API failed, trying fallback:', error);
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

  // Toast notification function
  function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    toast.style.cssText = `
      top: 20px;
      right: 20px;
      z-index: 9999;
      min-width: 300px;
      opacity: 0;
      transition: opacity 0.3s ease;
    `;
    toast.innerHTML = `
      <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
      ${message}
    `;

    // Add to page
    document.body.appendChild(toast);

    // Show toast
    setTimeout(() => {
      toast.style.opacity = '1';
    }, 100);

    // Remove toast after 3 seconds
    setTimeout(() => {
      toast.style.opacity = '0';
      setTimeout(() => {
        if (toast.parentNode) {
          toast.parentNode.removeChild(toast);
        }
      }, 300);
    }, 3000);
  }
</script>

<?= $this->include('layouts/footer') ?>
