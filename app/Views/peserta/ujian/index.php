<?php echo $this->include('layouts/header'); ?>

<style>
.timer-box {
  background: linear-gradient(135deg, #667eea, #764ba2);
  border-radius: 15px;
  padding: 1.5rem;
  box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
  font-weight: bold;
  text-align: center;
  color: white;
  position: relative;
  overflow: hidden;
}

.timer-box::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
  opacity: 0.3;
}

.timer-label {
  font-size: 0.9rem;
  opacity: 0.9;
  margin-bottom: 0.5rem;
  text-transform: uppercase;
  letter-spacing: 1px;
  position: relative;
}

.timer-value {
  font-size: 2.5rem;
  font-weight: 700;
  position: relative;
}

.timer-info {
  background: rgba(255,255,255,0.1);
  border-radius: 10px;
  padding: 0.75rem;
  margin-top: 1rem;
  font-size: 0.9rem;
  position: relative;
}

.nav-soal {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 1rem;
}

.nav-soal-button {
  width: 35px;
  height: 35px;
  border: none;
  border-radius: 50%;
  font-size: 0.9rem;
  padding: 0;
  text-align: center;
  background-color: rgba(255,255,255,0.1);
  color: white;
  transition: all 0.3s ease;
}

.nav-soal-button:hover {
  transform: scale(1.1);
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.nav-soal-button.answered {
  background: linear-gradient(135deg, #28a745, #20c997) !important;
  color: white !important;
  box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
}

.nav-soal-button.active {
  border: 3px solid white;
  font-weight: bold;
  transform: scale(1.1);
}

.card-ujian {
  background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
  backdrop-filter: blur(15px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 20px 40px rgba(0,0,0,0.1);
  color: white;
}

.progress-container {
  margin-bottom: 1.5rem;
}

.progress {
  height: 10px;
  border-radius: 5px;
  background: rgba(255,255,255,0.1);
  overflow: hidden;
}

.progress-bar {
  background: linear-gradient(90deg, #667eea, #764ba2);
  border-radius: 5px;
  transition: width 0.5s ease;
}

.btn-nav {
  background: linear-gradient(135deg, #667eea, #764ba2);
  border: none;
  border-radius: 10px;
  padding: 0.75rem 1.5rem;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-nav:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
  color: white;
}

.btn-selesai {
  background: linear-gradient(135deg, #dc3545, #fd7e14);
  border: none;
  border-radius: 10px;
  padding: 0.75rem 1.5rem;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-selesai:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(220, 53, 69, 0.4);
  color: white;
}

.form-check-input {
  width: 1.2em;
  height: 1.2em;
  margin-top: 0.25em;
  cursor: pointer;
}

.form-check-label {
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 10px;
  transition: all 0.2s ease;
}

.form-check-label:hover {
  background: rgba(255,255,255,0.1);
}

.form-check-input:checked + .form-check-label {
  background: rgba(102, 126, 234, 0.2);
  font-weight: 500;
}

.soal-number {
  display: inline-block;
  width: 30px;
  height: 30px;
  line-height: 30px;
  text-align: center;
  background: rgba(102, 126, 234, 0.2);
  border-radius: 50%;
  margin-right: 0.5rem;
}

@keyframes pulse {
  0% { opacity: 1; }
  50% { opacity: 0.5; }
  100% { opacity: 1; }
}

.nav-kategori-header {
  margin-top: 1rem;
  margin-bottom: 0.5rem;
  padding: 0.25rem 0;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}

.nav-kategori-header:first-child {
  margin-top: 0;
}

.nav-soal-container {
  display: flex;
  flex-wrap: wrap;
  gap: 0.25rem;
}

/* Timeout Modal Animations */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes pulse {
  0%, 100% { transform: scale(1); opacity: 0.3; }
  50% { transform: scale(1.1); opacity: 0.1; }
}

@keyframes clockTick {
  0%, 50% { transform: rotate(0deg); }
  25% { transform: rotate(-10deg); }
  75% { transform: rotate(10deg); }
  100% { transform: rotate(0deg); }
}

@keyframes progressGlow {
  0%, 100% { box-shadow: 0 0 5px rgba(40, 167, 69, 0.5); }
  50% { box-shadow: 0 0 20px rgba(40, 167, 69, 0.8); }
}

/* CSS untuk gambar dalam soal ujian */
#judul-soal img,
#opsi-jawaban img,
.form-check-label img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  margin: 10px 0;
  display: block;
}
</style>

<div class="glass-wrapper">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="text-white mb-1">
        <i class="fas fa-pen-alt me-2"></i><?= esc($ujian['judul']) ?>
      </h2>
      <p class="text-white-50 mb-0">
        <i class="fas fa-folder me-1"></i><?= esc($kategori['nama_kategori']) ?>
      </p>
    </div>
    <div>
      <a href="<?= base_url('peserta/dashboard') ?>" class="btn btn-nav btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Dashboard
      </a>
    </div>
  </div>

  <!-- Progress Bar -->
  <div class="progress-container">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <span class="text-white-50 small">Progress Pengerjaan</span>
      <span class="text-white small" id="progress-text">0 dari 0 soal</span>
    </div>
    <div class="progress">
      <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Soal Section -->
    <div class="col-lg-8">
      <div class="card-ujian">
        <div id="soal-container">
          <div class="d-flex align-items-center mb-3">
            <span class="soal-number" id="soal-number">1</span>
            <h5 class="mb-0" id="judul-soal">Loading...</h5>
          </div>
          <div id="opsi-jawaban"></div>
        </div>

        <!-- Navigation Buttons -->
        <div class="mt-4 d-flex justify-content-between align-items-center">
          <button class="btn btn-nav" id="btn-prev" style="display:none;">
            <i class="fas fa-chevron-left me-1"></i>Sebelumnya
          </button>
          <div class="d-flex gap-2">
            <button class="btn btn-nav" id="btn-lewati">
              <i class="fas fa-forward me-1"></i>Lewati
            </button>
            <button type="button" class="btn btn-selesai d-none" id="btn-selesai">
              <i class="fas fa-check me-1"></i>Selesai Ujian
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
      <!-- Timer -->
      <div class="timer-box mb-4">
        <div class="timer-label">
          <i class="fas fa-clock me-1"></i>Sisa Waktu
        </div>
        <div class="timer-value" id="timer">--:--</div>
        <div class="timer-info" id="timer-info">
          Ujian berakhir pada: <span id="end-time">--:--</span>
        </div>
      </div>

      <!-- Navigation Soal -->
      <div class="card-ujian">
        <h6 class="mb-3">
          <i class="fas fa-list me-2"></i>Navigasi Soal
        </h6>
        <div class="nav-soal" id="nav-soal"></div>

        <!-- Legend -->
        <div class="mt-3 pt-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
          <div class="d-flex justify-content-between text-white-50 small">
            <span><i class="fas fa-circle me-1" style="color: rgba(255,255,255,0.3);"></i>Belum dijawab</span>
            <span><i class="fas fa-circle me-1" style="color: #28a745;"></i>Sudah dijawab</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const soalListByKategori = <?= json_encode($soalListByKategori) ?>;
const kategoriInfo = <?= json_encode($kategoriInfo) ?>;
let jawaban = <?= json_encode($jawaban) ?>;
const pesertaId = <?= $pesertaId ?>;
const ujianId = <?= $ujian['id'] ?>;

// Sequential mata pelajaran variables
let currentKategoriIndex = parseInt(localStorage.getItem(`current_kategori_${ujianId}`)) || 0;
let currentSoalIndex = parseInt(localStorage.getItem(`current_soal_${ujianId}`)) || 0;

// Pastikan data tersedia
if (!soalListByKategori || soalListByKategori.length === 0) {
  console.error('soalListByKategori is empty or undefined');
  alert('Error: Data soal tidak tersedia. Silakan refresh halaman.');
}

let currentKategoriData = soalListByKategori[currentKategoriIndex];
let currentSoalList = currentKategoriData ? currentKategoriData.soal : [];

// Validasi data
if (!currentKategoriData) {
  console.error('currentKategoriData is undefined, resetting to first kategori');
  currentKategoriIndex = 0;
  currentSoalIndex = 0;
  currentKategoriData = soalListByKategori[0];
  currentSoalList = currentKategoriData ? currentKategoriData.soal : [];
}

// Perhitungan waktu per mata pelajaran
function initializeTimer() {
  if (!currentKategoriData) {
    console.error('currentKategoriData is not defined');
    return { durasi: 0, waktuMulaiKategori: Date.now(), waktuMenit: 60 };
  }

  const kategoriKey = `kategori_start_time_${ujianId}_${currentKategoriIndex}`;
  const waktuMenit = currentKategoriData.waktu_menit;

  let waktuMulaiKategori = localStorage.getItem(kategoriKey);
  if (!waktuMulaiKategori) {
    waktuMulaiKategori = new Date().getTime();
    localStorage.setItem(kategoriKey, waktuMulaiKategori);
  } else {
    waktuMulaiKategori = parseInt(waktuMulaiKategori);
  }

  const sekarang = new Date().getTime();
  const waktuBerlalu = Math.floor((sekarang - waktuMulaiKategori) / 1000);
  const totalWaktuKategori = waktuMenit * 60;
  let durasi = totalWaktuKategori - waktuBerlalu;

  if (durasi < 0) durasi = 0;

  return { durasi, waktuMulaiKategori, waktuMenit };
}

let timerData = initializeTimer();
let durasi = timerData.durasi;

// Debug info
console.log('=== DEBUG WAKTU UJIAN (Sequential) ===');
console.log('Current kategori:', currentKategoriData.nama_kategori);
console.log('Waktu mulai kategori:', new Date(timerData.waktuMulaiKategori));
console.log('Waktu sekarang:', new Date());
console.log('Durasi kategori (menit):', timerData.waktuMenit);
console.log('Sisa durasi (detik):', durasi);
console.log('=======================================');

// Set waktu selesai untuk display (per kategori)
function updateEndTime() {
  const waktuSelesai = timerData.waktuMulaiKategori + (timerData.waktuMenit * 60000);
  const endTime = new Date(waktuSelesai);
  const endTimeElement = document.getElementById('end-time');
  if (endTimeElement) {
    endTimeElement.textContent = endTime.toLocaleTimeString('id-ID', {
      hour: '2-digit',
      minute: '2-digit'
    });
  }
}



// Muat jawaban dari localStorage saat halaman dimuat
function loadJawabanFromStorage() {
  soalListByKategori.forEach(kategori => {
    kategori.soal.forEach(s => {
      const localJawaban = localStorage.getItem(`jawaban_${ujianId}_${s.id}`);
      if (localJawaban && !jawaban[s.id]) {
        jawaban[s.id] = localJawaban;
      }
    });
  });
}

// PERBAIKAN: Fungsi untuk mengumpulkan semua jawaban dari localStorage
function getAllJawabanFromStorage() {
  const allJawaban = {};
  soalListByKategori.forEach(kategori => {
    kategori.soal.forEach(s => {
      const localJawaban = localStorage.getItem(`jawaban_${ujianId}_${s.id}`);
      if (localJawaban) {
        allJawaban[s.id] = localJawaban;
      }
    });
  });
  return allJawaban;
}

// PERBAIKAN: Fungsi untuk sinkronisasi semua jawaban sebelum menyelesaikan ujian
function sinkronisasiSemuaJawaban() {
  return new Promise((resolve, reject) => {
    const allJawaban = getAllJawabanFromStorage();
    
    console.log('Jawaban yang akan disinkronisasi:', allJawaban);
    console.log('URL API:', "<?= base_url('api/ujian/sinkronisasi-jawaban') ?>");

    if (Object.keys(allJawaban).length === 0) {
      console.log('Tidak ada jawaban untuk disinkronisasi');
      resolve();
      return;
    }

    const formData = new URLSearchParams();
    formData.append('ujian_id', ujianId);
    formData.append('jawaban_data', JSON.stringify(allJawaban));

    fetch("<?= base_url('api/ujian/sinkronisasi-jawaban') ?>", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    }).then(response => {
      console.log('Response status:', response.status);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      // PERBAIKAN: Cek content type sebelum parse JSON
      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        return response.text().then(text => {
          console.error('Response bukan JSON:', text);
          throw new Error('Server response bukan JSON');
        });
      }

      return response.json();
    }).then(result => {
      console.log('Hasil sinkronisasi:', result);
      if (result.status === 'success') {
        console.log("Semua jawaban berhasil disinkronisasi:", result.message);
        resolve();
      } else {
        console.error("Gagal sinkronisasi:", result.message);
        reject(new Error(result.message));
      }
    }).catch(err => {
      console.error("Error sinkronisasi:", err);
      reject(err);
    });
  });
}

// PERBAIKAN: Event handler untuk tombol selesai ujian dengan tampilan menarik
function selesaiUjian() {
  showCustomConfirmDialog();
}

function showCustomConfirmDialog() {
  // Create beautiful modal overlay
  const modalOverlay = document.createElement('div');
  modalOverlay.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    animation: fadeIn 0.3s ease-out;
  `;

  // Create modal content
  const modalContent = document.createElement('div');
  modalContent.style.cssText = `
    background: white;
    border-radius: 20px;
    padding: 40px;
    max-width: 500px;
    width: 90%;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    transform: scale(0.9);
    animation: scaleIn 0.3s ease-out forwards;
  `;

  // Count answered questions
  let totalAnswered = 0;
  let totalSoal = 0;
  soalListByKategori.forEach(kategori => {
    kategori.soal.forEach(s => {
      totalSoal++;
      if (jawaban[s.id]) {
        totalAnswered++;
      }
    });
  });

  const percentage = totalSoal > 0 ? Math.round((totalAnswered / totalSoal) * 100) : 0;

  modalContent.innerHTML = `
    <div style="margin-bottom: 30px;">
      <div style="
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2.5rem;
      ">
        ⚠️
      </div>

      <h2 style="color: #2c3e50; margin-bottom: 15px; font-weight: 600;">
        Selesaikan Ujian?
      </h2>

      <p style="color: #7f8c8d; font-size: 1.1rem; margin-bottom: 25px;">
        Pastikan semua jawaban sudah tersimpan dengan benar
      </p>
    </div>

    <div style="
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      padding: 20px;
      border-radius: 15px;
      margin-bottom: 25px;
    ">
      <h4 style="margin-bottom: 15px;">📊 Status Pengerjaan</h4>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; text-align: left;">
        <div>
          <strong>📝 Soal Dijawab:</strong><br>
          <span style="font-size: 1.2rem;">${totalAnswered} dari ${totalSoal}</span>
        </div>
        <div>
          <strong>📈 Persentase:</strong><br>
          <span style="font-size: 1.2rem;">${percentage}%</span>
        </div>
      </div>
    </div>

    ${totalAnswered < totalSoal ? `
      <div style="
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 25px;
        font-size: 0.95rem;
      ">
        <strong>⚠️ Peringatan:</strong> Masih ada ${totalSoal - totalAnswered} soal yang belum dijawab
      </div>
    ` : ''}

    <div style="display: flex; gap: 15px; justify-content: center;">
      <button onclick="cancelFinish()" style="
        background: #6c757d;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
      " onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
        ❌ Batal
      </button>

      <button onclick="confirmFinish()" style="
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
      " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(255, 107, 107, 0.6)'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(255, 107, 107, 0.4)'">
        ✅ Ya, Selesaikan
      </button>
    </div>
  `;

  // Add CSS animations
  const style = document.createElement('style');
  style.textContent = `
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes scaleIn {
      from { transform: scale(0.9); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
  `;
  document.head.appendChild(style);

  modalOverlay.appendChild(modalContent);
  document.body.appendChild(modalOverlay);

  // Global functions for buttons
  window.cancelFinish = function() {
    document.body.removeChild(modalOverlay);
    document.head.removeChild(style);
  };

  window.confirmFinish = function() {
    document.body.removeChild(modalOverlay);
    document.head.removeChild(style);
    proceedWithFinish();
  };
}

function proceedWithFinish() {

  const btnSelesai = document.getElementById('btn-selesai');
  btnSelesai.disabled = true;
  btnSelesai.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

  // Sinkronisasi semua jawaban terlebih dahulu
  sinkronisasiSemuaJawaban()
    .then(() => {
      console.log('Sinkronisasi berhasil, menyelesaikan ujian...');
      
      // Setelah sinkronisasi berhasil, baru kirim form selesai ujian
      const formData = new URLSearchParams();
      formData.append('ujian_id', ujianId);

      return fetch("<?= base_url('peserta/ujian/selesai') ?>", {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      });
    })
    .then(response => {
      console.log('Response selesai ujian:', response.status);
      if (response.ok) {
        // Hapus data localStorage setelah berhasil (sequential mata pelajaran)
        soalListByKategori.forEach(kategori => {
          kategori.soal.forEach(s => {
            localStorage.removeItem(`jawaban_${ujianId}_${s.id}`);
          });
        });
        localStorage.removeItem(`current_kategori_${ujianId}`);
        localStorage.removeItem(`current_soal_${ujianId}`);
        // Hapus waktu mulai per kategori
        for (let i = 0; i < soalListByKategori.length; i++) {
          localStorage.removeItem(`kategori_start_time_${ujianId}_${i}`);
        }
        
        console.log('Ujian berhasil diselesaikan, redirect ke dashboard');
        
        // Redirect ke dashboard
        window.location.href = "<?= base_url('peserta/dashboard') ?>";
      } else {
        throw new Error('Gagal menyelesaikan ujian');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Terjadi kesalahan saat menyelesaikan ujian: ' + error.message);
      btnSelesai.disabled = false;
      btnSelesai.innerHTML = 'Selesai Ujian';
    });
}

function renderSoal() {
  if (!currentSoalList || currentSoalList.length === 0) {
    console.error('No soal available for current kategori');
    return;
  }

  const soal = currentSoalList[currentSoalIndex];
  const currentJawaban = jawaban[soal.id] || null;

  // Update header mata pelajaran
  const kategoriHeader = document.querySelector('.text-white-50');
  if (kategoriHeader) {
    kategoriHeader.innerHTML = `<i class="fas fa-folder me-1"></i>${currentKategoriData.nama_kategori} (${currentKategoriIndex + 1}/${soalListByKategori.length})`;
  }

  // Update nomor soal
  const soalNumber = document.getElementById('soal-number');
  if (soalNumber) {
    soalNumber.textContent = currentSoalIndex + 1;
  }

  const judulSoal = document.getElementById('judul-soal');
  if (judulSoal) {
    judulSoal.innerHTML = soal.pertanyaan;
  }

  const opsi = ['A','B','C','D','E'];
  let html = '';
  opsi.forEach(k => {
    const key = 'pilihan_' + k.toLowerCase();
    if (soal[key]) {
      const checked = currentJawaban === k ? 'checked' : '';
      html += `
        <div class="form-check mb-3">
          <input class="form-check-input" type="radio" name="jawaban" value="${k}" id="opsi${k}" ${checked} onchange="handleJawabanChange('${k}')">
          <label class="form-check-label" for="opsi${k}">
            <strong>${k}.</strong> ${soal[key]}
          </label>
        </div>`;
    }
  });

  const opsiJawaban = document.getElementById('opsi-jawaban');
  if (opsiJawaban) {
    opsiJawaban.innerHTML = html;
  }

  updateNav();
  updateProgress();
  toggleButtons();

  // Save current position
  localStorage.setItem(`current_kategori_${ujianId}`, currentKategoriIndex);
  localStorage.setItem(`current_soal_${ujianId}`, currentSoalIndex);
}

// Handle jawaban change with auto-next
function handleJawabanChange(jawaban) {
  const soal = currentSoalList[currentSoalIndex];
  simpanJawaban(jawaban);

  // Auto next after 500ms delay
  setTimeout(() => {
    nextSoal();
  }, 500);
}

// Navigation functions for sequential mata pelajaran
function nextSoal() {
  if (currentSoalIndex < currentSoalList.length - 1) {
    currentSoalIndex++;
    renderSoal();
  } else {
    // End of current kategori, check if there's next kategori
    if (currentKategoriIndex < soalListByKategori.length - 1) {
      showKategoriTransition();
    } else {
      // End of all kategori
      showFinishDialog();
    }
  }
}

function prevSoal() {
  if (currentSoalIndex > 0) {
    currentSoalIndex--;
    renderSoal();
  } else if (currentKategoriIndex > 0) {
    // Go to previous kategori
    currentKategoriIndex--;
    currentKategoriData = soalListByKategori[currentKategoriIndex];
    currentSoalList = currentKategoriData.soal;
    currentSoalIndex = currentSoalList.length - 1;
    timerData = initializeTimer();
    durasi = timerData.durasi;
    updateEndTime();
    renderSoal();
  }
}

function showKategoriTransition() {
  const nextKategori = soalListByKategori[currentKategoriIndex + 1];

  // Create beautiful modal overlay
  const modalOverlay = document.createElement('div');
  modalOverlay.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(74, 144, 226, 0.9), rgba(80, 200, 120, 0.9));
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    backdrop-filter: blur(10px);
  `;

  modalOverlay.innerHTML = `
    <div style="
      background: white;
      border-radius: 20px;
      padding: 40px;
      max-width: 500px;
      width: 90%;
      text-align: center;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      transform: scale(0.8);
      opacity: 0;
      transition: all 0.3s ease;
    " id="transition-modal">
      <div style="
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: checkmark 0.6s ease-in-out;
      ">
        <i class="fas fa-check" style="color: white; font-size: 2.5rem;"></i>
      </div>

      <h2 style="color: #2c3e50; margin-bottom: 15px; font-weight: 600;">
        🎉 Selamat!
      </h2>

      <p style="color: #7f8c8d; font-size: 1.1rem; margin-bottom: 25px;">
        Anda telah menyelesaikan mata pelajaran
      </p>

      <div style="
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 15px 25px;
        border-radius: 15px;
        margin-bottom: 25px;
        font-weight: 600;
        font-size: 1.2rem;
      ">
        📚 ${currentKategoriData.nama_kategori}
      </div>

      <div style="
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        border-left: 4px solid #007bff;
      ">
        <h4 style="color: #495057; margin-bottom: 15px;">Selanjutnya:</h4>
        <div style="color: #007bff; font-weight: 600; font-size: 1.1rem; margin-bottom: 10px;">
          📖 ${nextKategori.nama_kategori}
        </div>
        <div style="color: #6c757d; display: flex; justify-content: space-around; font-size: 0.9rem;">
          <span>📝 ${nextKategori.jumlah_soal} soal</span>
          <span>⏱️ ${nextKategori.waktu_menit} menit</span>
          <span>🎯 Grade: ${nextKategori.passing_grade}</span>
        </div>
      </div>

      <div style="display: flex; gap: 15px; justify-content: center;">
        <button onclick="continueToNextKategori()" style="
          background: linear-gradient(135deg, #28a745, #20c997);
          color: white;
          border: none;
          padding: 12px 30px;
          border-radius: 25px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
          box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
          <i class="fas fa-arrow-right me-2"></i>Lanjutkan
        </button>

        <button onclick="stayInCurrentKategori()" style="
          background: #6c757d;
          color: white;
          border: none;
          padding: 12px 30px;
          border-radius: 25px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
        " onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
          <i class="fas fa-pause me-2"></i>Tunda
        </button>
      </div>
    </div>
  `;

  document.body.appendChild(modalOverlay);

  // Animate modal in
  setTimeout(() => {
    const modal = document.getElementById('transition-modal');
    modal.style.transform = 'scale(1)';
    modal.style.opacity = '1';
  }, 100);

  // Add CSS animation for checkmark
  const style = document.createElement('style');
  style.textContent = `
    @keyframes checkmark {
      0% { transform: scale(0) rotate(0deg); }
      50% { transform: scale(1.2) rotate(180deg); }
      100% { transform: scale(1) rotate(360deg); }
    }
  `;
  document.head.appendChild(style);

  // Global functions for buttons
  window.continueToNextKategori = function() {
    document.body.removeChild(modalOverlay);
    currentKategoriIndex++;
    currentKategoriData = soalListByKategori[currentKategoriIndex];
    currentSoalList = currentKategoriData.soal;
    currentSoalIndex = 0;
    timerData = initializeTimer();
    durasi = timerData.durasi;
    updateEndTime();
    renderSoal();
  };

  window.stayInCurrentKategori = function() {
    document.body.removeChild(modalOverlay);
    // Stay in current kategori, user can continue later
  };
}

function showFinishDialog() {
  // Create beautiful finish modal overlay
  const modalOverlay = document.createElement('div');
  modalOverlay.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.9), rgba(255, 193, 7, 0.9));
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    backdrop-filter: blur(10px);
  `;

  modalOverlay.innerHTML = `
    <div style="
      background: white;
      border-radius: 20px;
      padding: 40px;
      max-width: 500px;
      width: 90%;
      text-align: center;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      transform: scale(0.8);
      opacity: 0;
      transition: all 0.3s ease;
    " id="finish-modal">
      <div style="
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #ff6b6b, #ffa726);
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: finishPulse 1s ease-in-out infinite;
      ">
        <i class="fas fa-flag-checkered" style="color: white; font-size: 2.5rem;"></i>
      </div>

      <h2 style="color: #2c3e50; margin-bottom: 15px; font-weight: 600;">
        🏁 Selesaikan Ujian?
      </h2>

      <p style="color: #7f8c8d; font-size: 1.1rem; margin-bottom: 25px;">
        Anda telah menyelesaikan semua mata pelajaran
      </p>

      <div style="
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 25px;
      ">
        <h4 style="margin-bottom: 15px;">📊 Ringkasan Ujian</h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; text-align: left;">
          <div>
            <strong>📚 Mata Pelajaran:</strong><br>
            <span style="font-size: 0.9rem;">${soalListByKategori.length} mata pelajaran</span>
          </div>
          <div>
            <strong>📝 Total Soal:</strong><br>
            <span style="font-size: 0.9rem;">${soalListByKategori.reduce((total, k) => total + k.soal.length, 0)} soal</span>
          </div>
        </div>
      </div>

      <div style="
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 25px;
        color: #856404;
      ">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Perhatian:</strong> Setelah menyelesaikan ujian, Anda tidak dapat mengubah jawaban lagi.
      </div>

      <div style="display: flex; gap: 15px; justify-content: center;">
        <button onclick="confirmFinishUjian()" style="
          background: linear-gradient(135deg, #dc3545, #c82333);
          color: white;
          border: none;
          padding: 12px 30px;
          border-radius: 25px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
          box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
          <i class="fas fa-check me-2"></i>Ya, Selesaikan
        </button>

        <button onclick="cancelFinishUjian()" style="
          background: #6c757d;
          color: white;
          border: none;
          padding: 12px 30px;
          border-radius: 25px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
        " onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
          <i class="fas fa-times me-2"></i>Batal
        </button>
      </div>
    </div>
  `;

  document.body.appendChild(modalOverlay);

  // Animate modal in
  setTimeout(() => {
    const modal = document.getElementById('finish-modal');
    modal.style.transform = 'scale(1)';
    modal.style.opacity = '1';
  }, 100);

  // Add CSS animation for pulse effect
  const style = document.createElement('style');
  style.textContent = `
    @keyframes finishPulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
  `;
  document.head.appendChild(style);

  // Global functions for buttons
  window.confirmFinishUjian = function() {
    document.body.removeChild(modalOverlay);
    selesaiUjian();
  };

  window.cancelFinishUjian = function() {
    document.body.removeChild(modalOverlay);
    // Stay in ujian, user can continue
  };
}

function simpanJawaban(j) {
  const soal = currentSoalList[currentSoalIndex];
  
  // Simpan ke localStorage terlebih dahulu
  localStorage.setItem(`jawaban_${ujianId}_${soal.id}`, j);
  jawaban[soal.id] = j;
  
  // Update UI segera setelah jawaban dipilih
  updateProgress();
  updateNav();
  
  // Kirim ke server
  fetch("<?= base_url('peserta/ujian/simpan-jawaban') ?>", {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({
      ujian_id: ujianId,
      soal_id: soal.id,
      jawaban: j
    })
  }).then(res => {
    if (res.ok) return res.json();
    throw new Error('Response bukan JSON');
  }).then(res => {
    if (res.status === 'success') {
      console.log("Jawaban berhasil disimpan ke server");
    } else {
      console.error("Gagal menyimpan ke server:", res.message);
    }
  }).catch(err => {
    console.error("Gagal simpan jawaban ke server:", err);
  });
}

function updateNav() {
  const nav = document.getElementById('nav-soal');
  if (!nav) return;

  nav.innerHTML = '';

  // Create navigation grouped by kategori
  soalListByKategori.forEach((kategori, kategoriIdx) => {
    // Add kategori header dengan warna yang lebih jelas
    const header = document.createElement('div');
    header.className = 'nav-kategori-header';
    header.innerHTML = `<small style="color: #ffffff; font-weight: 600; background: linear-gradient(135deg, #667eea, #764ba2); padding: 8px 12px; border-radius: 8px; display: block; margin-bottom: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">${kategori.nama_kategori}</small>`;
    nav.appendChild(header);

    // Add soal buttons for this kategori
    const soalContainer = document.createElement('div');
    soalContainer.className = 'nav-soal-container mb-2';

    kategori.soal.forEach((s, soalIdx) => {
      const btn = document.createElement('button');
      btn.innerText = soalIdx + 1;
      btn.className = 'nav-soal-button btn btn-sm me-1 mb-1';

      // Check if this is current soal
      if (kategoriIdx === currentKategoriIndex && soalIdx === currentSoalIndex) {
        btn.classList.add('active');
      }

      // Check if answered
      if (jawaban[s.id]) {
        btn.classList.add('answered');
      }

      btn.onclick = () => {
        currentKategoriIndex = kategoriIdx;
        currentKategoriData = soalListByKategori[currentKategoriIndex];
        currentSoalList = currentKategoriData.soal;
        currentSoalIndex = soalIdx;
        timerData = initializeTimer();
        durasi = timerData.durasi;
        updateEndTime();
        renderSoal();
      };

      soalContainer.appendChild(btn);
    });

    nav.appendChild(soalContainer);
  });
}

function updateProgress() {
  let totalAnswered = 0;
  let totalSoal = 0;

  // Count progress across all kategori
  soalListByKategori.forEach(kategori => {
    kategori.soal.forEach(s => {
      totalSoal++;
      if (jawaban[s.id]) {
        totalAnswered++;
      }
    });
  });

  const percent = totalSoal > 0 ? Math.round((totalAnswered / totalSoal) * 100) : 0;

  // Update progress bar
  const bar = document.getElementById('progressBar');
  if (bar) {
    bar.style.width = percent + '%';
    bar.setAttribute('aria-valuenow', percent);
    bar.innerText = percent + '%';
  }

  // Update progress text with kategori info
  const progressText = document.getElementById('progress-text');
  if (progressText) {
    progressText.textContent = `${totalAnswered} dari ${totalSoal} soal (${currentKategoriData.nama_kategori}: ${currentSoalIndex + 1}/${currentSoalList.length})`;
  }

  // Update document title
  document.title = `(${totalAnswered}/${totalSoal}) ${percent}% - <?= esc($ujian['judul']) ?>`;
}

function toggleButtons() {
  const prev = document.getElementById('btn-prev');
  const lewati = document.getElementById('btn-lewati');
  const selesai = document.getElementById('btn-selesai');

  // Show/hide previous button
  if (prev) {
    prev.style.display = (currentSoalIndex > 0 || currentKategoriIndex > 0) ? 'inline-block' : 'none';
  }

  // Show/hide lewati and selesai buttons
  if (lewati && selesai) {
    const isLastSoalOfLastKategori = (currentKategoriIndex === soalListByKategori.length - 1) &&
                                     (currentSoalIndex === currentSoalList.length - 1);

    if (isLastSoalOfLastKategori) {
      lewati.style.display = 'none';
      selesai.classList.remove('d-none');
    } else {
      lewati.style.display = 'inline-block';
      selesai.classList.add('d-none');
    }
  }
}




// Event listener untuk jawaban
document.addEventListener('change', e => {
  if (e.target.name === 'jawaban') {
    simpanJawaban(e.target.value);
  }
});

document.addEventListener('DOMContentLoaded', () => {
  loadJawabanFromStorage();

  const btnPrev = document.getElementById('btn-prev');
  const btnLewati = document.getElementById('btn-lewati');
  const btnSelesai = document.getElementById('btn-selesai');

  if (btnPrev) {
    btnPrev.addEventListener('click', () => {
      prevSoal();
    });
  }

  if (btnLewati) {
    btnLewati.addEventListener('click', () => {
      nextSoal();
    });
  }

  // PERBAIKAN: Event listener untuk tombol selesai
  if (btnSelesai) {
    btnSelesai.addEventListener('click', selesaiUjian);
  }

  // Load jawaban dari localStorage
  loadJawabanFromStorage();

  // Initialize timer dan render soal pertama
  timerData = initializeTimer();
  durasi = timerData.durasi;

  // Update end time display
  updateEndTime();

  updateTimer();
  renderSoal();
});

function updateTimer() {
  const m = String(Math.floor(durasi / 60)).padStart(2, '0');
  const s = String(durasi % 60).padStart(2, '0');
  const timerEl = document.getElementById('timer');
  if (timerEl) {
    timerEl.innerText = `${m}:${s}`;

    // Change color based on remaining time
    if (durasi <= 300) { // 5 minutes
      timerEl.style.color = '#ff6b6b';
      if (durasi <= 60) { // 1 minute
        timerEl.style.animation = 'pulse 1s infinite';
      }
    }
  }

  // Update timer info for current kategori
  const currentWaktuMenit = currentKategoriData ? currentKategoriData.waktu_menit : 60;
  const percentComplete = 100 - Math.round((durasi / (currentWaktuMenit * 60)) * 100);
  const timerInfo = document.getElementById('timer-info');
  if (timerInfo) {
    const endTime = new Date(Date.now() + (durasi * 1000));
    timerInfo.innerHTML = `
      <div class="d-flex justify-content-between align-items-center">
        <span>Progress: <strong>${percentComplete}%</strong></span>
        <span>Berakhir: <strong>${endTime.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</strong></span>
      </div>
    `;
  }

  // Debug log setiap 30 detik
  if (durasi % 30 === 0) {
    console.log(`Timer update: ${m}:${s} (${durasi} detik tersisa)`);
  }

  if (durasi > 0) {
    durasi--;
    setTimeout(updateTimer, 1000);
  } else {
    console.log('Waktu habis! Menjalankan timeout...');
    showTimeoutModal();
  }
}

// Function untuk menampilkan modal timeout yang menarik
function showTimeoutModal() {
  // Disable semua interaksi
  document.body.style.pointerEvents = 'none';

  const modalOverlay = document.createElement('div');
  modalOverlay.id = 'timeout-modal-overlay';
  modalOverlay.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.9), rgba(108, 117, 125, 0.9));
    backdrop-filter: blur(10px);
    z-index: 10000;
    display: flex;
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.5s ease-out;
  `;

  modalOverlay.innerHTML = `
    <div id="timeout-modal" style="
      background: linear-gradient(135deg, #2c3e50, #34495e);
      border: 2px solid rgba(220, 53, 69, 0.5);
      border-radius: 20px;
      padding: 3rem;
      text-align: center;
      max-width: 500px;
      width: 90%;
      box-shadow: 0 20px 60px rgba(0,0,0,0.5);
      transform: scale(0.8);
      opacity: 0;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    ">
      <!-- Animated Background -->
      <div style="
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(220, 53, 69, 0.1) 0%, transparent 70%);
        animation: pulse 2s infinite;
      "></div>

      <!-- Clock Icon with Animation -->
      <div style="
        font-size: 4rem;
        color: #dc3545;
        margin-bottom: 1.5rem;
        animation: clockTick 1s infinite;
        position: relative;
        z-index: 1;
      ">
        ⏰
      </div>

      <!-- Title -->
      <h2 style="
        color: #fff;
        margin-bottom: 1rem;
        font-size: 1.8rem;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        position: relative;
        z-index: 1;
      ">
        WAKTU HABIS!
      </h2>

      <!-- Message -->
      <p style="
        color: rgba(255,255,255,0.9);
        margin-bottom: 2rem;
        font-size: 1.1rem;
        line-height: 1.5;
        position: relative;
        z-index: 1;
      ">
        Ujian Anda telah berakhir.<br>
        Sistem sedang menyimpan jawaban Anda...
      </p>

      <!-- Progress Bar -->
      <div style="
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        height: 8px;
        margin-bottom: 2rem;
        overflow: hidden;
        position: relative;
        z-index: 1;
      ">
        <div id="timeout-progress" style="
          background: linear-gradient(90deg, #28a745, #20c997);
          height: 100%;
          width: 0%;
          border-radius: 10px;
          transition: width 0.3s ease;
          animation: progressGlow 2s infinite;
        "></div>
      </div>

      <!-- Status Text -->
      <div id="timeout-status" style="
        color: #28a745;
        font-weight: bold;
        font-size: 0.9rem;
        position: relative;
        z-index: 1;
      ">
        Menyimpan jawaban...
      </div>
    </div>
  `;

  document.body.appendChild(modalOverlay);

  // Animate modal in
  setTimeout(() => {
    const modal = document.getElementById('timeout-modal');
    modal.style.transform = 'scale(1)';
    modal.style.opacity = '1';
  }, 100);

  // Start timeout process
  handleTimeoutProcess();
}

// PERBAIKAN: Auto-save berkala untuk memastikan jawaban tersimpan
setInterval(() => {
  const allJawaban = getAllJawabanFromStorage();
  if (Object.keys(allJawaban).length > 0) {
    sinkronisasiSemuaJawaban().catch(err => {
      console.log('Auto-save error (normal):', err.message);
    });
  }
}, 30000); // Auto-save setiap 30 detik

// Function untuk menangani proses timeout dengan progress
async function handleTimeoutProcess() {
  const progressBar = document.getElementById('timeout-progress');
  const statusText = document.getElementById('timeout-status');

  try {
    // Step 1: Sinkronisasi jawaban (40%)
    statusText.textContent = 'Menyimpan jawaban...';
    progressBar.style.width = '20%';

    // PERBAIKAN: Retry mechanism untuk sinkronisasi
    let syncSuccess = false;
    for (let i = 0; i < 3; i++) {
      try {
        await sinkronisasiSemuaJawaban();
        syncSuccess = true;
        break;
      } catch (err) {
        console.log(`Sync attempt ${i + 1} failed:`, err.message);
        if (i === 2) {
          // Log detail error untuk debugging
          console.error('Final sync attempt failed:', err);
          throw err; // Throw on final attempt
        }
        await new Promise(resolve => setTimeout(resolve, 500)); // Wait before retry
      }
    }

    progressBar.style.width = '40%';

    // Step 2: Finalisasi ujian (70%)
    statusText.textContent = 'Menyelesaikan ujian...';
    progressBar.style.width = '60%';

    // PERBAIKAN: Retry mechanism untuk timeout
    let timeoutSuccess = false;
    for (let i = 0; i < 3; i++) {
      try {
        const response = await fetch("<?= base_url('api/ujian/timeout') ?>", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: new URLSearchParams({
            ujian_id: ujianId
          })
        });

        if (!response.ok) {
          throw new Error('HTTP ' + response.status);
        }

        // PERBAIKAN: Cek content type sebelum parse JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          const text = await response.text();
          console.error('Response bukan JSON:', text);
          throw new Error('Server response bukan JSON');
        }

        const result = await response.json();
        if (result.status !== 'success') {
          throw new Error(result.message || 'Unknown error');
        }

        timeoutSuccess = true;
        break;
      } catch (err) {
        console.log(`Timeout attempt ${i + 1} failed:`, err.message);
        if (i === 2) {
          // Log detail error untuk debugging
          console.error('Final timeout attempt failed:', err);
          throw err; // Throw on final attempt
        }
        await new Promise(resolve => setTimeout(resolve, 500)); // Wait before retry
      }
    }

    progressBar.style.width = '70%';

    // Step 3: Cleanup (100%)
    statusText.textContent = 'Menyelesaikan...';
    progressBar.style.width = '100%';

    // Wait a bit for visual feedback
    await new Promise(resolve => setTimeout(resolve, 1000));

    // Success message
    statusText.textContent = 'Ujian berhasil diselesaikan!';
    statusText.style.color = '#28a745';

    // Wait before redirect
    await new Promise(resolve => setTimeout(resolve, 1500));

    // Redirect to dashboard
    window.location.href = "<?= base_url('peserta/dashboard') ?>";

  } catch (error) {
    console.error('Error during timeout:', error);

    // Error handling dengan detail error
    progressBar.style.background = 'linear-gradient(90deg, #dc3545, #c82333)';
    progressBar.style.width = '100%';
    statusText.textContent = `Terjadi kesalahan: ${error.message}`;
    statusText.style.color = '#dc3545';
    statusText.style.fontSize = '0.8rem';

    // Still redirect after error
    setTimeout(() => {
      window.location.href = "<?= base_url('peserta/dashboard') ?>";
    }, 3000); // Beri waktu lebih lama untuk membaca error
  }
}

// PERBAIKAN: Simpan jawaban sebelum user meninggalkan halaman
window.addEventListener('beforeunload', (e) => {
  const allJawaban = getAllJawabanFromStorage();
  if (Object.keys(allJawaban).length > 0) {
    // Menggunakan sendBeacon untuk memastikan data terkirim meski halaman ditutup
    const formData = new FormData();
    formData.append('ujian_id', ujianId);
    formData.append('jawaban_data', JSON.stringify(allJawaban));
    
    navigator.sendBeacon("<?= base_url('peserta/ujian/sinkronisasi-jawaban') ?>", formData);
  }
});
</script>

<?php echo $this->include('layouts/footer'); ?>