<?php $role = session('role'); ?>

<a href="<?= base_url($role . '/dashboard') ?>"><i class="fas fa-home me-2"></i>Dashboard</a>

<?php if ($role === 'admin'): ?>
  <a href="<?= base_url('admin/pembimbing') ?>"><i class="fas fa-chalkboard-teacher me-2"></i>Kelola Pembimbing</a>
  <a href="<?= base_url('admin/peserta') ?>"><i class="fas fa-user-graduate me-2"></i>Kelola Peserta</a>
<?php endif; ?>

<?php if ($role === 'admin'): ?>
  <a href="#"><i class="fas fa-users me-2"></i>Data Peserta</a>
  <a href="#"><i class="fas fa-question-circle me-2"></i>Bank Soal</a>
  <a href="#"><i class="fas fa-chart-bar me-2"></i>Rekap Nilai</a>
<?php elseif ($role === 'pembimbing'): ?>
  <a href="#"><i class="fas fa-user-check me-2"></i>Monitoring</a>
  <a href="#"><i class="fas fa-question me-2"></i>Input Soal</a>
<?php elseif ($role === 'peserta'): ?>
  <a href="#"><i class="fas fa-book-open me-2"></i>Ujian</a>
  <a href="#"><i class="fas fa-list me-2"></i>Hasil Ujian</a>
<?php endif; ?>
