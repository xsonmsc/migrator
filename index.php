<?php
$dir = __DIR__ . "/migrations";
$migrations = [];
if (is_dir($dir)) {
    foreach (glob($dir . "/*.php") as $path) {
        $migrations[] = basename($path, ".php");
    }
    sort($migrations, SORT_NATURAL | SORT_FLAG_CASE);
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Migration Studio</title>
    <style>
        :root {
            --bg: #f4f2ee;
            --ink: #1c1c1c;
            --muted: #6b6b6b;
            --card: #ffffff;
            --accent: #0f3d2e;
            --line: #ded7cf;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "IBM Plex Sans", "Segoe UI", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(800px 500px at 15% 10%, #efe7db 0%, rgba(239,231,219,0) 70%),
                radial-gradient(900px 600px at 85% 20%, #f6e9d6 0%, rgba(246,233,214,0) 70%),
                var(--bg);
        }
        header { padding: 28px 24px 10px; }
        h1 {
            margin: 0 0 8px;
            font-family: "Playfair Display", Georgia, serif;
            letter-spacing: 0.5px;
        }
        .sub { color: var(--muted); }
        .container {
            padding: 16px 24px 40px;
            display: grid;
            gap: 16px;
            grid-template-columns: 1fr;
        }
        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
        }
        button {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }
        .list {
            display: grid;
            gap: 10px;
        }
        .item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: center;
            padding: 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            color: var(--ink);
        }
        .name { font-weight: 600; }
        .btns { display: flex; gap: 6px; flex-wrap: wrap; }
        .btns a, .btns button {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
        .btns button.secondary {
            background: #f0ebe4;
            color: var(--ink);
            border: 1px solid var(--line);
        }
        .empty { color: var(--muted); }
    </style>
</head>
<body>
    <header>
        <h1>Migration Studio</h1>
        <div class="sub">Migration oluştur ve mevcut migration'ları çalıştır.</div>
    </header>

    <div class="container">
        <div class="card" style="display:flex;gap:8px;flex-wrap:wrap;">
            <button onclick="location.href='create.php'">+ Migration Oluştur</button>
            <button class="secondary" onclick="location.reload()">Yenile</button>
        </div>

        <div class="card">
            <h3>Mevcut Migration Dosyaları</h3>
            <?php if (empty($migrations)): ?>
                <div class="empty">Henüz migration yok.</div>
            <?php else: ?>
                <div class="list">
                    <?php foreach ($migrations as $m): ?>
                        <div class="item" data-name="<?php echo htmlspecialchars($m); ?>">
                            <div class="name"><?php echo htmlspecialchars($m); ?></div>
                            <div class="btns">
                                <a href="run.php?file=<?php echo urlencode($m); ?>">Run</a>
                                <button class="secondary" onclick="backupMig('<?php echo htmlspecialchars($m); ?>')">Backup</button>
                                <button class="secondary" onclick="deleteMig('<?php echo htmlspecialchars($m); ?>')">Sil</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

<script>
function backupMig(name){
    fetch("api/backup_migration.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ name })
    }).then(r => r.json()).then(res => {
        if (res && res.ok) {
            location.reload();
        } else {
            alert("Backup hata: " + (res && res.error ? res.error : "unknown"));
        }
    });
}

function deleteMig(name){
    if (!confirm("Silmek istediğine emin misin?")) return;
    fetch("api/delete_migration.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ name })
    }).then(r => r.json()).then(res => {
        if (res && res.ok) {
            location.reload();
        } else {
            alert("Silme hata: " + (res && res.error ? res.error : "unknown"));
        }
    });
}
</script>
</body>
</html>
