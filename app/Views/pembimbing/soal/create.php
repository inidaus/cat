<?= $this->include('layouts/header') ?>

<style>
.form-container {
  max-width: 900px;
  margin: auto;
  background: #f8f9fa;
  padding: 2rem;
  border-radius: 1rem;
  box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.form-container label {
  color: #333 !important;
  font-weight: 600 !important;
  margin-bottom: 0.5rem !important;
  display: block !important;
}

/* Additional label fixes */
label {
  color: #333 !important;
  font-weight: 600 !important;
}

.mb-3 label {
  color: #333 !important;
  font-weight: 600 !important;
}

/* Specific fixes for form labels */
.form-label {
  color: #333 !important;
  font-weight: 600 !important;
}

.form-container .form-control,
.form-container .form-select {
  border: 1px solid #ddd;
  border-radius: 0.5rem;
  padding: 0.75rem;
}

.form-container .form-control:focus,
.form-container .form-select:focus {
  border-color: #6a11cb;
  box-shadow: 0 0 0 0.2rem rgba(106, 17, 203, 0.25);
}

/* TinyMCE Custom Styling */
.tox-tinymce {
  border: 1px solid #ddd !important;
  border-radius: 0.5rem !important;
}

.tox .tox-toolbar {
  background: #f8f9fa !important;
  border-bottom: 1px solid #dee2e6 !important;
}

.tox .tox-edit-area {
  border: none !important;
}

.tox .tox-statusbar {
  border-top: 1px solid #dee2e6 !important;
  background: #f8f9fa !important;
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

/* Make TinyMCE responsive */
@media (max-width: 768px) {
  .tox .tox-toolbar__group {
    flex-wrap: wrap;
  }
}
</style>

<div class="glass-wrapper">
  <div class="form-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="glass-title" style="background: linear-gradient(to right, #6a11cb, #2575fc); color: #fff; padding: 10px 20px; border-radius: 8px;">
        <i class="fas fa-plus me-2"></i> Tambah Soal Baru
      </h2>
      <a href="<?= base_url('pembimbing/soal') ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Bank Soal
      </a>
    </div>

    <!-- Flash messages akan ditampilkan via toast -->

    <form action="<?= base_url('pembimbing/soal/save') ?>" method="post">
      <div class="row mb-3">
        <div class="col-md-6">
          <label>Kategori Soal <span class="text-danger">*</span></label>
          <select name="kategori_id" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($kategori as $k): ?>
              <option value="<?= $k['id'] ?>"><?= esc($k['nama_kategori']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-md-3">
          <label>Kunci Jawaban <span class="text-danger">*</span></label>
          <select name="kunci_jawaban" class="form-select" required>
            <option value="">-- Pilih --</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
            <option value="E">E</option>
          </select>
        </div>
        <div class="col-md-3">
          <label>Bobot Soal <span class="text-danger">*</span></label>
          <input type="number" name="bobot" class="form-control" value="1" min="1" max="100" required>
        </div>
      </div>

      <div class="mb-3">
        <label>Pertanyaan <span class="text-danger">*</span></label>
        <textarea name="pertanyaan" id="editor-pertanyaan" class="form-control" rows="5" required></textarea>
      </div>

      <?php foreach (['a','b','c','d','e'] as $opt): ?>
      <div class="mb-3">
        <label>Pilihan <?= strtoupper($opt) ?> <?= $opt != 'e' ? '<span class="text-danger">*</span>' : '<span class="text-muted">(Opsional)</span>' ?></label>
        <textarea name="pilihan_<?= $opt ?>" class="form-control" rows="3" <?= $opt != 'e' ? 'required' : '' ?>></textarea>
      </div>
      <?php endforeach ?>

      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="reset" class="btn btn-outline-secondary me-md-2">
          <i class="fas fa-undo me-1"></i> Reset Form
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Simpan Soal
        </button>
      </div>
    </form>
  </div>
</div>

<!-- WYSIWYG Editor - TinyMCE -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
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

$(document).ready(function() {
  // Add IDs to textareas for TinyMCE first
  $('textarea[name^="pilihan_"]').each(function(index) {
    var optionLetter = $(this).attr('name').split('_')[1];
    $(this).attr('id', 'editor-pilihan-' + optionLetter);
  });

  // Initialize TinyMCE for question editor
  tinymce.init({
    selector: '#editor-pertanyaan',
    height: 200,
    plugins: 'link image media table lists code fullscreen',
    toolbar: 'undo redo | formatselect | bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | link image media | table | code fullscreen',
    menubar: false,
    branding: false,
    placeholder: 'Tulis pertanyaan di sini...',
    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
    setup: function (editor) {
      editor.on('init', function () {
        console.log('TinyMCE Question Editor initialized');
      });

      // Handle form validation
      editor.on('blur', function () {
        editor.save(); // Save content to textarea
      });
    },
    // PERBAIKAN: Gunakan base64 langsung (lebih reliable)
    file_picker_types: 'image',
    file_picker_callback: function (callback, value, meta) {
      if (meta.filetype === 'image') {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.onchange = function () {
          var file = this.files[0];

          // Validasi ukuran file (maksimal 1MB untuk base64)
          if (file.size > 1 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 1MB untuk gambar.');
            return;
          }

          var reader = new FileReader();
          reader.onload = function () {
            // Langsung gunakan base64 (lebih reliable)
            callback(reader.result, {
              alt: file.name
            });
          };
          reader.readAsDataURL(file);
        };
        input.click();
      }
    }
  });

  // Initialize TinyMCE for answer options
  tinymce.init({
    selector: 'textarea[name^="pilihan_"]',
    height: 100,
    plugins: 'link image lists code',
    toolbar: 'bold italic underline | forecolor | bullist numlist | link image | code',
    menubar: false,
    branding: false,
    placeholder: 'Tulis opsi jawaban...',
    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
    // PERBAIKAN: Upload gambar untuk pilihan jawaban
    images_upload_url: '<?= base_url('upload/image') ?>',
    images_upload_base_path: '<?= base_url('uploads/soal/') ?>',
    images_upload_credentials: true,
    file_picker_types: 'image',
    file_picker_callback: function (callback, value, meta) {
      if (meta.filetype === 'image') {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.onchange = function () {
          var file = this.files[0];

          // Upload file ke server
          var formData = new FormData();
          formData.append('file', file);

          fetch('<?= base_url('upload/image') ?>', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(result => {
            if (result.location) {
              callback(result.location, {
                alt: file.name
              });
            } else {
              alert('Error: ' + (result.error || 'Upload gagal'));
            }
          })
          .catch(error => {
            console.error('Upload error:', error);
            alert('Terjadi kesalahan saat upload gambar');
          });
        };
        input.click();
      }
    },
    setup: function (editor) {
      editor.on('init', function () {
        console.log('TinyMCE Answer Editor initialized for: ' + editor.id);
      });

      // Handle form validation
      editor.on('blur', function () {
        editor.save(); // Save content to textarea
      });
    }
  });

  // Form submission handler
  $('form').on('submit', function(e) {
    // Save all TinyMCE content before validation
    tinymce.triggerSave();

    // Check if required TinyMCE fields are empty
    var isValid = true;
    var errorMessages = [];

    // Check pertanyaan
    var pertanyaanContent = tinymce.get('editor-pertanyaan').getContent({format: 'text'}).trim();
    if (!pertanyaanContent) {
      isValid = false;
      errorMessages.push('Pertanyaan harus diisi');
    }

    // Check required pilihan (A, B, C, D)
    var requiredOptions = ['a', 'b', 'c', 'd'];
    requiredOptions.forEach(function(opt) {
      var editorId = 'editor-pilihan-' + opt;
      var editor = tinymce.get(editorId);
      if (editor) {
        var content = editor.getContent({format: 'text'}).trim();
        if (!content) {
          isValid = false;
          errorMessages.push('Pilihan ' + opt.toUpperCase() + ' harus diisi');
        }
      }
    });

    if (!isValid) {
      e.preventDefault();
      alert('Form tidak valid:\n' + errorMessages.join('\n'));
      return false;
    }

    return true;
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
