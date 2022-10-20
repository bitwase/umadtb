<span class="tt_pg"><b>Atendimento</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#29/06/2016{
	-Desenvolvido;
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$id = $_REQUEST[id];//recebe id do agendamento
$inicia = $_POST[inicia];//quando desejar iniciar o atendimento
if($inicia == 1){
	mysql_query("update consultas set situacao = 3 where id = $id");//atualiza para em andamento
	mysql_query("insert into atendimento (atendimento,inicio,situacao) values ('$id',now(),'3')");
}

//verificar se já foi iniciado o atendimento, se sim, abrir caixa de texto, se não, opção para iniciar
$at = mysql_num_rows(mysql_query("select * from atendimento where atendimento = $id"));
$ddat = mysql_fetch_assoc(mysql_query("select id, situacao, date_format(inicio,'%d/%m/%Y %H:%i') as 'inicio' from atendimento where atendimento = $id"));

$finaliza = $_POST[finaliza];//saber se vai finalizar o atendimento
if($finaliza == 1){
$desc = addslashes($_POST[desc]);
mysql_query("update atendimento set fim = now(), tempo = timediff(now(),inicio), descricao = '$desc', situacao = 4 where id = $ddat[id]");
mysql_query("update consultas set situacao = 4 where id = $id");//atualiza para em andamento
}


$dd = mysql_fetch_assoc(mysql_query("
select cl.nome as 'cliente', cl.rg, cl.cpf, date_format(cl.dt_nasc, '%d/%m/%Y') as 'nasc', cl.end, cl.num, cl.compl, cl.bairro, cl.cidade, cl.uf, cl.tel1, cl.tel2, cl.email, c.paciente as 'idcli', e.especialidade, c.especialidade as 'idesp', date_format(c.data,'%d/%m/%Y %H:%i')  as 'dtAg' from consultas c 
inner join clientes cl on c.paciente = cl.id
inner join especialidades e on c.especialidade = e.id
where c.id = $id
"));
$data = date("d/m/Y H:i");
?>
<b>Dados do Atendimento</b><br>
<?php
echo "
<b>Data Agendada</b> $dd[dtAg]<br>
<b>Data Atendimento</b> $ddat[inicio]<br>
<b>Cliente</b> $dd[cliente]<br>
<b>Especialidade</b> $dd[especialidade] <br>
";
?>
<input type="button" value="Atendimento" id="bt1" onclick="troca(1)">
<input type="button" value="Histórico" id="bt2" onclick="troca(2)">
<input type="button" value="Agendamentos" id="bt3" onclick="troca(3)">
<input type="button" value="Dados do Cliente" id="bt4" onclick="troca(4)">

<div id="at_atendimento">
<b>Atendimento</b><br>
<?php
if($at == 0){
echo "
<br>
<form action='#' method='POST'>
<b>Iniciar o Atendimento?</b><br>
<input type='hidden' name='inicia' value='1'>
<input type='submit' value='Iniciar'>
</form>
";
}

if($at > 0 && $ddat[situacao] == 3){
echo "
<br>
<form action='#' method='POST'>
<b>Atendimento</b><br>
<textarea name='desc' rows='6' cols='40'></textarea><br>
<input type='hidden' name='finaliza' value='1'>
<input type='submit' value='Finalizar'>
</form>
";
}
?>
</div>

<div id="at_historico">
<b>Histórico</b><br>
<?php
//ver com Sandrinha, qual melhor tipo de histórico... se apenas da especialidade, da especialidade com o atendente, com o atendente independente da especialidade, todos.
//mostrar todos da especialidade
$lp1 = mysql_query("select a.descricao as 'hist', date_format(a.inicio,'%d/%m/%Y %H:%i') as 'inicio', date_format(a.fim,'%d/%m/%Y %H:%i') as 'fim', date_format(a.tempo,'%H:%i') as 'duracao', at.cor, at.nome as 'atendente', date_format(c.data,'%d') as 'dia', date_format(c.data,'%m') as 'mes', date_format(c.data,'%Y') as 'ano', date_format(c.data,'%d/%m/%Y %H:%i') as 'data', e.especialidade from atendimento a 
inner join consultas c on a.atendimento = c.id
inner join atendentes at on c.atendente = at.id
inner join especialidades e on c.especialidade = e.id
where a.atendimento in( 
select id from consultas where paciente = $dd[idcli] AND especialidade = $dd[idesp] and situacao = 4
	)");
while($lp=mysql_fetch_assoc($lp1)){
switch($lp[mes]){
	case 1:
	$mes = "Janeiro";
	break;
	case 2:
	$mes = "Fevereiro";
	break;
	case 3:
	$mes = "Março";
	break;
	case 4:
	$mes = "Abril";
	break;
	case 5:
	$mes = "Maio";
	break;
	case 6:
	$mes = "Junho";
	break;
	case 7:
	$mes = "Julho";
	break;
	case 8:
	$mes = "Agosto";
	break;
	case 9:
	$mes = "Setembro";
	break;
	case 10:
	$mes = "Outubro";
	break;
	case 11:
	$mes = "Novembro";
	break;
	case 12:
	$mes = "Dezembro";
	break;
}
if($lp[cor] == "#000000" || $lp[cor] == "#1C1C1C"){
$txt = "color:#fff;";
}
else {
$txt = "";
}
echo "<div class='hist'>
<div class='bg' style='background:$lp[cor];'></div>
<div class='cnt'>
	<div class='dia'>$lp[dia]</div>
	<div class='mes'>$mes</div>
	<div class='info' style='$txt'>
<b>Inicio:</b> $lp[inicio]<br>
<b>Término:</b> $lp[fim]<br>
<b>Duração:</b> $lp[duracao]<br>
<b>Especialidade:</b> $lp[especialidade]<br>
<b>Atendente:</b> $lp[atendente]<br>
</div>
<div class='desc'>
$lp[hist]
</div>
	<div class='ano'>$lp[ano]</div>
</div>
</div>";
}
?>

</div>

<div id="at_agendamentos">
<b>Agendamentos</b><br>
<?php
$lp1 = mysql_query("select date_format(c.data,'%d') as 'dia', date_format(c.data,'%m') as 'mes', date_format(c.data,'%Y') as 'ano', date_format(c.data,'%d/%m/%Y %H:%i') as 'data', a.cor, e.especialidade, a.nome, c.situacao  from consultas c 
inner join atendentes a on c.atendente = a.id
inner join especialidades e on c.especialidade = e.id
where c.paciente = '$dd[idcli]' and data > now() order by data asc");
while($lp=mysql_fetch_assoc($lp1)){
switch($lp[mes]){
	case 1:
	$mes = "Janeiro";
	break;
	case 2:
	$mes = "Fevereiro";
	break;
	case 3:
	$mes = "Março";
	break;
	case 4:
	$mes = "Abril";
	break;
	case 5:
	$mes = "Maio";
	break;
	case 6:
	$mes = "Junho";
	break;
	case 7:
	$mes = "Julho";
	break;
	case 8:
	$mes = "Agosto";
	break;
	case 9:
	$mes = "Setembro";
	break;
	case 10:
	$mes = "Outubro";
	break;
	case 11:
	$mes = "Novembro";
	break;
	case 12:
	$mes = "Dezembro";
	break;
}
switch($lp[situacao]){
	case 1:
		$st = "Agendado";
		break;
	case 2:
		$st = "Reagendado";
		break;
	case 3:
		$st = "Em Andamento";
		break;
	case 4:
		$st = "Realizado";
		break;
	case 5:
		$st = "Cancelado";
		break;
}
if($lp[cor] == "#000000" || $lp[cor] == "#1C1C1C"){
$txt = "color:#fff;";
}
else {
$txt = "";
}
echo "<div class='agnd'>
<div class='bg' style='background:$lp[cor];'></div>
<div class='cnt'>
	<div class='dia'>$lp[dia]</div>
	<div class='mes'>$mes</div>
	<div class='info' style='$txt'>
<b>Data:</b> $lp[data]<br>
<b>Especialidade:</b> $lp[especialidade]<br>
<b>Atendente:</b> $lp[nome]<br>
<b>Status: $st</b> 
</div>
	<div class='ano'>$lp[ano]</div>
</div>
</div>";
}
?>
</div>

<div id="at_dados">
<b>Dados</b><br>
<b>Nome:</b><?php echo "$dd[cliente]";?> <span style="margin-left:15px;"><b>Nascimento:</b><?php echo "$dd[nasc]";?></span><br>
<b>RG:</b><?php echo "$dd[rg]";?> <span style="margin-left:15px;"><b>CPF:</b><?php echo "$dd[cpf]";?></span><br>
<b>Endereço:</b><?php echo "$dd[end], $dd[num] $dd[compl], $dd[bairro], $dd[cidade] - $dd[uf]";?><br>
<b>Telefone:</b> <?php echo "$dd[tel1]"; ?><br>
<b>Celular:</b> <?php echo "$dd[tel2]"; ?><br>
<b>Email:</b> <?php echo "$dd[email]"; ?><br>

</div>

<script>
troca(1);
function troca(n){
if(n==1){
document.getElementById('bt1').style.opacity = "1";
document.getElementById('bt2').style.opacity = "0.4";
document.getElementById('bt3').style.opacity = "0.4";
document.getElementById('bt4').style.opacity = "0.4";

document.getElementById('at_atendimento').style.display = "block";
document.getElementById('at_historico').style.display = "none";
document.getElementById('at_agendamentos').style.display = "none";
document.getElementById('at_dados').style.display = "none";
}
if(n==2){
document.getElementById('bt1').style.opacity = "0.4";
document.getElementById('bt2').style.opacity = "1";
document.getElementById('bt3').style.opacity = "0.4";
document.getElementById('bt4').style.opacity = "0.4";

document.getElementById('at_atendimento').style.display = "none";
document.getElementById('at_historico').style.display = "block";
document.getElementById('at_agendamentos').style.display = "none";
document.getElementById('at_dados').style.display = "none";
}

if(n==2.1){
document.getElementById('bt1').style.display = "none";
troca(2);
}
if(n==3){
document.getElementById('bt1').style.opacity = "0.4";
document.getElementById('bt2').style.opacity = "0.4";
document.getElementById('bt3').style.opacity = "1";
document.getElementById('bt4').style.opacity = "0.4";

document.getElementById('at_atendimento').style.display = "none";
document.getElementById('at_historico').style.display = "none";
document.getElementById('at_agendamentos').style.display = "block";
document.getElementById('at_dados').style.display = "none";
}
if(n==4){
document.getElementById('bt1').style.opacity = "0.4";
document.getElementById('bt2').style.opacity = "0.4";
document.getElementById('bt3').style.opacity = "0.4";
document.getElementById('bt4').style.opacity = "1";

document.getElementById('at_atendimento').style.display = "none";
document.getElementById('at_historico').style.display = "none";
document.getElementById('at_agendamentos').style.display = "none";
document.getElementById('at_dados').style.display = "block";
}
}

</script>
<?php
if(($at > 0 && $ddat[situacao] == 4) || $finaliza == 1){
echo "
<script>
troca(2.1);
</script>
";
}
?>
