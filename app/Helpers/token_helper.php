<?php

/**
 * Token Helper Functions
 * 
 * Helper functions untuk generate dan manage token ujian
 */

if (!function_exists('generateUjianToken')) {
    /**
     * Generate token ujian yang konsisten
     * 
     * @param int $ujianId ID ujian
     * @param string|null $updatedAt Timestamp update ujian
     * @param string|null $createdAt Timestamp create ujian
     * @return string Token 6 karakter
     */
    function generateUjianToken($ujianId, $updatedAt = null, $createdAt = null)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        
        // Gunakan ujian ID dan timestamp sebagai seed untuk konsistensi
        $seedValue = $ujianId . date('Ymd') . ($updatedAt ?? $createdAt ?? date('Y-m-d H:i:s'));
        
        // Buat hash dari seed untuk mendapatkan token yang konsisten
        $hash = md5($seedValue);
        $token = '';
        
        // Ambil 6 karakter dari hash dan konversi ke format yang diinginkan
        for ($i = 0; $i < 6; $i++) {
            $index = hexdec(substr($hash, $i * 2, 2)) % strlen($chars);
            $token .= $chars[$index];
        }
        
        return $token;
    }
}

if (!function_exists('generateRandomToken')) {
    /**
     * Generate token random baru (untuk refresh)
     * 
     * @param int $length Panjang token (default: 6)
     * @return string Token random
     */
    function generateRandomToken($length = 6)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $token = '';
        
        for ($i = 0; $i < $length; $i++) {
            $token .= $chars[random_int(0, strlen($chars) - 1)];
        }
        
        return $token;
    }
}

if (!function_exists('validateUjianToken')) {
    /**
     * Validasi token ujian
     * 
     * @param string $inputToken Token yang diinput user
     * @param int $ujianId ID ujian
     * @param string|null $updatedAt Timestamp update ujian
     * @param string|null $createdAt Timestamp create ujian
     * @return bool True jika valid
     */
    function validateUjianToken($inputToken, $ujianId, $updatedAt = null, $createdAt = null)
    {
        $correctToken = generateUjianToken($ujianId, $updatedAt, $createdAt);
        return strtoupper(trim($inputToken)) === strtoupper(trim($correctToken));
    }
}

if (!function_exists('formatTokenDisplay')) {
    /**
     * Format token untuk display dengan styling
     * 
     * @param string $token Token yang akan diformat
     * @param string $cssClass CSS class tambahan
     * @return string HTML formatted token
     */
    function formatTokenDisplay($token, $cssClass = '')
    {
        $defaultClass = 'token-code bg-primary text-white px-2 py-1 rounded';
        $class = $cssClass ? $defaultClass . ' ' . $cssClass : $defaultClass;
        
        return sprintf(
            '<code class="%s" style="font-family: \'Courier New\', monospace; font-weight: bold; letter-spacing: 2px;">%s</code>',
            $class,
            strtoupper($token)
        );
    }
}

if (!function_exists('getTokenExpiryTime')) {
    /**
     * Get waktu expired token (berdasarkan hari)
     * 
     * @param string $createdDate Tanggal pembuatan token (Y-m-d)
     * @return string Waktu expired (Y-m-d 23:59:59)
     */
    function getTokenExpiryTime($createdDate = null)
    {
        $date = $createdDate ?: date('Y-m-d');
        return $date . ' 23:59:59';
    }
}

if (!function_exists('isTokenExpired')) {
    /**
     * Cek apakah token sudah expired
     * 
     * @param string $createdDate Tanggal pembuatan token (Y-m-d)
     * @return bool True jika expired
     */
    function isTokenExpired($createdDate = null)
    {
        $expiryTime = getTokenExpiryTime($createdDate);
        return time() > strtotime($expiryTime);
    }
}

if (!function_exists('getTokenInfo')) {
    /**
     * Get informasi lengkap token
     * 
     * @param int $ujianId ID ujian
     * @param string|null $updatedAt Timestamp update ujian
     * @param string|null $createdAt Timestamp create ujian
     * @return array Token info
     */
    function getTokenInfo($ujianId, $updatedAt = null, $createdAt = null)
    {
        $token = generateUjianToken($ujianId, $updatedAt, $createdAt);
        $createdDate = date('Y-m-d');
        
        return [
            'token' => $token,
            'formatted' => formatTokenDisplay($token),
            'created_date' => $createdDate,
            'expiry_time' => getTokenExpiryTime($createdDate),
            'is_expired' => isTokenExpired($createdDate),
            'length' => strlen($token),
            'type' => 'alphanumeric'
        ];
    }
}
