<footer class="text-center mt-5 text-white small">
  <p class="mb-1">&copy; <?= date('Y') ?> <strong>CBT System 3.17</strong> – Scrab Team Supported • Dev. by <a href="https://inova.my.id" target="_blank" class="text-warning">Langit Inovasi</a></p>
  <em>Membentuk Masa Depan Digital Pendidikan Indonesia</em>
  <div class="mt-2">
    <small class="text-muted">
      <i class="fas fa-code"></i> PHP <?= PHP_VERSION ?> |
      <i class="fas fa-check-circle"></i> Supports PHP 8.2 - 8.4
    </small>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Toggle status peserta
  document.querySelectorAll('.toggle-status').forEach(function(toggle) {
    toggle.addEventListener('change', function () {
      const userId = this.getAttribute('data-id');
      const isChecked = this.checked;

      fetch(`<?= base_url('admin/peserta/toggle') ?>/${userId}`, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (!data.success) {
          alert('Gagal mengubah status');
          this.checked = !isChecked;
        }
      })
      .catch(err => {
        alert('Error: ' + err);
        this.checked = !isChecked;
      });
    });
  });
</script>
<script>
  const btnCard = document.getElementById('btnCard');
  const btnTable = document.getElementById('btnTable');
  const cardView = document.getElementById('cardView');
  const tableView = document.getElementById('tableView');

  if (btnCard && btnTable && cardView && tableView) {
    btnCard.addEventListener('click', () => {
      btnCard.classList.add('active');
      btnTable.classList.remove('active');
      cardView.classList.remove('d-none');
      tableView.classList.add('d-none');
    });

    btnTable.addEventListener('click', () => {
      btnTable.classList.add('active');
      btnCard.classList.remove('active');
      tableView.classList.remove('d-none');
      cardView.classList.add('d-none');
    });
  }
</script>

<script>
  // Isi otomatis form edit saat tombol Edit diklik
  document.querySelectorAll('.btn-edit').forEach(function(button) {
    button.addEventListener('click', function () {
      const editId = document.getElementById('edit-id');
      const editNama = document.getElementById('edit-nama');
      const editNim = document.getElementById('edit-nim');
      const editAngkatan = document.getElementById('edit-angkatan');

      if (editId) editId.value = this.dataset.id;
      if (editNama) editNama.value = this.dataset.nama;
      if (editNim) editNim.value = this.dataset.nim;
      if (editAngkatan) editAngkatan.value = this.dataset.angkatan;
    });
  });
</script>
<script>
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const keyword = this.value.toLowerCase();
      const items = document.querySelectorAll('.peserta-item');

      items.forEach(function (item) {
        const nama = item.querySelector('.nama-peserta')?.textContent.toLowerCase() || '';
        const nim  = item.querySelector('.nim-peserta')?.textContent.toLowerCase() || '';

        if (nama.includes(keyword) || nim.includes(keyword)) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    });
  }
</script>
