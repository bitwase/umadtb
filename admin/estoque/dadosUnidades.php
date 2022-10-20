<?php

include "../seguranca.php";

$a = $_REQUEST['a'];
$x = $_REQUEST['x'];

if($x != ""){
    $ft = " and (c.de = '$x' or c.para = '$x')";
}

if ($a == "universal") {//listar todas as universais

    $cap = $pdo->query("
        select c.de, c.para, c.fator, c.ref, c.registro,
        u.nome 
        from est_conversoes c
        inner join tb_usuario u on c.us = u.id
        where item is null $ft
    ");

    $ord = 0;
    //contadores...

    $ag = date("Y-m-d H:i");

    $dataSet = array();
    $aux = 0;
    while ($fn = $cap->fetch()) {

        $ref = $fn['fator']." ".$fn['de']." = 1 ".$fn['para']." <br> 1 ".$fn['para']." = ". number_format((1/(float)$fn['fator']),5,",",".")." ".$fn['de'];

        $dataSet[$aux] = array(
            "ID" => $id,
            "Acao" => "",
            "De" => $fn['de'],
            "Para" => $fn['para'],
            "Fator" => $fn['fator'],
            "Referencia" => $ref,
            "Usuario" => $fn['nome'],
            "DataRegistro" => $fn['registro']
        );
        $aux++;
    }
    $json = array("data" => $dataSet);

    header("Content-type: application/json");
    //  $viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}

if ($a == "especifica") {//listar todas as universais

    $cap = $pdo->query("
        select c.de, c.para, c.fator, c.ref, c.registro, c.item as 'cod',
        u.nome,
        p.descricao
        from est_conversoes c
        inner join tb_usuario u on c.us = u.id
        inner join est_produtos p on c.item = p.id
        where item is not null $ft
    ");

    $ord = 0;
    //contadores...

    $ag = date("Y-m-d H:i");

    $dataSet = array();
    $aux = 0;
    while ($fn = $cap->fetch()) {

        $ref = $fn['fator']." ".$fn['de']." = 1 ".$fn['para']." <br> 1 ".$fn['para']." = ". number_format((1/(float)$fn['fator']),5,",",".")." ".$fn['de'];

        $dataSet[$aux] = array(
            "ID" => $id,
            "Cod" => $fn['cod'],
            "Descricao" => $fn['descricao'],
            "De" => $fn['de'],
            "Para" => $fn['para'],
            "Fator" => $fn['fator'],
            "Referencia" => $ref,
            "Usuario" => $fn['nome'],
            "DataRegistro" => $fn['registro']
        );
        $aux++;
    }
    $json = array("data" => $dataSet);

    header("Content-type: application/json");
    //  $viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}