<?php
include 'conexao.php';
$r = $_REQUEST[r];

$vd = mysql_num_rows(mysql_query("select * from tb_inscritos where replace(replace(rg,'-',''),'.','') = '$r'"));

echo $vd;

?>