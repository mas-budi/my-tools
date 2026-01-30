<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Network & Hash Converter Tools</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{background:#E1E8EF}
.container-box{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,.05);
}
.code{font-family:monospace}
.result{margin-top:8px;font-weight:600}
textarea{resize:vertical}
</style>
</head>
<body>

<div class="container my-5">
<div class="container-box">

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="/" class="btn btn-outline-secondary btn-sm">← Kembali ke Beranda</a>
    <h4 class="m-0">Network & Converter Tools</h4>
</div>

<!-- PORT -->
<div class="mb-4">
<h6>Port Number ↔ Service Name</h6>
<input type="number" id="portInput" class="form-control" placeholder="Contoh: 80">
<div id="portResult" class="result code"></div>
</div>

<!-- DECIMAL BINARY -->
<div class="mb-4">
<h6>Decimal ↔ Binary</h6>
<input type="number" id="decInput" class="form-control" placeholder="Contoh: 10">
<div id="binResult" class="result code"></div>
</div>

<!-- DECIMAL HEX -->
<div class="mb-4">
<h6>Decimal ↔ Hexadecimal</h6>
<input type="number" id="hexDecInput" class="form-control" placeholder="Contoh: 255">
<div id="hexResult" class="result code"></div>
</div>

<!-- BANDWIDTH -->
<div class="mb-4">
<h6>Mbps ↔ MB/s</h6>
<input type="number" id="mbpsInput" class="form-control" placeholder="Contoh: 100">
<div id="mbpsResult" class="result code"></div>
</div>

<hr class="my-4">

<!-- TEXT TO SHA-256 -->
<div class="mb-2">
<h6>Text → SHA-256</h6>
<textarea id="shaInput" class="form-control mb-2" rows="3" placeholder="Masukkan teks di sini"></textarea>
<input type="text" id="shaOutput" class="form-control code" readonly placeholder="SHA-256 hash">
<button class="btn btn-sm btn-outline-primary mt-2" id="copySha">Copy Hash</button>
</div>

</div>
</div>

<script>
// COMMON PORTS MAP
const commonPortMap = {
    20: "FTP (Data)", 21: "FTP (Control)", 22: "SSH", 23: "Telnet",
    25: "SMTP", 53: "DNS", 67: "DHCP (Server)", 68: "DHCP (Client)",
    80: "HTTP", 110: "POP3", 123: "NTP", 143: "IMAP",
    161: "SNMP", 179: "BGP", 194: "IRC", 389: "LDAP",
    443: "HTTPS", 445: "SMB/Microsoft-DS", 465: "SMTPS", 514: "Syslog",
    587: "SMTP (Submission)", 636: "LDAPS", 993: "IMAPS", 995: "POP3S",
    1433: "MSSQL", 1723: "PPTP", 2049: "NFS", 3306: "MySQL",
    3389: "RDP", 5432: "PostgreSQL", 5900: "VNC", 6379: "Redis",
    8080: "HTTP Proxy/Alt", 8443: "HTTPS Alt", 27017: "MongoDB"
};

portInput.addEventListener('input', () => {
    const p = portInput.value;
    // Judul Logika: Port Umum (Common Port)
    portResult.textContent = commonPortMap[p]
        ? `Service: ${commonPortMap[p]}`
        : p ? "Bukan port/service umum" : "";
});


// DECIMAL ↔ BINARY
decInput.addEventListener('input',()=>{
    binResult.textContent = decInput.value
        ? `Binary: ${Number(decInput.value).toString(2)}`
        : "";
});

// DECIMAL ↔ HEX
hexDecInput.addEventListener('input',()=>{
    hexResult.textContent = hexDecInput.value
        ? `Hex: ${Number(hexDecInput.value).toString(16).toUpperCase()}`
        : "";
});

// BANDWIDTH
mbpsInput.addEventListener('input',()=>{
    if(!mbpsInput.value){ mbpsResult.textContent=""; return; }
    const mbps = Number(mbpsInput.value);
    const mbs = (mbps/8).toFixed(2);
    mbpsResult.textContent = `${mbps} Mbps = ${mbs} MB/s`;
});

// TEXT → SHA-256
async function sha256(text){
    const data = new TextEncoder().encode(text);
    const hash = await crypto.subtle.digest('SHA-256', data);
    return Array.from(new Uint8Array(hash))
        .map(b => b.toString(16).padStart(2,'0'))
        .join('');
}

shaInput.addEventListener('input', async ()=>{
    shaOutput.value = shaInput.value
        ? await sha256(shaInput.value)
        : "";
});

// COPY SHA (mobile-safe)
copySha.addEventListener('click',()=>{
    if(!shaOutput.value) return;

    if(navigator.clipboard && window.isSecureContext){
        navigator.clipboard.writeText(shaOutput.value);
    }else{
        const t=document.createElement("textarea");
        t.value=shaOutput.value;
        document.body.appendChild(t);
        t.select();
        document.execCommand("copy");
        document.body.removeChild(t);
    }
});
</script>

</body>
</html>
