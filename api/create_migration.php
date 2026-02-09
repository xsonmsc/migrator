<?php

$data=json_decode($_POST["config"],true);

header("Content-Type: application/json");

$rawName = $data["name"] ?? "migration";
$base = preg_replace("/[^A-Za-z0-9_-]+/", "_", $rawName);
$base = trim($base, "_-");
if ($base === "") {
    $base = "migration";
}

$dir = __DIR__ . "/../migrations";
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}
$final = $base;
$i = 1;
while (file_exists($dir . "/" . $final . ".php")) {
    $final = $base . "_" . $i;
    $i++;
}

$file = $dir . "/" . $final . ".php";

$dupMode = $data["dup_mode"] ?? "ignore";

$code="<?php
require __DIR__.'/../db.php';
require __DIR__.'/../core/MigrationEngine.php';
require __DIR__.'/../core/ColumnTransformer.php';
require __DIR__.'/../core/TableManager.php';

\$oldDB=connectDB('{$data["db1"]}');
\$newDB=connectDB('{$data["db2"]}');
\$globalPhp = '';
";

$globalPhp = $data["global_php"] ?? "";
if (!empty($globalPhp)) {
    $code .= "\n\$globalPhp = " . var_export($globalPhp, true) . ";\n";
}

if($data["create_table"])
{
    $cols=var_export($data["new_columns"], true);

    $code.="
TableManager::createIfNotExists(
\$newDB,
'{$data["table2"]}',
{$cols}
);
";
}

$map=var_export($data["mappings"], true);

$code.="
\$mappings={$map};

\$rows=\$oldDB->query(\"SELECT * FROM {$data["table1"]}\")->fetchAll();

echo '<div style=\"width:100%;background:#eee\"><div id=\"bar\" style=\"width:0%;background:#000;color:#fff\">0%</div></div>';

\$sql=MigrationEngine::buildInsert('{$data["table2"]}',\$mappings,'{$dupMode}');
\$insert=\$newDB->prepare(\$sql);

\$total=count(\$rows);
\$i=0;

if (\$total === 0) {
    echo \"<script>
    document.getElementById('bar').style.width='100%';
    document.getElementById('bar').textContent='100%';
    </script>\";
}

foreach(\$rows as \$row)
{
    if (!empty(\$globalPhp)) {
        \$skip=false;
        eval(\$globalPhp);
        if (!empty(\$skip)) {
            continue;
        }
    }

    \$data=MigrationEngine::buildRow(\$row,\$mappings);
    \$insert->execute(\$data);

    \$i++;
    \$pct = (\$total > 0) ? (\$i/\$total*100) : 100;
    echo \"<script>
    document.getElementById('bar').style.width='\".\$pct.\"%';
    document.getElementById('bar').textContent='\".round(\$pct).\"%';
    </script>\";
    flush();
}

echo '<script>
document.getElementById("bar").style.width="100%";
document.getElementById("bar").textContent="100%";
setTimeout(function(){ window.location.href="index.php"; }, 1500);
</script>';
echo 'DONE';
";

file_put_contents($file,$code);

echo json_encode([
    "ok" => true,
    "name" => $final
]);
?>
