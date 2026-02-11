<?php

class TableManager
{
    private static function normalizeType($name,$type)
    {
        $type=strtolower(trim($type));

        if($type==="") return null;

        // transformer type fix
        if($type==="int_to_date") return "datetime";

        // short types
        if($type==="varchar") return "varchar(255)";
        if($type==="int") return "int";

        // url alanları auto fix
        if(str_contains($name,"url") && $type==="int")
            return "varchar(255)";

        // basic güvenlik
        if(!preg_match('/^[a-z0-9(), ]+$/',$type))
            return null;

        return $type;
    }

    public static function addColumnsIfMissing($pdo,$table,$columns)
    {
        if(empty($columns)) return;

        $dbName=$pdo->query("SELECT DATABASE()")->fetchColumn();
        if(!$dbName) return;

        $stmt=$pdo->prepare("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA=? AND TABLE_NAME=?
        ");
        $stmt->execute([$dbName,$table]);

        $existing=[];
        foreach($stmt->fetchAll(PDO::FETCH_COLUMN) as $c)
            $existing[strtolower($c)]=true;

        $processed=[];

        foreach($columns as $c)
        {
            $name=$c["name"]??"";
            $type=$c["type"]??"";

            $name=trim($name);

            if($name==="") continue;
            if(isset($processed[strtolower($name)])) continue;
            if(isset($existing[strtolower($name)])) continue;

            $processed[strtolower($name)]=true;

            $type=self::normalizeType($name,$type);
            if(!$type) continue;

            $sql="ALTER TABLE `{$table}` ADD COLUMN `{$name}` {$type}";

            try{
                $pdo->exec($sql);
            }catch(Exception $e){
                echo "COLUMN ERROR: ".$name."<br>";
                continue; // 🔥 migration stop etmesin
            }
        }
    }
}
?>
