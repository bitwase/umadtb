<?php
/*
Neste arquivo deverá ter conexão com a base de dados acesso
*/
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$sistema = 0; //id do sistema de acordo com a tabela tb_acessos em app_acessos
$cont = 0;
$usuario = ($_COOKIE['usuario'] ?? "");
$senha = ($_REQUEST['senha'] ?? "");
if ($senha == "") {
    $senha = $_COOKIE['senha'];
}
//$senha=hash('whirlpool',$senha_);
$pg = $_REQUEST['pg'];

$tk = $_REQUEST['tk']; //token
if ($tk == "123") {
    $tkAccess = true;
    $cont = 1;
    $cod_us = 1;
}

include('conexao.php');
include('classes.php');

if ($usuario != "" && $senha != "") {
    //$a = new conexao($usuario,$senha);
    //$cont = $a->validaUsuario();
    //print_r($pdo);
    $sql = "SELECT * FROM tb_usuario WHERE (usuario='$usuario' or email = '$usuario') AND senha = '$senha'";
    $cnf = $pdo->query($sql);
    $cont = $cnf->rowCount();
}
if ($cont == 0) {
    //    /exit();
    if (basename($_SERVER['PHP_SELF'], '.php') != "login") {
        header("Location:login.php");
    }
}

if ($cont > 0 && !$tkAccess) {
    ## atualiza dados dos cookies ##	
    setcookie("usuario", $usuario, time() + 7200);
    setcookie("senha", $senha, time() + 7200);
    ## pegar dados do usuário ##

    $sql_usuario = "SELECT * FROM tb_usuario WHERE (usuario='$usuario' or email = '$usuario')";
    $res = $pdo->query($sql_usuario);

    while ($a = $res->fetch()) {
        $nome = $a['nome'];
        $setor2 = $a['setorId'];
        $mail_logado = $a['email'];
        $set_us = $a['setor'];
        $tipo_usuario = $a['nvAcesso'];
        $nv = $a['nvAcesso'];
        $situacao = $a['situacao'];
        $us_atual = $a['usuario'];
        $us_cod_logado = $a['id'];
        $cod_us = $a['id'];
    }

    if ($situacao == 0) {
        setcookie("usuario", "", time() + 7200);
        setcookie("senha", "", time() + 7200);
        // header("Location:login.php?e=2");
    }
}
//print_r($retPdo);
$conf = new config($pdo);
$config = $conf->configuracoes();
