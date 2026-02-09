<?php

$config = require __DIR__ . "/../config.php";

header("Content-Type: application/json");

$dbs = array_keys($config["databases"] ?? []);

echo json_encode($dbs);
?>
