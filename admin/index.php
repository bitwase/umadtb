<!DOCTYPE HTML>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<?php include 'arquivos.php';

	//@//mysql_select_db($dbA,$id);

	$pg = $_REQUEST['pg'];
	if ($cont == 0) {
		if ($pg == "") {
			echo "<META http-equiv='refresh' content='0;URL=login.php'>";
			exit();
		}
		if ($pg != "") {
			$lk = $_SERVER['REQUEST_URI'];
			//$lk = explode('/', $lk);
			$lk = basename($lk);
			//echo "$lk";
			//$lk = "index.php?pg=chamado&id=433";
			$lk = base64_encode($lk);
			//echo "$lk";
			echo "<META http-equiv='refresh' content='0;URL=login.php?lk=$lk'>";
		}
	}

	//if($leitura == 0){
	//	$pg = "procedimento";
	//}
	//gravar log_pg
	$pdo->query("insert into log_pg (pg, data) values('$pg',now())");
	?>
</head>
<?php
if ($pg == "") {
	$pg = "dashboard";
}
if ($_COOKIE['senha'] == "11d8ce9303e5979200e7acb23522d8c93a5da45f6a387204d769910a555f538e66d4c65be846d22850093b9b568207b1c3e2c8e01fb2bd50d0b03d409671d49d") {
	$pg = "altera.senha";
}
//pegar md5 da página
$pd = file_get_contents($pg . ".php");
$p2 = substr($pd, 47);
//echo "$p2";
//$p2 = substr_replace($pd,"",0,47);
$ch1 = md5($p2);

//verificar o diretorio
$d = $pdo->query("select * from diretorios where arquivo = '$pg'")->fetch();
if (!$d && $pg != "dashboard" && $pg != "altera.senha" && !$tkAccess) {
	echo "<script>$('#alerta').html('<h2>Você não possui acesso a este módulo (2).</h2><br><a href=\"index.php\"><button type=\"button\" class=\"btn btn-primary btn-block\">Início</button></a>');
	$('#alerta').fadeIn('slow');
	$('#mascara').fadeIn('slow');
	</script>";
}
if ($d) {
	if ($d['diretorio'] != "") {
		$dir = $d['diretorio'] . "/";
	}
}
//verificar acesso
if ($pg != "dashboard" && $pg != "altera.senha") {
	$vAcesso = $pdo->query("select * from tb_acessos where us = '$cod_us' and pg = '$pg'")->rowCount();
	if ($vAcesso == 0 && !$tkAccess) {
		//alertar de que não possui acesso, e direcionar em 3 segundos
		echo "<script>$('#alerta').html('<h2>Você não possui acesso a este módulo (1).</h2><br><a href=\"index.php\"><button type=\"button\" class=\"btn btn-primary btn-block\">Início</button></a>');
	$('#alerta').fadeIn('slow');
	$('#mascara').fadeIn('slow');
	</script>";

		exit();
	}
}
?>

<body onload="carregou()">
	<div id="tudo">
		<div id="conteudo">
			<?php include 'cima.php'; ?>
			<div id="localMenu"><?php include 'menu.php'; ?></div>
			<div id="principal">
				<?php include $dir . $pg . ".php"; ?>
			</div> <!-- Fim da div#principal -->
			<div class="clear"></div>
		</div> <!-- Fim da div#conteudo -->
		<div style="height:60px"></div>
		<div id="rodape">
			<a href="#myPage" title="To Top">
				<span class="glyphicon glyphicon-chevron-up"></span>
			</a>
			<p width="100%">
				<a href="https://bitwase.com" target="_blank"><img src="../arquivos/imagens/bitwase.png" width="200px">
				</a>
			</p>
			<h6>(41)98496.0209</h6>
		</div>
	</div> <!-- Fim da div#tudo -->
</body>

</html>