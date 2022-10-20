<body class="telaLogin" onload="carregou();">
<div id="fundo"></div>
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
include 'divCadastro.php';

?>
<img src="arquivos/imagens/logo-nova.png" class="logoLoginNovo" height="288px">
<img src="arquivos/imagens/cadeado.png" class="cadeado" onclick="telaLogar()">
<div id='logar2' style='display:block;'>
<br><!--div id="btCadastro" onclick="cadastro()"> Jovem, faça aqui o seu cadastro</div>
ou--><div id="btCadastro" onclick="cadastroEvento()">Faça aqui a sua inscrição para nossos eventos.</div>
</div>
<?php
//echo "</div>";
echo "
<div id='logar' class='$class' style='display:none;'>
<form action='valida.php' method='post'>
<b>Login</b><br>
<span id='erro_login'>$mens_erro</span><br>
<input type='text' name='usuario' size='8' placeholder='Usuário' required title='Necessário informar usuário para acesso ao sistema.'><br>
<input type='password' name='senha' size='8' placeholder='Senha' required title='Necessário informar senha para acesso ao sistema.'><br>
<input type='submit' value='Entrar'>
</form>
</div>";
?>
</body>
<script>
function telaLogar(){
	if(document.getElementById("logar").style.display == "none"){
		document.getElementById("logar").style.display = "block";
		document.getElementById("logar2").style.display = "none";
	}
	else{
		document.getElementById("logar2").style.display = "block";
		document.getElementById("logar").style.display = "none";
	}
}

<?php if($erro == 1 || $erro == 2){?>
	telaLogar();
<?php } ?>
</script>
