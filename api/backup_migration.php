<?php

header("Content-Type: application/json");

$name = $_POST["name"] ?? "";
$name = preg_replace("/[^A-Za-z0-9_-]+/", "", $name);
if ($name === "") {
    echo json_encode(["ok" => false, "error" => "invalid name"]);
    exit;
}

$srcDir = __DIR__ . "/../migrations";
$dstDir = __DIR__ . "/../backups";

$src = $srcDir . "/" . $name . ".php";
$dst = $dstDir . "/" . $name . ".php";

if (!file_exists($src)) {
    echo json_encode(["ok" => false, "error" => "file not found"]);
    exit;
}

if (!is_dir($dstDir)) {
    mkdir($dstDir, 0777, true);
}

if (!rename($src, $dst)) {
    echo json_encode(["ok" => false, "error" => "backup failed"]);
    exit;
}

echo json_encode(["ok" => true]);
?>
