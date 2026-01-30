<?php
$hash_result = "";
$match_result = "";
$cost_value = isset($_POST['cost']) ? (int)$_POST['cost'] : 10;

if (isset($_POST['generate'])) {
    $password = $_POST['password_to_hash'];
    $options = ['cost' => $cost_value];
    $start = microtime(true);
    $hash_result = password_hash($password, PASSWORD_BCRYPT, $options);
    $time_taken = round((microtime(true) - $start) * 1000, 2);
}

if (isset($_POST['test'])) {
    $password = $_POST['password_input'];
    $hash = $_POST['hash_input'];
    $match_result = password_verify($password, $hash) 
        ? "<span style='color: #2e7d32;'>✅ Match! Password sesuai.</span>" 
        : "<span style='color: #d32f2f;'>❌ No Match! Password salah.</span>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bcrypt Tool</title>
    <style>
        :root {
            --primary-color: #007bff;
            --success-color: #28a745;
            --bg-color: #f0f2f5;
        }

        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            background-color: var(--bg-color);
            margin: 0; padding: 15px;
            display: flex; flex-direction: column; align-items: center;
        }

        .container { width: 100%; max-width: 500px; }

        h2 { color: #333; text-align: center; margin-top: 10px; font-size: 1.5rem; }

        .card { 
            background: white; 
            padding: 20px; 
            margin-bottom: 20px; 
            border-radius: 12px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
        }

        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }

        input[type="text"], textarea, select { 
            width: 100%; 
            padding: 12px; 
            margin-bottom: 15px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            font-size: 15px; /* Mencegah auto-zoom di iOS */
            box-sizing: border-box;
        }

        button { 
            width: 100%; 
            padding: 14px; 
            border: none; 
            border-radius: 8px; 
            font-size: 15px; 
            font-weight: bold; 
            cursor: pointer;
            transition: opacity 0.2s;
            color: white;
        }

        .btn-gen { background-color: var(--success-color); }
        .btn-test { background-color: var(--primary-color); }
        button:active { opacity: 0.8; }

        .result-box { 
            background: #f8f9fa; 
            padding: 15px; 
            border-radius: 8px; 
            border-left: 4px solid #ddd;
            margin-top: 15px;
            word-wrap: break-word;
            font-family: 'Courier New', Courier, monospace;
            font-size: 16px;
        }

        .info-text { font-size: 12px; color: #888; margin-bottom: 15px; }

        /* Media Query untuk layar sangat kecil */
        @media (max-width: 400px) {
            body { padding: 10px; }
            h2 { font-size: 1.2rem; }
            .card { padding: 15px; }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Bcrypt Tool</h2>
    <a href="/">Kembali ke Beranda</a>
<br></br>

    <div class="card">
        <form method="post">
            <label>Generate Hash</label>
            <input type="text" name="password_to_hash" placeholder="Masukkan password..." required>
            
            <label>Cost Factor</label>
            <select name="cost">
                <?php for($i=4; $i<=14; $i++): ?>
                    <option value="<?= $i ?>" <?= ($i == $cost_value) ? 'selected' : '' ?>>
                        Cost: <?= $i ?> <?= ($i == 10 ? '(Recomended)' : '') ?>
                    </option>
                <?php endfor; ?>
            </select>
            
            <button type="submit" name="generate" class="btn-gen">GENERATE BCRYPT</button>
        </form>

        <?php if ($hash_result): ?>
            <div class="result-box" style="border-color: var(--success-color);">
                <strong>Hash Result:</strong><br>
                <span id="hashText"><?= $hash_result ?></span>
                <br><br>
                <small>Speed: <?= $time_taken ?> ms</small>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <form method="post">
            <label>Match Tester</label>
            <input type="text" name="password_input" placeholder="Password mentah..." required>
            <textarea name="hash_input" rows="3" placeholder="Paste hash di sini ($2y$10$...)" required></textarea>
            
            <button type="submit" name="test" class="btn-test">CHECK MATCH</button>
        </form>

        <?php if ($match_result): ?>
            <div class="result-box" style="border-color: var(--primary-color); text-align: center;">
                <?= $match_result ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>