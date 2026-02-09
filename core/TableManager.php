<?php

class TableManager
{
    public static function createIfNotExists($pdo,$table,$columns)
    {
        $defs=[];

        foreach($columns as $c)
            $defs[]="`{$c["name"]}` {$c["type"]}";

        $sql="CREATE TABLE IF NOT EXISTS {$table}(
            ".implode(",",$defs)."
        ) ENGINE=InnoDB";

        $pdo->exec($sql);
    }
}
?>