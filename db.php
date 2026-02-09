<?php

$config = require __DIR__ . "/config.php";

function connectDB($key)
{
    global $config;

    if (!isset($config["databases"][$key])) {
        throw new Exception("DB not found: " . $key);
    }

    $db = $config["databases"][$key];

    return new PDO(
        "mysql:host={$db["host"]};dbname={$db["dbname"]};charset={$db["charset"]}",
        $db["user"],
        $db["pass"],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
}
?>