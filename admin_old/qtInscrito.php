<?php
include "conexao.php";

$e = $_REQUEST[e];

//procurar pelo evento e ver a quantidade máxima permitida

//procurar por este evento a quantidade de inscrições já realizadas

//se for maior ou igual, retornar zero
//se for menor, retornar 1

$q = mysql_fetch_assoc(mysql_query("select qtLimite, obs from tb_eventos where id = $e"));
$qt = $q[qtLimite];

$i = mysql_num_rows(mysql_query("select * from tb_inscricao where evento = $e and st = 1"));

if($i >= $qt){
	echo "0";
}
if($i < $qt){
	echo "1 :-: $q[obs]";
}
?>
