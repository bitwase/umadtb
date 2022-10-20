<?php
include 'seguranca.php';
$id = $_REQUEST[id];//recebe id do produto
$tp = $_REQUEST[tp];

if($tp == ""){
$pa = mysql_fetch_assoc(mysql_query("select * from produtos 
 where id = $id"));
 
if($pa){
echo json_encode($pa);
}
}

if($tp == "1"){//pegar dados para entradas
$pa = mysql_fetch_assoc(mysql_query("select p.descricao, p.qt, p.qtmin, um.um from produtos p
inner join unidademedida um on p.um = um.id 
 where p.id = $id"));
 
if($pa){
echo json_encode($pa);
}
}

if($tp == 2){ //pegar unidades de medida
$pa1 = mysql_query("select * from unidademedida order by um");
$pa = array();
$aux=0;
while($pa2 = mysql_fetch_assoc($pa1)){
	$pa[$aux] = array("unid"=>"$pa2[um]","idum"=>"$pa2[id]");
$aux++;
}
if($pa){
echo json_encode($pa);
}
}
?>
