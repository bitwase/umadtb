<?php

include 'seguranca.php';

$u = $_REQUEST['u']; //usuário 
$a = $_REQUEST['a']; //arquivo
$t = $_REQUEST['t']; //tipo P ou D
$acao = $_REQUEST['acao']; //ação que será executada pela página

if ($acao == "") {
    //procurar se o usuário em questão possui o acesso, se sim, remove, caso contrário adiciona

    $va = $pdo->query("select * from tb_acessos where tipo = '$t' and pg = '$a' and us = '$u'")->rowCount();
    if ($va) {
        $pdo->query("delete from tb_acessos where tipo = '$t' and pg = '$a' and us = '$u'");
    }
    if (!$va) {
        $pdo->query("insert into tb_acessos (us, pg, tipo) values('$u', '$a', '$t')");
    }
}


if ($acao == "dash") {
    //procurar se o usuário em questão possui o acesso, se sim, remov. caso contrário insere

    $l = $pdo->query("select pg from tb_acessos where tipo = 'D' and us = '$u'");

    $aux = 0;
    while ($r = $l->fetch()) {
        $retDash[$aux] = array(
            "pg" => $r['pg']
        );
        $aux++;
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($retDash, JSON_PRETTY_PRINT);
}
