<?php

$file = $_GET["file"] ?? null;

if (!$file) {
    exit("no file");
}

$path = __DIR__ . "/migrations/" . basename($file) . ".php";

if (!file_exists($path)) {
    exit("migration not found");
}

echo "<h3 id='title'>Running Migration: " . htmlspecialchars($file) . "</h3>";
echo "<div id='warn' style='margin:8px 0;color:#555'>Lütfen sayfayı kapatmayın. İşlem sürerken veri kaybı olabilir.</div>";

echo "<div style='width:100%;background:#eee;height:25px'>
        <div id='bar'
             style='width:0%;background:#000;color:#fff;height:25px;text-align:center'>
        0%
        </div>
      </div><br>";

echo "<script>
function detectLang(){
    var saved = localStorage.getItem('lang');
    if (saved) return saved;
    var sys = (navigator.language || 'en').toLowerCase();
    if (sys.indexOf('tr')===0) return 'tr';
    if (sys.indexOf('az')===0) return 'az';
    return 'en';
}
var I18N = {
  en:{title:'Running Migration:',warn:'Please do not close the page. Data may be lost while running.',back:'Go Back'},
  tr:{title:'Migration Çalışıyor:',warn:'Lütfen sayfayı kapatmayın. İşlem sürerken veri kaybı olabilir.',back:'Geri Dön'},
  az:{title:'Migration İşləyir:',warn:'Zəhmət olmasa səhifəni bağlamayın. Proses zamanı məlumat itə bilər.',back:'Geri Qayıt'}
};
var lang = detectLang();
var dict = I18N[lang] || I18N.en;
var titleEl = document.getElementById('title');
var warnEl = document.getElementById('warn');
if (titleEl) titleEl.textContent = dict.title + ' " . addslashes($file) . "';
if (warnEl) warnEl.textContent = dict.warn;

setInterval(function(){
    var bar = document.getElementById('bar');
    if (!bar) return;
    var w = (bar.style.width || '0%');
    if (bar.textContent.trim() !== w) {
        bar.textContent = w;
    }
}, 400);
</script>";

require $path;

echo "<div style='margin-top:16px;display:flex;gap:8px;flex-wrap:wrap'>
        <button id='back_btn' onclick=\"location.href='index.php'\" style='padding:8px 12px;border:1px solid #ccc;background:#f7f7f7;cursor:pointer'>Geri Dön</button>
      </div>";
echo "<script>
var backBtn = document.getElementById('back_btn');
if (backBtn) backBtn.textContent = dict.back;
</script>";
?>
