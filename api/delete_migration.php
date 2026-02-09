<?php

header("Content-Type: application/json");

$name = $_POST["name"] ?? "";
$name = preg_replace("/[^A-Za-z0-9_-]+/", "", $name);
if ($name === "") {
    echo json_encode(["ok" => false, "error" => "invalid name"]);
    exit;
}

$src = __DIR__ . "/../migrations/" . $name . ".php";
if (!file_exists($src)) {
    echo json_encode(["ok" => false, "error" => "file not found"]);
    exit;
}

if (!unlink($src)) {
    echo json_encode(["ok" => false, "error" => "delete failed"]);
    exit;
}

echo json_encode(["ok" => true]);
?>
