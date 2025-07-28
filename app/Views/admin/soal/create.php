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
      <h2 class="glass-title" style="background: linear-gradient(to right, #6a11cb, #2575fc); color: #fff; padding: 10px 20px; border-radius: 8px;"><i class="fas fa-plus me-2"></i> Tambah Soal</h2>
      <a href="<?= base_url('admin/soal') ?>" class="btn btn-secondary btn-sm">Kembali ke Bank Soal</a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
    <?php endif ?>

    <form action="<?= base_url('admin/soal/save') ?>" method="post">
      <div class="row mb-3">
        <div class="col-md-6">
          <label>Kategori Soal</label>
          <select name="kategori_id" class="form-select" required>
            <option value="">- Pilih -</option>
            <?php foreach ($kategori as $k): ?>
              <option value="<?= $k['id'] ?>"><?= esc($k['nama_kategori']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-md-6">
          <label>Pembimbing</label>
          <select name="pembimbing_id" class="form-select" required>
            <option value="">- Pilih -</option>
            <?php foreach ($pembimbing as $pb): ?>
              <option value="<?= $pb['id'] ?>"><?= esc($pb['nama_lengkap']) ?></option>
            <?php endforeach ?>
          </select>
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

      <div class="row">
        <div class="col-md-6 mb-3">
          <label>Kunci Jawaban</label>
          <select name="kunci_jawaban" class="form-select" required>
            <?php foreach (['A','B','C','D','E'] as $opt): ?>
              <option value="<?= $opt ?>"><?= $opt ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label>Bobot Nilai</label>
          <input type="number" name="bobot" class="form-control" min="1" value="1">
        </div>
      </div>

      <div class="d-grid">
        <button class="btn btn-primary">Simpan Soal</button>
      </div>
    </form>
  </div>
</div>

<?php echo $this->include('layouts/footer'); ?>

<!-- WYSIWYG Editor - TinyMCE -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
<script>
$(document).ready(function() {
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

  // Add IDs to textareas for TinyMCE first
  $('textarea[name^="pilihan_"]').each(function(index) {
    var optionLetter = $(this).attr('name').split('_')[1];
    $(this).attr('id', 'editor-pilihan-' + optionLetter);
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
});
</script>
