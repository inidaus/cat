<?= $this->include('layouts/header') ?>

<div class="glass-wrapper">
  <div class="form-container">
    <h2 class="glass-title mb-4"><i class="fas fa-edit me-2"></i>Edit Ujian</h2>

    <form action="<?= base_url('pembimbing/ujian/update/' . $ujian['id']) ?>" method="post">
      <div class="mb-3">
        <label for="judul" class="form-label">Judul Ujian</label>
        <input type="text" name="judul" class="form-control" required value="<?= esc($ujian['judul']) ?>">
      </div>

      <div class="mb-3">
        <label for="kategori_id" class="form-label">Kategori Soal</label>
        <select name="kategori_id" class="form-select" required>
          <option value="">- Pilih -</option>
          <?php foreach ($kategori as $k): ?>
            <option value="<?= $k['id'] ?>" <?= $k['id'] == $ujian['kategori_id'] ? 'selected' : '' ?>>
              <?= esc($k['nama_kategori']) ?>
            </option>
          <?php endforeach ?>
        </select>
      </div>

      <div class="row g-3">
        <div class="col-md-4">
          <label>Jumlah Soal</label>
          <input type="number" name="jumlah_soal" class="form-control" required value="<?= $ujian['jumlah_soal'] ?>">
        </div>
        <div class="col-md-4">
          <label>Durasi (menit)</label>
          <input type="number" name="waktu_menit" class="form-control" required value="<?= $ujian['waktu_menit'] ?>">
        </div>
        <div class="col-md-4">
          <label>Toleransi Terlambat (menit)</label>
          <input type="number" name="toleransi_menit" class="form-control" required value="<?= $ujian['toleransi_menit'] ?>">
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6">
          <label>Tanggal Mulai</label>
          <input type="datetime-local" name="mulai" class="form-control" required value="<?= date('Y-m-d\TH:i', strtotime($ujian['mulai'])) ?>">
        </div>
        <div class="col-md-6">
          <label>Passing Grade</label>
          <input type="number" name="passing_grade" class="form-control" required value="<?= $ujian['passing_grade'] ?>">
        </div>
      </div>

      <div class="form-check form-switch my-3">
        <input class="form-check-input" type="checkbox" name="acak_soal" value="1" <?= $ujian['acak_soal'] ? 'checked' : '' ?>>
        <label class="form-check-label">Acak Soal</label>
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?= base_url('pembimbing/ujian') ?>" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<?= $this->include('layouts/footer') ?>
