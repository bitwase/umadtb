<?php

include 'seguranca.php';
$tp = $_REQUEST[tp];

if($tp == "1"){//seleciona todos do mês
$pa = mysql_fetch_assoc(mysql_query("select nome, date_format(nascimento,'%d/%m') as 'data' from tb_inscritos order by nascimento asc"));
}

if($tp == "2"){//seleciona todos do dia
$pa = mysql_fetch_assoc(mysql_query("select nome, date_format(nascimento,'%d/%m') as 'data' from tb_inscritos order by nome asc"));
}

if($pa){
echo json_encode($pa);
}
?>
