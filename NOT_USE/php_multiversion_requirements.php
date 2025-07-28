<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Multi-Version Requirements - CBT System</title>
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
        .version-info { 
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-4">
                    <h1 class="text-white">
                        <i class="fas fa-list-check me-2"></i>
                        PHP Multi-Version Requirements
                    </h1>
                    <p class="text-white-50">Detailed system requirements and configuration</p>
                </div>
            </div>
        </div>

        <!-- PHP Version Info -->
        <div class="row">
            <div class="col-12">
                <div class="version-info">
                    <h3 class="mb-1">
                        <i class="fab fa-php me-2"></i>
                        Current PHP Version: <?= $version['version'] ?>
                    </h3>
                    <p class="mb-0">
                        Compatible: <?= $version['compatible'] ? 'Yes' : 'No' ?> | 
                        Minimum Required: <?= $version['minimum_version'] ?> |
                        Architecture: <?= $version['architecture'] ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Extensions Details -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-puzzle-piece me-2"></i>
                            Required Extensions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Status: </strong>
                            <?php if ($extensions['required']['all_loaded']): ?>
                                <span class="status-good">
                                    <i class="fas fa-check-circle"></i> All Required Extensions Loaded
                                </span>
                            <?php else: ?>
                                <span class="status-error">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <?= count($extensions['required']['missing']) ?> Missing Extensions
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($extensions['required']['missing'])): ?>
                            <h6 class="text-danger">Missing Required Extensions:</h6>
                            <div class="extension-grid">
                                <?php foreach ($extensions['required']['missing'] as $ext): ?>
                                    <div class="extension-item extension-missing">
                                        <i class="fas fa-times me-1"></i><?= $ext ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="alert alert-danger mt-3">
                                <h6><i class="fas fa-exclamation-triangle"></i> Installation Instructions:</h6>
                                <p class="mb-1"><strong>Ubuntu/Debian:</strong></p>
                                <code>sudo apt-get install php-{extension-name}</code>
                                <p class="mb-1 mt-2"><strong>CentOS/RHEL:</strong></p>
                                <code>sudo yum install php-{extension-name}</code>
                                <p class="mb-1 mt-2"><strong>Windows (XAMPP):</strong></p>
                                <code>Enable in php.ini: extension={extension-name}</code>
                            </div>
                        <?php endif; ?>

                        <h6 class="text-success mt-3">Loaded Required Extensions:</h6>
                        <div class="extension-grid">
                            <?php foreach ($extensions['required']['loaded'] as $ext): ?>
                                <div class="extension-item extension-loaded">
                                    <i class="fas fa-check me-1"></i><?= $ext ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            Optional Extensions
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">These extensions provide enhanced functionality but are not required.</p>
                        
                        <?php if (!empty($extensions['optional']['loaded'])): ?>
                            <h6 class="text-success">Loaded Optional Extensions:</h6>
                            <div class="extension-grid">
                                <?php foreach ($extensions['optional']['loaded'] as $ext): ?>
                                    <div class="extension-item extension-optional">
                                        <i class="fas fa-check me-1"></i><?= $ext ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($extensions['optional']['missing'])): ?>
                            <h6 class="text-warning mt-3">Available Optional Extensions:</h6>
                            <div class="extension-grid">
                                <?php foreach ($extensions['optional']['missing'] as $ext): ?>
                                    <div class="extension-item extension-optional">
                                        <i class="fas fa-plus me-1"></i><?= $ext ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="alert alert-info mt-3">
                                <h6><i class="fas fa-info-circle"></i> Benefits:</h6>
                                <ul class="mb-0">
                                    <li><strong>redis/memcached:</strong> Enhanced caching performance</li>
                                    <li><strong>imagick:</strong> Advanced image processing</li>
                                    <li><strong>xdebug:</strong> Development and debugging tools</li>
                                    <li><strong>opcache:</strong> Improved PHP performance</li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Details -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>
                            Detailed PHP Configuration
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
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $descriptions = [
                                        'memory_limit' => 'Maximum memory a script can consume',
                                        'max_execution_time' => 'Maximum script execution time',
                                        'post_max_size' => 'Maximum POST data size',
                                        'upload_max_filesize' => 'Maximum file upload size',
                                        'date.timezone' => 'Default timezone for date functions',
                                        'opcache.enable' => 'Enable OPcache for better performance',
                                        'display_errors' => 'Show errors on screen (disable in production)',
                                        'log_errors' => 'Log errors to file',
                                    ];
                                    ?>
                                    <?php foreach ($config as $setting => $values): ?>
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
                                            <td class="small text-muted">
                                                <?= $descriptions[$setting] ?? 'Configuration setting' ?>
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

        <!-- Actions -->
        <div class="row">
            <div class="col-12 text-center">
                <a href="<?= base_url('php-multiversion-check') ?>" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-arrow-left me-1"></i>Back to Check
                </a>
                <a href="<?= base_url('php-multiversion-check/api') ?>" class="btn btn-info btn-lg me-2" target="_blank">
                    <i class="fas fa-code me-1"></i>JSON Report
                </a>
                <a href="<?= base_url() ?>" class="btn btn-secondary btn-lg">
                    <i class="fas fa-home me-1"></i>Home
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
