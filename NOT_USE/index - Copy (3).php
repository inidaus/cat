<?php echo $this->include('layouts/header'); ?>

<style>
.timer-box {
  background: #fff;
  border-radius: 10px;
  padding: 1rem;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
  font-weight: bold;
  text-align: center;
}
.nav-soal {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}
.nav-soal-button {
  width: 28px;
  height: 28px;
  border: none;
  border-radius: 50%;
  font-size: 0.8rem;
  padding: 0;
  text-align: center;
  background-color: #e9ecef;
  color: #000;
}
.nav-soal-button.answered {
  background-color: #198754 !important;
  color: white !important;
}
.nav-soal-button.active {
  border: 2px solid #0d6efd;
  font-weight: bold;
}
.card-ujian {
  background: #ffffff;
  border-radius: 1rem;
  padding: 2rem;
  box-shadow: 0 0 15px rgba(0,0,0,0.1);
  color: #333;
}
.progress-container {
  margin-bottom: 1rem;
}
</style>

<div class="glass-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="glass-title">
      <i class="fas fa-pen-alt me-2"></i> Ujian: <?= esc($ujian['judul']) ?>
    </h2>
    <div>
      <a href="<?= base_url('peserta/dashboard') ?>" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>
    </div>
  </div>

  <div class="progress-container">
    <div class="progress">
      <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-8">
      <div class="card-ujian">
        <div id="soal-container">
          <h5 id="judul-soal"></h5>
          <div id="opsi-jawaban"></div>
        </div>
        <div class="mt-4 d-flex justify-content-between">
          <button class="btn btn-secondary" id="btn-prev" style="display:none;">&laquo; Sebelumnya</button>
          <button class="btn btn-secondary" id="btn-next">Berikutnya &raquo;</button>
        </div>
        <div class="mt-3 text-end d-none" id="selesai-section">
          <form action="<?= base_url('peserta/ujian/selesai') ?>" method="post" onsubmit="return confirm('Yakin ingin menyelesaikan ujian ini?')">
            <input type="hidden" name="ujian_id" value="<?= $ujian['id'] ?>">
            <button type="submit" class="btn btn-danger">Selesai Ujian</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="timer-box mb-3">
        <i class="fas fa-clock me-2"></i> Sisa Waktu: <span id="timer">--:--</span>
      </div>
      <div class="card-ujian">
        <h6>Nomor Soal</h6>
        <div class="nav-soal" id="nav-soal"></div>
      </div>
    </div>
  </div>
</div>

<script>
const soalList = <?= json_encode($soalList) ?>;
let jawaban = <?= json_encode($jawaban) ?>; // Ubah dari const ke let
const pesertaId = <?= $pesertaId ?>;
const ujianId = <?= $ujian['id'] ?>;
const waktuMulai = new Date("<?= $ujian['mulai'] ?>").getTime();
const sekarang = new Date().getTime();
let durasi = Math.floor((waktuMulai + <?= $ujian['waktu_menit'] ?> * 60000 - sekarang) / 1000);
if (durasi < 0) durasi = 0;

let indexSoal = parseInt(localStorage.getItem(`index_soal_${ujianId}`)) || 0;

// Muat jawaban dari localStorage saat halaman dimuat
function loadJawabanFromStorage() {
  soalList.forEach(s => {
    const localJawaban = localStorage.getItem(`jawaban_${ujianId}_${s.id}`);
    if (localJawaban && !jawaban[s.id]) {
      jawaban[s.id] = localJawaban;
    }
  });
}

function renderSoal() {
  const soal = soalList[indexSoal];
  const currentJawaban = jawaban[soal.id] || null;

  document.getElementById('judul-soal').innerHTML = `${indexSoal + 1}. ${soal.pertanyaan}`;

  const opsi = ['A','B','C','D','E'];
  let html = '';
  opsi.forEach(k => {
    const key = 'pilihan_' + k.toLowerCase();
    if (soal[key]) { // Pastikan pilihan ada
      const checked = currentJawaban === k ? 'checked' : '';
      html += `
        <div class="form-check">
          <input class="form-check-input" type="radio" name="jawaban" value="${k}" id="opsi${k}" ${checked}>
          <label class="form-check-label" for="opsi${k}">${k}. ${soal[key]}</label>
        </div>`;
    }
  });
  document.getElementById('opsi-jawaban').innerHTML = html;
  
  updateNav();
  updateProgress();
  toggleButtons();
  localStorage.setItem(`index_soal_${ujianId}`, indexSoal);
}

function simpanJawaban(j) {
  const soal = soalList[indexSoal];
  
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
    // Meskipun gagal ke server, jawaban tetap tersimpan di localStorage
  });
}

function updateNav() {
  const nav = document.getElementById('nav-soal');
  nav.innerHTML = '';
  soalList.forEach((s, i) => {
    const btn = document.createElement('button');
    btn.innerText = i + 1;
    btn.className = 'nav-soal-button btn';
    
    // Tandai soal aktif
    if (i === indexSoal) {
      btn.classList.add('active');
    }
    
    // Tandai soal yang sudah dijawab
    if (jawaban[s.id]) {
      btn.classList.add('answered');
    }
    
    btn.onclick = () => {
      indexSoal = i;
      renderSoal();
    };
    nav.appendChild(btn);
  });
}

function updateProgress() {
  let answered = 0;
  soalList.forEach(s => {
    if (jawaban[s.id]) {
      answered++;
    }
  });
  
  const total = soalList.length;
  const percent = total > 0 ? Math.round((answered / total) * 100) : 0;
  
  const bar = document.getElementById('progressBar');
  bar.style.width = percent + '%';
  bar.setAttribute('aria-valuenow', percent);
  bar.innerText = percent + '%';
  
  console.log(`Progress: ${answered}/${total} = ${percent}%`); // Debug log
}

function toggleButtons() {
  const prev = document.getElementById('btn-prev');
  const next = document.getElementById('btn-next');
  const selesai = document.getElementById('selesai-section');
  
  prev.style.display = indexSoal > 0 ? 'inline-block' : 'none';
  
  const isLast = indexSoal === soalList.length - 1;
  next.style.display = isLast ? 'none' : 'inline-block';
  selesai.classList.toggle('d-none', !isLast);
}

// Event listener untuk jawaban
document.addEventListener('change', e => {
  if (e.target.name === 'jawaban') {
    simpanJawaban(e.target.value);
  }
});

document.addEventListener('DOMContentLoaded', () => {
  // Muat jawaban dari localStorage
  loadJawabanFromStorage();
  
  document.getElementById('btn-prev').addEventListener('click', () => {
    if (indexSoal > 0) {
      indexSoal--;
      renderSoal();
    }
  });

  document.getElementById('btn-next').addEventListener('click', () => {
    if (indexSoal < soalList.length - 1) {
      indexSoal++;
      renderSoal();
    }
  });

  updateTimer();
  renderSoal();
});

function updateTimer() {
  const m = String(Math.floor(durasi / 60)).padStart(2, '0');
  const s = String(durasi % 60).padStart(2, '0');
  document.getElementById('timer').innerText = `${m}:${s}`;
  if (durasi > 0) {
    durasi--;
    setTimeout(updateTimer, 1000);
  } else {
    alert('Waktu habis! Ujian akan diselesaikan.');
    window.location.href = "<?= base_url('peserta/dashboard') ?>";
  }
}
</script>

<?php echo $this->include('layouts/footer'); ?>