<?php

class MigrationEngine
{
    public static function buildInsert($table,$mappings,$mode="ignore")
    {
        $cols=[];
        $params=[];

        foreach($mappings as $m)
        {
            foreach($m["targets"] as $t)
            {
                $cols[]=$t;
                $params[]=":".$t;
            }
        }

        $base = "INSERT INTO {$table}(".implode(",",$cols).")
                VALUES(".implode(",",$params).")";

        if ($mode === "ignore") {
            return "INSERT IGNORE INTO {$table}(".implode(",",$cols).")
                VALUES(".implode(",",$params).")";
        }

        if ($mode === "update") {
            $updates = [];
            foreach ($cols as $c) {
                $updates[] = "{$c}=VALUES({$c})";
            }
            return $base . " ON DUPLICATE KEY UPDATE " . implode(",", $updates);
        }

        return $base;
    }

    public static function buildRow($row,$mappings)
    {
        $data=[];

        foreach($mappings as $m)
        {
            $value = $row[$m["source"]] ?? null;

            if(!empty($m["datatype"]))
                $value = ColumnTransformer::convert($value,$m["datatype"]);

            if(!empty($m["php"]))
            {
                $v=$value;
                $value = eval($m["php"]);
            }

            foreach($m["targets"] as $t)
                $data[$t]=$value;
        }

        return $data;
    }
}
?>
