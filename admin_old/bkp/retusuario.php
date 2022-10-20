<?php
include 'seguranca.php';
$id = $_REQUEST[id];//id do cliente

$pa = mysql_fetch_assoc(mysql_query("select * from usuarios where id = $id"));
if($pa){
echo json_encode($pa);
}
?>
