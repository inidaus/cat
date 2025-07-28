<?= $this->include('layouts/header') ?>

<style>
.token-container {
  min-height: 80vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.token-card {
  background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
  backdrop-filter: blur(15px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 20px;
  padding: 3rem;
  max-width: 500px;
  width: 100%;
  text-align: center;
  box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.token-icon {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, #667eea, #764ba2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2rem;
  font-size: 2rem;
  color: white;
  box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.token-title {
  color: white;
  font-size: 1.8rem;
  font-weight: 700;
  margin-bottom: 1rem;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.token-subtitle {
  color: rgba(255,255,255,0.8);
  margin-bottom: 2rem;
  font-size: 1.1rem;
}

.token-input {
  background: rgba(255,255,255,0.1);
  border: 2px solid rgba(255,255,255,0.2);
  border-radius: 15px;
  padding: 1rem 1.5rem;
  font-size: 1.2rem;
  color: white;
  text-align: center;
  letter-spacing: 2px;
  font-weight: 600;
  text-transform: uppercase;
  transition: all 0.3s ease;
  width: 100%;
  margin-bottom: 2rem;
}

.token-input:focus {
  outline: none;
  border-color: #667eea;
  background: rgba(255,255,255,0.15);
  box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
}

.token-input::placeholder {
  color: rgba(255,255,255,0.5);
  letter-spacing: 1px;
  text-transform: none;
}

.btn-token {
  background: linear-gradient(135deg, #667eea, #764ba2);
  border: none;
  border-radius: 15px;
  padding: 1rem 2rem;
  color: white;
  font-weight: 600;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  width: 100%;
  margin-bottom: 1rem;
}

.btn-token:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
  color: white;
}

.btn-token:disabled {
  opacity: 0.6;
  transform: none;
  box-shadow: none;
}

.btn-back {
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 15px;
  padding: 0.75rem 1.5rem;
  color: rgba(255,255,255,0.8);
  font-weight: 500;
  transition: all 0.3s ease;
  width: 100%;
}

.btn-back:hover {
  background: rgba(255,255,255,0.15);
  color: white;
  border-color: rgba(255,255,255,0.3);
}

.ujian-info {
  background: rgba(255,255,255,0.05);
  border-radius: 15px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  border: 1px solid rgba(255,255,255,0.1);
}

.info-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
  color: rgba(255,255,255,0.9);
}

.info-item:last-child {
  margin-bottom: 0;
}

.info-label {
  color: rgba(255,255,255,0.7);
  font-size: 0.9rem;
}

.info-value {
  color: white;
  font-weight: 600;
}

.error-message {
  background: rgba(220, 53, 69, 0.2);
  border: 1px solid rgba(220, 53, 69, 0.3);
  border-radius: 10px;
  padding: 1rem;
  margin-bottom: 1rem;
  color: #ff6b6b;
  font-weight: 500;
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}

.shake {
  animation: shake 0.5s ease-in-out;
}
</style>

<div class="glass-wrapper">
  <div class="token-container">
    <div class="token-card">
      <div class="token-icon">
        <i class="fas fa-key"></i>
      </div>
      
      <h2 class="token-title">Masukkan Token Ujian</h2>
      <p class="token-subtitle">Silakan masukkan token yang diberikan oleh pembimbing untuk memulai ujian</p>
      
      <!-- Informasi Ujian -->
      <div class="ujian-info">
        <div class="info-item">
          <span class="info-label">
            <i class="fas fa-book me-1"></i>Ujian
          </span>
          <span class="info-value"><?= esc($ujian['judul']) ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">
            <i class="fas fa-folder me-1"></i>Kategori
          </span>
          <span class="info-value"><?= esc($kategori['nama_kategori']) ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">
            <i class="fas fa-clock me-1"></i>Durasi
          </span>
          <span class="info-value"><?= $ujian['waktu_menit'] ?> menit</span>
        </div>
        <div class="info-item">
          <span class="info-label">
            <i class="fas fa-question-circle me-1"></i>Jumlah Soal
          </span>
          <span class="info-value"><?= $ujian['jumlah_soal'] ?> soal</span>
        </div>
      </div>
      
      <!-- Error Message -->
      <?php if (session('error')): ?>
        <div class="error-message">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <?= session('error') ?>
        </div>
      <?php endif; ?>
      
      <!-- Token Form -->
      <form action="<?= base_url('peserta/ujian/verify-token') ?>" method="post" id="tokenForm">
        <input type="hidden" name="ujian_id" value="<?= $ujian['id'] ?>">
        
        <input type="text" 
               class="token-input" 
               name="token" 
               id="tokenInput"
               placeholder="Masukkan token ujian"
               maxlength="20"
               required
               autocomplete="off">
        
        <button type="submit" class="btn btn-token" id="submitBtn">
          <i class="fas fa-unlock me-2"></i>Mulai Ujian
        </button>
      </form>
      
      <a href="<?= base_url('peserta/dashboard') ?>" class="btn btn-back">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
      </a>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const tokenInput = document.getElementById('tokenInput');
  const tokenForm = document.getElementById('tokenForm');
  const submitBtn = document.getElementById('submitBtn');
  
  // Auto uppercase dan format token
  tokenInput.addEventListener('input', function() {
    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
  });
  
  // Handle form submission
  tokenForm.addEventListener('submit', function(e) {
    const token = tokenInput.value.trim();
    
    if (token.length < 3) {
      e.preventDefault();
      tokenInput.classList.add('shake');
      setTimeout(() => tokenInput.classList.remove('shake'), 500);
      return;
    }
    
    // Disable button during submission
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memverifikasi...';
  });
  
  // Focus pada input saat halaman dimuat
  tokenInput.focus();
  
  // Handle enter key
  tokenInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      tokenForm.dispatchEvent(new Event('submit'));
    }
  });
});
</script>

<?= $this->include('layouts/footer') ?>
