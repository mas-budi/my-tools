<?php
$domain = '';
$rows = [];
$error = '';
$checkedAt = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domain = trim($_POST['domain'] ?? '');

    if (!filter_var("http://$domain", FILTER_VALIDATE_URL)) {
        $error = "Domain tidak valid";
    } else {
        $checkedAt = time();

        $map = [
            'A'     => DNS_A,
            'AAAA'  => DNS_AAAA,
            'CNAME' => DNS_CNAME,
            'MX'    => DNS_MX,
            'TXT'   => DNS_TXT,
            'NS'    => DNS_NS,
        ];

        foreach ($map as $type => $flag) {
            $records = dns_get_record($domain, $flag);
            foreach ($records as $r) {
                $value =
                    $r['ip'] ??
                    $r['ipv6'] ??
                    ($r['target'] ?? '') .
                    (isset($r['pri']) ? " (Priority {$r['pri']})" : '') ??
                    ($r['txt'] ?? '');

                $rows[] = [
                    'type' => $type,
                    'value' => $value,
                    'ttl' => $r['ttl'] ?? '-'
                ];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>DNS Tools</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
:root{
    --primary:#0d6efd;
    --bg:#f8f9fa;
    --card:#fff;
    --border:#dee2e6;
}
*{box-sizing:border-box;font-family:system-ui,sans-serif}
body{margin:0;background:var(--bg)}
.navbar{background:var(--primary);padding:12px 20px;color:#fff}
.container{max-width:1000px;margin:30px auto;padding:0 15px}
.card{background:var(--card);border:1px solid var(--border);border-radius:8px;padding:20px;margin-bottom:20px}
input{width:100%;padding:10px;border-radius:6px;border:1px solid var(--border)}
button{padding:10px 16px;border-radius:6px;border:none;background:var(--primary);color:#fff;cursor:pointer}
.alert{background:#f8d7da;color:#842029;padding:10px;border-radius:6px;margin-bottom:15px}

table{width:100%;border-collapse:collapse}
th,td{padding:10px;border-bottom:1px solid var(--border);text-align:left}
th{background:#f1f3f5}
.badge{background:#198754;color:#fff;padding:4px 8px;border-radius:4px;font-size:12px}
</style>
</head>

<body>

<div class="navbar">
⬅ <a href="/" style="color:#fff;text-decoration:none;">Kembali ke Beranda</a>
</div>

<div class="container">

<div class="card">
<h2>DNS Tools</h2>

<?php if ($error): ?>
<div class="alert"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <input name="domain" placeholder="contoh: example.com" value="<?= htmlspecialchars($domain) ?>">
    <br><br>
    <button>Cek DNS</button>
</form>
</div>

<?php if ($rows): ?>
<div class="card">
<h3>Hasil DNS Record</h3>

<table>
<thead>
<tr>
    <th>Type</th>
    <th>Value</th>
    <th>TTL</th>
</tr>
</thead>
<tbody>
<?php foreach ($rows as $r): ?>
<tr>
    <td><strong><?= $r['type'] ?></strong></td>
    <td><?= htmlspecialchars($r['value']) ?></td>
    <td><span class="badge"><?= $r['ttl'] ?></span></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php
$ttls = array_column($rows, 'ttl');
$avgTtl = round(array_sum($ttls) / count($ttls));
$elapsed = time() - $checkedAt;
?>


</div>
<?php endif; ?>

<hr>

</div>
</body>
</html>