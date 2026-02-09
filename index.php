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
        <div class="sub" data-i18n="home_sub">Migration oluştur ve mevcut migration'ları çalıştır.</div>
    </header>

    <div class="container">
        <div class="card" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;justify-content:space-between;">
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button onclick="location.href='create.php'" data-i18n="create_btn">+ Migration Oluştur</button>
                <button class="secondary" onclick="location.reload()" data-i18n="refresh_btn">Yenile</button>
            </div>
            <div>
                <select id="lang" class="secondary" style="padding:6px 10px;border-radius:8px;">
                    <option value="en">EN</option>
                    <option value="tr">TR</option>
                    <option value="az">AZ</option>
                </select>
            </div>
        </div>

        <div class="card">
            <h3 data-i18n="existing">Mevcut Migration Dosyaları</h3>
            <?php if (empty($migrations)): ?>
                <div class="empty" data-i18n="empty">Henüz migration yok.</div>
            <?php else: ?>
                <div class="list">
                    <?php foreach ($migrations as $m): ?>
                        <div class="item" data-name="<?php echo htmlspecialchars($m); ?>">
                            <div class="name"><?php echo htmlspecialchars($m); ?></div>
                            <div class="btns">
                                <a href="run.php?file=<?php echo urlencode($m); ?>" data-i18n="run_btn">Run</a>
                                <button class="secondary" onclick="backupMig('<?php echo htmlspecialchars($m); ?>')" data-i18n="backup_btn">Backup</button>
                                <button class="secondary" onclick="deleteMig('<?php echo htmlspecialchars($m); ?>')" data-i18n="delete_btn">Sil</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

<script>
const I18N = {
    en: {
        home_sub: "Create migrations and run existing ones.",
        create_btn: "+ Create Migration",
        refresh_btn: "Refresh",
        existing: "Existing Migration Files",
        empty: "No migrations yet.",
        run_btn: "Run",
        backup_btn: "Backup",
        delete_btn: "Delete",
        delete_confirm: "Are you sure you want to delete?",
        backup_err: "Backup error: ",
        delete_err: "Delete error: "
    },
    tr: {
        home_sub: "Migration oluştur ve mevcut migration'ları çalıştır.",
        create_btn: "+ Migration Oluştur",
        refresh_btn: "Yenile",
        existing: "Mevcut Migration Dosyaları",
        empty: "Henüz migration yok.",
        run_btn: "Çalıştır",
        backup_btn: "Yedekle",
        delete_btn: "Sil",
        delete_confirm: "Silmek istediğine emin misin?",
        backup_err: "Backup hata: ",
        delete_err: "Silme hata: "
    },
    az: {
        home_sub: "Migration yaradın və mövcud migrationları işə salın.",
        create_btn: "+ Migration Yarat",
        refresh_btn: "Yenilə",
        existing: "Mövcud Migration Faylları",
        empty: "Hələ migration yoxdur.",
        run_btn: "İşə sal",
        backup_btn: "Yedəklə",
        delete_btn: "Sil",
        delete_confirm: "Silmək istədiyinizə əminsiniz?",
        backup_err: "Backup xətası: ",
        delete_err: "Silmə xətası: "
    }
};

function detectLang() {
    const saved = localStorage.getItem("lang");
    if (saved && I18N[saved]) return saved;
    const sys = (navigator.language || "en").toLowerCase();
    if (sys.startsWith("tr")) return "tr";
    if (sys.startsWith("az")) return "az";
    return "en";
}

function applyLang(lang) {
    const dict = I18N[lang] || I18N.en;
    document.querySelectorAll("[data-i18n]").forEach(el => {
        const key = el.getAttribute("data-i18n");
        if (dict[key]) el.textContent = dict[key];
    });
    localStorage.setItem("lang", lang);
    document.documentElement.lang = lang;
}

const langSel = document.getElementById("lang");
const initLang = detectLang();
langSel.value = initLang;
applyLang(initLang);
langSel.addEventListener("change", () => applyLang(langSel.value));

function backupMig(name){
    fetch("api/backup_migration.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ name })
    }).then(r => r.json()).then(res => {
        if (res && res.ok) {
            location.reload();
        } else {
            const dict = I18N[detectLang()] || I18N.en;
            alert(dict.backup_err + (res && res.error ? res.error : "unknown"));
        }
    });
}

function deleteMig(name){
    const dict = I18N[detectLang()] || I18N.en;
    if (!confirm(dict.delete_confirm)) return;
    fetch("api/delete_migration.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ name })
    }).then(r => r.json()).then(res => {
        if (res && res.ok) {
            location.reload();
        } else {
            alert(dict.delete_err + (res && res.error ? res.error : "unknown"));
        }
    });
}
</script>
</body>
</html>
