<?php
include '../seguranca.php';
$c = $_REQUEST['c'];//nome da categoria
$s = $_REQUEST['s'];//nome da subcategoria
$cat = $pdo->query("select * from est_grupo where grupo = '$c' and st = '1'")->fetch();
$ct = $cat['id']; 

//$pa1 = mysql_query("select * from tb_subCatFin where cat = '$ct' and st = 1 order by sub");//seleciona todas onde for igual ao informado
//echo "select * from tb_subCatFin where sub = '$s' and cat = '$ct'";
$qt = $pdo->query("select * from est_subgrupo where subgrupo = '$s' and grupo = '$ct' and st = '1'")->rowCount();
$pa = array();
$aux=0;
//while($pa2 = mysql_fetch_assoc($pa1)){
	$pa[$aux] = array("qt"=>"$qt");
$aux++;
//}
if($pa){
echo json_encode($pa);
}
