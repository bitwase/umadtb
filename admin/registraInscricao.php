<?php

include "conexao.php";
error_reporting(~E_ALL);

$tk = $_POST['tk'];

if ($tk != "b5e25c4c183f366901ebbbb73412faf6") {
    exit();
}

$evento = $_POST['evento'];
$rg = $_POST['rg'];
$nome = $_POST['nome'];
$nascimento = $_POST['nascimento'];
$cep = $_POST['cep'];
$logradouro = $_POST['logradouro'];
$num = $_POST['num'];
$complemento = $_POST['complemento'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$uf = $_POST['uf'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];

//verificar se cadsatro já existe pelo RG informado
$ve = mysql_num_rows(mysql_query("select * from tb_inscritos where rg = '$rg'"));
if ($ve == "0") {
    //se não houver, deve inserir o cadastro, e buscar o ID que foi incluso, em seguida registrar no evento se ainda não existir
    mysql_query("insert into tb_inscritos (rg, nome, nascimento, cep, rua, num, complemento, bairro, cidade, uf, tel1, email, dataInscricao) values(
        '$rg',
        '$nome',
        '$nascimento',
        '$cep',
        '$logradouro',
        '$num',
        '$complemento',
        '$bairro',
        '$cidade',
        '$uf',
        '$telefone',
        '$email',
        now()
    )") or die(mysql_error());

    //buscar o id pelo nome e nascimento (pode não ter RG)
    $pid = mysql_fetch_assoc(mysql_query("select id from tb_inscritos where nome = '$nome' and nascimento = '$nascimento'"));

    //inserir no evento
    mysql_query("insert into tb_inscricao (inscrito, evento, st, pg, data) values(
        '$pid[id]',
        '$evento',
        '1',
        '1',
        now()
    )") or die(mysql_error());
}
if ($ve > 0) {
    //se houver, deve apenas pegar o ID e inserir como evento atual se ainda não existi
    $pid = mysql_fetch_assoc(mysql_query("select id from tb_inscritos where nome = '$nome' and nascimento = '$nascimento'"));

    //verificar se não está inserito
    $vni = mysql_num_rows(mysql_query("select * from tb_inscricao where inscrito = '$pid[id]' and evento = '$evento'"));

    if ($vni == 0) {
        mysql_query("insert into tb_inscricao (inscrito, evento, st, pg, data) values(
        '$pid[id]',
        '$evento',
        '1',
        '1',
        now()
    )") or die(mysql_error());
    }

}

echo "OK";
//se tudo der certo, retornar "OK", se der erro, retornar "qualquer coisa"
