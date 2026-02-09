<?php

class ColumnTransformer
{
    public static function convert($v,$type)
    {
        switch($type)
        {
            case "int_to_date":
                return date("Y-m-d H:i:s",(int)$v);

            case "json":
                return json_encode($v);

            case "string":
                return (string)$v;

            case "int":
                return (int)$v;

            default:
                return $v;
        }
    }
}
?>