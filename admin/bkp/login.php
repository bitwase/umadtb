﻿<body onload="carregou();">
<?php
error_reporting(~E_ALL);
$erro = $_REQUEST[e];
if($erro == 1){
$mens_erro = "Dados informados não conferem.";
$class="erro_login";
/*echo "<script>
alert('Dados informados não conferem.');
</script>";*/
}
if($erro == 2){
$mens_erro = "Usuário desativado. Falar com administrador.";
$class="erro_login";
/*echo "<script>
alert('Usuário desativado. Falar com administrador.');
</script>";*/
}
//$titulo ="BW - Clínicas";
//echo "<title>$titulo</title>";
//echo "<div id='conteudo'>";
include 'arquivos.php';
//include 'loga.php';
//echo "</div>";
echo "
<div id='logar' class='$class'>
<img src='arquivos/imagens/logo-login.png' class='logo_login'>
<form action='valida.php' method='post'>
<span id='erro_login'>$mens_erro</span><br>
<input type='text' name='usuario' size='8' placeholder='Usuário' required title='Necessário informar usuário para acesso ao sistema.'><br>
<input type='password' name='senha' size='8' placeholder='Senha' required title='Necessário informar senha para acesso ao sistema.'><br>
<input type='submit' value='Entrar'>
</form>
</div>";
?>
</body>
