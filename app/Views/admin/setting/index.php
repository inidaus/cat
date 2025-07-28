<?= $this->include('layouts/header') ?>

<style>
.card-admin {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
  color: #fff;
  transition: 0.3s;
  height: 100%;
}
.card-admin:hover {
  transform: scale(1.02);
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.15);
}
.text-light-title {
  color: #fff;
  text-shadow: 0 0 5px rgba(255,255,255,0.3);
}
</style>

<div class="glass-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="glass-title"><i class="fas fa-cog me-2"></i> Pengaturan</h2>
    <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary btn-sm">Kembali</a>
  </div>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>

  <div class="mb-4">
    <h5 class="mb-2 text-light-title">🧑‍💼 Administrator</h5>
    <button class="btn btn-success btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Admin</button>

    <div class="row g-3">
      <?php foreach ($admin as $a): ?>
        <div class="col-md-4">
          <div class="card card-admin p-3 h-100">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-user-circle me-2"></i><?= esc($a['nama_lengkap']) ?></h5>
              <p class="card-text"><strong>Username:</strong> <?= esc($a['username']) ?></p>
              <div class="d-flex gap-2">
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $a['id'] ?>">
                  <i class="fas fa-edit"></i> Edit
                </button>
                <form action="<?= base_url('admin/setting/deleteAdmin/' . $a['id']) ?>" method="post" onsubmit="return confirm('Hapus admin ini?')" class="d-inline">
                  <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach ?>
    </div>
  </div>


</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('admin/setting/saveAdmin') ?>" method="post">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Admin</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Admin -->
<?php foreach ($admin as $a): ?>
<div class="modal fade" id="modalEdit<?= $a['id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('admin/setting/updateAdmin') ?>" method="post">
        <input type="hidden" name="id" value="<?= $a['id'] ?>">
        <div class="modal-header">
          <h5 class="modal-title">Edit Admin</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="<?= esc($a['nama_lengkap']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?= esc($a['username']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Password Baru (kosongkan jika tidak ingin mengubah)</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach ?>

<?= $this->include('layouts/footer') ?>
