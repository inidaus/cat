<?= $this->include('layouts/header') ?>

<h2 class="glass-title">Tambah Pembimbing</h2>

<form action="<?= base_url('admin/pembimbing/save') ?>" method="post">
  <div class="mb-3">
    <label>Nama Lengkap</label>
    <input type="text" name="nama_lengkap" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Username</label>
    <input type="text" name="username" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control">
  </div>
  <div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <button class="btn btn-primary">Simpan</button>
</form>

<?= $this->include('layouts/footer') ?>
