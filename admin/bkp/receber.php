<!DOCTYPE HTML>
<html lang="pt-br">
<?php
/*
Alterações
#26/05/2016{
	-deve receber o número do pagamento;
	-verifica se é venda ou agendamento;
	-verifica se existe parcela anterior em aberto;
	-se existir, emite alerta e não permite continuar;
	-caso não exista, permite continuar;
	-ao pagar, verifica se ainda existe parcela a ser pago para este agendamento/venda;
	-se não existir, altera situação para concluído;
}
*/
?>
<head>
	<meta charset="UTF-8">
	<?php include 'arquivos.php';
 ?>
</head>
<?php 
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}

$id = $_REQUEST[id];
$acao = $_POST[acao];
$cp = mysql_fetch_assoc(mysql_query(
	"select id, date_format(dt_ag,'%d/%m/%Y') as 'vct', dt_ag as 'ag', tipo2, motivo, valor, obs from financeiro where id = $id"
	));
			$tpa = substr($cp[tipo2],0,1);//tipo de ação A gendamento; V enda;
		$tpnum = substr($cp[tipo2],1);
if($acao == 1){//cancelar
	$hj = date("d/m/Y H:i");
	$mtv = $_POST[mtv];
	$obs = $cp[obs]." <br> Cancelado por $nome_ em $hj. Motivo: $mtv";
	mysql_query("update financeiro set sit = '3', obs = '$obs' where id = $id");
	echo "<script>alert('Agendamento de Recebimento cancelado.');
	opener.location.reload();
	window.close();
	</script>";
}

if($acao == 2){//realizar
	$hj = date("d/m/Y H:i");
	$data = $_POST[data];
	$obs = $cp[obs]." <br> Realizado por $nome_ em $hj. Recebido em: $data";
	mysql_query("update financeiro set sit = '2', obs = '$obs' where id = $id");
	$ver2 = mysql_num_rows(mysql_query("select * from financeiro where tipo2 = '$cp[tipo2]' and sit = 1 "));
	if($ver2 == 0){
		$tpa = substr($cp[tipo2],0,1);//tipo de ação A gendamento; V enda;
		$tpnum = substr($cp[tipo2],1);
		if($tpa == "V"){
			//se for venda, tem que mudar status para 3
			mysql_query("update vendas set st = 3 where id = '$tpnum'");
		}
	}
	echo "<script>alert('Agendamento de Recebimento Concluído.');
	opener.location.reload();
	window.close();
	</script>";
}

?>
<body onload="carregou()" onbeforeunload="fecha()">
<div id="tudo">
<div id="conteudo">
<div id="principal">
<span class="tt_pg"><b>Contas à Receber</b></span><br>
<?php
echo "
<b>Vencimento: </b>$cp[vct]<br>
<b>Descrição: </b>$cp[motivo]<br>
<b>Valor: </b>$cp[valor]<br>
<b>Observação: </b>$cp[obs]<br><br>
";
?>
<input type="button" value="Informar Recebimento" style="background:#3CB371" onclick="pagar()"> 
<input type="button" value="Cancelar" style="background:#FF4500" onclick="cancela()">
<div id="canc" style="display:none">
<b>Deseja realmente cancelar este agendamento?</b><br>
<form action="#" method="POST">
<input type="hidden" name="acao" value="1">
<b>Motivo:</b> <input type="text" name="mtv" id="mtv" size="35" required><br>
<input type="submit" value="Sim" style="background:#3CB371"> 
<input type="button" value="Não" style="background:#FF4500" onclick="naocancela()">
</form>
</div>

<div id="paga" style="display:none">
<b>Informar Pagamento</b><br>
<form action="#" method="POST">
<input type="hidden" name="acao" value="2">
<b>Data de Recebimento:</b> <input type="text" name="data" class="date" id="data" size="10" required><br>
<input type="submit" value="Salvar" style="background:#3CB371"> 
</form>
</div>
<div id="rodape_imprime">
  BWC <br><b>Desenvolvido por Wellington U. Santos</b>
</div>
</div> <!-- Fim da div#principal -->
<div class="clear"></div>
</div> <!-- Fim da div#conteudo -->
<div id="rodape">
  BWC <br><b>Desenvolvido por Wellington U. Santos</b>
</div>
</div> <!-- Fim da div#tudo -->
</body>
</html>
<script>
window.opener.mostraMascara();
function cancela(){
	document.getElementById("canc").style.display = "block";
	document.getElementById("paga").style.display = "none";
	document.getElementById("data").required = false;
	document.getElementById("mtv").required = true;
}
function naocancela(){
	document.getElementById("canc").style.display = "none";
	document.getElementById("mtv").required = false;
}
function pagar(){
	naocancela();
	document.getElementById("paga").style.display = "block";
	document.getElementById("data").required = true;
}

function fecha(){
	window.opener.escondeMascara();
	window.close();
}
</script>
<?php
	//verificar parcela anterior em aberto;
	$ver = mysql_num_rows(mysql_query("select * from financeiro where tipo2 = '$cp[tipo2]' and sit = 1 and dt_ag < '$cp[ag]'"));
if($ver > 0){
	echo "<script>
	alert('Existe parcela anterior com pendência.');
	fecha();
	</script>";
}
?>