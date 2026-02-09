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

echo "<div style='width:100%;background:#eee;height:25px'>
        <div id='bar'
             style='width:0%;background:#000;color:#fff;height:25px;text-align:center'>
        0%
        </div>
      </div><br>";

require $path;
?>