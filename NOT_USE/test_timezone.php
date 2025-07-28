<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Timezone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .info { background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .server { background: #e3f2fd; }
        .client { background: #f3e5f5; }
        .comparison { background: #e8f5e8; }
    </style>
</head>
<body>
    <h1>Test Timezone - CBT System</h1>
    
    <div class="info server">
        <h3>🖥️ Server Information</h3>
        <p><strong>PHP Timezone:</strong> <?= date_default_timezone_get() ?></p>
        <p><strong>Server Time:</strong> <?= date('Y-m-d H:i:s T') ?></p>
        <p><strong>Server Timestamp:</strong> <?= time() ?></p>
        <p><strong>App Timezone Config:</strong> <?= config('App')->appTimezone ?></p>
        <?php if (class_exists('App\Config\Timezone')): ?>
            <?php $timezoneConfig = new \App\Config\Timezone(); ?>
            <p><strong>Force Timezone:</strong> <?= $timezoneConfig->forceTimezone ?: 'Auto-detect' ?></p>
        <?php endif; ?>
    </div>
    
    <div class="info client">
        <h3>💻 Client Information (JavaScript)</h3>
        <p><strong>Client Timezone:</strong> <span id="clientTimezone">Loading...</span></p>
        <p><strong>Client Time:</strong> <span id="clientTime">Loading...</span></p>
        <p><strong>Client Timestamp:</strong> <span id="clientTimestamp">Loading...</span></p>
        <p><strong>UTC Offset:</strong> <span id="utcOffset">Loading...</span></p>
    </div>
    
    <div class="info comparison">
        <h3>⚖️ Comparison</h3>
        <p><strong>Time Difference:</strong> <span id="timeDiff">Calculating...</span></p>
        <p><strong>Status:</strong> <span id="status">Checking...</span></p>
    </div>
    
    <div class="info">
        <h3>🔧 Test Cases</h3>
        <p><strong>Sample Date Creation:</strong></p>
        <ul>
            <li>PHP date('Y-m-d H:i:s'): <?= date('Y-m-d H:i:s') ?></li>
            <li>PHP new DateTime(): <?= (new DateTime())->format('Y-m-d H:i:s T') ?></li>
            <li>JavaScript new Date(): <span id="jsDate">Loading...</span></li>
        </ul>
    </div>
    
    <script>
        // Get client timezone info
        const now = new Date();
        const clientTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const clientTime = now.toLocaleString('id-ID', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZoneName: 'short'
        });
        const clientTimestamp = Math.floor(now.getTime() / 1000);
        const utcOffset = -now.getTimezoneOffset() / 60;
        
        // Update client info
        document.getElementById('clientTimezone').textContent = clientTimezone;
        document.getElementById('clientTime').textContent = clientTime;
        document.getElementById('clientTimestamp').textContent = clientTimestamp;
        document.getElementById('utcOffset').textContent = `UTC${utcOffset >= 0 ? '+' : ''}${utcOffset}`;
        document.getElementById('jsDate').textContent = now.toString();
        
        // Calculate time difference
        const serverTimestamp = <?= time() ?>;
        const timeDiff = Math.abs(clientTimestamp - serverTimestamp);
        
        document.getElementById('timeDiff').textContent = `${timeDiff} seconds`;
        
        if (timeDiff <= 2) {
            document.getElementById('status').innerHTML = '<span style="color: green;">✅ Synchronized</span>';
        } else if (timeDiff <= 60) {
            document.getElementById('status').innerHTML = '<span style="color: orange;">⚠️ Minor difference</span>';
        } else {
            document.getElementById('status').innerHTML = '<span style="color: red;">❌ Major difference</span>';
        }
        
        // Auto refresh every 5 seconds
        setInterval(() => {
            location.reload();
        }, 5000);
    </script>
    
    <p><small>Page auto-refreshes every 5 seconds</small></p>
</body>
</html>
