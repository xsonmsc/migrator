<?php

$file = $_GET["file"] ?? null;

if (!$file) {
    exit("no file");
}

$path = __DIR__ . "/migrations/" . basename($file) . ".php";

if (!file_exists($path)) {
    exit("migration not found");
}

echo "<h3>Running Migration: " . htmlspecialchars($file) . "</h3>";
echo "<div style='margin:8px 0;color:#555'>Lütfen sayfayı kapatmayın. İşlem sürerken veri kaybı olabilir.</div>";

echo "<div style='width:100%;background:#eee;height:25px'>
        <div id='bar'
             style='width:0%;background:#000;color:#fff;height:25px;text-align:center'>
        0%
        </div>
      </div><br>";

require $path;

echo "<div style='margin-top:16px;display:flex;gap:8px;flex-wrap:wrap'>
        <button onclick=\"location.href='index.php'\" style='padding:8px 12px;border:1px solid #ccc;background:#f7f7f7;cursor:pointer'>Geri Dön</button>
      </div>";
?>
