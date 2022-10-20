<!DOCTYPE HTML>
<html lang="pt-br">
<?php
/*
Alterações
#24/06/2016{
		-Receber por request o id do agendamento;
		-Pegar todos os dados e receber alterações;
		-Alterar status do agendamento atual para "REAGENDADO", e inserir um novo cadastro;
		-Antes de inserir um novo cadastro, verificar agenda de quem vai atender com o novo horároi se não está ocupado, se estiver, emitir alerta em tela solicitando nova data;
		-data 
		-hr inicio
		-hr fim
		-cliente
		-especialidade
		-atendente
}

#28/06/2016{
	-colocar regra para selecionar atendente, cliente e especialidade;
}
//necessario verificar em financeiro, com o id anterior, qual era a situação do pagamento.
//se estava agendado, deve alterar status de todos com AXX para cancelado, e inserir valores igual para AYY, onde XX refere-se ao id anteriro, e YY ao novo ID
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

$nv = $_REQUEST[novo];//recebe data e hora conforme selecionado na agenda
if($nv != ""){
$dt = $nv[0].$nv[1].$nv[2].$nv[3].$nv[4].$nv[5].$nv[6].$nv[7].$nv[8].$nv[9];
$hr = $nv[10].$nv[11].$nv[12].$nv[13].$nv[14];
}
$cli = $_REQUEST[cli];//recebe cod do cliente

$id = $_REQUEST[id];

//pegar dados do cliente
$ddcli = mysql_fetch_assoc(mysql_query("select nome from clientes where id = $cli"));

$ag = mysql_fetch_assoc(mysql_query(
	"select a.id, date_format(a.data,'%d/%m/%Y %H:%i') as 'data', date_format(a.hr_fim,'%H:%i') as 'hr_fim', cl.nome as 'cliente', e.especialidade, at.nome as 'atendente', a.atendente as 'idat', a.paciente as 'idcli', a.especialidade as 'idesp' from consultas a 
	inner join clientes cl on a.paciente = cl.id
	inner join especialidades e on a.especialidade = e.id
	inner join atendentes at on a.atendente = at.id
	where a.id = $id"
	));

$salva = $_POST[salva];
	
if($salva == 1){//reagendar
$dt = $_POST[ndata];//dd/mm/aaaa 01 34 6789
$data = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
$hora = $_POST[hora];
$hora_t = $_POST[hora_t];

//dados para inserir no novo agendamento
$atendente = $ag[idat];//id do atendente
$cliente = $ag[idcli];//id cliente
$especialidade = $ag[idesp];//id especialidade

//verificar se atendente poderá atender na nova data/horario
$c1 = mysql_num_rows(mysql_query("select * from consultas where situacao = 1 AND id != '$id' AND date_format(data,'%Y-%m-%d') = '$data' and atendente = '$atendente' and  ((hr_inicio between '$hora' and subtime('$hora_t','00:01')) or (hr_fim between addtime('$hora','00:01') and '$hora_t'))"));

if($c1 > 0){
echo "<script type='text/javascript'>
alert('ALTERAÇÃO NÃO REALIZADA. Horário já ocupado para este atendente.');
</script>
";
}
if($c1 == 0){	
mysql_query("INSERT INTO consultas  
	(data,paciente,especialidade,atendente,situacao,hr_inicio,hr_fim)
	VALUES(
	'$data $hora',
	'$cliente',
	'$especialidade',
	'$atendente',
	'1',
	'$hora',
	'$hora_t')") or die(mysql_error());

//atualiza status do anterior para reagendado
mysql_query("update consultas set situacao = 2 where id = '$id'");


//pegar último id p/ chamar a função se informa pagamento ou agenda pagamento
$ua = mysql_fetch_assoc(mysql_query("select max(id) as 'id' from consultas"));

	echo "<script>alert('Reagendamento Realizado.');
	opener.location.reload();
	window.close();
	</script>";
}
}

?>
<body onload="carregou()" onbeforeunload="fecha()">
<div id="tudo">
<div id="conteudo">
<div id="principal">
<span class="tt_pg"><b>Agendamento</b></span><br>
<?php
echo "
<b>Especialidade: </b>$ag[especialidade]<br>
<b>Atendente: </b>$ag[atendente]<br><br>
";
?>
<form action="#" method="POST">
<input type="hidden" name="salva" value="1">
<b>Cliente</b> <input type="text" name="cli" id="cliente" size="50" value="<?php echo $ddcli[nome];?>" style="text-transform:uppercase"><br>
<b>Nova Data</b> <input type="text" size="11" class="date" name="ndata" required value="<?php echo $dt;?>"><br>
<b>Hora Início</b> <input type="text" name="hora" class="hora" id="dt1" size="4" required value="<?php echo $hr;?>" ><br>
<b>Hora Término</b> <input type="text" name="hora_t" class="hora" id="dt1" size="4" ><br><br>
<input type="submit" value="Salvar">
</form>

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
