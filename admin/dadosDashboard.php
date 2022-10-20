<?php
include "seguranca.php";

//quantidade total de projetos
$sqlQtInscritos = "select * from tb_inscricao";
$qtTotalInscritos = $pdo->query($sqlQtInscritos)->rowCount();

$retDash = array(
    "totalInscritos" => $qtTotalInscritos,
);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($retDash, JSON_PRETTY_PRINT);
