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
            --accent-2: #c05d1a;
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
        header {
            padding: 28px 24px 10px;
        }
        h1 {
            margin: 0 0 8px;
            font-family: "Playfair Display", Georgia, serif;
            letter-spacing: 0.5px;
        }
        .sub {
            color: var(--muted);
        }
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
        .grid-2 {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }
        label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            display: block;
            margin-bottom: 6px;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 10px;
            font-size: 14px;
            background: #fff;
        }
        textarea { min-height: 70px; }
        button {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }
        button.secondary {
            background: #f0ebe4;
            color: var(--ink);
            border: 1px solid var(--line);
        }
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f7f2ea;
            border: 1px solid var(--line);
            color: var(--muted);
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
        }
        .mapping {
            display: grid;
            gap: 16px;
            grid-template-columns: 1fr 1.2fr;
        }
        .list {
            border: 1px dashed var(--line);
            border-radius: 12px;
            padding: 12px;
            min-height: 180px;
            background: #fcfaf7;
        }
        .source-item {
            padding: 8px 10px;
            border: 1px solid var(--line);
            border-radius: 10px;
            margin-bottom: 8px;
            background: #fff;
            cursor: grab;
        }
        .target-row {
            display: grid;
            grid-template-columns: 160px 1fr 160px;
            gap: 10px;
            align-items: center;
            padding: 10px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            margin-bottom: 10px;
        }
        .dropzone {
            border: 1px dashed var(--line);
            padding: 8px 10px;
            border-radius: 10px;
            min-height: 38px;
            color: var(--muted);
            background: #fbf9f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .dropzone.filled { color: var(--ink); border-style: solid; }
        .drop-text { flex: 1; }
        .clear-btn {
            background: #f0ebe4;
            color: var(--ink);
            border: 1px solid var(--line);
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
        }
        .php-wrap { display: none; }
        .php-wrap.active { display: block; }
        .drop-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 8px;
            align-items: center;
        }
        .small {
            font-size: 12px;
            color: var(--muted);
        }
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        @media (max-width: 900px) {
            .mapping { grid-template-columns: 1fr; }
            .target-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header>
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
            <div>
                <h1>Migration Studio</h1>
                <div class="sub" data-i18n="create_sub">Kaynak ve hedef tablolar arasında sürükle-bırak ile alan eşleştir.</div>
            </div>
            <div class="actions">
                <button class="secondary" onclick="location.href='index.php'" data-i18n="home_btn">Ana Sayfa</button>
                <select id="lang" class="secondary" style="padding:6px 10px;border-radius:8px;">
                    <option value="en">EN</option>
                    <option value="tr">TR</option>
                    <option value="az">AZ</option>
                </select>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="card">
            <div class="grid-2">
                <div>
                    <label data-i18n="mig_name">Migration Name</label>
                    <input id="mig_name" placeholder="ornek_migration">
                </div>
            </div>
        </div>

        <div class="card">
            <div class="grid-2">
                <div>
                    <label data-i18n="source_db">Kaynak DB</label>
                    <select id="db1"></select>
                </div>
                <div>
                    <label data-i18n="target_db">Hedef DB</label>
                    <select id="db2"></select>
                </div>
                <div>
                    <label data-i18n="source_table">Kaynak Tablo</label>
                    <select id="table1"></select>
                </div>
                <div>
                    <label data-i18n="target_table">Hedef Tablo</label>
                    <select id="table2"></select>
                </div>
                <div>
                    <label data-i18n="dup_mode">Duplicate Key</label>
                    <select id="dup_mode">
                        <option value="ignore">IGNORE</option>
                        <option value="update">UPDATE</option>
                        <option value="error">ERROR</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="mapping">
                <div>
                    <label data-i18n="source_cols">Kaynak Kolonlar</label>
                    <div id="source_list" class="list"></div>
                </div>
                <div>
                    <label data-i18n="target_cols">Hedef Kolonlar</label>
                    <div id="target_list" class="list"></div>
                    <div class="small" data-i18n="target_help">Hedef kolona sürükle-bırak ile kaynak eşleştir. Kolon tipi ve dönüşüm seçebilirsin.</div>
                </div>
            </div>
        </div>

        <div class="card">
            <label data-i18n="new_cols">Yeni Hedef Kolonlar (opsiyonel)</label>
            <div id="new_columns"></div>
            <div class="actions">
                <button class="secondary" onclick="addNewColumn()" data-i18n="add_col">+ Yeni Kolon</button>
                <button class="secondary" onclick="useNewColumnsAsTargets()" data-i18n="apply_targets">Hedef listesine uygula</button>
            </div>
        </div>

        <div class="card">
            <label data-i18n="global_php">Custom PHP (global)</label>
            <textarea id="global_php" placeholder="Örn: if(isset($row['status'])) { ... }"></textarea>
            <div class="actions">
                <button onclick="generate()" data-i18n="generate">Generate Migration</button>
            </div>
        </div>
    </div>

<script>
const db1 = document.getElementById("db1");
const db2 = document.getElementById("db2");
const table1 = document.getElementById("table1");
const table2 = document.getElementById("table2");
const sourceList = document.getElementById("source_list");
const targetList = document.getElementById("target_list");
const newColumns = document.getElementById("new_columns");

const DATATYPES = [
    "",
    "int", "bigint", "smallint", "tinyint", "mediumint",
    "decimal", "float", "double",
    "char", "varchar", "text", "mediumtext", "longtext",
    "date", "datetime", "timestamp", "time", "year",
    "json", "boolean", "enum", "set",
    "int_to_date"
];

function mapType(mysqlType){
    if (!mysqlType) return "";
    const t = mysqlType.toLowerCase();
    if (t.startsWith("bigint")) return "bigint";
    if (t.startsWith("mediumint")) return "mediumint";
    if (t.startsWith("smallint")) return "smallint";
    if (t.startsWith("tinyint")) return "tinyint";
    if (t.startsWith("int")) return "int";
    if (t.startsWith("decimal")) return "decimal";
    if (t.startsWith("double")) return "double";
    if (t.startsWith("float")) return "float";
    if (t.startsWith("char")) return "char";
    if (t.startsWith("varchar")) return "varchar";
    if (t.includes("text")) return "text";
    if (t.startsWith("datetime")) return "datetime";
    if (t.startsWith("timestamp")) return "timestamp";
    if (t.startsWith("date")) return "date";
    if (t.startsWith("time")) return "time";
    if (t.startsWith("year")) return "year";
    if (t.startsWith("json")) return "json";
    if (t.startsWith("enum")) return "enum";
    if (t.startsWith("set")) return "set";
    if (t.startsWith("bool") || t.startsWith("boolean")) return "boolean";
    return "";
}

function api(url){
    return fetch(url).then(r => r.json());
}

function clearSelect(sel){
    sel.innerHTML = "";
}

function addOption(sel, value){
    const opt = document.createElement("option");
    opt.value = value;
    opt.textContent = value;
    sel.appendChild(opt);
}

function loadDatabases(){
    api("api/get_databases.php").then(list => {
        clearSelect(db1);
        clearSelect(db2);
        list.forEach(db => {
            addOption(db1, db);
            addOption(db2, db);
        });
        db1.dispatchEvent(new Event("change"));
        db2.dispatchEvent(new Event("change"));
    });
}

function loadTables(dbSel, tableSel){
    const db = dbSel.value;
    clearSelect(tableSel);
    if (!db) return;
    api("api/get_tables.php?db=" + encodeURIComponent(db)).then(list => {
        (list || []).forEach(t => addOption(tableSel, t));
        tableSel.dispatchEvent(new Event("change"));
    });
}

function loadColumns(db, table){
    if (!db || !table) return Promise.resolve([]);
    return api("api/get_columns.php?db=" + encodeURIComponent(db) + "&table=" + encodeURIComponent(table));
}

function renderSource(cols){
    sourceList.innerHTML = "";
    cols.forEach(c => {
        const item = document.createElement("div");
        item.className = "source-item";
        item.textContent = c.Field;
        item.draggable = true;
        item.dataset.col = c.Field;
        item.dataset.type = c.Type || "";
        item.addEventListener("dragstart", e => {
            e.dataTransfer.setData("text/plain", c.Field);
            e.dataTransfer.setData("text/type", c.Type || "");
        });
        sourceList.appendChild(item);
    });
}

function renderTargets(cols, { merge = false } = {}){
    const existing = new Set();
    if (merge) {
        targetList.querySelectorAll(".target-row").forEach(r => existing.add(r.dataset.target));
    } else {
        targetList.innerHTML = "";
    }
    cols.forEach(c => {
        if (existing.has(c.Field)) return;
        const row = document.createElement("div");
        row.className = "target-row";
        row.dataset.target = c.Field;

        const name = document.createElement("div");
        name.textContent = c.Field;

        const drop = document.createElement("div");
        drop.className = "dropzone";
        const dropText = document.createElement("div");
        dropText.className = "drop-text";
        dropText.textContent = i18n("drop_here");
        const clearBtn = document.createElement("button");
        clearBtn.className = "clear-btn";
        clearBtn.type = "button";
        clearBtn.textContent = "Kaldır";
        clearBtn.addEventListener("click", () => {
            dropText.textContent = i18n("drop_here");
            drop.classList.remove("filled");
            delete drop.dataset.source;
        });
        drop.appendChild(dropText);
        drop.appendChild(clearBtn);
        drop.addEventListener("dragover", e => e.preventDefault());
        drop.addEventListener("drop", e => {
            e.preventDefault();
            const col = e.dataTransfer.getData("text/plain");
            const type = e.dataTransfer.getData("text/type");
            dropText.textContent = col;
            drop.classList.add("filled");
            drop.dataset.source = col;
            const mapped = mapType(type);
            if (mapped) {
                select.value = mapped;
            }
        });

        const select = document.createElement("select");
        select.className = "datatype";
        DATATYPES.forEach(v => {
            const opt = document.createElement("option");
            opt.value = v;
            opt.textContent = v || "none";
            select.appendChild(opt);
        });

        const phpWrap = document.createElement("div");
        phpWrap.className = "php-wrap";
        const php = document.createElement("input");
        php.className = "php";
        php.placeholder = i18n("php_placeholder");
        phpWrap.appendChild(php);

        const addPhpBtn = document.createElement("button");
        addPhpBtn.className = "secondary";
        addPhpBtn.type = "button";
        addPhpBtn.textContent = i18n("php_btn");
        addPhpBtn.addEventListener("click", () => {
            phpWrap.classList.toggle("active");
            if (phpWrap.classList.contains("active")) {
                php.focus();
            } else {
                php.value = "";
            }
        });

        const container = document.createElement("div");
        container.style.display = "grid";
        container.style.gridTemplateColumns = "1fr";
        container.style.gap = "6px";
        const dropRow = document.createElement("div");
        dropRow.className = "drop-row";
        dropRow.appendChild(drop);
        dropRow.appendChild(addPhpBtn);
        container.appendChild(dropRow);
        container.appendChild(phpWrap);

        row.appendChild(name);
        row.appendChild(container);
        row.appendChild(select);
        targetList.appendChild(row);
    });
}

function addNewColumn(){
    const row = document.createElement("div");
    row.className = "target-row";
    const name = document.createElement("input");
    name.className = "new_name";
    name.placeholder = i18n("col_name_placeholder");

    const type = document.createElement("select");
    type.className = "new_type";
    DATATYPES.filter(v => v && v !== "int_to_date").forEach(v => {
        const opt = document.createElement("option");
        opt.value = v;
        opt.textContent = v;
        type.appendChild(opt);
    });

    const del = document.createElement("button");
    del.className = "secondary";
    del.type = "button";
    del.textContent = i18n("delete_btn");
    del.addEventListener("click", () => row.remove());

    row.appendChild(name);
    row.appendChild(type);
    row.appendChild(del);
    newColumns.appendChild(row);
}

function useNewColumnsAsTargets(){
    const list = [];
    newColumns.querySelectorAll(".target-row").forEach(row => {
        const name = row.querySelector(".new_name").value.trim();
        if (name) list.push({ Field: name });
    });
    if (list.length === 0) {
        alert(i18n("add_col_first"));
        return;
    }
    renderTargets(list, { merge: true });
}

function collectMappings(){
    const mappings = [];
    targetList.querySelectorAll(".target-row").forEach(row => {
        const target = row.dataset.target;
        const drop = row.querySelector(".dropzone");
        const source = drop.dataset.source || "";
        const datatype = row.querySelector(".datatype").value;
        const php = row.querySelector(".php").value;
        if (target && source) {
            mappings.push({
                source: source,
                targets: [target],
                datatype: datatype,
                php: php
            });
        }
    });
    return mappings;
}

function collectNewColumns(){
    const list = [];
    newColumns.querySelectorAll(".target-row").forEach(row => {
        const name = row.querySelector(".new_name").value.trim();
        const type = row.querySelector(".new_type").value.trim();
        if (name && type) {
            list.push({ name, type });
        }
    });
    return list;
}

function generate(){
    const name = document.getElementById("mig_name").value.trim();
    if (!name) {
        alert(i18n("name_required"));
        return;
    }
    const payload = {
        name: name,
        db1: db1.value,
        db2: db2.value,
        table1: table1.value,
        table2: table2.value,
        dup_mode: document.getElementById("dup_mode").value,
        create_table: false,
        mappings: collectMappings(),
        new_columns: collectNewColumns(),
        global_php: document.getElementById("global_php").value
    };
    fetch("api/create_migration.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ config: JSON.stringify(payload) })
    }).then(r => r.json()).then(res => {
        if (res && res.ok) {
            alert(i18n("created") + " " + res.name);
        } else {
            alert(i18n("error") + " " + (res && res.error ? res.error : "unknown"));
        }
    });
}

const I18N = {
    en: {
        create_sub: "Map fields between source and target tables with drag & drop.",
        home_btn: "Home",
        mig_name: "Migration Name",
        source_db: "Source DB",
        target_db: "Target DB",
        source_table: "Source Table",
        target_table: "Target Table",
        source_cols: "Source Columns",
        target_cols: "Target Columns",
        target_help: "Drag a source column onto the target. You can choose type and transform.",
        dup_mode: "Duplicate Key",
        new_cols: "New Target Columns (optional)",
        add_col: "+ Add Column",
        apply_targets: "Apply to target list",
        global_php: "Custom PHP (global)",
        generate: "Generate Migration",
        drop_here: "Drop source column",
        php_btn: "+ PHP",
        php_placeholder: "Custom PHP (return)",
        col_name_placeholder: "column_name",
        delete_btn: "Delete",
        add_col_first: "Please add a new column first.",
        name_required: "Migration name is required.",
        created: "Created:",
        error: "Error:"
    },
    tr: {
        create_sub: "Kaynak ve hedef tablolar arasında sürükle-bırak ile alan eşleştir.",
        home_btn: "Ana Sayfa",
        mig_name: "Migration Name",
        source_db: "Kaynak DB",
        target_db: "Hedef DB",
        source_table: "Kaynak Tablo",
        target_table: "Hedef Tablo",
        source_cols: "Kaynak Kolonlar",
        target_cols: "Hedef Kolonlar",
        target_help: "Hedef kolona sürükle-bırak ile kaynak eşleştir. Kolon tipi ve dönüşüm seçebilirsin.",
        dup_mode: "Duplicate Key",
        new_cols: "Yeni Hedef Kolonlar (opsiyonel)",
        add_col: "+ Yeni Kolon",
        apply_targets: "Hedef listesine uygula",
        global_php: "Custom PHP (global)",
        generate: "Generate Migration",
        drop_here: "Kaynak kolon bırak",
        php_btn: "+ PHP",
        php_placeholder: "Custom PHP (return)",
        col_name_placeholder: "column_name",
        delete_btn: "Sil",
        add_col_first: "Önce yeni kolon ekleyin.",
        name_required: "Migration name gerekli.",
        created: "Oluşturuldu:",
        error: "Hata:"
    },
    az: {
        create_sub: "Mənbə və hədəf cədvəllər arasında sürüklə-burax ilə sahə xəritələyin.",
        home_btn: "Ana Səhifə",
        mig_name: "Migration Name",
        source_db: "Mənbə DB",
        target_db: "Hədəf DB",
        source_table: "Mənbə Cədvəl",
        target_table: "Hədəf Cədvəl",
        source_cols: "Mənbə Sütunlar",
        target_cols: "Hədəf Sütunlar",
        target_help: "Hədəf sütununa sürüklə-burax ilə mənbə xəritələ. Tip və çevirmə seçə bilərsən.",
        dup_mode: "Duplicate Key",
        new_cols: "Yeni Hədəf Sütunlar (opsional)",
        add_col: "+ Yeni Sütun",
        apply_targets: "Hədəf siyahısına tətbiq et",
        global_php: "Custom PHP (global)",
        generate: "Generate Migration",
        drop_here: "Mənbə sütunu burax",
        php_btn: "+ PHP",
        php_placeholder: "Custom PHP (return)",
        col_name_placeholder: "column_name",
        delete_btn: "Sil",
        add_col_first: "Əvvəl yeni sütun əlavə edin.",
        name_required: "Migration adı vacibdir.",
        created: "Yaradıldı:",
        error: "Xəta:"
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

function i18n(key) {
    const lang = detectLang();
    return (I18N[lang] && I18N[lang][key]) || I18N.en[key] || key;
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

db1.addEventListener("change", () => loadTables(db1, table1));
db2.addEventListener("change", () => loadTables(db2, table2));
table1.addEventListener("change", () => {
    loadColumns(db1.value, table1.value).then(renderSource);
});
table2.addEventListener("change", () => {
    loadColumns(db2.value, table2.value).then(renderTargets);
});

loadDatabases();
</script>
</body>
</html>
