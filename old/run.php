<?php

$file = $_GET["file"] ?? null;

if(!$file) exit("no file");

require __DIR__."/migrations/".$file.".php";
?>