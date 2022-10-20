<?php

//use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;
include 'conexao.php';
include 'classes.php';

//echo "<pre>";
//print_r($_SERVER);
##mail_title##
##mail_body##

/**
 * Script para atualização e alertas
 */

## Atualização e alerta de Status de Cursos Realizados ##

/**
 * Listar todos os cursos a vencer
 */

$cnf = new config($pdo);
$config = $cnf->configuracoes();

validaCursos($pdo, $config);
validaProjetos($pdo, $config);

function validaCursos($pdo, $config)
{

    $mail_cursos = "";
    $txt_vencidos = "";

    $sqlCursosVencido = "select 
    c.id, c.dataVencimento, c.curso, date_format(c.dataVencimento, '%d/%m/%Y') as 'vct', col.nome
    from tb_cursos_colaborador c
    inner join tb_colaboradores col on c.colaborador = col.id
    where c.dataVencimento < now() and c.st != 4 
    order by c.dataVencimento";
    $lcv = $pdo->query($sqlCursosVencido);
    $qcv = $lcv->rowCount();
    if ($qcv > 0) {
        $txt_vencidos .= "<h3 style='color:#FF3423'>Cursos Vencidos: <b>$qcv</b></h3>";
        $txt_vencidos .= "<div>
    <div style='width:100px;float:left'><b>Curso</b></div>
    <div style='width:100px;float:left'><b>Vencimento</b></div>
    <div style='width:300px;float:left'><b>Colaborador</b></div>
    </div><br>";
    }
    while ($l = $lcv->fetch()) {
        //atualizar ID para st 3
        $pdo->query("update tb_cursos_colaborador set st = 2 where id = $l[id]");
        //chamar função apra enviar alerta por email
        $txt_vencidos .= "<div>
    <div style='width:100px;float:left'>$l[curso]</div>
    <div style='width:100px;float:left'>$l[vct]</div>
    <div style='width:300px;float:left'>$l[nome]</div>
    </div><br>";
        //    echo "$l[id] - $l[dataVencimento]<br>";
    }


    ## CURSOS A VENCER ##
    $txt_vencer = "";

    $sqlCursosVencer = "select 
    c.id, c.dataVencimento, c.curso, date_format(c.dataVencimento, '%d/%m/%Y') as 'vct', col.nome
    from tb_cursos_colaborador c
    inner join tb_colaboradores col on c.colaborador = col.id
    where datediff(c.dataVencimento, now()) <= '$config[diasVencimentoCurso]' and c.st != 2
    order by c.dataVencimento";
    $lcv2 = $pdo->query($sqlCursosVencer);
    $qcv2 = $lcv2->rowCount();
    if ($qcv2 > 0) {
        $txt_vencer .= "<h3 style='color:#E9FA10'>Cursos a Vencer: <b>$qcv2</b></h3>";
        $txt_vencer .= "<div>
        <div style='width:100px;float:left'><b>Curso</b></div>
        <div style='width:100px;float:left'><b>Vencimento</b></div>
        <div style='width:300px;float:left'><b>Colaborador</b></div>
        </div><br>";
    }
    while ($l = $lcv2->fetch()) {
        //atualizar ID para st 3
        $pdo->query("update tb_cursos_colaborador set st = 3 where id = $l[id]");
        //chamar função apra enviar alerta por email
        $txt_vencer .= "<div>
    <div style='width:100px;float:left'>$l[curso]</div>
    <div style='width:100px;float:left'>$l[vct]</div>
    <div style='width:300px;float:left'>$l[nome]</div>
    </div><br>";
        //    echo "$l[id] - $l[dataVencimento]<br>";
    }

    $mail_cursos = "$txt_vencidos <br> $txt_vencer";
    $assunto = "Vencimento de Cursos";
    $mf = $config['templateMail'];
    $mf = str_replace("##mail_title##", $assunto, $mf);
    $mf = str_replace("##mail_body##", $mail_cursos, $mf);
    $mf = addslashes($mf);

    if ($qcv > 0 || $qcv2 > 0) {
        //inserer como um registro para ser enviado
        $sqlInsereMail = "insert into tb_email (destinatario, nomeDestinatario, assunto, mensagem, st) values(
            '$config[emailCursoVencido]',
            '$config[destinatarioCursoVencido]',
            '$assunto',
            '$mf',
        '1'
        )";
        $r = $pdo->query($sqlInsereMail);
        $idMailCursos = $pdo->lastInsertId();
        chamaEmail($idMailCursos);
    }
}

function validaProjetos($pdo, $config)
{
    //listar projetos que estão atrasadas e atualizar o status (3)
    /**
     * Em atraso é:
     */
    $query = "SELECT
    o.id,
    o.nomeProjeto,
    datediff(o.inicioPrevisto, now()) as 'inicio',
    datediff(o.conclusaoPrevisto, now()) as 'fim',
    date_format(o.inicioEfetivo, '%d/%m/%Y') as 'inicioEfetivo',
    date_format(o.inicioPrevisto, '%d/%m/%Y') as 'inicioPrevisto',
    date_format(o.conclusaoPrevisto, '%d/%m/%Y') as 'conclusaoPrevisto',
    c.fantasia as 'cliente'
    FROM
        tb_projetos o
        inner join tb_clientes c on o.cliente = c.id
    where
        (
            datediff(o.inicioPrevisto, now()) < 0
            or datediff(o.conclusaoPrevisto, now()) < 0
        )
        and o.st not in(5)";

    $vd = $pdo->query($query);
    $qoa = $vd->rowCount();
    $txt_projetos = "";
    if ($qoa > 0) {
        $txt_projetos .= "<h3 style='color:#FF3423'>Total: <b>$qoa</b></h3>";

        $txt_projetos .= "<div>
        <div style='width:100%;float:left'><b>Projeto</b></div>
        <div style='width:32%;float:left'><b>Prev. Início</b></div>
        <div style='width:32%;float:left'><b>Início Efetivo</b></div>
        <div style='width:32%;float:left'><b>Prev. Conclusão</b></div>
        </div><br>";
    }
    while ($l = $vd->fetch()) {

        $pdo->query("update tb_projetos set st = 3 where id = $l[id]");

        $inicioEfetivo = $l['inicioEfetivo'] ?? "-";
        $prevConclusao = $l['conclusaoPrevisto'] ?? "-";
        $projeto = "$l[nomeProjeto] - $l[cliente]";

        $txt_projetos .= "<div>
        <div style='width:100%;float:left;background:#666'>$projeto</div>
        <div style='width:32%;float:left'>$l[inicioPrevisto]</div>
        <div style='width:32%;float:left'>$inicioEfetivo</b></div>
        <div style='width:32%;float:left'>$prevConclusao</div>
        </div><div style='width:100%;height:20px;float:left;'></div>";
    }

    $mail_projetos = "$txt_projetos";
    $assunto = "Projetos em Atraso";
    $mf = $config['templateMail'];
    $mf = str_replace("##mail_title##", $assunto, $mf);
    $mf = str_replace("##mail_body##", $mail_projetos, $mf);
    $mf = addslashes($mf);

    if ($qoa > 0) {
        //inserer como um registro para ser enviado
        $sqlInsereMail = "insert into tb_email (destinatario, nomeDestinatario, assunto, mensagem, st) values(
            '$config[emailProjetoVencido]',
            '$config[destinatarioProjetoVencido]',
            '$assunto',
            '$mf',
        '1'
        )";
        $r = $pdo->query($sqlInsereMail);
        $idMailCursos = $pdo->lastInsertId();
        chamaEmail($idMailCursos);
    }
}

function chamaEmail($i)
{
    $ch = curl_init();

    $postRequest = array(
        'id' => $i,
    );
    $srv = $_SERVER["HTTP_HOST"];
    $loc = $_SERVER["PHP_SELF"];
    $lc = explode("/", $loc);
    if (!isset($lc[2])) {
        $lc[2] = "";
    }
    if ($srv == "localhost") {
        curl_setopt($ch, CURLOPT_URL, 'http://localhost/engerede/mail/index.php?id=' . $i);
    }
    if ($srv != "localhost") {
        if ($lc[2] == "dev") {
            curl_setopt($ch, CURLOPT_URL, 'http://hom.bitwase.com/engerede/mail/index.php?id=' . $i);
        }
        if ($lc[2] != "dev") {
            curl_setopt($ch, CURLOPT_URL, 'https://sistema.bitwase.com/mail/index.php?id=' . $i);
        }
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postRequest);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    /*    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Authorization: xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
    $headers[] = 'Content-Type: application/json';
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    */
    $result = curl_exec($ch);
    //echo $result;
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
}
