<?php
include "conexao.php";

$s = $_REQUEST[s];

//procurar pelo evento e ver a quantidade máxima permitida

//procurar por este evento a quantidade de inscrições já realizadas

//se for maior ou igual, retornar zero
//se for menor, retornar 1


$c1 = mysql_query("select distinct congregacao from tb_congregacao where setor = '$s'");
$ret = "<select name='congregacao' required> <option value=''>Selecione</option>";

while($c = mysql_fetch_assoc($c1)){
	$ret .= "<option value='$c[congregacao]'>$c[congregacao]</option>";
}

$ret .= "</select>";
echo "$ret";


