<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Color Code Selector</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f8f9fa; }
        .preview-box{
            height:140px;
            border-radius:12px;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:22px;
            font-weight:600;
            text-shadow:0 2px 4px rgba(0,0,0,.4);
        }
        .code-input{
            font-family: monospace;
        }
        .copy-msg{
            font-size:13px;
            color:green;
            display:none;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="/" class="btn btn-outline-secondary btn-sm">← Kembali</a>
        <h3 class="m-0">Color Code Selector</h3>
    </div>

    <!-- Preview -->
    <div id="previewBox" class="preview-box mb-4" style="background:#4E73DF">
        <span id="previewLabel">#C02EC2</span>
    </div>

    <!-- Controls -->
    <div class="row g-3 align-items-center mb-4">
        <div class="col-auto">
            <input type="color" id="colorPicker" class="form-control form-control-color" value="#C02EC2">
        </div>
        <div class="col">
            <input type="text" id="hexInput" class="form-control code-input" value="#C02EC2">
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-copy="hexInput">Copy</button>
        </div>
    </div>

    <!-- Output -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">RGB</label>
            <div class="input-group">
                <input type="text" id="rgbOutput" class="form-control code-input" readonly>
                <button class="btn btn-outline-secondary" data-copy="rgbOutput">Copy</button>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label">HSL</label>
            <div class="input-group">
                <input type="text" id="hslOutput" class="form-control code-input" readonly>
                <button class="btn btn-outline-secondary" data-copy="hslOutput">Copy</button>
            </div>
        </div>
    </div>

    <div id="copyMsg" class="copy-msg">✔ Berhasil disalin</div>

    <hr>

    <h5 class="mb-3">Standard Reference</h5>
    <table class="table align-middle">
        <thead class="table-light">
            <tr>
                <th>Preview</th>
                <th>Nama</th>
                <th>Hex</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $colors = [
            ["Midnight Blue","#2c3e50"],
            ["Turquoise","#1abc9c"],
            ["Pumpkin","#d35400"],
            ["Concrete","#95a5a6"]
        ];
        foreach($colors as $i=>$c){
            echo "
            <tr>
                <td><div style='width:30px;height:30px;border-radius:50%;background:$c[1]'></div></td>
                <td>$c[0]</td>
                <td><code id='ref$i'>$c[1]</code></td>
                <td><button class='btn btn-sm btn-outline-secondary' data-copy='ref$i'>Copy</button></td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script>
/* ========= UTIL ========= */
function hexToRgb(hex){
    hex = hex.replace('#','');
    if(hex.length === 3){
        hex = hex.split('').map(h => h+h).join('');
    }
    const bigint = parseInt(hex,16);
    return {
        r:(bigint>>16)&255,
        g:(bigint>>8)&255,
        b:bigint&255
    };
}

function rgbToHsl(r,g,b){
    r/=255; g/=255; b/=255;
    const max=Math.max(r,g,b), min=Math.min(r,g,b);
    let h,s,l=(max+min)/2;

    if(max===min){
        h=s=0;
    }else{
        const d=max-min;
        s=l>0.5?d/(2-max-min):d/(max+min);
        switch(max){
            case r: h=(g-b)/d+(g<b?6:0); break;
            case g: h=(b-r)/d+2; break;
            case b: h=(r-g)/d+4; break;
        }
        h*=60;
    }
    return `hsl(${Math.round(h)}, ${Math.round(s*100)}%, ${Math.round(l*100)}%)`;
}

/* ========= ELEMENT ========= */
const picker = document.getElementById('colorPicker');
const hexInput = document.getElementById('hexInput');
const preview = document.getElementById('previewBox');
const label = document.getElementById('previewLabel');
const rgbOut = document.getElementById('rgbOutput');
const hslOut = document.getElementById('hslOutput');
const copyMsg = document.getElementById('copyMsg');

/* ========= UPDATE ========= */
function updateFromHex(hex){
    if(!/^#([0-9A-F]{3}|[0-9A-F]{6})$/i.test(hex)) return;

    hex = hex.toUpperCase();
    const {r,g,b} = hexToRgb(hex);

    preview.style.background = hex;
    label.textContent = hex;
    picker.value = hex;
    hexInput.value = hex;
    rgbOut.value = `rgb(${r}, ${g}, ${b})`;
    hslOut.value = rgbToHsl(r,g,b);
}

/* ========= EVENTS ========= */
picker.addEventListener('input', e => updateFromHex(e.target.value));
hexInput.addEventListener('input', e => updateFromHex(e.target.value));

document.addEventListener('click', e => {
    if(e.target.dataset.copy){
        const el = document.getElementById(e.target.dataset.copy);
        navigator.clipboard.writeText(el.value || el.innerText);
        copyMsg.style.display='block';
        setTimeout(()=>copyMsg.style.display='none',1500);
    }
});

/* INIT */
updateFromHex(hexInput.value);
</script>

</body>
</html>
