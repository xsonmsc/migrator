<?php

if($_POST)
{
    $name = $_POST["name"];
    $db1 = $_POST["db1"];
    $db2 = $_POST["db2"];
    $table1 = $_POST["table1"];
    $table2 = $_POST["table2"];
    $mapping = $_POST["map"];

    $file = __DIR__."/migrations/{$name}.php";

    $code = "<?php

require __DIR__.'/../db.php';
require __DIR__.'/../functions.php';

\$oldDB = connectDB('{$db1}');
\$newDB = connectDB('{$db2}');

\$rows = \$oldDB->query(\"SELECT * FROM {$table1}\")->fetchAll();

\$total = count(\$rows);
\$i=0;

echo '<div style=\"width:100%;background:#eee\"><div id=\"bar\" style=\"width:0%;background:#000;color:#fff\">0%</div></div>';

\$insert = \$newDB->prepare(\"INSERT INTO {$table2} (".implode(",",array_values($mapping)).") VALUES (:".
implode(",:",array_values($mapping)).")\");

foreach(\$rows as \$row)
{
    \$data = [";

    foreach($mapping as $from=>$to)
    {
        $code .= "'{$to}'=>\$row['{$from}'],";
    }

    $code .= "];

    \$insert->execute(\$data);

    \$i++;
    progress(\$i,\$total);
}

echo 'DONE';
";

    file_put_contents($file,$code);

    echo "Migration file created: ".$file;
}
?>