<?php
include "seguranca.php";

$campo = $_POST['campo'];
$valor = $_POST['valor'];
$msgLog = $_POST['msgLog'];

//pegar o valor antigo para gerar o log
$query = "select $campo from config where id = '1'";
$c = new regDados($pdo, $query);
$rl = $c->consulta();

//efetiva a alteração
try{
if ($valor) {
    $query = "update config set $campo = '$valor' where id = '1'";
}
if (!$valor) {
    $query = "update config set $campo = null where id = '1'";
}
//echo $query;
$c = new regDados($pdo, $query);
$c->registra();
}
catch (PDOException $e) {
    return 'ERROR: ' . $e->getMessage();
}
//dados para log

$log = "$msgLog de <b>$rl[$campo]</b> para <b>$valor</b>.";

$lg = new logConfig($pdo, $cod_us, $log);
$r = $lg->registraLog();