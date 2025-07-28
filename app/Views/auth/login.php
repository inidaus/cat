<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login CAT System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: url('https://images.unsplash.com/photo-1522199755839-a2bacb67c546?auto=format&fit=crop&w=1350&q=80') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .glass-card {
      backdrop-filter: blur(20px);
      background: rgba(255, 255, 255, 0.1);
      border-radius: 1rem;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.3);
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 2rem;
      width: 100%;
      max-width: 400px;
      color: white;
    }

    .glass-card input {
      background: rgba(255,255,255,0.1);
      border: none;
      color: #fff;
    }

    .glass-card input::placeholder {
      color: #ccc;
    }

    .glass-card input:focus {
      background: rgba(255,255,255,0.2);
      outline: none;
      color: white;
    }

    .btn-glow {
      background: #00c6ff;
      border: none;
      color: white;
      transition: all 0.3s ease-in-out;
    }

    .btn-glow:hover {
      background: #0072ff;
      box-shadow: 0 0 15px #0072ff80;
    }
      html, body {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
  }

  .login-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  footer {
    text-align: center;
    color: #fff;
    font-size: 0.8rem;
    padding: 10px;
  }
  </style>
</head>
<body>
  <div class="glass-card">
    <div class="text-center mb-4">
  <h3 style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7);"> <i class="fas fa-user-circle"></i> Login CAT</h3>
  <p style="color: rgba(255,255,255,0.9); text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
    Silakan login untuk melanjutkan
  </p>
</div>


    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="post">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-glow">Login</button>
      </div>
    </form>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->include('layouts/footer') ?>
</body>
</html>

