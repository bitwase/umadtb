<?php
include 'seguranca.php';
$id = $_REQUEST[id];//id do cliente

$pa = mysql_fetch_assoc(mysql_query("select *, date_format(dt_nasc,'%d/%m/%Y') as 'nasc' from clientes where id = $id"));
if($pa){
echo json_encode($pa);
}
?>
