<?php
$results = [];

if (isset($_POST['proses'])) {
    $input = $_POST['input_data'];

    // 1. SHA-256 (Hexadecimal)
    $results['SHA-256 (Hex)'] = hash('sha256', $input);

    // 2. SHA-256 (Base64)
    $results['SHA-256 (Base64)'] = base64_encode(hash('sha256', $input, true));

    // 3. Bcrypt
    $results['Bcrypt'] = password_hash($input, PASSWORD_BCRYPT);

    // 4. Argon2id (parameter umum)
    $results['Argon2id'] = password_hash(
        $input,
        PASSWORD_ARGON2ID,
        [
            'memory_cost' => 32768, // 32 MB
            'time_cost'   => 3,
            'threads'     => 1

        ]
    );

    // 5. Text to Hexadecimal (ASCII → HEX)
    $results['Hexadecimal'] = strtoupper(bin2hex($input));

    // 6. Konversi angka (jika numerik)
    if (is_numeric($input)) {
        $num = (int)$input;
        $results['Decimal to Binary'] = decbin($num);
        $results['Decimal to Hexadecimal (Number)'] = strtoupper(dechex($num));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hashing Tool</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; }
        .btn-copy { min-width: 110px; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card shadow mx-auto" style="max-width: 900px;">
        <div class="card-header bg-dark text-white text-center">
            <h5 class="mb-0">Multi Hashing Tools</h5>
        </div>

        <div class="card-body p-4">
            <form method="POST" class="mb-4">
                <label class="fw-bold mb-2">Input Data:</label>
                <textarea name="input_data" class="form-control mb-3" rows="3" required><?= isset($_POST['input_data']) ? htmlspecialchars($_POST['input_data']) : '' ?></textarea>

                <div class="d-grid gap-2">
                    <button type="submit" name="proses" class="btn btn-primary fw-bold">
                        PROSES DATA
                    </button>
                    
                    <button type="button" class="btn btn-warning" onclick="resetForm()">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </button>

                    <a href="/" class="btn btn-outline-secondary">
                        <i class="bi bi-house"></i> Kembali ke Beranda
                    </a>
                </div>
            </form>

            <?php if (!empty($results)): ?>
                <div class="row g-3">
                    <?php foreach ($results as $label => $value): ?>
                        <div class="col-12">
                            <label class="small fw-bold text-secondary"><?= $label ?></label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="res_<?= md5($label) ?>"
                                    class="form-control bg-light"
                                    value="<?= htmlspecialchars($value) ?>"
                                    readonly
                                >
                                <button class="btn btn-outline-secondary btn-copy"
                                    onclick="copyValue('res_<?= md5($label) ?>', this)">
                                    <i class="bi bi-clipboard"></i> Salin
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function copyValue(id, btn) {
    const input = document.getElementById(id);
    navigator.clipboard.writeText(input.value).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2"></i>';
        btn.classList.replace('btn-outline-secondary', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.replace('btn-success', 'btn-outline-secondary');
        }, 1000);
    });
}
</script>

<script>
function copyValue(id, btn) {
    const input = document.getElementById(id);
    navigator.clipboard.writeText(input.value).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2"></i>';
        btn.classList.replace('btn-outline-secondary', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.replace('btn-success', 'btn-outline-secondary');
        }, 1000);
    });
}

// RESET FORM & HASIL
function resetForm() {
    // Kosongkan textarea
    document.querySelector('textarea[name="input_data"]').value = '';

    // Hapus hasil output
    const results = document.querySelectorAll('[id^="res_"]');
    results.forEach(el => el.value = '');

    // Optional: fokus kembali ke input
    document.querySelector('textarea[name="input_data"]').focus();
}
</script>


</body>
</html>
