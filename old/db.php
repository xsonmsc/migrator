<?php

$config = require __DIR__."/config.php";

function connectDB($key)
{
    global $config;
    $db = $config["databases"][$key];

    return new PDO(
        "mysql:host={$db["host"]};dbname={$db["dbname"]};charset={$db["charset"]}",
        $db["user"],
        $db["pass"],
        [
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
        ]
    );
}

function getTables($pdo)
{
    return $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
}

function getColumns($pdo,$table)
{
    return $pdo->query("DESCRIBE `$table`")->fetchAll();
}
?>