<?php
include 'seguranca.php';
$i = $_REQUEST[i];//item
$v = $_REQUEST[v];//venda
//página responsável por atualizar valor de compra do produto... deverá questionar se será alterado somente nesta compra, ou se deverá alterar também no cadastro do produto... não permirir alterar para compras anteriores
include 'arquivos.php';
//pegar dados do produto
$pdt = mysql_fetch_assoc(mysql_query("select * from produtos where id = $i"));
$vlrat = number_format($pdt[vlrcmp],"2",".","");

$salva = $_POST[salva];
if($salva == 1){
	$nvl = $_POST[nvlr];
	$apl = $_POST[aplica];
	if($apl == 1){//somente na compra
		mysql_query("update cmppdt set vlu = '$nvl' where pdt = $i AND cmp = $v");
	}
	if($apl == 2){//compra e produto
		mysql_query("update cmppdt set vlu = '$nvl' where pdt = $i AND cmp = $v");
		mysql_query("update produtos set vlrcmp = '$nvl' where id = $i");
	}
	//script para emitir alerta, fechar a pagina e atyalzar a original
	$url = "index.php?pg=cn.compra&vn=$v";
	echo "<script>
	alert('Valor atualizado com sucesso.');
	opener.location.href = '$url';
	window.close();
	</script>";
}

echo "
<b>Produto:</b> $pdt[descricao] <br>
<b>Valor de Compra Atual:</b> R$$vlrat <br>
";
?>
<body onload="carregou();" style="background:#fff">
<form action="#" method="post">
<input type="hidden" name="salva" value="1">
<b>Novo Valor de Compra: </b><input type="text" name="nvlr" class="vlr" required><br>
<input type="radio" name="aplica" id="ap1" value="1" checked><label for="ap1">Aplicar Somente Para esta Compra</label><br>
<input type="radio" name="aplica" id="ap2" value="2"><label for="ap2">Atualizar Para Produto</label><br>
<input type="submit" value="Atualizar">
</form>
</body>
