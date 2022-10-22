<?php

include "../conexao.php";

$a = $_REQUEST['a']; //ação a executar

if ($a == 1) { //pegar dados de uma inscrição específica
    
    $id = $_REQUEST['i']; //id da inscrição
    
    $sql = "select e.evento, ins.nome, i.pg as 'pagamento'
    from tb_inscricao i
    inner join tb_inscritos ins on i.inscrito = ins.id
    inner join tb_eventos e on i.evento = e.id
    where i.id = $id";

    try {
        $pd = $pdo->query($sql)->fetch();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    $dataSet = array(
        "evento" => $pd['evento'],
        "nome" => $pd['nome'],
        "pagamento" => $pd['pagamento'],
    );

    header("Content-type: application/json");
    //  $viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}

if ($a == "delete") {
    //remover a inscrição
    $id = $_REQUEST['i']; //id da inscrição

    $sql = "delete from tb_inscricao where id = '$id'";
    $pdo->query($sql);
}

if ($a == "update") {
    //remover a inscrição
    $id = $_REQUEST['i']; //id da inscrição

    $campo = $_REQUEST['campo'];
    $valor = $_REQUEST['valor'];
    $sql = "update tb_inscricao set $campo = '$valor' where id = '$id'";
    $pdo->query($sql);
}

if ($a == "lista") {

    $ev = 1; //receber por parâmetro 

    $cap = $pdo->query("
        select c.nome, c.nascimento, c.rg, c.tel1 as 'telefone', c.email, i.pg, i.data, i.id from tb_inscricao i
        inner join tb_inscritos c on i.inscrito = c.id 
        where i.id > 0 and evento = '$ev' order by c.nome asc
    ");

    $ord = 0;
    //contadores...

    $ag = date("Y-m-d H:i");

    $dataSet = array();
    $aux = 0;
    while ($fn = $cap->fetch()) {

        $ord++;
        $id = $fn['id'];

        $lk = "<a href='?pg=inscrito&i=$fn[id]'><i class='fa fa-pencil'></i></a>";

        switch ($fn['pg']) {
            case 1:
                $pagamento = "Pendente";
                break;

            case 2:
                $pagamento = "Pg. Dinheiro";
                break;

            case 3:
                $pagamento = "Pg. Pix";
                break;
        }

        $dataSet[$aux] = array(
            "ID" => $id,
            "Acao" => $lk,
            "Nome" => $fn['nome'],
            "Nascimento" => $fn['nascimento'],
            "RG" => $fn['rg'],
            "Telefone" => $fn['telefone'],
            "Email" => $fn['email'],
            "Pagamento" => $pagamento,
            "DataRegistro" => $fn['data'],
        );
        $aux++;
    }
    $json = array("data" => $dataSet);

    header("Content-type: application/json");
    //  $viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}
