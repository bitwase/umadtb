<?php
include 'seguranca.php';
$id = $_REQUEST[id];//id da especialidade ou atendente dependendo da situação
$tp = $_REQUEST[tp];//tipo 1 edita, tipo 2 remove atendentes
$at = $_REQUEST[at];//id do atendente

if($tp == 1){
$pa1 = mysql_query("select e.especialidade, e.valor, e.situacao, a.nome, a.id as 'idat' from especialidades e
left join atende at on e.id = at.especialidade
left join atendentes a on at.atendente = a.id
 where e.id = $id order by a.nome");
$pa = array();
$aux=0;
while($pa2 = mysql_fetch_assoc($pa1)){
	$pa[$aux] = array("especialidade"=>"$pa2[especialidade]","valor"=>"$pa2[valor]","situacao"=>"$pa2[situacao]","nome"=>"$pa2[nome]","idat"=>"$pa2[idat]");
$aux++;
}
if($pa){
echo json_encode($pa);
}
}

if($tp == 2){
$pa1 = mysql_query("select a.nome, a.id from atendentes a
 where a.id not in (
select atendente from atende where especialidade = $id
) order by nome");
$pa = array();
$aux=0;
while($pa2 = mysql_fetch_assoc($pa1)){
	$pa[$aux] = array("nome2"=>"$pa2[nome]","idat2"=>"$pa2[id]");
$aux++;
}
if($pa){
echo json_encode($pa);
}
}

if($tp == 3){
mysql_query(" delete from atende where atendente = '$at' AND especialidade = '$id'
");
}

if($tp == 4){
mysql_query(" insert into atende (atendente,especialidade) values('$at','$id')
");
}

if($tp == 5){
$pa1 = mysql_query("select a.nome, a.id from atendentes a
 where a.situacao = 1 and a.id in (
select atendente from atende where especialidade = $id
) order by nome");
$pa = array();
$aux=0;
while($pa2 = mysql_fetch_assoc($pa1)){
	$pa[$aux] = array("nome"=>"$pa2[nome]","idat"=>"$pa2[id]");
$aux++;
}
if($pa){
echo json_encode($pa);
}
}

if($tp == 6){//inserir regra para retornar somente as especialidadea ao auqk atente ou atenderpa 
$pa1 = mysql_query("select e.especialidade, e.id from especialidades e
 where e.id in (
select especialidade from atende where atendente = $id
) order by especialidade");
$pa = array();
$aux=0;
while($pa2 = mysql_fetch_assoc($pa1)){
	$pa[$aux] = array("especialidade"=>"$pa2[especialidade]","id"=>"$pa2[id]");
$aux++;
}
if($pa){
echo json_encode($pa);
}
}

if($tp == 7){//inserir regra para retornar somente as especialidadea ao auqk atente ou atenderpa 
$pa1 = mysql_query("select e.especialidade, e.id from especialidades e
 where e.id not in (
select especialidade from atende where atendente = $id
) order by especialidade");
$pa = array();
$aux=0;
while($pa2 = mysql_fetch_assoc($pa1)){
	$pa[$aux] = array("especialidade"=>"$pa2[especialidade]","id"=>"$pa2[id]");
$aux++;
}
if($pa){
echo json_encode($pa);
}
}

if($tp == 8){
mysql_query(" delete from atende where atendente = '$at' AND especialidade = '$id'
");
}

if($tp == 9){
mysql_query(" insert into atende (atendente,especialidade) values('$at','$id')
");
}
?>