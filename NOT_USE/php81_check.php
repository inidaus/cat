<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP 8.1 Compatibility Check - CBT System</title>
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
        .version-info { 
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
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
        .config-table th { background: #f8f9fa; }
        .config-match { background: #d4edda; }
        .config-mismatch { background: #f8d7da; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-4">
                    <h1 class="text-white">
                        <i class="fas fa-server me-2"></i>
                        PHP 8.1 Compatibility Check
                    </h1>
                    <p class="text-white-50">CBT System - Development Environment</p>
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
                            <p class="mb-0">
                                <?= $report['php_version']['sapi'] ?> | 
                                <?= $report['php_version']['os'] ?> | 
                                <?= $report['php_version']['architecture'] ?>
                            </p>
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
                            <strong>Status: </strong>
                            <?php if ($report['extensions']['all_loaded']): ?>
                                <span class="status-good">
                                    <i class="fas fa-check-circle"></i> All Required Extensions Loaded
                                </span>
                            <?php else: ?>
                                <span class="status-error">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <?= count($report['extensions']['missing']) ?> Missing Extensions
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($report['extensions']['missing'])): ?>
                            <h6 class="text-danger">Missing Extensions:</h6>
                            <div class="extension-grid">
                                <?php foreach ($report['extensions']['missing'] as $ext): ?>
                                    <div class="extension-item extension-missing">
                                        <i class="fas fa-times me-1"></i><?= $ext ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <h6 class="text-success mt-3">Loaded Extensions:</h6>
                        <div class="extension-grid">
                            <?php foreach ($report['extensions']['loaded'] as $ext): ?>
                                <div class="extension-item extension-loaded">
                                    <i class="fas fa-check me-1"></i><?= $ext ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PHP 8.1 Features -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-star me-2"></i>
                            PHP 8.1 Features
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($report['features'] as $feature => $enabled): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><?= ucwords(str_replace('_', ' ', $feature)) ?>:</span>
                                <span class="<?= $enabled ? 'feature-enabled' : 'feature-disabled' ?>">
                                    <i class="fas fa-<?= $enabled ? 'check' : 'times' ?>"></i>
                                    <?= $enabled ? 'Enabled' : 'Disabled' ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Check -->
        <div class="row">
            <div class="col-12">
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
                                        <th>Current Value</th>
                                        <th>Recommended</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($report['configuration'] as $setting => $values): ?>
                                        <tr class="<?= $values['matches'] ? 'config-match' : 'config-mismatch' ?>">
                                            <td><code><?= $setting ?></code></td>
                                            <td><code><?= $values['current'] ?: 'Not Set' ?></code></td>
                                            <td><code><?= $values['recommended'] ?></code></td>
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Settings -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Performance Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($report['performance'] as $setting => $value): ?>
                                <div class="col-md-6 mb-2">
                                    <strong><?= ucwords(str_replace('_', ' ', $setting)) ?>:</strong>
                                    <span class="text-muted"><?= is_bool($value) ? ($value ? 'Enabled' : 'Disabled') : $value ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
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
                <a href="<?= base_url('php81-check/requirements') ?>" class="btn btn-info btn-lg me-2">
                    <i class="fas fa-list me-1"></i>View Requirements
                </a>
                <a href="<?= base_url('php81-check/api') ?>" class="btn btn-secondary btn-lg" target="_blank">
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
                        CBT System PHP 8.1 Compatible
                    </small>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
