<?php
error_reporting(~E_ALL);
$ip1 = $_SERVER [REMOTE_HOST]; //ip de internet
$ip2 = $_SERVER [REMOTE_ADDR]; // ip do usuário
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
{
$ip1 = $_SERVER['REMOTE_ADDR'];
$ip2=$_SERVER['HTTP_X_FORWARDED_FOR'];
}
else
{
$ip1=$_SERVER['REMOTE_ADDR'];
$ip2 = "";
}

$usuario=$_POST['usuario'];
$senha=hash('whirlpool',$_POST['senha']);
include('conexao.php');	
$confirma= mysql_query("SELECT * FROM usuarios WHERE usuario='$usuario' AND senha='$senha'") or die(mysql_error());
$cont=mysql_num_rows($confirma);
$us_log = mysql_fetch_assoc(mysql_query("SELECT * FROM usuarios WHERE usuario='$usuario' AND senha='$senha'"));
//$cont = 0; 
if ($cont > 0){
	setcookie ("usuario",$usuario,time()+3600);
	setcookie ("senha",$senha,time()+3600);
    mysql_query("INSERT INTO log_acesso (data,ip,ip2,usuario) VALUES (now(),'$ip1','$ip2','$us_log[id]')") or die(mysql_error());

//////////////////////////////////////////////////
	header ("Location: index.php");	

}
else{
	echo"Algo errado.";
	setcookie ("usuario","");
	setcookie ("senha","");
	header ("Location: login.php?e=1");
}
?>
