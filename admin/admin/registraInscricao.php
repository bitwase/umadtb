<?php

include "../conexao.php";
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

$dados = "
Evento: $evento <br> 
RG: $rg <br> 
Nome: $nome <br>
Nascimento: $nascimento <br>
CEP: $cep <br>
Logradouro: $logradouro <br>
Num: $num <br>
Complemento: $complemento <br>
Bairro: $bairro <br>
Cidade: $cidade <br>
UF: $uf <br>
Telefone: $telefone <br>
Email: $email <br>
";

try {
    $sql = "insert into tentativa_cadastro (data, post, nome) values(now(),'$dados', '$nome')";
    $pdo->query($sql);
} catch (PDOException $e) {
    return $e->getMessage();
}

//verificar se cadsatro já existe pelo RG informado

$ve = $pdo->query("select * from tb_inscritos where rg = '$rg'")->rowCount();
if ($ve == "0") {
    //se não houver, deve inserir o cadastro, e buscar o ID que foi incluso, em seguida registrar no evento se ainda não existir
    try {
        $sql = "insert into tb_inscritos (rg, nome, nascimento, cep, rua, num, complemento, bairro, cidade, uf, tel1, email, dataInscricao) values(
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
    )";
        $pdo->query($sql);
    } catch (PDOException $e) {
        return $e->getMessage();
    }

    $id = $pdo->lastInsertId();

    //inserir no evento

    try {
        $sql = "insert into tb_inscricao (inscrito, evento, st, pg, data) values(
        '$id',
        '$evento',
        '1',
        '1',
        now()
    )";

        $pdo->query($sql);
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
if ($ve > 0) {
    //se houver, deve apenas pegar o ID e inserir como evento atual se ainda não existir

    $pid = $pdo->query("select id from tb_inscritos where nome = '$nome' and nascimento = '$nascimento'")->fetch();

    //verificar se não está inserito
    $vni = $pdo->query("select * from tb_inscricao where inscrito = '$pid[id]' and evento = '$evento'")->rowCount();

    if ($vni == 0) {
        try {
            $sql = "insert into tb_inscricao (inscrito, evento, st, pg, data) values(
        '$pid[id]',
        '$evento',
        '1',
        '1',
        now()
    )";
            $pdo->query($sql);
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }
}
 
echo "OK";
//se tudo der certo, retornar "OK", se der erro, retornar "qualquer coisa"
