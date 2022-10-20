<?php
include "../seguranca.php";
$a = $_REQUEST['a'];

if ($a == 1) { //insere
    /*    $desc = $_POST['desc'];
    $grupo = $_POST['grupo'];
    $subgrupo = $_POST['subgrupo'];
    $unidade = $_POST['unidade'];

    $r = new regProdutoEstoque($pdo, $desc, $grupo, $subgrupo, $unidade, $cod_us);
    $re = $r->registraProduto();

    echo "$re";*/
}

if ($a == 2) { //dados de produto informado
    $p = $_REQUEST['p'];

    $pdt = $pdo->query("
        select p.id, p.descricao, p.st, 
        u.unidade, 
        g.grupo,
        s.subgrupo
        from est_produtos p
        inner join est_unidades u on p.um = u.id
        inner join est_grupo g on p.grupo = g.id
        inner join est_subgrupo s on p.subgrupo = s.id
        where p.id = '$p'
    ")->fetch();

    $dataSet = array();
    $aux = 0;
        $id = $pdt['id'];

        //consulta saldos
        //disponível
        $d = $pdo->query("select quantidade from est_estoque where item = '$p' and status = 'DISPONIVEL'")->fetch();
        //encomendado
        $e = $pdo->query("select quantidade from est_estoque where item = '$p' and status = 'ENCOMENDADO'")->fetch();
        //reservado
        $r = $pdo->query("select quantidade from est_estoque where item = '$p' and status = 'RESERVADO'")->fetch();

        $dataSet[$aux] = array(
            "ID" => $id,
            "Descricao" => $pdt['descricao'],
            "Grupo" => $pdt['grupo'],
            "Subgrupo" => $pdt['subgrupo'],
            "Un" => $pdt['unidade'],
            "Situacao" => $status,
            "DataRegistro" => "",
            "Disponivel" => $d['quantidade'],
            "Encomendado" => $e['quantidade'],
            "Reservado" => $r['quantidade']
        );
        
    $json = array("data" => $dataSet);

    header("Content-type: application/json");
    //  $viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}

if ($a == "lista") {

    $pdt = $pdo->query("
        select p.id, p.descricao, p.st, 
        u.unidade, 
        g.grupo,
        s.subgrupo
        from est_produtos p
        inner join est_unidades u on p.um = u.id
        inner join est_grupo g on p.grupo = g.id
        inner join est_subgrupo s on p.subgrupo = s.id
    ");

    $dataSet = array();
    $aux = 0;
    while ($fn = $pdt->fetch()) {

        $id = "<a href='?pg=produto&p=$fn[id]'>$fn[id]</a>";

        $dataSet[$aux] = array(
            "ID" => $id,
            "Descricao" => $fn['descricao'],
            "Grupo" => $fn['grupo'],
            "Subgrupo" => $fn['subgrupo'],
            "Un" => $fn['unidade'],
            "Situacao" => $status,
            "DataRegistro" => ''
        );
        $aux++;
    }
    $json = array("data" => $dataSet);

    header("Content-type: application/json");
    //  $viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}
