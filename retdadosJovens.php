<?php
include 'conexao.php';
$rg = $_REQUEST[rg];
//$pdo = new PDO("mysql:host=bwumadepar.mysql.uhserver.com; dbname=bwumadepar; charset=utf8;", "bwumadepar", "b3tw1s2@");
//$pa = $pdo>prepare("select * from tb_inscritos where rg = '$rg'");
//$pa->execute();
$tp = $_REQUEST[tp];
$id = $_REQUEST[id];
if($tp == ""){
$pa = mysql_fetch_assoc(mysql_query("select *, date_format(nascimento,'%d/%m/%Y') as 'nascimento' from tb_inscritos where rg = '$rg'"));
}

if($tp == "2"){
$pa = mysql_fetch_assoc(mysql_query("select *, date_format(nascimento,'%d/%m/%Y') as 'nascimento' from tb_inscritos where id = '$id'"));
}

if($pa){
echo json_encode($pa);
}
?>
