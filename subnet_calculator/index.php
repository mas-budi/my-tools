<?php
/**
 * IPv4 Subnet Calculator - Final Version with Back Navigation
 */

ini_set('display_errors', 0); 
error_reporting(E_ALL);

$result = null;
$subnets = [];
$numberOfSubnets = 0;
$ip = $_POST['address'] ?? '';
$cidr = isset($_POST['cidr']) ? (int)$_POST['cidr'] : '';
$target_cidr = isset($_POST['target_cidr']) ? (int)$_POST['target_cidr'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($ip) && $cidr !== '') {
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && $cidr >= 0 && $cidr <= 32) {
        $ip_long = ip2long($ip);
        $mask_long = $cidr == 0 ? 0 : (~0 << (32 - $cidr));
        $net_long = $ip_long & $mask_long;
        $broadcast_long = $net_long | (~$mask_long);
        
        $result = [
            'network'   => long2ip($net_long),
            'broadcast' => long2ip($broadcast_long),
            'mask'      => long2ip($mask_long),
            'range'     => ($cidr >= 31) ? "N/A" : long2ip($net_long + 1) . " - " . long2ip($broadcast_long - 1),
            'hosts'     => ($cidr >= 31) ? 0 : pow(2, (32 - $cidr)) - 2
        ];

        if ($target_cidr > $cidr && $target_cidr <= 32) {
            $numberOfSubnets = pow(2, ($target_cidr - $cidr));
            $ipsPerSubnet = pow(2, (32 - $target_cidr));
            $limit = min($numberOfSubnets, 256); 
            for ($i = 0; $i < $limit; $i++) {
                $sub_net_start = $net_long + ($i * $ipsPerSubnet);
                $sub_brd_end = $sub_net_start + $ipsPerSubnet - 1;
                $subnets[] = [
                    'network'   => long2ip($sub_net_start),
                    'broadcast' => long2ip($sub_brd_end),
                    'range'     => ($target_cidr >= 31) ? "N/A" : long2ip($sub_net_start + 1) . " - " . long2ip($sub_brd_end - 1)
                ];
            }
        }
    } else {
        $error = "Format IP Address atau CIDR tidak valid!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subnet Calculator - JurnalPC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .calculator-card { border-radius: 15px; border: none; overflow: hidden; }
        .header-gradient { background: linear-gradient(45deg, #2c3e50, #3498db); color: white; }
        .sticky-table-head { position: sticky; top: 0; background: white; z-index: 10; }
        .btn-back { color: #6c757d; text-decoration: none; transition: 0.3s; font-size: 0.9rem; }
        .btn-back:hover { color: #3498db; }
        code { font-weight: bold; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="mb-3 d-flex align-items-center">
                <a href="https://tools.jurnalpc.com" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>

            <div class="card shadow-sm calculator-card mb-4">
                <div class="card-header header-gradient p-3 text-center">
                    <h4 class="mb-0">IPv4 Subnet Calculator</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label fw-bold">IP Address</label>
                                <input type="text" name="address" class="form-control form-control-lg" placeholder="e.g. 192.168.1.1" value="<?= htmlspecialchars($ip) ?>" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-bold">CIDR Prefix</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">/</span>
                                    <input type="number" name="cidr" class="form-control" placeholder="24" min="0" max="32" value="<?= htmlspecialchars($cidr) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Subnetting (Opsional)</label>
                            <select name="target_cidr" class="form-select">
                                <option value="">-- Tetap /<?= $cidr ?: '?' ?> (Jangan bagi network) --</option>
                                <?php for($i = ($cidr ?: 0) + 1; $i <= 32; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($target_cidr == $i) ? 'selected' : '' ?>>
                                        Bagi menjadi blok /<?= $i ?> (<?= number_format(pow(2, $i - (int)$cidr)) ?> Subnet)
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="row mt-4 g-2">
                            <div class="col-9">
                                <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">Hitung Sekarang</button>
                            </div>
                            <div class="col-3">
                                <a href="index.php" class="btn btn-outline-secondary btn-lg w-100 shadow-sm" title="Reset">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger mt-3"><?= $error ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($result): ?>
                <div class="card shadow-sm calculator-card mb-4">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr><th colspan="2" class="px-4 py-3">Informasi Network Utama</th></tr>
                            </thead>
                            <tbody>
                                <tr><td class="px-4">Network Address</td><td class="text-end"><code><?= $result['network'] ?></code></td></tr>
                                <tr><td class="px-4">Subnet Mask</td><td class="text-end text-muted"><?= $result['mask'] ?></td></tr>
                                <tr><td class="px-4">Broadcast Address</td><td class="text-end text-danger"><code><?= $result['broadcast'] ?></code></td></tr>
                                <tr><td class="px-4">Usable IP Range</td><td class="text-end"><span class="badge bg-info text-dark"><?= $result['range'] ?></span></td></tr>
                                <tr><td class="px-4">Total Usable Hosts</td><td class="text-end"><span class="badge bg-success"><?= number_format($result['hosts']) ?></span></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if (!empty($subnets)): ?>
                    <div class="card shadow-sm calculator-card">
                        <div class="card-header bg-white p-3">
                            <h6 class="mb-0 fw-bold">Daftar Pecahan Subnet (/<?= $target_cidr ?>)</h6>
                        </div>
                        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                            <table class="table table-sm table-striped mb-0">
                                <thead class="table-dark sticky-table-head">
                                    <tr>
                                        <th class="ps-3">No</th>
                                        <th>Network ID</th>
                                        <th>IP Range</th>
                                        <th>Broadcast</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subnets as $index => $sub): ?>
                                        <tr>
                                            <td class="ps-3"><?= $index + 1 ?></td>
                                            <td><code class="text-primary"><?= $sub['network'] ?></code></td>
                                            <td><small><?= $sub['range'] ?></small></td>
                                            <td><code><?= $sub['broadcast'] ?></code></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <p class="text-center mt-4 text-muted small">© tools.jurnalpc.com - Subnet Calculator </p>
        </div>
    </div>
</div>

</body>
</html>