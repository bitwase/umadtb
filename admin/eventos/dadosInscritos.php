<?php

include "../conexao.php";

$a = $_REQUEST['a'];

if ($a == "lista") {

    $ev = 1;//receber por parâmetro 

    $cap = $pdo->query("
        select c.id, c.nome, c.nascimento, c.rg, c.tel1 as 'telefone', c.email, i.data from tb_inscritos c
        inner join tb_inscricao i on i.inscrito = c.id 
        where c.id > 0 and evento = '$ev' order by c.nome asc
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
            "RG" => $fn['rg'],
            "Telefone" => $fn['telefone'],
            "Email" => $fn['email'],
            "DataRegistro" => $fn['data'],
        );
        $aux++;
    }
    $json = array("data" => $dataSet);

    header("Content-type: application/json");
    //  $viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}
