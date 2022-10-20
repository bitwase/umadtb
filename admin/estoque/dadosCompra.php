<?php
/*
RESPONSÁVEL POR ENVIAR OS DADOS QUANDO FOR CONSULTA, E POR ATUALIZAR QUANDO FOR UPDATE
*/
include "../seguranca.php";
$a = $_REQUEST['a'];
$c = $_REQUEST['c']; //compra

if ($a == 1) { //inclui item compra

    //chamar função pra enviar
    $item = $_POST['item'];
    $qt = $_POST['qt'];
    $un = $_POST['un'];
    $vl = $_POST['vl'];
    //remover todos os pontos
    $vl = str_replace(".", "", $vl);
    //substituir vírgula por ponto
    $vl = str_replace(",", ".", $vl);

    $reg = new addItemCompra($pdo, $c, $item, $un, $qt, $vl, $cod_us);
    echo $reg->adicionaItem();
}

if ($a == 2) { //atualizar dados Compra

    $refCompra = $_POST['refCompra'];
    $fornecedor = $_POST['fornecedor'];
    $refFornecedor = $_POST['refFornecedor'];
    $prevEntrega = $_POST['prevEntrega'];

    //comparar as alterações
    $sql = "select 
        fornecedor,
        refFornecedor,
        refCompra,
        previsaoEntrega
        from est_compras
        where id = $c";

    $di = $pdo->query($sql);
    $dd = $di->fetch();

    $pa = array(
        "fornecedor" => $dd['fornecedor'],
        "refFornecedor" => $dd['refFornecedor'],
        "refCompra" => $dd['refCompra'],
        "previsaoEntrega" => $dd['previsaoEntrega']
    );

    $nd = array(
        "fornecedor" => $fornecedor,
        "refFornecedor" => $refFornecedor,
        "refCompra" => $refCompra,
        "previsaoEntrega" => $prevEntrega
    );

    $log = "";
    $dif = array_diff($nd, $pa);
    foreach ($dif as $k => $v) {
        // $sql = "update $tabela set $k = '$v' where $clausula = '$c'";
        $a = new atualizaCompra($pdo, $c, $nd[$k], $k);
        $a->atualiza();
        switch ($k) {
            case "fornecedor":
                $kAlt = "Fornecedor";
                break;
            case "refFornecedor":
                $kAlt = "Referência Fornecedor";
                break;
            case "refCompra":
                $kAlt = "Referência Compra";
                break;
            case "previsaoEntrega":
                $kAlt = "Previsão Entrega";
                break;
            default:
                $kAlt = $k;
                break;
        }
        $log .= "Alterado <b>$kAlt</b>  de <b>$pa[$k]</b> para <b>$nd[$k]</b><br>";
    }
    //chamar função para gravar Log
    //$h = new hisFinanceiro($pdo, $cod_us, $id, $log);
    //$h->registraHistorico();
}

if ($a == 3) { //atualizar dados linha compra

    $id = $_POST['id'];
    $codItens = $_POST['codItens'];
    $unItens = $_POST['unItens'];
    $qtSolItens = $_POST['qtSolItens'];
    $qtCanItens = $_POST['qtCanItens'];
    $dtPrevItens = $_POST['dtPrevItens'];
    $vlUnItens = $_POST['vlUnItens'];
    //regra para remover
    $remove = $_POST['remove'];

    //comparar as alterações
    //echo "RM".$remove;
    if($remove == "true"){
        $item = $pdo->query("select produto from est_compra_produto where id = '$id'")->fetch();
        $item = $item['produto'];
        $pdo->query("delete from est_compra_produto where id = '$id'");
        $at = new atualizaEncomendado($pdo, $item);
        $at->atualiza();
    }
    if ($remove == "false") {
        $sql = "select 
        produto,
        unidade,
        quantidade,
        quantidadeCancelado,
        prevEntrega,
        valor
        from est_compra_produto
        where id = $id";

        $di = $pdo->query($sql);
        $dd = $di->fetch();

        $pa = array(
            "produto" => $dd['produto'],
            "unidade" => $dd['unidade'],
            "quantidade" => $dd['quantidade'],
            "quantidadeCancelado" => $dd['quantidadeCancelado'],
            "prevEntrega" => $dd['prevEntrega'],
            "valor" => number_format($dd['valor'], 2, ",", "."),
        );

        $nd = array(
            "produto" => $codItens,
            "unidade" => $unItens,
            "quantidade" => $qtSolItens,
            "quantidadeCancelado" => $qtCanItens,
            "prevEntrega" => $dtPrevItens,
            "valor" => $vlUnItens //valor no formato nacional
        );


        $log = "";
        $dif = array_diff($nd, $pa);
        #echo "<pre>";
        #print_r($pa);
        #print_r($nd);
        #print_r($dif);

        foreach ($dif as $k => $v) {
            // $sql = "update $tabela set $k = '$v' where $clausula = '$c'";
            if ($k == "valor") {
                $aux = $nd[$k];
                $aux = str_replace(".", "", $aux);
                $nd[$k] = str_replace(",", ".", $aux);
            }
            $a = new atualizaLinhaCompra($pdo, $id, $nd[$k], $k);
            $a->atualiza();

            $log .= "Alterado <b>$kAlt</b>  de <b>$pa[$k]</b> para <b>$nd[$k]</b><br>";
        }
        echo $log;
    }//fim se não remove
    //chamar função para gravar Log
    //$h = new hisFinanceiro($pdo, $cod_us, $id, $log);
    //$h->registraHistorico();
}

if ($a == 5) { //registrar pagamento
    $id = $_POST['id'];
    $contaPg = $_POST['contaPg'];
    $dataPg = $_POST['dataPg'];
    $horaPg = $_POST['horaPg'];

    $r = new regPagamento($pdo, $id, $contaPg, $dataPg, $horaPg, $cod_us);
    $ret = $r->registra();
    echo $ret;
}

if ($a == 6) { //retornar dados de ID específico

    $id = $_REQUEST['id'];

    $filtro = "";

    if (!empty($id)) {
        $filtro .= " and f.id = '$id' ";
    }


    $cap = $pdo->query("
        select f.id, f.id, f.sit, date_format(f.data,'%d/%m/%Y %H:%i') as 'data', date_format(f.dt_ag,'%d/%m/%Y') as 'vct', f.motivo, f.valor, f.obs, date_format(f.dt_ag,'%d/%m/%Y') as 'ag', u.nome, c.centro, cf.categoria, sc.sub  from fin_financeiro f
        left join tb_usuario u on f.us = u.id 
        left join tb_centrocusto c on c.id = f.centro
        left join fin_catfin cf on cf.id = f.categoria
        left join fin_subcatfin sc on sc.id = f.subcategoria
        where f.id > 0 $filtro order by dt_ag asc
    ");

    $ord = 0;
    //contadores...

    $ag = date("Y-m-d H:i");

    $dataSet = array();
    $aux = 0;
    while ($fn = $cap->fetch()) {

        $ord++;
        $id = $fn['id'];

        $centroCusto = "$fn[centro]";
        $categoria = "$fn[categoria]";
        $subCategoria = "$fn[sub]";

        $vl = number_format($fn['valor'], 2, ",", ".");
        $valor = "R$$vl";

        switch ($fn['sit']) {
            case 1:
                $status = "Agendado";
                break;
            case 2:
                $status = "Realizado";
                break;
            case 3:
                $status = "Cancelado";
                break;
        }

        $dataSet[$aux] = array(
            "Venc" => $fn['vct'],
            "Valor" => $valor,
            "Descricao" => $fn['motivo'],
            "Status" => $status,
            "Categoria" => $categoria,
            "Subcategoria" => $subCategoria,
            "CentroCusto" => $centroCusto,
        );
        $aux++;
    }
    $json = array("data" => $dataSet);

    header("Content-type: application/json");
    //  $viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}

if ($a == 7) { //retornar anexos
    //Titulo Anexo

    $i = $_REQUEST['i'];

    $sql2 = "select a.id, date_format(a.data, '%d/%m/%Y %H:%i') as 'data', a.titulo, a.local, u.nome
    from tb_anexos a 
    inner join tb_usuario u on a.us = u.id
    where tipo = 'FINANCEIRO' and ref = '$i' 
    order by a.data";
    $rd = $pdo->query($sql2);
    $dataSet = array();
    $aux = 0;
    $total = 0;
    while ($r = $rd->fetch()) {

        //		$lk = "<i class='fa fa-pencil' onclick='editaCentro($r[id], $r[qt], $r[cat_id], $r[centro_id])' title='Editar Dados'></i>";
        $anexo = "<a href='$r[local]' target='_blank' style='font-size:20px;'><i class='fa fa-file-pdf-o'></i></a> <a href='$r[local]' download target='_blank' style='font-size:20px;'><i class='fa fa-file-arrow-down'></i></a>";

        $lk = "<a href='#' onclick='abreRemoveAnexo($r[id],\"$r[titulo]\")' style='font-size:20px;'><i class='fa fa-trash'></i></a>";

        $dataSet[$aux] = array(
            "ID" => "$r[id]",
            "Remover" => "$lk",
            "Titulo" => "$r[titulo]",
            "Anexo" => "$anexo",
            "Data" => "$r[data]",
            "Usuario" => "$r[nome]",
        );
        $total += $r['total'];
        $aux++;
    }

    $dataSet = array("data" => $dataSet);
    //echo "<pre>";
    //print_r($dataSet);
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}

if ($a == 8) { //insere anexos
    $titulo = $_POST['an_titulo'];
    $id = $_POST['i'];

    if (isset($_FILES['an_anexo']['name'])) {
        /* Getting file name */
        $filename = date("YmdHis") . $_FILES['an_anexo']['name'];

        /* Location */
        $location = "../documentos/financeiro/" . $filename;
        $locationDB = "documentos/financeiro/" . $filename;
        $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);

        /* Valid extensions */
        $valid_extensions = array("pdf", "jpg", "jpeg", "png");

        $response = 0;
        /* Check file extension */
        //if (in_array(strtolower($imageFileType), $valid_extensions)) {
        //ignorando validação, aceitar qualquer formato
        /* Upload file */
        if (move_uploaded_file($_FILES['an_anexo']['tmp_name'], $location)) {
            $response = $location;
        }
        //}
        $queryAnexo = "insert into tb_anexos (titulo, local, tipo, ref, us, data) values('$titulo', '$locationDB','FINANCEIRO', '$id', '$cod_us', now())";
        $pdo->query($queryAnexo);

        //gravar no histórico da projeto
        $log = "Inserido anexo <b>$titulo</b>";
        $gl = new hisFinanceiro($pdo, $cod_us, $id, $log);
        echo $gl->registraHistorico();
        //        echo $response;
        //exit;
    }
}

if ($a == 9) { //remove anexo
    $id = $_POST['id']; //id do anexo
    $i = $_POST['i']; //id do anexo
    //procurar qual o caminho pelo id
    $sqlCaminho = "select * from tb_anexos where id = '$id'";
    $c = $pdo->query($sqlCaminho)->fetch();
    //fazer unlink
    unlink("../" . $c['local']);
    //remover da base de dados
    $sqlDeleta = "delete from tb_anexos where id = '$id'";
    $pdo->query($sqlDeleta);
    //gravar log
    $log = "Removido anexo <b>$c[titulo]</b>";
    $gl = new hisFinanceiro($pdo, $cod_us, $i, $log);
    echo $gl->registraHistorico();
}

if ($a == "itens") {

    $filtro = "";

    if (!empty($c)) {
        //filtrar compra
    }
    //query com os itens

    $ord = 0;
    //contadores...

    $dataSet = array();
    $aux = 0;
    $sql = "select cp.id, cp.linha, cp.produto, cp.unidade, cp.valor, cp.quantidade, (cp.valor*cp.quantidade) as 'total',
    p.descricao
    from est_compra_produto cp
    inner join est_produtos p on cp.produto = p.id
    where cp.compra = $c order by cp.linha";

    $cap = $pdo->query($sql);
    while ($fn = $cap->fetch()) {

        $dataSet[$aux] = array(
            "ID" => $fn['id'],
            "Linha" => $fn['linha'],
            "Cod" => $fn['produto'],
            "Descricao" => $fn['descricao'],
            "Qt" => $fn['quantidade'],
            "UnMed" => $fn['unidade'],
            "VlUn" => "R$" . number_format($fn['valor'], 2, ",", "."),
            "VlTot" => "R$" . number_format($fn['total'], 2, ",", ".")
        );
        $aux++;
    }

    $json = array("data" => $dataSet);

    header("Content-type: application/json");
    //$viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}

if ($a == "especifico") {

    $id = $_REQUEST['i'];
    $ord = 0;
    //contadores...

    $dataSet = array();
    $aux = 0;
    $sql = "select cp.id, cp.linha, cp.produto, cp.unidade, cp.valor, cp.quantidade, coalesce(cp.quantidadeRecebido,0) as 'quantidadeRecebido', coalesce(cp.quantidadeCancelado,0) as 'quantidadeCancelado', (cp.valor*cp.quantidade) as 'total', cp.prevEntrega, date_format(cp.dataRecebimento, '%Y-%m-%d') as 'dataRecebimento', cp.inclusao, cp.dataAlteracao,
    p.descricao,
    i.nome as 'usInclusao', a.nome as 'usAlteracao'
    from est_compra_produto cp
    inner join est_produtos p on cp.produto = p.id
    left join tb_usuario i on cp.us = i.id
    left join tb_usuario a on cp.usAlteracao = a.id
    where cp.compra = $c and cp.id = '$id'";

    $cap = $pdo->query($sql);
    while ($fn = $cap->fetch()) {

        $dataSet[$aux] = array(
            "ID" => $fn['id'],
            "linha" => $fn['linha'],
            "cod" => $fn['produto'],
            "descricao" => $fn['descricao'],
            "qt" => $fn['quantidade'],
            "qtRecebido" => $fn['quantidadeRecebido'],
            "qtCancelado" => $fn['quantidadeCancelado'],
            "unMed" => $fn['unidade'],
            "vlUn" => number_format($fn['valor'], 2, ",", "."),
            "vlTot" => number_format($fn['total'], 2, ",", "."),
            "prevEntrega" => $fn['prevEntrega'],
            "dataRecebimento" => $fn['dataRecebimento'],
            "usAlteracao" => $fn['usAlteracao'],
            "dataAlteracao" => $fn['dataAlteracao'],
            "usInclusao" => $fn['usInclusao'],
            "inclusao" => $fn['inclusao'],
        );
        $aux++;
    }

    $json = array("data" => $dataSet);

    header("Content-type: application/json");
    //$viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}

if ($a == "his") {
    $id = $_REQUEST['id'];

    $log = "";
    $sql = "select date_format(l.data, '%d/%m/%Y %H:%i') as 'data', u.nome, l.log  from log_financeiro l inner join tb_usuario u on l.us = u.id where l.fin = '$id' order by l.data desc";
    $pl = $pdo->query($sql);
    while ($l = $pl->fetch()) {
        $log .= " $l[data] - $l[nome]<br><br>$l[log] <hr>";
    }

    $retLot = array("log" => $log);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($retLot, JSON_PRETTY_PRINT);
}

if ($a == "lista") {

    //Compra
    //Fornecedor
    //PrevisaoEntrega
    //Situacao
    //DatRegistro
    //UsuarioRegistro
    $pdt = $pdo->query("
        select c.id, date_format(c.previsaoEntrega, '%d/%m/%Y') as 'prevEntrega', c.status, date_format(c.dataInclusao, '%d/%m/%Y') as 'dataInclusao',
        u.nome,
        f.fantasia  
        from est_compras c
        inner join est_fornecedores f on c.fornecedor = f.id
        inner join tb_usuario u on c.us = u.id
    ");

    $dataSet = array();
    $aux = 0;
    while ($fn = $pdt->fetch()) {

        $id = "<a href='#' onclick='direcionaCompra($fn[id])'>$fn[id]</a>";

        $dataSet[$aux] = array(
            "ID" => $fn['id'],
            "Compra" => $id,
            "Fornecedor" => $fn['fantasia'],
            "PrevisaoEntrega" => $fn['prevEntrega'],
            "Situacao" => $fn['situacao'],
            "DataRegistro" => $fn['status'],
            "UsuarioRegistro" => $fn['nome']
        );
        $aux++;
    }
    $json = array("data" => $dataSet);

    header("Content-type: application/json");
    //  $viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}

if ($a == "dados") {

    $sql = "
    select c.id, c.previsaoEntrega, c.status, date_format(c.dataInclusao, '%d/%m/%Y') as 'dataInclusao', c.origem, c.fornecedor as 'codFornecedor', c.refFornecedor, c.refCompra, date_format(c.dataEntrega, '%Y-%m-%d') as 'dtEntrega',
    u.nome,
    f.fantasia , f.doc
    from est_compras c
    inner join est_fornecedores f on c.fornecedor = f.id
    inner join tb_usuario u on c.us = u.id
    where c.id = '$c'
    ";
    $cmp = $pdo->query($sql)->fetch();

    $sqlValor = "select coalesce(sum(quantidade * valor),0) as 'total' from est_compra_produto where compra = '$c'";
    $resumo = $pdo->query($sqlValor)->fetch();
    $rv = number_format($resumo['total'], 2, ",", ".");

    switch ($cmp['origem']) {
        case 1:
            $origem = "Manual";
            break;
        case 2:
            $origem = "Requisição";
            break;
    }

    ## DEFINIR REGRAS PARA STATUS ##
    $status = "Em andamento";

    $dataSet = array();
    $aux = 0;
    $dataSet = array(
        "compra" => $cmp['id'],
        "fornecedor" => $cmp['fantasia'],
        "codFornecedor" => $cmp['codFornecedor'],
        "refFornecedor" => $cmp['refFornecedor'],
        "previsaoEntrega" => $cmp['previsaoEntrega'],
        "Situacao" => $cmp['situacao'],
        "DataRegistro" => $cmp['status'],
        "UsuarioRegistro" => $cmp['nome'],
        "resumoValor" => $rv,
        "origem" => $origem,
        "refCompra" => $cmp['refCompra'],
        "dtEntrega" => $cmp['dtEntrega'],
        "status" => $status
    );
    //    $json = array("data" => $dataSet);

    header("Content-type: application/json");
    //  $viewdata['data'] = $dataSet;
    echo json_encode($dataSet, JSON_PRETTY_PRINT);
}
