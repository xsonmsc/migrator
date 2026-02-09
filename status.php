<?php

header("Content-Type: application/json");

if(!file_exists("progress.json"))
{
    echo json_encode(["status"=>"idle"]);
    exit;
}

echo file_get_contents("progress.json");
?>