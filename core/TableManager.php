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

    public static function addColumnsIfMissing($pdo,$table,$columns)
    {
        if (empty($columns)) {
            return;
        }

        $dbName = $pdo->query("SELECT DATABASE()")->fetchColumn();
        if (empty($dbName)) {
            return;
        }

        $stmt = $pdo->prepare(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?"
        );
        $stmt->execute([$dbName, $table]);
        $existing = [];
        foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $col) {
            $existing[strtolower($col)] = true;
        }

        foreach ($columns as $c) {
            $name = $c["name"] ?? "";
            $type = $c["type"] ?? "";
            if ($name === "" || $type === "") {
                continue;
            }
            if (isset($existing[strtolower($name)])) {
                continue;
            }
            $pdo->exec("ALTER TABLE `{$table}` ADD COLUMN `{$name}` {$type}");
        }
    }
}
?>
