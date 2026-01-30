<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Online Tools</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --primary: #6366f1;
    --bg: #d8e0e9;
    --card: #ffffff;
    --text: #1e293b;
}

/* ===== BASE ===== */
body {
    margin: 0;
    font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
    background: var(--bg);
    color: var(--text);
    padding-bottom: 50px;
}

/* ===== HEADER ===== */
.site-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 10px;
    background: rgba(99,102,241,.25);
    backdrop-filter: blur(6px);
    border-bottom: 1px solid #e2e8f0;
    z-index: 999;
}

.site-header h1 {
    font-size: 1.4rem;
    font-weight: 800;
}

.site-header h3 {
    font-size: 1rem;
    font-weight: 800;
}

.site-header a {
    text-decoration: none;
}

/* ===== CONTAINER ===== */
.container {
    max-width: 1100px;
    margin: auto;
    padding: 30px 16px 10px 16px;
}

/* ===== GRID ===== */
.menu-grid {
    display: grid;
    gap: 16px;
    grid-template-columns: repeat(2, 1fr); /* HP */
}

@media (min-width: 768px) {
    .menu-grid { grid-template-columns: repeat(3, 1fr); }
}

@media (min-width: 1024px) {
    .menu-grid { grid-template-columns: repeat(4, 1fr); }
}

/* ===== APP CARD ===== */
.app {
    background: var(--card);
    border-radius: 22px;
    padding: 18px 10px;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    box-shadow: 0 6px 16px rgba(0,0,0,.08);
    transition: transform .15s ease, box-shadow .15s ease;
}

.app i {
    font-size: 2.8rem;
    color: var(--primary);
}

.app span {
    font-size: .85rem;
    font-weight: 600;
    text-align: center;
    line-height: 1.2;
}

.app:active {
    transform: scale(.96);
}

/* ===== FOOTER ===== */
.site-footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    padding: 6px;
    text-align: center;
    font-size: 1rem;
    background: rgba(87, 90, 242, 0.2);
    backdrop-filter: blur(6px);
    border-top: 1px solid #e2e8f0;
}
</style>
</head>

<body>

<header class="site-header">
    <h1>My Online Tools</h1>
    <h3><a href="about.html">About</a></h3>
</header>

<div class="container">
    <div class="menu-grid">

<?php
$blacklist = ['.well-known','cgi-bin'];

/* Mapping folder → aplikasi */
$appMap = [
    'File_Hash_Generator' => ['icon'=>'fa-file','name'=>'File Hashing'],
    'Hashing_Tools' => ['icon'=>'fa-file-lines','name'=>'Text Hashing'],
    'DNS_Tools'  => ['icon'=>'fa-globe','name'=>'DNS Lookup'],
    'ip_address_lookup'   => ['icon'=>'fa-network-wired','name'=>'IP Address Lookup'],
    'html_color_code'   => ['icon'=>'fa-brush','name'=>'HTML Color Code'],
    'http' => ['icon'=>'fa-server','name'=>'HTTP Checker'],
    'subnet_calculator'   => ['icon'=>'fa-network-wired','name'=>'Subnet Calculator'],
    'unit_converter' => ['icon'=>'fa-arrow-right-arrow-left','name'=>'Unit Converter'],
    'jpg_compress' => ['icon'=>'fa-solid fa-image','name'=>'Jpeg Compress'],
    'bcrypt_hash_generator' => ['icon'=>'fa-solid fa-key','name'=>'Bcrypt Hash Generator'],
];

$dirs = array_filter(glob('*'), 'is_dir');

foreach ($dirs as $dir) {
    if (in_array($dir, $blacklist)) continue;

    $icon = $appMap[$dir]['icon'] ?? 'fa-toolbox';
    $name = $appMap[$dir]['name'] ?? ucfirst($dir);

    echo "
    <a href='$dir/' class='app'>
        <i class='fa-solid $icon'></i>
        <span>$name</span>
    </a>";
}
?>

    </div>
</div>

<footer class="site-footer">
    © <?= date('Y') ?> Budi Setiaji
</footer>

</body>
</html>
