<?php
// 1. Logika untuk mendapatkan IP Pengunjung
$user_ip = $_SERVER['REMOTE_ADDR'];

$ip_data = null;
$error = null;

// Jika form disubmit atau tombol "Cek IP Saya" ditekan
if (isset($_GET['ip']) && !empty($_GET['ip'])) {
    $target_ip = filter_var($_GET['ip'], FILTER_SANITIZE_STRING);
    
    // API URL
    $api_url = "http://ip-api.com/json/" . $target_ip . "?fields=status,message,country,countryCode,regionName,city,zip,lat,lon,timezone,isp,org,as,query";
    
    // Mengambil data
    $response = @file_get_contents($api_url);
    $ip_data = json_decode($response, true);

    if ($ip_data['status'] === 'fail') {
        $error = "Gagal mendapatkan data: " . $ip_data['message'];
        $ip_data = null;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Geolocation Tool</title>
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #64748b;
            --bg: #f1f5f9;
            --card: #ffffff;
            --text: #0f172a;
        }

        body { font-family: 'Inter', system-ui, sans-serif; background-color: var(--bg); color: var(--text); padding: 20px 10px; margin: 0; }
        .container { width: 100%; max-width: 800px; margin: auto; }
        
        /* Tombol Navigasi Atas */
        .nav-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .btn-back { text-decoration: none; color: var(--secondary); font-size: 1.3rem; display: flex; align-items: center; gap: 5px; font-weight: 600; }
        .btn-back:hover { color: var(--text); }

        .card { background: var(--card); padding: 2rem; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        
        h2 { margin-top: 0; color: var(--primary); text-align: center; margin-bottom: 1.5rem; }
        
        .search-box { display: flex; flex-direction: column; gap: 12px; margin-bottom: 2rem; }
        .input-group { display: flex; gap: 10px; }
        input[type="text"] { flex: 1; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; }
        
        .button-group { display: flex; gap: 10px; }
        button, .btn-check-self { flex: 1; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: 600; text-align: center; font-size: 0.9rem; transition: 0.3s; border: none; }
        
        button { background: var(--primary); color: white; }
        button:hover { background: #1d4ed8; }
        
        .btn-check-self { background: #e2e8f0; color: var(--text); text-decoration: none; }
        .btn-check-self:hover { background: #cbd5e1; }

        .content-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { .content-layout { grid-template-columns: 1fr; } }

        .result-table { width: 100%; border-collapse: collapse; }
        .result-table td { padding: 10px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }
        .label { font-weight: 600; color: #64748b; width: 100px; }

        .map-container { border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; height: 250px; }
        iframe { width: 100%; height: 100%; border: none; }

        .error { background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center; }
    </style>
</head>
<body>

<div class="container">
    <div class="nav-header">
        <a href="../" class="btn-back">← Kembali ke Beranda</a>
    </div>

    <div class="card">
        <h2>🌐 IP Lookup & Maps</h2>
        
        <form method="GET" class="search-box">
            <div class="input-group">
                <input type="text" name="ip" placeholder="Masukkan IP atau Domain..." required value="<?php echo isset($_GET['ip']) ? htmlspecialchars($_GET['ip']) : ''; ?>">
            </div>
            <div class="button-group">
                <button type="submit">Lacak IP</button>
                <a href="?ip=<?php echo $user_ip; ?>" class="btn-check-self">Cek IP Saya (<?php echo $user_ip; ?>)</a>
            </div>
        </form>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($ip_data): ?>
            <div class="content-layout">
                <div>
                    <table class="result-table">
                        <tr><td class="label">IP</td><td><strong><?php echo $ip_data['query']; ?></strong></td></tr>
                        <tr><td class="label">Negara</td><td><?php echo $ip_data['country']; ?></td></tr>
                        <tr><td class="label">Kota</td><td><?php echo $ip_data['city']; ?></td></tr>
                        <tr><td class="label">ISP</td><td><?php echo $ip_data['isp']; ?></td></tr>
                        <tr><td class="label">Koordinat</td><td><?php echo $ip_data['lat']; ?>, <?php echo $ip_data['lon']; ?></td></tr>
                    </table>
                </div>

                <div class="map-container">
                    <?php 
                        $lat = $ip_data['lat'];
                        $lon = $ip_data['lon'];
                        $map_url = "https://maps.google.com/maps?q={$lat},{$lon}&t=&z=13&ie=UTF8&iwloc=&output=embed";
                    ?>
                    <iframe src="<?php echo $map_url; ?>"></iframe>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>