<?php

require __DIR__ . "/../db.php";

header("Content-Type: application/json");

$db = $_GET["db"] ?? null;

if (!$db) {
    echo json_encode([]);
    exit;
}

try {

    $pdo = connectDB($db);

    $tables = $pdo
        ->query("SHOW TABLES")
        ->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($tables);

} catch (Exception $e) {

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
?>