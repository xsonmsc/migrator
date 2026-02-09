<?php
require "db.php";

$db = $_GET["db"] ?? null;
$table = $_GET["table"] ?? null;

if(!$db || !$table) exit;

$pdo = connectDB($db);

$cols = getColumns($pdo,$table);

echo json_encode($cols);
?>