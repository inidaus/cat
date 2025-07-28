<?php echo $this->include('layouts/header'); ?>

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
  border-color: #fc4a1a;
  box-shadow: 0 0 0 0.2rem rgba(252, 74, 26, 0.25);
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

/* Make TinyMCE responsive */
@media (max-width: 768px) {
  .tox .tox-toolbar__group {
    flex-wrap: wrap;
  }
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
</style>

<div class="glass-wrapper">
  <div class="form-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="glass-title" style="background: linear-gradient(to right, #fc4a1a, #f7b733); color: #fff; padding: 10px 20px; border-radius: 8px;"><i class="fas fa-edit me-2"></i> Edit Soal</h2>
      <div class="d-flex gap-2">
        <a href="<?= base_url('admin/soal') ?>" class="btn btn-secondary btn-sm">Kembali ke Bank Soal</a>
        <a href="<?= base_url('admin/soal/preview/' . $soal['id']) ?>" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-eye"></i> Preview</a>
        <a href="<?= base_url('admin/soal/duplicate/' . $soal['id']) ?>" class="btn btn-success btn-sm"><i class="fas fa-copy"></i> Duplikat</a>
      </div>
    </div>

    <!-- Flash messages akan ditampilkan via toast -->

    <form action="<?= base_url('admin/soal/update') ?>" method="post">
      <input type="hidden" name="id" value="<?= $soal['id'] ?>">

      <div class="row mb-3">
        <div class="col-md-6">
          <label>Kategori Soal</label>
          <select name="kategori_id" class="form-select" required>
            <?php foreach ($kategori as $k): ?>
              <option value="<?= $k['id'] ?>" <?= $soal['kategori_id'] == $k['id'] ? 'selected' : '' ?>><?= esc($k['nama_kategori']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-md-6">
          <label>Pembimbing</label>
          <select name="pembimbing_id" class="form-select" required>
            <?php foreach ($pembimbing as $pb): ?>
              <option value="<?= $pb['id'] ?>" <?= $soal['pembimbing_id'] == $pb['id'] ? 'selected' : '' ?>><?= esc($pb['nama_lengkap']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label>Pertanyaan <span class="text-danger">*</span></label>
        <textarea name="pertanyaan" id="editor-pertanyaan" class="form-control" rows="5" required><?= $soal['pertanyaan'] ?></textarea>
      </div>

      <?php foreach (['a','b','c','d','e'] as $opt): ?>
        <?php $field = 'pilihan_' . $opt; ?>
        <div class="mb-3">
          <label>Pilihan <?= strtoupper($opt) ?> <?= $opt != 'e' ? '<span class="text-danger">*</span>' : '<span class="text-muted">(Opsional)</span>' ?></label>
          <textarea name="<?= $field ?>" class="form-control" rows="3" <?= $opt != 'e' ? 'required' : '' ?>><?= $soal[$field] ?></textarea>
        </div>
      <?php endforeach ?>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label>Kunci Jawaban</label>
          <select name="kunci_jawaban" class="form-select" required>
            <?php foreach (['A','B','C','D','E'] as $opt): ?>
              <option value="<?= $opt ?>" <?= $soal['kunci_jawaban'] == $opt ? 'selected' : '' ?>><?= $opt ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label>Bobot Nilai</label>
          <input type="number" name="bobot" class="form-control" min="1" value="<?= $soal['bobot'] ?>">
        </div>
      </div>

      <div class="d-grid mb-4">
        <button class="btn btn-warning">Perbarui Soal</button>
      </div>
    </form>
  </div>
</div>

<?php echo $this->include('layouts/footer'); ?>

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
