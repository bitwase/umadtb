<?php

include 'seguranca.php';
$tp = $_REQUEST[tp];

if($tp == "1"){//seleciona todos do mês
$pa1 = mysql_query("select nome, date_format(nascimento,'%d/%m') as 'data' from tb_inscritos where cidade = 'Telêmaco Borba' and date_format(nascimento,'%m') = date_format(now(),'%m') order by data asc");
$pa = array();
$aux=0;
while($pa2 = mysql_fetch_assoc($pa1)){
	$pa[$aux] = array("nome"=>"$pa2[nome]","data"=>"$pa2[data]");
$aux++;
}
}

if($tp == "2"){//seleciona todos do dia
$pa1 = mysql_query("select nome, date_format(nascimento,'%d/%m') as 'data' from tb_inscritos where  cidade = 'Telêmaco Borba' and date_format(nascimento,'%d/%m') = date_format(now(),'%d/%m') order by nome asc");
$pa = array();
$aux=0;
while($pa2 = mysql_fetch_assoc($pa1)){
	$pa[$aux] = array("nome"=>"$pa2[nome]","data"=>"$pa2[data]");
$aux++;
}
}

if($pa){
echo json_encode($pa);
}
?>
