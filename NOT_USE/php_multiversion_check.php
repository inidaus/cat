<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Multi-Version Compatibility Check - CBT System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container { margin-top: 2rem; margin-bottom: 2rem; }
        .card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        .card-header { 
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            font-weight: 600;
        }
        .status-good { color: #28a745; }
        .status-warning { color: #ffc107; }
        .status-error { color: #dc3545; }
        .feature-enabled { color: #28a745; }
        .feature-disabled { color: #6c757d; }
        .feature-unsupported { color: #dc3545; }
        .version-info { 
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        .version-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            margin: 0.1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .version-supported { background: #d4edda; color: #155724; }
        .version-current { background: #cce5ff; color: #004085; border: 2px solid #0066cc; }
        .extension-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .extension-item {
            padding: 0.5rem;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        .extension-loaded { background: #d4edda; color: #155724; }
        .extension-missing { background: #f8d7da; color: #721c24; }
        .extension-optional { background: #fff3cd; color: #856404; }
        .config-table th { background: #f8f9fa; }
        .config-match { background: #d4edda; }
        .config-mismatch { background: #f8d7da; }
        .feature-section {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .php-version-header {
            background: linear-gradient(135deg, #6f42c1, #e83e8c);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-4">
                    <h1 class="text-white">
                        <i class="fas fa-server me-2"></i>
                        PHP Multi-Version Compatibility Check
                    </h1>
                    <p class="text-white-50">CBT System - Supports PHP 8.0, 8.1, 8.2, 8.3, 8.4</p>
                </div>
            </div>
        </div>

        <!-- PHP Version Info -->
        <div class="row">
            <div class="col-12">
                <div class="version-info">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-1">
                                <i class="fab fa-php me-2"></i>
                                PHP <?= $report['php_version']['version'] ?>
                            </h3>
                            <p class="mb-2">
                                <?= $report['php_version']['sapi'] ?> | 
                                <?= $report['php_version']['os'] ?> | 
                                <?= $report['php_version']['architecture'] ?>
                                <?php if ($report['php_version']['jit_enabled']): ?>
                                    | <i class="fas fa-rocket"></i> JIT Enabled
                                <?php endif; ?>
                            </p>
                            <div>
                                <strong>Supported Versions:</strong>
                                <?php foreach ($report['php_version']['supported_versions'] as $version): ?>
                                    <span class="version-badge <?= $version === substr(PHP_VERSION, 0, 3) ? 'version-current' : 'version-supported' ?>">
                                        PHP <?= $version ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if ($report['php_version']['compatible']): ?>
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Compatible
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-times-circle me-1"></i>Not Compatible
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Extensions Check -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-puzzle-piece me-2"></i>
                            PHP Extensions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Required Extensions: </strong>
                            <?php if ($report['extensions']['required']['all_loaded']): ?>
                                <span class="status-good">
                                    <i class="fas fa-check-circle"></i> All Required Extensions Loaded
                                </span>
                            <?php else: ?>
                                <span class="status-error">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <?= count($report['extensions']['required']['missing']) ?> Missing Extensions
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($report['extensions']['required']['missing'])): ?>
                            <h6 class="text-danger">Missing Required Extensions:</h6>
                            <div class="extension-grid">
                                <?php foreach ($report['extensions']['required']['missing'] as $ext): ?>
                                    <div class="extension-item extension-missing">
                                        <i class="fas fa-times me-1"></i><?= $ext ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <h6 class="text-success mt-3">Loaded Required Extensions:</h6>
                        <div class="extension-grid">
                            <?php foreach ($report['extensions']['required']['loaded'] as $ext): ?>
                                <div class="extension-item extension-loaded">
                                    <i class="fas fa-check me-1"></i><?= $ext ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!empty($report['extensions']['optional']['loaded'])): ?>
                            <h6 class="text-warning mt-3">Optional Extensions (Loaded):</h6>
                            <div class="extension-grid">
                                <?php foreach ($report['extensions']['optional']['loaded'] as $ext): ?>
                                    <div class="extension-item extension-optional">
                                        <i class="fas fa-plus me-1"></i><?= $ext ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- PHP Features by Version -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-star me-2"></i>
                            PHP Features by Version
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($report['features'] as $phpVersion => $features): ?>
                            <div class="feature-section">
                                <div class="php-version-header">
                                    <?= strtoupper($phpVersion) ?> Features
                                </div>
                                <?php foreach ($features as $feature => $enabled): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small"><?= ucwords(str_replace('_', ' ', $feature)) ?>:</span>
                                        <span class="<?= $enabled ? 'feature-enabled' : 'feature-disabled' ?>">
                                            <i class="fas fa-<?= $enabled ? 'check' : 'times' ?>"></i>
                                            <?= $enabled ? 'Enabled' : 'Disabled' ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration and Performance -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>
                            PHP Configuration
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Setting</th>
                                        <th>Current</th>
                                        <th>Recommended</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($report['configuration'], 0, 10) as $setting => $values): ?>
                                        <tr class="<?= $values['matches'] ? 'config-match' : 'config-mismatch' ?>">
                                            <td><code class="small"><?= $setting ?></code></td>
                                            <td><code class="small"><?= $values['current'] ?: 'Not Set' ?></code></td>
                                            <td><code class="small"><?= $values['recommended'] ?></code></td>
                                            <td>
                                                <?php if ($values['matches']): ?>
                                                    <i class="fas fa-check text-success"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted">Showing first 10 settings. View full report via API.</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Performance
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php foreach (array_slice($report['performance'], 0, 8) as $setting => $value): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small"><?= ucwords(str_replace('_', ' ', $setting)) ?>:</span>
                                <span class="text-muted small">
                                    <?= is_bool($value) ? ($value ? 'Enabled' : 'Disabled') : $value ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row">
            <div class="col-12 text-center">
                <a href="<?= base_url() ?>" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-home me-1"></i>Back to Home
                </a>
                <a href="<?= base_url('php-multiversion-check/requirements') ?>" class="btn btn-info btn-lg me-2">
                    <i class="fas fa-list me-1"></i>View Requirements
                </a>
                <a href="<?= base_url('php-multiversion-check/api') ?>" class="btn btn-secondary btn-lg" target="_blank">
                    <i class="fas fa-code me-1"></i>JSON Report
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <p class="text-white-50">
                    <small>
                        Generated on <?= $report['timestamp'] ?> | 
                        CBT System - Multi PHP Version Compatible (8.0 - 8.4)
                    </small>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
