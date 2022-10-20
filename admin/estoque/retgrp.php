<?php
include '../seguranca.php';
$c = $_REQUEST['c']; //id da grupoegoria
$a = $_REQUEST['a']; //ação 1-verificar existencia vazio busca sub

//$grupo = $pdo->query("select * from est_grupo where grupoegoria = '$c' and st = '1'")->fetch();
//$ct = $grupo['id'];

if ($a == "") {
	$pa1 = $pdo->query("select * from est_subgrupo where grupo = '$c' and st = 1 order by subgrupo"); //seleciona todas onde for igual ao informado
	$pa = array();
	$aux = 0;
	$lista = "";
	while ($pa2 = $pa1->fetch()) {
		$lista .= "<option value='$pa2[subgrupo]' data-value='$pa2[id]'></option>";
		$aux++;
	}
	$pa = array("lista" => "$lista");
}

if ($a == "1") {//validar grupoegoria
	$pa1 = $pdo->query("select * from est_grupo where id = '$c'")->rowCount();
	if($pa1 > 0){
		$valida = "true";
	}
	else{
		$valida = "false";
	}
	$pa = array("valid" => $valida);
}

if ($pa) {
	echo json_encode($pa);
}
