<?php
#################################
##### CADASTRA CLIENTE ##########
#################################
include "conexao.php";

/*
RECEBER ID DA GRID
RECEBER ID DO USUÁRIO
RECEBER STATE
*/

$g = $_POST['g'];//grid
$u = $_POST['u'];//usuario
$s = $_POST['s'];//state

$s = addslashes($s);

//procurar se existe já consta definição...
$e = $pdo->query("select * from tb_gridusuario where usuario = '$u' and grid = '$g'")->rowCount();
//se existir, atualizar
if($e == 1){
	$pdo->query("update tb_gridusuario set padrao = '$s' where usuario = '$u' and grid = '$g'");
}
//se não inclui

if($e == 0){
	$pdo->query("insert into tb_gridusuario (grid,usuario,padrao) values('$g','$u','$s')");
}
?>
