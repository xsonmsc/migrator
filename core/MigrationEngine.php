<?php

class MigrationEngine
{
    public static function buildInsert($table, $mappings, $mode="ignore")
    {
        // unique kolon listesi
        $cols = [];
        foreach($mappings as $m){
            foreach($m["targets"] as $t){
                $cols[$t] = $t; // unique keys
            }
        }
        $cols = array_values($cols);

        $params = array_map(fn($c)=>":".$c, $cols);

        $base = "INSERT INTO `{$table}` (".implode(",", $cols).")
                VALUES(".implode(",", $params).")";

        if($mode === "ignore")
            return "INSERT IGNORE INTO `{$table}` (".implode(",", $cols).")
                    VALUES(".implode(",", $params).")";

        if($mode === "update"){
            $updates = [];
            foreach($cols as $c){
                $updates[] = "`{$c}`=VALUES(`{$c}`)";
            }
            return $base . " ON DUPLICATE KEY UPDATE " . implode(",", $updates);
        }

        return $base;
    }

    public static function buildRow($row, $mappings)
    {
        $data = [];
        $used = [];

        foreach($mappings as $m){
            $value = $row[$m["source"]] ?? null;

            if(!empty($m["datatype"]))
                $value = ColumnTransformer::convert($value, $m["datatype"]);

            if(!empty($m["php"])){
                $v = $value;
                $text = $value;
                try{
                    $result = eval($m["php"]);
                    if($result !== null) $value = $result;
                }catch(Throwable $e){
                    echo "PHP TRANSFORM ERROR: ".$m["source"]."<br>";
                    continue;
                }
            }

            foreach($m["targets"] as $t){
                if(isset($used[$t])) continue; // duplicate target skip
                $data[$t] = $value;
                $used[$t] = true;
            }
        }

        return $data;
    }
}
?>
