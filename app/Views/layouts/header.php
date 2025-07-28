<?php
// Menggunakan timezone sederhana untuk menghindari masalah waktu
$serverTimezone = 'Asia/Jakarta';
$tzDB = $serverTimezone;

// Set timezone PHP
date_default_timezone_set($serverTimezone);

// Informasi timezone sederhana
$timezoneInfo = [
    'server_timezone' => $serverTimezone,
    'server_time' => date('Y-m-d H:i:s'),
    'server_timestamp' => time(),
    'server_offset' => date('P')
];
?>

<!DOCTYPE html>
<html lang="en-GB">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - <?= ucfirst(session('role')) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <meta name="time-format" content="24h">
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/luxon@3/build/global/luxon.min.js"></script>

  <style>
    body {
      background: url('<?= base_url('assets/bg-image.jpg') ?>') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
    }

    .glass-wrapper {
      min-height: 100vh;
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.05);
      padding: 2rem;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: white;
      padding: 2rem;
      min-height: 160px;
    }

    .glass-card h3 {
      text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }

    .btn-logout {
      background-color: rgba(255, 255, 255, 0.2);
      color: white;
      border: none;
    }

    .btn-logout:hover {
      background-color: rgba(255, 255, 255, 0.4);
      color: black;
    }

    .glass-title {
      color: white;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
    }

    .sticky-footer {
      position: relative;
      bottom: 0;
      width: 100%;
      padding: 1rem 0;
    }

    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      position: relative;
      overflow-x: hidden;
    }

    .glass-wrapper {
      min-height: calc(100vh - 80px);
    }

    .col-md-1-5 {
      flex: 0 0 auto;
      width: 12.5%;
    }

    @media (max-width: 768px) {
      .col-md-1-5 {
        width: 50%;
      }
    }

    .modal-backdrop.show {
      z-index: 1040;
    }

    .modal.show {
      z-index: 1050;
    }

    .modal-content input,
    .modal-content button,
    .modal-content select {
      z-index: 1051;
      position: relative;
    }

    /* Realtime clock inline */
    #clock-global {
      color: #fff;
      font-size: 1rem;
      font-weight: 500;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
    }

    /* Global 24-Hour Time Picker Styles */
    input[type="time"] {
      -webkit-appearance: none;
      -moz-appearance: textfield;
      appearance: none;
    }

    /* Completely hide AM/PM field */
    input[type="time"]::-webkit-datetime-edit-ampm-field {
      display: none !important;
      visibility: hidden !important;
      width: 0 !important;
      height: 0 !important;
      opacity: 0 !important;
      position: absolute !important;
      left: -9999px !important;
    }

    /* Hide spin buttons */
    input[type="time"]::-webkit-inner-spin-button {
      display: none !important;
    }

    /* Style the calendar picker indicator */
    input[type="time"]::-webkit-calendar-picker-indicator {
      cursor: pointer;
      opacity: 0.6;
    }

    input[type="time"]::-webkit-calendar-picker-indicator:hover {
      opacity: 1;
    }

    /* Style hour and minute fields */
    input[type="time"]::-webkit-datetime-edit-hour-field,
    input[type="time"]::-webkit-datetime-edit-minute-field {
      background-color: transparent;
      border: none;
      outline: none;
      text-align: center;
    }

    /* Style the separator */
    input[type="time"]::-webkit-datetime-edit-text {
      padding: 0 2px;
    }

    .table-hover tbody tr:hover {
  background-color: rgba(0, 123, 255, 0.05);
}

code {
  font-weight: bold;
  color: #007bff;
}
.form-container {
  max-width: 800px;
  margin: auto;
  background: rgba(255,255,255,0.1);
  backdrop-filter: blur(8px);
  padding: 2rem;
  border-radius: 1rem;
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  color: white;
}

  </style>
</head>
<body>
<script>
// ===== KONFIGURASI WAKTU SERVER =====
// Aplikasi ini menggunakan waktu server sebagai acuan utama
// Semua waktu yang ditampilkan akan mengikuti zona waktu server
// Tidak peduli dari zona waktu mana user mengakses aplikasi
const DateTime = luxon.DateTime;
const serverTimezone = "<?= $tzDB ?>"; // Timezone server
const serverInfo = <?= json_encode($timezoneInfo) ?>; // Info server untuk debugging

// Hitung offset antara server dan client untuk sinkronisasi waktu
const serverTimestamp = serverInfo.server_timestamp * 1000; // Convert to milliseconds
const clientTimestamp = Date.now();
const timeOffset = serverTimestamp - clientTimestamp;

// Log informasi timezone server (untuk debugging)
console.log('=== SERVER TIMEZONE CONFIG ===');
console.log('PHP OS Family:', serverInfo.php_os_family);
console.log('Config Force Timezone:', serverInfo.config_force_timezone);
console.log('Config Default Timezone:', serverInfo.config_default_timezone);
console.log('Config Auto Detect:', serverInfo.config_auto_detect);
console.log('Final Server Timezone:', serverInfo.final_server_timezone);
console.log('Server Time:', serverInfo.server_time);
console.log('Server Timestamp:', serverInfo.server_timestamp);
console.log('Server Offset:', serverInfo.server_offset);
console.log('Server Offset (seconds):', serverInfo.server_offset_seconds);
console.log('Server DST:', serverInfo.server_dst);
console.log('System Date (ISO):', serverInfo.system_date);
console.log('Time Offset (ms):', timeOffset);
console.log('===============================');

function updateClockGlobal() {
  const el = document.getElementById('clock-global');
  const elMobile = document.getElementById('clock-global-mobile');

  try {
    // Gunakan waktu server yang sebenarnya dengan offset correction
    const serverNow = new Date(Date.now() + timeOffset);

    if (typeof DateTime !== 'undefined') {
      // Gunakan Luxon jika tersedia
      const now = DateTime.fromJSDate(serverNow).setZone(serverTimezone);
      const day = now.setLocale('id').toFormat('cccc');
      const date = now.setLocale('id').toFormat("dd LLLL yyyy");
      const time = now.toFormat("HH:mm:ss");
      const clockText = `${day}, ${date} | ${time}`;

      if (el) el.innerText = clockText;
      if (elMobile) elMobile.innerText = clockText;
    } else {
      // Fallback jika Luxon tidak tersedia
      const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
        timeZone: serverTimezone
      };

      const formatter = new Intl.DateTimeFormat('id-ID', options);
      const parts = formatter.formatToParts(serverNow);

      const day = parts.find(part => part.type === 'weekday').value;
      const date = `${parts.find(part => part.type === 'day').value} ${parts.find(part => part.type === 'month').value} ${parts.find(part => part.type === 'year').value}`;
      const time = `${parts.find(part => part.type === 'hour').value}:${parts.find(part => part.type === 'minute').value}:${parts.find(part => part.type === 'second').value}`;

      const clockText = `${day}, ${date} | ${time}`;

      if (el) el.innerText = clockText;
      if (elMobile) elMobile.innerText = clockText;
    }
  } catch (error) {
    console.error('Error updating clock:', error);
    // Fallback sederhana jika ada error
    const serverNow = new Date(Date.now() + timeOffset);
    const clockText = serverNow.toLocaleString('id-ID', {
      timeZone: serverTimezone,
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
      hour12: false
    });

    if (el) el.innerText = clockText;
    if (elMobile) elMobile.innerText = clockText;
  }
}
setInterval(updateClockGlobal, 1000);
updateClockGlobal();

// Global 24-Hour Time Picker Script
(function() {
    'use strict';

    // Set document locale to force 24-hour format
    function setLocale24Hour() {
        document.documentElement.setAttribute('lang', 'en-GB');
    }

    // Initialize 24-hour format for a time input
    function initTimeInput(input) {
        if (!input || input.type !== 'time') return;

        input.setAttribute('step', '60');
        input.setAttribute('min', '00:00');
        input.setAttribute('max', '23:59');
        input.setAttribute('pattern', '[0-9]{2}:[0-9]{2}');

        // Format input value to ensure 24-hour format
        function formatTime(value) {
            if (!value) return value;
            const parts = value.split(':');
            if (parts.length >= 2) {
                const hours = parseInt(parts[0], 10);
                const minutes = parseInt(parts[1], 10);
                if (hours >= 0 && hours <= 23 && minutes >= 0 && minutes <= 59) {
                    return String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0');
                }
            }
            return value;
        }

        // Event listeners
        input.addEventListener('input', function() {
            const formatted = formatTime(this.value);
            if (formatted !== this.value) {
                this.value = formatted;
            }
        });

        input.addEventListener('click', function() {
            setLocale24Hour();
        });

        if (input.value) {
            input.value = formatTime(input.value);
        }
    }

    // Initialize all time inputs on the page
    function initAllTimeInputs() {
        const timeInputs = document.querySelectorAll('input[type="time"]');
        timeInputs.forEach(initTimeInput);
    }

    // Observer for dynamically added time inputs
    function setupMutationObserver() {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        if (node.type === 'time') {
                            initTimeInput(node);
                        }
                        const timeInputs = node.querySelectorAll && node.querySelectorAll('input[type="time"]');
                        if (timeInputs) {
                            timeInputs.forEach(initTimeInput);
                        }
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    // Initialize when DOM is ready
    function init() {
        setLocale24Hour();
        initAllTimeInputs();
        setupMutationObserver();
    }

    // Run initialization
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.addEventListener('load', function() {
        setLocale24Hour();
        initAllTimeInputs();
    });

    // Expose functions globally
    window.TimePicker24H = {
        init: init,
        initTimeInput: initTimeInput,
        initAllTimeInputs: initAllTimeInputs,
        setLocale24Hour: setLocale24Hour
    };
})();
</script>
