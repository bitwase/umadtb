<?php
include 'seguranca.php';
$id = $_REQUEST[id];//recebe id do financeiro

$pa = mysql_fetch_assoc(mysql_query("select * from financeiro 
 where id = $id"));
 
if($pa){
echo json_encode($pa);
}
?>