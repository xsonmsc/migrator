<?php
require "db.php";
$config = require "config.php";
$dbNames = array_keys($config["databases"]);
?>

<h2>Migration Builder</h2>

Migration Name:
<input id="name"><br><br>

DB1:
<select id="db1">
<?php foreach($dbNames as $d) echo "<option>$d</option>"; ?>
</select>

Source Table:
<input id="table1">
<button onclick="loadSource()">Load Source Columns</button>

<br><br>

DB2:
<select id="db2">
<?php foreach($dbNames as $d) echo "<option>$d</option>"; ?>
</select>

Target Table:
<input id="table2">
<button onclick="loadTarget()">Load Target Columns</button>

<hr>

<div style="display:flex;gap:50px">

<div>
<h3>Source Columns</h3>
<div id="sourceCols"></div>
</div>

<div>
<h3>Mapping</h3>
<div id="mapping"></div>
</div>

</div>

<br>

<button onclick="createMigration()">Create Migration</button>

<script>

let sourceCols=[];
let targetCols=[];

function loadSource(){
fetch(`get_columns.php?db=${db1.value}&table=${table1.value}`)
.then(r=>r.json())
.then(d=>{
sourceCols=d;
renderMapping();
});
}

function loadTarget(){
fetch(`get_columns.php?db=${db2.value}&table=${table2.value}`)
.then(r=>r.json())
.then(d=>{
targetCols=d;
renderMapping();
});
}

function renderMapping(){

let html="";

sourceCols.forEach(col=>{

html+=`
<div style="margin:5px 0">
${col.Field}
<select data-from="${col.Field}">
<option value="">--</option>
${targetCols.map(t=>`<option value="${t.Field}">${t.Field}</option>`).join("")}
</select>
</div>
`;

});

mapping.innerHTML=html;
}

function createMigration(){

let map={};

document.querySelectorAll("#mapping select").forEach(s=>{
if(s.value) map[s.dataset.from]=s.value;
});

fetch("create_migration.php",{
method:"POST",
headers:{"Content-Type":"application/x-www-form-urlencoded"},
body:new URLSearchParams({
name:name.value,
db1:db1.value,
db2:db2.value,
table1:table1.value,
table2:table2.value,
map:JSON.stringify(map)
})
})
.then(r=>r.text())
.then(alert);
}
</script>
