<?php

require __DIR__ . "/../db.php";

header("Content-Type: application/json");

$db = $_GET["db"] ?? null;
$table = $_GET["table"] ?? null;

if (!$db || !$table) {
    echo json_encode([]);
    exit;
}

try {

    $pdo = connectDB($db);

    $cols = $pdo
        ->query("DESCRIBE `$table`")
        ->fetchAll();

    echo json_encode($cols);

} catch (Exception $e) {

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
?>