<?php

include "../conexao.php";

$a = $_REQUEST['a'];

if ($a == "lista") {

    $cap = $pdo->query("
        select id, nome, nascimento, tel1 as 'telefone', email, dataInscricao from tb_inscritos 
        where id > 0 order by nome asc
    ");

    $ord = 0;
    //contadores...

    $ag = date("Y-m-d H:i");

    $dataSet = array();
    $aux = 0;
    while ($fn = $cap->fetch()) {

        $ord++;
        $id = $fn['id'];

        $lk = "<i class='fa fa-pencil'></i>";

        $dataSet[$aux] = array(
            "ID" => $id,
            "Acao" => $lk,
            "Nome" => $fn['nome'],
            "Nascimento" => $fn['nascimento'],
            "Telefone" => $fn['telefone'],
            "Email" => $fn['email'],
            "DataRegistro" => $fn['dataInscricao'],
        );
        $aux++;
    }
    $json = array("data" => $dataSet);

    header("Content-type: application/json");
    //  $viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}
