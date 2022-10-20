<?php
error_reporting(~E_ALL);
$usuario=$_COOKIE['usuario'];
$senha=$_COOKIE['senha'];
//$senha=hash('whirlpool',$senha_);
include('conexao.php');

$confirma=mysql_query("SELECT * FROM usuarios WHERE usuario='$usuario' AND senha = '$senha'") or die (mysql_error());
$cont=mysql_num_rows($confirma);
if($cont>0){
	setcookie ("usuario",$usuario,time()+3600);
	setcookie ("senha",$senha,time()+3600);
$sql_usuario="SELECT * FROM usuarios WHERE usuario='$usuario'";
$res_usuario=mysql_query($sql_usuario);	
	
	while ($row = mysql_fetch_assoc($res_usuario)) {
    $nome_=$row['nome'];
	$cod_us = $row['id'];
    if($row['situacao'] == 0){
		$cont = 0;
		setcookie ("usuario","");
		setcookie ("senha","");
		header ("Location: login.php?e=2");
	}
}
}
?>
