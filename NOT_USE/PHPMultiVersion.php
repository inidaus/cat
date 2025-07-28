<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * PHP Multi-Version Compatibility Configuration
 * 
 * This configuration ensures the application runs optimally on PHP 8.0, 8.1, 8.2, 8.3, and 8.4
 * and takes advantage of modern PHP features while maintaining backward compatibility.
 */
class PHPMultiVersion extends BaseConfig
{
    /**
     * Supported PHP versions
     */
    public array $supportedVersions = ['8.0', '8.1', '8.2', '8.3', '8.4'];

    /**
     * Minimum required PHP version
     */
    public string $minimumVersion = '8.0.0';

    /**
     * Enable modern PHP features based on version
     */
    public bool $enableModernFeatures = true;

    /**
     * PHP 8.0+ Features
     */
    public bool $enableUnionTypes = true;           // PHP 8.0+
    public bool $enableNamedArguments = true;      // PHP 8.0+
    public bool $enableAttributes = true;          // PHP 8.0+
    public bool $enableMatchExpression = true;     // PHP 8.0+
    public bool $enableNullsafeOperator = true;    // PHP 8.0+

    /**
     * PHP 8.1+ Features
     */
    public bool $enableReadonlyProperties = true;  // PHP 8.1+
    public bool $enableEnums = true;               // PHP 8.1+
    public bool $enableFibers = false;             // PHP 8.1+ (disabled by default)
    public bool $enableIntersectionTypes = true;   // PHP 8.1+
    public bool $enableNewInInitializers = true;   // PHP 8.1+
    public bool $enableFirstClassCallables = true; // PHP 8.1+

    /**
     * PHP 8.2+ Features
     */
    public bool $enableReadonlyClasses = true;     // PHP 8.2+
    public bool $enableDisjunctiveNormalForm = true; // PHP 8.2+
    public bool $enableConstantsInTraits = true;   // PHP 8.2+

    /**
     * PHP 8.3+ Features
     */
    public bool $enableTypedClassConstants = true; // PHP 8.3+
    public bool $enableOverrideAttribute = true;   // PHP 8.3+
    public bool $enableDeepCloning = true;         // PHP 8.3+

    /**
     * PHP 8.4+ Features (Future)
     */
    public bool $enablePropertyHooks = true;       // PHP 8.4+
    public bool $enableAsymmetricVisibility = true; // PHP 8.4+

    /**
     * Multi-version error handling
     */
    public array $errorHandling = [
        'deprecation_warnings' => true,
        'strict_types' => false, // Can be enabled per file
        'error_reporting' => E_ALL & ~E_DEPRECATED, // Hide deprecation warnings in production
        'display_errors' => false, // Always false in production
        'log_errors' => true,
    ];

    /**
     * Performance optimizations for all supported PHP versions
     */
    public array $performance = [
        'opcache_enabled' => true,
        'opcache_validate_timestamps' => false, // Disable in production
        'opcache_revalidate_freq' => 0,
        'opcache_max_accelerated_files' => 10000,
        'opcache_memory_consumption' => 256,
        'opcache_interned_strings_buffer' => 16,
        'opcache_fast_shutdown' => true,
        'opcache_jit' => 'tracing', // JIT for PHP 8.0+
        'opcache_jit_buffer_size' => '100M', // JIT buffer
    ];

    /**
     * Required PHP extensions for PHP 8.0+
     */
    public array $requiredExtensions = [
        'mbstring',
        'intl',
        'json',
        'mysqlnd',
        'openssl',
        'curl',
        'gd',
        'zip',
        'xml',
        'dom',
        'libxml',
        'session',
        'hash',
        'filter',
        'pcre',
        'spl',
        'reflection',
        'ctype',
        'tokenizer',
        'fileinfo',
    ];

    /**
     * Optional extensions for enhanced functionality
     */
    public array $optionalExtensions = [
        'redis',
        'memcached',
        'imagick',
        'xdebug',
        'opcache',
    ];

    /**
     * Recommended PHP.ini settings for PHP 8.0+
     */
    public array $recommendedSettings = [
        'memory_limit' => '256M',
        'max_execution_time' => '300',
        'max_input_time' => '300',
        'post_max_size' => '50M',
        'upload_max_filesize' => '50M',
        'max_file_uploads' => '20',
        'date.timezone' => 'Asia/Jakarta',
        'default_charset' => 'UTF-8',
        'mbstring.internal_encoding' => 'UTF-8',
        'mbstring.http_output' => 'UTF-8',
        'session.cookie_httponly' => '1',
        'session.cookie_secure' => '1',
        'session.use_strict_mode' => '1',
        'expose_php' => '0',
        'display_errors' => '0',
        'log_errors' => '1',
        'error_log' => WRITEPATH . 'logs/php_errors.log',
        'opcache.enable' => '1',
        'opcache.jit' => 'tracing',
        'opcache.jit_buffer_size' => '100M',
    ];

    /**
     * Check if current PHP version is compatible
     */
    public function isCompatible(): bool
    {
        return version_compare(PHP_VERSION, $this->minimumVersion, '>=');
    }

    /**
     * Check if current PHP version supports specific feature
     */
    public function supportsFeature(string $feature): bool
    {
        $featureVersions = [
            'union_types' => '8.0.0',
            'named_arguments' => '8.0.0',
            'attributes' => '8.0.0',
            'match_expression' => '8.0.0',
            'nullsafe_operator' => '8.0.0',
            'readonly_properties' => '8.1.0',
            'enums' => '8.1.0',
            'fibers' => '8.1.0',
            'intersection_types' => '8.1.0',
            'new_in_initializers' => '8.1.0',
            'first_class_callables' => '8.1.0',
            'readonly_classes' => '8.2.0',
            'disjunctive_normal_form' => '8.2.0',
            'constants_in_traits' => '8.2.0',
            'typed_class_constants' => '8.3.0',
            'override_attribute' => '8.3.0',
            'deep_cloning' => '8.3.0',
            'property_hooks' => '8.4.0',
            'asymmetric_visibility' => '8.4.0',
        ];

        if (!isset($featureVersions[$feature])) {
            return false;
        }

        return version_compare(PHP_VERSION, $featureVersions[$feature], '>=');
    }

    /**
     * Get PHP version info
     */
    public function getVersionInfo(): array
    {
        return [
            'version' => PHP_VERSION,
            'major' => PHP_MAJOR_VERSION,
            'minor' => PHP_MINOR_VERSION,
            'release' => PHP_RELEASE_VERSION,
            'version_id' => PHP_VERSION_ID,
            'compatible' => $this->isCompatible(),
            'supported_versions' => $this->supportedVersions,
            'minimum_version' => $this->minimumVersion,
            'sapi' => PHP_SAPI,
            'os' => PHP_OS,
            'architecture' => PHP_INT_SIZE * 8 . '-bit',
            'jit_enabled' => function_exists('opcache_get_status') && 
                           opcache_get_status()['jit']['enabled'] ?? false,
        ];
    }

    /**
     * Check required and optional extensions
     */
    public function checkExtensions(): array
    {
        $result = [
            'required' => [
                'missing' => [],
                'loaded' => [],
                'all_loaded' => true,
            ],
            'optional' => [
                'missing' => [],
                'loaded' => [],
            ],
        ];

        // Check required extensions
        foreach ($this->requiredExtensions as $extension) {
            if (extension_loaded($extension)) {
                $result['required']['loaded'][] = $extension;
            } else {
                $result['required']['missing'][] = $extension;
                $result['required']['all_loaded'] = false;
            }
        }

        // Check optional extensions
        foreach ($this->optionalExtensions as $extension) {
            if (extension_loaded($extension)) {
                $result['optional']['loaded'][] = $extension;
            } else {
                $result['optional']['missing'][] = $extension;
            }
        }

        // Legacy format for backward compatibility
        $result['missing'] = $result['required']['missing'];
        $result['loaded'] = $result['required']['loaded'];
        $result['all_loaded'] = $result['required']['all_loaded'];

        return $result;
    }

    /**
     * Get current PHP configuration
     */
    public function getCurrentConfig(): array
    {
        $config = [];
        
        foreach ($this->recommendedSettings as $setting => $recommended) {
            $current = ini_get($setting);
            $config[$setting] = [
                'current' => $current,
                'recommended' => $recommended,
                'matches' => $current === $recommended,
            ];
        }

        return $config;
    }

    /**
     * Get available features based on current PHP version
     */
    public function getAvailableFeatures(): array
    {
        $features = [];
        
        // PHP 8.0+ features
        $features['php80'] = [
            'union_types' => $this->supportsFeature('union_types') && $this->enableUnionTypes,
            'named_arguments' => $this->supportsFeature('named_arguments') && $this->enableNamedArguments,
            'attributes' => $this->supportsFeature('attributes') && $this->enableAttributes,
            'match_expression' => $this->supportsFeature('match_expression') && $this->enableMatchExpression,
            'nullsafe_operator' => $this->supportsFeature('nullsafe_operator') && $this->enableNullsafeOperator,
        ];

        // PHP 8.1+ features
        $features['php81'] = [
            'readonly_properties' => $this->supportsFeature('readonly_properties') && $this->enableReadonlyProperties,
            'enums' => $this->supportsFeature('enums') && $this->enableEnums,
            'fibers' => $this->supportsFeature('fibers') && $this->enableFibers,
            'intersection_types' => $this->supportsFeature('intersection_types') && $this->enableIntersectionTypes,
            'new_in_initializers' => $this->supportsFeature('new_in_initializers') && $this->enableNewInInitializers,
            'first_class_callables' => $this->supportsFeature('first_class_callables') && $this->enableFirstClassCallables,
        ];

        // PHP 8.2+ features
        $features['php82'] = [
            'readonly_classes' => $this->supportsFeature('readonly_classes') && $this->enableReadonlyClasses,
            'disjunctive_normal_form' => $this->supportsFeature('disjunctive_normal_form') && $this->enableDisjunctiveNormalForm,
            'constants_in_traits' => $this->supportsFeature('constants_in_traits') && $this->enableConstantsInTraits,
        ];

        // PHP 8.3+ features
        $features['php83'] = [
            'typed_class_constants' => $this->supportsFeature('typed_class_constants') && $this->enableTypedClassConstants,
            'override_attribute' => $this->supportsFeature('override_attribute') && $this->enableOverrideAttribute,
            'deep_cloning' => $this->supportsFeature('deep_cloning') && $this->enableDeepCloning,
        ];

        // PHP 8.4+ features
        $features['php84'] = [
            'property_hooks' => $this->supportsFeature('property_hooks') && $this->enablePropertyHooks,
            'asymmetric_visibility' => $this->supportsFeature('asymmetric_visibility') && $this->enableAsymmetricVisibility,
        ];

        return $features;
    }

    /**
     * Generate compatibility report
     */
    public function getCompatibilityReport(): array
    {
        return [
            'php_version' => $this->getVersionInfo(),
            'extensions' => $this->checkExtensions(),
            'configuration' => $this->getCurrentConfig(),
            'features' => $this->getAvailableFeatures(),
            'performance' => $this->performance,
            'error_handling' => $this->errorHandling,
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }
}
