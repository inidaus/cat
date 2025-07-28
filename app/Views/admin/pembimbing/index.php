<?= $this->include('layouts/header') ?>

<style>
.card-pembimbing {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  border-radius: 1rem;
  transition: transform 0.2s ease-in-out;
}
.card-pembimbing:hover {
  transform: scale(1.02);
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
}
.card-pembimbing .card-body {
  padding: 1.5rem;
}
.card-pembimbing h5 {
  font-weight: 600;
  color: #fff;
}
.card-pembimbing p {
  margin-bottom: 1rem;
  color: #ddd;
}
</style>

<div class="glass-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="glass-title"><i class="fas fa-user-tie me-2"></i> Data Pembimbing</h2>
    <div class="d-flex flex-wrap gap-2">
      <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Pembimbing</button>
      <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
  </div>

  <div class="row g-3">
    <?php foreach ($pembimbing as $p): ?>
      <div class="col-md-6">
        <div class="card card-pembimbing">
          <div class="card-body">
            <h5 class="card-title mb-2"><i class="fas fa-chalkboard-teacher me-2"></i><?= esc($p['nama_lengkap']) ?></h5>
            <p class="card-text">
              <strong>NIP:</strong> <?= esc($p['nip']) ?>
            </p>
            <div class="d-flex gap-2">
              <button class="btn btn-warning btn-sm btn-edit" data-bs-toggle="modal" data-bs-target="#modalEdit"
                data-id="<?= $p['id'] ?>"
                data-nama="<?= esc($p['nama_lengkap']) ?>"
                data-nip="<?= esc($p['nip']) ?>">
                <i class="fas fa-edit"></i>
              </button>
              <form action="<?= base_url('admin/pembimbing/delete/' . $p['id']) ?>" method="post" onsubmit="return confirm('Yakin ingin menghapus pembimbing ini?')">
                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>
</div>

<!-- Modal Tambah Pembimbing -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white bg-opacity-75 border-0 rounded-3">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Pembimbing</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('admin/pembimbing/save') ?>" method="post">
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>NIP</label>
            <input type="text" name="nip" class="form-control" required>
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

<!-- Modal Edit Pembimbing -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white bg-opacity-75 border-0 rounded-3">
      <div class="modal-header">
        <h5 class="modal-title">Edit Pembimbing</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('admin/pembimbing/update') ?>" method="post">
        <input type="hidden" name="id" id="edit-id">
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="edit-nama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>NIP</label>
            <input type="text" name="nip" id="edit-nip" class="form-control" required>
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

<script>
  document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function () {
      document.getElementById('edit-id').value = this.dataset.id;
      document.getElementById('edit-nama').value = this.dataset.nama;
      document.getElementById('edit-nip').value = this.dataset.nip;
    });
  });
</script>

<?= $this->include('layouts/footer') ?>
