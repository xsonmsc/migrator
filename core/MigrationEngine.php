<?php

class MigrationEngine
{
    public static function buildInsert($table,$mappings)
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

        return "INSERT INTO {$table}(".implode(",",$cols).")
                VALUES(".implode(",",$params).")";
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