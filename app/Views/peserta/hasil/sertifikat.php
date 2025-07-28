<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sertifikat - <?= esc($detail['judul']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap');
    
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }
    
    .certificate-container {
      max-width: 800px;
      margin: 0 auto;
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.1);
      overflow: hidden;
      position: relative;
    }
    
    .certificate-header {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      padding: 2rem;
      text-align: center;
      position: relative;
    }
    
    .certificate-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
      opacity: 0.3;
    }
    
    .certificate-title {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      position: relative;
      z-index: 1;
    }
    
    .certificate-subtitle {
      font-size: 1.1rem;
      opacity: 0.9;
      position: relative;
      z-index: 1;
    }
    
    .certificate-body {
      padding: 3rem 2rem;
      text-align: center;
    }
    
    .recipient-name {
      font-family: 'Playfair Display', serif;
      font-size: 2.2rem;
      font-weight: 700;
      color: #2c3e50;
      margin: 1.5rem 0;
      border-bottom: 3px solid #667eea;
      display: inline-block;
      padding-bottom: 0.5rem;
    }
    
    .certificate-text {
      font-size: 1.1rem;
      color: #5a6c7d;
      line-height: 1.6;
      margin: 1.5rem 0;
    }
    
    .exam-title {
      font-size: 1.4rem;
      font-weight: 600;
      color: #2c3e50;
      margin: 1rem 0;
    }
    
    .score-section {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      border-radius: 15px;
      padding: 1.5rem;
      margin: 2rem 0;
      border: 2px solid #667eea;
    }
    
    .score-value {
      font-size: 3rem;
      font-weight: 700;
      color: #28a745;
      margin-bottom: 0.5rem;
    }
    
    .score-label {
      color: #6c757d;
      font-weight: 500;
    }
    
    .certificate-footer {
      display: flex;
      justify-content: space-between;
      align-items: end;
      margin-top: 3rem;
      padding-top: 2rem;
      border-top: 2px solid #e9ecef;
    }
    
    .signature-section {
      text-align: center;
      flex: 1;
    }
    
    .signature-line {
      width: 200px;
      height: 2px;
      background: #667eea;
      margin: 2rem auto 0.5rem;
    }
    
    .signature-title {
      font-weight: 600;
      color: #2c3e50;
    }
    
    .certificate-date {
      color: #6c757d;
      font-size: 0.9rem;
    }
    
    .certificate-id {
      position: absolute;
      bottom: 10px;
      right: 20px;
      font-size: 0.8rem;
      color: #adb5bd;
    }
    
    .decorative-border {
      position: absolute;
      top: 10px;
      left: 10px;
      right: 10px;
      bottom: 10px;
      border: 3px solid #667eea;
      border-radius: 15px;
      pointer-events: none;
    }
    
    .decorative-corner {
      position: absolute;
      width: 30px;
      height: 30px;
      border: 3px solid #764ba2;
    }
    
    .corner-tl { top: 20px; left: 20px; border-right: none; border-bottom: none; }
    .corner-tr { top: 20px; right: 20px; border-left: none; border-bottom: none; }
    .corner-bl { bottom: 20px; left: 20px; border-right: none; border-top: none; }
    .corner-br { bottom: 20px; right: 20px; border-left: none; border-top: none; }
    
    @media print {
      body { background: white; padding: 0; }
      .certificate-container { box-shadow: none; }
    }
  </style>
</head>
<body>
  <div class="certificate-container">
    <div class="decorative-border"></div>
    <div class="decorative-corner corner-tl"></div>
    <div class="decorative-corner corner-tr"></div>
    <div class="decorative-corner corner-bl"></div>
    <div class="decorative-corner corner-br"></div>
    
    <div class="certificate-header">
      <div class="certificate-title">SERTIFIKAT</div>
      <div class="certificate-subtitle">Certificate of Achievement</div>
    </div>
    
    <div class="certificate-body">
      <div class="certificate-text">
        Dengan ini menyatakan bahwa
      </div>
      
      <div class="recipient-name">
        <?= esc($peserta['nama']) ?>
      </div>
      
      <div class="certificate-text">
        NIM: <strong><?= esc($peserta['nim']) ?></strong>
      </div>
      
      <div class="certificate-text">
        telah berhasil menyelesaikan ujian
      </div>
      
      <div class="exam-title">
        "<?= esc($detail['judul']) ?>"
      </div>
      
      <div class="certificate-text">
        Kategori: <strong><?= esc($detail['nama_kategori']) ?></strong>
      </div>
      
      <div class="score-section">
        <div class="score-value"><?= $detail['nilai'] ?></div>
        <div class="score-label">Nilai Akhir</div>
        <div class="mt-2">
          <small class="text-muted">
            Passing Grade: <?= $detail['passing_grade'] ?? 70 ?> | 
            Tanggal: <?= date('d M Y', strtotime($detail['selesai'])) ?>
          </small>
        </div>
      </div>
      
      <div class="certificate-footer">
        <div class="signature-section">
          <div class="certificate-date">
            <?= date('d M Y') ?>
          </div>
          <div class="signature-line"></div>
          <div class="signature-title">Tanggal Penerbitan</div>
        </div>
        
        <div class="signature-section">
          <div style="height: 60px;"></div>
          <div class="signature-line"></div>
          <div class="signature-title">Pembimbing</div>
        </div>
      </div>
    </div>
    
    <div class="certificate-id">
      ID: CERT-<?= $detail['ujian_id'] ?>-<?= $detail['peserta_id'] ?>-<?= date('Ymd', strtotime($detail['selesai'])) ?>
    </div>
  </div>
  
  <div class="text-center mt-4 d-print-none">
    <button onclick="window.print()" class="btn btn-primary btn-lg me-2">
      <i class="fas fa-print me-1"></i>Print Sertifikat
    </button>
    <button onclick="window.close()" class="btn btn-secondary btn-lg">
      <i class="fas fa-times me-1"></i>Tutup
    </button>
  </div>
  
  <script>
    // Auto print when page loads (optional)
    // window.onload = function() { window.print(); }
  </script>
</body>
</html>
