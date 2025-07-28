<?= $this->include('layouts/header') ?>

<style>
.preview-container {
  max-width: 800px;
  margin: auto;
  background: #fff;
  padding: 2rem;
  border-radius: 1rem;
  box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.question-card {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 0.5rem;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.answer-option {
  background: #fff;
  border: 2px solid #e9ecef;
  border-radius: 0.5rem;
  padding: 1rem;
  margin-bottom: 0.75rem;
  transition: all 0.3s ease;
}

.answer-option:hover {
  border-color: #007bff;
  background-color: #f8f9ff;
}

.answer-option.correct {
  border-color: #28a745;
  background-color: #d4edda;
}

.option-label {
  display: inline-block;
  width: 30px;
  height: 30px;
  background: #6c757d;
  color: white;
  border-radius: 50%;
  text-align: center;
  line-height: 30px;
  font-weight: bold;
  margin-right: 1rem;
}

.answer-option.correct .option-label {
  background: #28a745;
}

.info-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.875rem;
  font-weight: 500;
}

.badge-kategori {
  background: #e3f2fd;
  color: #1976d2;
}

.badge-bobot {
  background: #fff3e0;
  color: #f57c00;
}

.badge-kunci {
  background: #e8f5e8;
  color: #2e7d32;
}

/* CSS untuk gambar dalam soal */
.question-content img,
.option-content img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  margin: 10px 0;
  display: block;
}
</style>

<div class="glass-wrapper">
  <div class="preview-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="glass-title" style="background: linear-gradient(to right, #667eea, #764ba2); color: #fff; padding: 10px 20px; border-radius: 8px;">
        <i class="fas fa-eye me-2"></i> Preview Soal
      </h2>
      <div class="d-flex gap-2">
        <button onclick="window.close()" class="btn btn-secondary btn-sm">
          <i class="fas fa-times me-1"></i> Tutup
        </button>
        <a href="<?= base_url('pembimbing/soal/edit/' . $soal['id']) ?>" class="btn btn-warning btn-sm" target="_blank">
          <i class="fas fa-edit me-1"></i> Edit
        </a>
        <button onclick="window.print()" class="btn btn-info btn-sm">
          <i class="fas fa-print me-1"></i> Print
        </button>
      </div>
    </div>

    <!-- Info Soal -->
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="d-flex flex-wrap gap-2">
          <span class="info-badge badge-kategori">
            <i class="fas fa-folder me-1"></i> <?= esc($soal['kategori']) ?>
          </span>
          <span class="info-badge badge-bobot">
            <i class="fas fa-weight me-1"></i> Bobot: <?= $soal['bobot'] ?>
          </span>
          <span class="info-badge badge-kunci">
            <i class="fas fa-key me-1"></i> Kunci: <?= $soal['kunci_jawaban'] ?>
          </span>
        </div>
      </div>
    </div>

    <!-- Pertanyaan -->
    <div class="question-card">
      <h5 class="mb-3">
        <i class="fas fa-question-circle text-primary me-2"></i>Pertanyaan:
      </h5>
      <div class="question-content">
        <?= $soal['pertanyaan'] ?>
      </div>
    </div>

    <!-- Pilihan Jawaban -->
    <div class="mb-4">
      <h5 class="mb-3">
        <i class="fas fa-list text-success me-2"></i>Pilihan Jawaban:
      </h5>

      <?php
      $options = ['A', 'B', 'C', 'D', 'E'];
      $fields = ['pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e'];
      ?>

      <?php for ($i = 0; $i < 5; $i++): ?>
        <?php if (!empty($soal[$fields[$i]])): ?>
          <div class="answer-option <?= $soal['kunci_jawaban'] == $options[$i] ? 'correct' : '' ?>">
            <span class="option-label"><?= $options[$i] ?></span>
            <span class="option-content"><?= $soal[$fields[$i]] ?></span>
            <?php if ($soal['kunci_jawaban'] == $options[$i]): ?>
              <i class="fas fa-check-circle text-success float-end mt-1"></i>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      <?php endfor; ?>
    </div>

    <!-- Footer Info -->
    <div class="text-center text-muted mt-4">
      <small>
        <i class="fas fa-clock me-1"></i>
        Preview dibuat pada <?= date('d/m/Y H:i:s') ?>
      </small>
    </div>
  </div>
</div>

<script>
// Auto focus untuk accessibility
document.addEventListener('DOMContentLoaded', function() {
  // Highlight kunci jawaban
  const correctAnswer = document.querySelector('.answer-option.correct');
  if (correctAnswer) {
    correctAnswer.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
});

// Print styles
window.addEventListener('beforeprint', function() {
  document.body.style.background = 'white';
});

window.addEventListener('afterprint', function() {
  document.body.style.background = '';
});
</script>

<?= $this->include('layouts/footer') ?>
