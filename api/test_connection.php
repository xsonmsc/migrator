<?php

require __DIR__ . "/../db.php";

header("Content-Type: application/json");

$db = $_GET["db"] ?? null;

try {

    connectDB($db);

    echo json_encode([
        "ok" => true
    ]);

} catch (Exception $e) {

    echo json_encode([
        "ok" => false,
        "error" => $e->getMessage()
    ]);
}
?>