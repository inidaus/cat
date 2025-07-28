<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Timezone Configuration
 * 
 * Konfigurasi timezone untuk aplikasi CBT
 * Semua waktu dalam aplikasi akan menggunakan timezone ini
 */
class Timezone extends BaseConfig
{
    /**
     * Default timezone untuk aplikasi
     *
     * Ubah sesuai dengan lokasi server Anda:
     * - Asia/Jakarta (WIB, UTC+7)
     * - Asia/Kuala_Lumpur (MYT, UTC+8)
     * - Asia/Singapore (SGT, UTC+8)
     * - Asia/Bangkok (ICT, UTC+7)
     * - Asia/Manila (PHT, UTC+8)
     * - Asia/Tokyo (JST, UTC+9)
     *
     * @var string
     */
    public $defaultTimezone = 'Asia/Jakarta';

    /**
     * Auto-detect timezone dari sistem operasi
     * 
     * Jika true, akan mencoba mendeteksi timezone dari OS
     * Jika false, akan menggunakan defaultTimezone
     * 
     * @var bool
     */
    public $autoDetect = true;

    /**
     * Fallback timezone jika auto-detect gagal
     *
     * @var string
     */
    public $fallbackTimezone = 'Asia/Jakarta';

    /**
     * Force timezone override
     *
     * Jika tidak null, akan memaksa menggunakan timezone ini
     * Berguna untuk debugging atau server yang timezone-nya tidak bisa dideteksi
     * Set ke null untuk auto-detect timezone sistem
     *
     * @var string|null
     */
    public $forceTimezone = 'Asia/Jakarta'; // Force timezone ke Asia/Jakarta

    /**
     * Mapping Windows timezone ke PHP timezone
     *
     * @var array
     */
    public $windowsTimezoneMap = [
        'W. Indonesia Standard Time' => 'Asia/Jakarta',        // Indonesia WIB (+7) - PRIORITAS
        'SE Asia Standard Time' => 'Asia/Jakarta',             // SE Asia (+7) -> Jakarta
        'Singapore Standard Time' => 'Asia/Singapore',         // Singapore (+8)
        'Malay Peninsula Standard Time' => 'Asia/Kuala_Lumpur', // Malaysia (+8)
        'China Standard Time' => 'Asia/Shanghai',              // China (+8)
        'Tokyo Standard Time' => 'Asia/Tokyo',                 // Japan (+9)
        'Korea Standard Time' => 'Asia/Seoul',                 // Korea (+9)
        'Central Indonesia Standard Time' => 'Asia/Makassar',  // Indonesia WITA (+8)
        'Eastern Indonesia Standard Time' => 'Asia/Jayapura',  // Indonesia WIT (+9)
        'India Standard Time' => 'Asia/Kolkata',               // India (+5:30)
        'Myanmar Standard Time' => 'Asia/Yangon',              // Myanmar (+6:30)
        'Indochina Time' => 'Asia/Ho_Chi_Minh'                 // Vietnam (+7)
    ];

    /**
     * Mapping offset ke timezone
     *
     * @var array
     */
    public $offsetTimezoneMap = [
        7 => 'Asia/Jakarta',         // +7 (Indonesia WIB, Thailand, Vietnam) - PRIORITAS
        8 => 'Asia/Kuala_Lumpur',    // +8 (Malaysia, Singapore, Philippines)
        9 => 'Asia/Tokyo',           // +9 (Japan, Korea)
        5.5 => 'Asia/Kolkata',       // +5:30 (India)
        6.5 => 'Asia/Yangon',        // +6:30 (Myanmar)
        0 => 'UTC',                  // UTC
        -5 => 'America/New_York',    // -5 (US Eastern)
        -8 => 'America/Los_Angeles', // -8 (US Pacific)
        1 => 'Europe/London',        // +1 (UK, during DST)
        2 => 'Europe/Berlin'         // +2 (Central Europe)
    ];

    /**
     * Get configured timezone
     * 
     * @return string
     */
    public function getTimezone(): string
    {
        // Jika ada force timezone, gunakan itu
        if ($this->forceTimezone) {
            return $this->forceTimezone;
        }

        // Jika auto-detect disabled, gunakan default
        if (!$this->autoDetect) {
            return $this->defaultTimezone;
        }

        // Coba deteksi dari sistem
        $detected = $this->detectSystemTimezone();
        
        return $detected ?: $this->fallbackTimezone;
    }

    /**
     * Detect system timezone
     *
     * @return string|null
     */
    private function detectSystemTimezone(): ?string
    {
        // Coba dari Windows timezone
        if (PHP_OS_FAMILY === 'Windows') {
            $output = [];
            exec('tzutil /g 2>nul', $output);
            if (!empty($output[0])) {
                $windowsTimezone = trim($output[0]);
                if (isset($this->windowsTimezoneMap[$windowsTimezone])) {
                    return $this->windowsTimezoneMap[$windowsTimezone];
                }
            }

            // Coba alternatif command untuk Windows
            $output2 = [];
            exec('wmic timezone get StandardName /value 2>nul', $output2);
            foreach ($output2 as $line) {
                if (strpos($line, 'StandardName=') === 0) {
                    $windowsTimezone = trim(substr($line, 13));
                    if (isset($this->windowsTimezoneMap[$windowsTimezone])) {
                        return $this->windowsTimezoneMap[$windowsTimezone];
                    }
                }
            }
        }

        // Coba dari offset sistem
        $currentOffset = (int)(date('Z') / 3600);
        if (isset($this->offsetTimezoneMap[$currentOffset])) {
            return $this->offsetTimezoneMap[$currentOffset];
        }

        // Coba deteksi dari environment variable
        $envTimezone = getenv('TZ');
        if ($envTimezone && in_array($envTimezone, timezone_identifiers_list())) {
            return $envTimezone;
        }

        return null;
    }
}
