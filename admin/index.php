<!DOCTYPE HTML>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<?php include 'arquivos.php';
if($cont == 0){
echo "<META http-equiv='refresh' content='0;URL=login.php'>";
exit();
}
 ?>
</head>
<?php 
$pg = $_REQUEST[pg];
if($pg == ""){
	if($nivel == 1 || $nivel == 2){
		$pg = "v.inscritos";
	}
	if($nivel == 3){
		$pg = "v.inscritosEventos";
	}
}
?>
<body onload="carregou()">
<div id="tudo">
<div id="conteudo">
<?php 
include 'cima.php';
include 'divs.php';?>
<div id="principal">
<?php include $pg.".php";?>
<div id="rodape_imprime">
  Bitwase Sistemas Web<br><b>A solução que você precisa.</b>
</div>
</div> <!-- Fim da div#principal -->
<div class="clear"></div>
</div> <!-- Fim da div#conteudo -->
<div id="rodape">
  Bitwase Sistemas Web<br><b>A solução que você precisa.</b>
</div>
</div> <!-- Fim da div#tudo -->
</body>
</html>
