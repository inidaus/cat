<?php

/**
 * Server Time Helper
 * 
 * Helper functions untuk menangani waktu server
 * Semua waktu menggunakan timezone server sebagai acuan
 */

if (!function_exists('server_time')) {
    /**
     * Get current server time
     * 
     * @param string $format Format waktu (default: 'Y-m-d H:i:s')
     * @return string
     */
    function server_time($format = 'Y-m-d H:i:s')
    {
        // Pastikan menggunakan timezone server
        $serverTimezone = date_default_timezone_get();
        date_default_timezone_set($serverTimezone);
        
        return date($format);
    }
}

if (!function_exists('server_timezone')) {
    /**
     * Get server timezone
     * 
     * @return string
     */
    function server_timezone()
    {
        return date_default_timezone_get();
    }
}

if (!function_exists('server_timezone_offset')) {
    /**
     * Get server timezone offset
     * 
     * @return string Format: +07:00 atau -05:00
     */
    function server_timezone_offset()
    {
        return date('P');
    }
}

if (!function_exists('format_server_time')) {
    /**
     * Format timestamp menggunakan timezone server
     * 
     * @param string|int $timestamp Timestamp atau string waktu
     * @param string $format Format output
     * @return string
     */
    function format_server_time($timestamp, $format = 'Y-m-d H:i:s')
    {
        $serverTimezone = date_default_timezone_get();
        date_default_timezone_set($serverTimezone);
        
        if (is_string($timestamp)) {
            $timestamp = strtotime($timestamp);
        }
        
        return date($format, $timestamp);
    }
}

if (!function_exists('server_datetime_for_db')) {
    /**
     * Get current server datetime untuk database
     * 
     * @return string Format: Y-m-d H:i:s
     */
    function server_datetime_for_db()
    {
        return server_time('Y-m-d H:i:s');
    }
}

if (!function_exists('server_date_for_db')) {
    /**
     * Get current server date untuk database
     * 
     * @return string Format: Y-m-d
     */
    function server_date_for_db()
    {
        return server_time('Y-m-d');
    }
}

if (!function_exists('server_time_for_display')) {
    /**
     * Format waktu untuk display (Indonesia)
     * 
     * @param string|int $timestamp
     * @return string Format: Senin, 15 Januari 2024 | 14:30:45
     */
    function server_time_for_display($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        } elseif (is_string($timestamp)) {
            $timestamp = strtotime($timestamp);
        }
        
        $serverTimezone = date_default_timezone_get();
        date_default_timezone_set($serverTimezone);
        
        // Array hari dalam bahasa Indonesia
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin', 
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        
        // Array bulan dalam bahasa Indonesia
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];
        
        $dayName = $days[date('l', $timestamp)];
        $monthName = $months[date('F', $timestamp)];
        
        $day = date('j', $timestamp);
        $year = date('Y', $timestamp);
        $time = date('H:i:s', $timestamp);
        
        return "{$dayName}, {$day} {$monthName} {$year} | {$time}";
    }
}

if (!function_exists('is_server_dst')) {
    /**
     * Check if server is currently in Daylight Saving Time
     * 
     * @return bool
     */
    function is_server_dst()
    {
        return (bool) date('I');
    }
}

if (!function_exists('server_timezone_info')) {
    /**
     * Get comprehensive server timezone information
     * 
     * @return array
     */
    function server_timezone_info()
    {
        return [
            'timezone' => server_timezone(),
            'offset' => server_timezone_offset(),
            'dst' => is_server_dst(),
            'current_time' => server_time(),
            'timestamp' => time(),
            'display_time' => server_time_for_display()
        ];
    }
}
