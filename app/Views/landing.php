<?= $this->include('layouts/header') ?>

<style>
  body {
    background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                url("https://images.unsplash.com/photo-1519389950473-47ba0277781c") no-repeat center center fixed;
    background-size: cover;
    min-height: 100vh;
  }
  .glass-panel {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 60px 40px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.3);
    color: white;
    max-width: 600px;
    width: 90%;
    margin: auto;
  }
  .glass-panel h1 {
    font-size: 3rem;
    font-weight: bold;
  }
  .glass-panel p {
    font-size: 1.2rem;
  }
</style>

<div class="d-flex justify-content-center align-items-center text-center" style="min-height: 90vh;">
  <div class="glass-panel">
    <h1>CAT System</h1>
    <p class="mt-3">Computer Assisted Test untuk seleksi dan evaluasi digital modern dengan dukungan Scrab Team Indonesia.</p>
    <a href="<?= base_url('login') ?>" class="btn btn-lg btn-primary mt-4 px-5 py-2">
      <i class="fas fa-sign-in-alt me-2"></i> Mulai Login
    </a>
  </div>
</div>

<?= $this->include('layouts/footer') ?>
