<?php

include "seguranca.php";

$a = $_REQUEST['a'];
$p = $_REQUEST['p'];

if($a == "1"){//inserir / alterar
    $i = $_REQUEST['i'];
    $v = $_REQUEST['v'];

    //remover se existir
    $pdo->query("delete from pg_accordion where pg = '$p' and us = '$cod_us' and ref = '$i'");
    $pdo->query("insert into pg_accordion (pg, ref, class, us) values(
        '$p',
        '$i',
        '$v',
        '$cod_us'
    )");
}

if ($a == "2") { //consultar
    $lp = $pdo->query("select * from pg_accordion where pg = '$p' and us = '$cod_us'");

    $dataSet = array();

    $aux = 0;
    while ($r = $lp->fetch()) {
        $dataSet[$aux] = array(
            "campo" => "$r[ref]",
            "vis" => "$r[class]"
        );
        $aux++;
    }
    header("Content-type: application/json");
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}
