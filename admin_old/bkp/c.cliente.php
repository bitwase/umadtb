<span class="tt_pg"><b>Cliente</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#23/05/2016{
	-Desenvolvido;
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$id = $_REQUEST[id];//recebe id do cliente

### RECEBE DADOS PARA ENVIO DE EMAIL ###
$envia = $_POST[envia];
if($envia == 1){
$assunto = addslashes($_POST[assunto]);
$mensagem = addslashes($_POST[mensagem]);

mysql_query("insert into email (dest,assunto,mensagem) values('$id','$assunto','$mensagem')");
## CHAMAR VIA AJAX PÁGINA PARA ENVIO DE EMAIL mail.cliente.php ##
?>
<script>
$.ajax({
      url:'mail.cliente.php',//ação 8 - ajusta histórico
      complete: function (response) {
	//alert(response.responseText);
      },
      error: function () {
         // alert('Erro');
      }
  });
</script>
<?php
}

### RECEBE DADOS PARA NOVO AGENDAMENTO ###
$agenda = $_POST[agenda];//recebe 1
if($agenda == 1){
	$dt = $_POST[ndata];
	$dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1]; 
	$hora = $_POST[hora];
	$hora_t = $_POST[hora_t];
	$cli = $id;//recebido diretamente
	$esp = $_POST[esp];
	$atn = $_POST[atendente];
	
	$c1 = mysql_num_rows(mysql_query("select * from consultas where situacao = 1 AND date_format(data,'%Y-%m-%d') = '$dt' and atendente = '$atn' and  ((hr_inicio between '$hora' and subtime('$hora_t','00:01')) or (hr_fim between addtime('$hora','00:01') and '$hora_t'))"));
	if($c1 > 0){
	echo "<script type='text/javascript'>
	alert('AGENDAMENTO NÃO REALIZADO. Horário já ocupado para este atendente.');
	</script>
	";
	}
if($c1 == 0){
echo "<script>
mostraMascara();
carregando();
</script>";	
mysql_query("INSERT INTO consultas  
	(data,paciente,especialidade,atendente,situacao,hr_inicio,hr_fim)
	VALUES(
	'$dt $hora',
	'$cli',
	'$esp',
	'$atn',
	'1',
	'$hora',
	'$hora_t')") or die(mysql_error());
echo "<script>
carregando();
</script>";
//se envia sms ativo
if($cnf_sms){//verifica se está habilitado para enviar sms
if($cnf_smsConsulta){//verifica se está habilitado para envio de sms lembrete de consulta
	mandaSms($cnf_mSmsConsulta,$cli,$dt);
}
}
if($cnf_email){//verifica se permite enviar email
if($cnf_emailConsulta){//verificar se permite enviar email de consulta
	mandaEmail($cnf_mEmailConsulta,$cli,$dt);
}
}
//pegar último id p/ chamar a função se informa pagamento ou agenda pagamento
$ua = mysql_fetch_assoc(mysql_query("select max(id) as 'id' from consultas"));
	echo "<META http-equiv='refresh' content='0;URL=index.php?pg=pagamentos&tp=1&cn=$ua[id]'>";
}
}

/* FUNÇÃO PARA AGENDAMENTO DE ENVIO DE SMS */
function mandaSms($mensagem,$cliente,$dt_env){
//dt_env tem que subtrair 1 na inserção..
//dt_ag deve ser igual a data atual
//st neste momento deve ser 0
mysql_query("insert into tb_sms (mensagem,cliente,dt_ag,dt_en,st) values ('$mensagem','$cliente',now(), DATE_SUB('$dt_env',INTERVAL 1 day),'0')");
//tp = 1 novo agendamento
//script abaixo, insere na tb do cliente, se retorno positivo, insere na tb bw
?>

<script>
$.ajax({
      url:'sms.php?tp=1',
      complete: function (response) {
//alert(response.responseText);
	$.ajax({
	      url:response.responseText,
	      complete: function (response) {	
	//alert(response.responseText);
	  },
	error: function () {
	 // alert('Erro');
	 }
	 });		
      },
      error: function () {
         // alert('Erro');
      }
  });
</script>

<?php
}

function mandaEmail($mensagem,$cliente,$dt_env){
mysql_query("insert into email (cliente,assunto,mensagem,data,dt_en,st) 
values ('$cliente','Lembrete de Agendamento','$mensagem',now(), DATE_SUB('$dt_env',INTERVAL 1 day),'0')") or die(mysql_error());
//tp = 1 novo agendamento
//script abaixo, insere na tb do cliente, se retorno positivo, insere na tb bw
//seguir mesma logica de sms....
?>
<script>
$.ajax({
      url:'email.php?tp=1',
      complete: function (response) {
//alert(response.responseText);	
      },
      error: function () {
         // alert('Erro');
      }
  });
</script>

<?php
}


$dd = mysql_fetch_assoc(mysql_query("select *, date_format(dt_nasc,'%d/%m/%Y') as 'nasc' from clientes where id = $id"));

$fin2 = mysql_num_rows(mysql_query("
SELECT * from financeiro f
inner join usuarios u on f.us = u.id 
 where (f.tipo2 in (select concat('V',id) from vendas where cliente = '$id') OR
f.tipo2 in (select concat('A',id) from consultas where paciente = '$id')) AND date_format(f.dt_ag, '%Y-%m-%d') < date_format(now(),'%Y-%m-%d') and f.sit = 1
order by f.data desc
"));
 ?>
<div id="dd_cliente">
<b>Nome:</b><?php echo "$dd[nome]";?> <span style="margin-left:15px;"><b>Nascimento:</b><?php echo "$dd[nasc]";?></span><br>
<b>RG:</b><?php echo "$dd[rg]";?> <span style="margin-left:15px;"><b>CPF:</b><?php echo "$dd[cpf]";?></span><br>
<b>Endereço:</b><?php echo "$dd[end], $dd[num] $dd[compl], $dd[bairro], $dd[cidade] - $dd[uf]";?><br>
<b>Situação:</b> <?php if($fin2 == 0){ echo "Adimplente.";} else if($fin2 > 0){ echo "Inadimplente.";} ?><br>
</div><hr>
<input type="button" value="Agendamentos" id="bt1" onclick="troca(1)">
<input type="button" value="Compras" id="bt2" onclick="troca(2)">
<input type="button" value="Pagamentos" id="bt3" onclick="troca(3)">
<input type="button" value="Contato" id="bt4" onclick="troca(4)">
<div id="cli_agendamentos">
<b>Agendamentos</b><br><br>
<b>Próximos</b><?php if($dd[situacao] == 1){?><a href="#" title="Novo Agendamento" onclick="nvagendamento(<?php echo $id; ?>)"><img src="arquivos/icones/117.png" class="bt_p"></a><?php } ?><br>
<?php 
$lp1 = mysql_query("select date_format(c.data,'%d') as 'dia', date_format(c.data,'%m') as 'mes', date_format(c.data,'%Y') as 'ano', date_format(c.data,'%d/%m/%Y %H:%i') as 'data', date_format(c.hr_fim,'%H:%i') as 'hr_fim', e.especialidade, a.nome, a.cor, c.situacao  from consultas c 
inner join atendentes a on c.atendente = a.id
inner join especialidades e on c.especialidade = e.id
where c.paciente = '$id' and data > now() order by data asc");
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
	<div class='info'  style='$txt'>
<b>Data:</b> $lp[data] - $lp[hr_fim]<br>
<b>Especialidade:</b> $lp[especialidade]<br>
<b>Atendente:</b> $lp[nome]<br>
<b>Status:</b> $st
</div>
	<div class='ano'>$lp[ano]</div>
</div>
</div>";
}
?>
<hr>
<b><span onclick="esconde('anteriores');">Anteriores</span></b><br>
<div id="anteriores" style="display:block;">
<?php 
$lp1 = mysql_query("select date_format(c.data,'%d') as 'dia', date_format(c.data,'%m') as 'mes', date_format(c.data,'%Y') as 'ano', date_format(c.data,'%d/%m/%Y %H:%i') as 'data', date_format(c.hr_fim,'%H:%i') as 'hr_fim', e.especialidade, a.nome, a.cor, c.situacao  from consultas c 
inner join atendentes a on c.atendente = a.id
inner join especialidades e on c.especialidade = e.id
where c.paciente = '$id' and data < now() order by c.data desc");
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
<b>Data:</b> $lp[data] - $lp[hr_fim]<br>
<b>Especialidade:</b> $lp[especialidade]<br>
<b>Atendente:</b> $lp[nome]<br>
<b>Status:</b> $st
</div>
	<div class='ano'>$lp[ano]</div>
</div>

</div>";
}
?>
</div>
</div><br>
<div id="cli_compras">
<b>Compras</b><br>
<?php //dentro desta div, verificar todos os agendamentos por usuário, onde deve mostrar todos com as cores de fundo?>
<table id="produtos" class="display" width="100%"></table>
<hr>
<script>
var dataSet = [
<?php
$vnd= mysql_query("
select v.id, date_format(v.data,'%d/%m/%Y %H:%i') as 'data', v.st, c.nome as 'cliente', vn.nome as 'vendedor' from vendas v
inner join clientes c on v.cliente = c.id
inner join usuarios vn on v.vendedor = vn.id
where v.cliente = '$id'
");
 $od = 0;
while($pdt = mysql_fetch_assoc($vnd)){
$od++;//define ordem
$qti = mysql_fetch_assoc(mysql_query("select sum(qt) as 'qt', sum(qt*vlu) as 'vl' from vndpdt where vnd = $pdt[id]"));
switch($pdt[st]){
	case 1:
	$st = "Em Aberto";
	$lk = "<a href=\'index.php?pg=cn.venda&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
	case 2:
	$st = "Orçamento";
	$lk = "<a href=\'index.php?pg=cn.venda&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
	case 3:
	$st = "Concluído";
	$lk = "<a href=\'index.php?pg=cn.venda&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
	case 4:
	$st = "Pagamento Agendado";
	$lk = "<a href=\'index.php?pg=cn.venda&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
	case 5:
	$st = "Cancelado";
	$lk = "<a href=\'index.php?pg=cn.venda&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
}
	echo "
	['$od','$pdt[id]','$pdt[data]','$pdt[cliente]','$pdt[vendedor]','$qti[qt]','R$$qti[vl]','$st','$lk',],";
}
?>
];
 
$(document).ready(function() {
    $('#produtos').DataTable( {
		 "scrollX": true,
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
        ],
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "" },
			{ title: "Venda" },
            { title: "Data" },
            { title: "Cliente" },
            { title: "Vendedor" },
            { title: "Qt. Itens" },
            { title: "Vlr Total" },
            { title: "Situação" },
            { title: "" }
        ]
    } );
} );
</script>
</div>
<div id="cli_pagamentos">
<b>Pagamentos</b><br>
<?php //dentro desta div, verificar todos os agendamentos por usuário, onde deve mostrar todos com as cores de fundo?>
<table id="tabela" class="display" width="100%"></table>

<script language="javascript" type="text/javascript">
var dataSetPagamentos = [
<?php
//tentar concatenar no SQL, usando subconsultas pra mostrar somente o que for com tipo2 A(valor a concatenar , ou tipo V(valor)),
$fin = mysql_query("
SELECT date_format(f.data,'%d/%m/%Y %H:%i') as 'data', date_format(f.dt_ag,'%d/%m/%Y') as 'ag', date_format(f.dt_ag,'%Y-%m-%d') as 'agcmp', f.tipo, f.valor, f.motivo, u.nome, f.sit, f.obs FROM financeiro f
inner join usuarios u on f.us = u.id 
where f.tipo2 in (select concat('V',id) from vendas where cliente = '$id') OR
f.tipo2 in (select concat('A',id) from consultas where paciente = '$id')
order by f.data desc
");

while($fn = mysql_fetch_assoc($fin)){
	if($fn[tipo] == 1){
		$tipo = "Entrada";
		$cor = "green";
	}
	else if($fn[tipo] == 2){
		$tipo = "Saída";
		$cor = "red";
	}
//comapração de datas para alerta de atraso
$hj = date('Y-m-d');//data atual para comparação
$dp = $fn[agcmp];//data do agendamento 
$hj = strtotime($hj);
$dp = strtotime($dp);

if($hj > $dp && $fn[sit] == 1){
$atrasado = "<img src=\'arquivos/icones/8.png\' class=\'bt_p\' title=\'Pagamento em Atraso.\'>";
}
else{
$atrasado = "";
}

	if($fn[sit] == 1){
		$situacao = "Agendado";
	}
	elseif($fn[sit] == 2){
		$situacao = "Realizado";
	}
	elseif($fn[sit] == 3){
		$situacao = "Cancelado";
	}
	//$obs = str_replace($fn[obs],"<br >","<br>");
	echo "
	['$fn[data]','$tipo','<span style=\"color:$cor\">R$$fn[valor]</span>','$fn[motivo]','$fn[nome]','$situacao','$fn[ag] $atrasado','$obs'],
	";
}
?>
];
 
$(document).ready(function() {
    $('#tabela').DataTable( {
	 "scrollX": true,
         "paging":         false,
		data: dataSetPagamentos,
        columns: [
            { title: "Data" },
            { title: "Tipo Mov." },
            { title: "Valor" },
            { title: "Motivo" },
            { title: "Usuário" },
            { title: "Situação" },
            { title: "Data Ag." },
            { title: "Observação" }
        ]
    } );
} );
</script>
</div>
<div id="cli_contatos">
<b>Contato</b><br>
<b>Telefone:</b><?php $tel = $dd[tel1]; if($dd[tel2] != ""){ $tel.= "/ $dd[tel2]";} echo "$tel";?><br>
<b>Email:</b><?php echo "$dd[email]";?><br>
<?php
if($dd[email] != ""){
?>
<form action="#" method="POST">
<input type="hidden" name="envia" value="1">
<b>Assunto</b><input type="text" name="assunto" required size="20"><br>
<b>Mensagem</b><br>
<textarea name="mensagem" required rows="5" cols="50"></textarea><br><br>
<input type="submit" value="Enviar Email">
</form>
<?php
}
?>
</div>

<div id="agCliente" style="display:none;">
<img src="arquivos/icones/116.png" class="bt" style="position:absolute; top:5px; right:5px;" onclick="fechaNvagendamento()">
<span class="tt_pg"><b>Novo Agendamento</b></span><br><br>
<form action="#" method="post">
<input type="hidden" name="agenda" value="1">
<b>Especialidade</b> <select name="esp" id="esp" required onchange="lAt()">
<option value="">Selecione</option>
<?php 
$esp1 = mysql_query("select * from especialidades where situacao = 1 order by especialidade");
while($esp = mysql_fetch_assoc($esp1)){
	echo "<option value='$esp[id]'>$esp[especialidade]</option>";
}
?>
</select><br>
<div id="listaAtende">
<b>Atendente</b>
<select name="atendente" required>
<option value="">Selecione</option>
</select></div><br>
<b>Data</b><input type="text" size="10" name="ndata" class="date" required> <b>Início</b> <input type="text" name="hora" class="hora" size="4" required value="" >
<b>Término</b> <input type="text" name="hora_t" class="hora" size="4" required><br>
<input type="submit" value="Agendar"> <input type="button" value="Cancelar" onclick="fechaNvagendamento()">
</form>
</div>
<script>
troca(1);

function esconde(x){
if(document.getElementById(x).style.display == 'none'){
document.getElementById(x).style.display = 'block';
}
else{
document.getElementById(x).style.display = 'none';
}
}

function troca(n){
if(n==1){
document.getElementById('bt1').style.opacity = "1";
document.getElementById('bt2').style.opacity = "0.4";
document.getElementById('bt3').style.opacity = "0.4";
document.getElementById('bt4').style.opacity = "0.4";

document.getElementById('cli_agendamentos').style.display = "block";
document.getElementById('cli_compras').style.display = "none";
document.getElementById('cli_pagamentos').style.display = "none";
document.getElementById('cli_contatos').style.display = "none";

}
if(n==2){
document.getElementById('bt1').style.opacity = "0.4";
document.getElementById('bt2').style.opacity = "1";
document.getElementById('bt3').style.opacity = "0.4";
document.getElementById('bt4').style.opacity = "0.4";

document.getElementById('cli_agendamentos').style.display = "none";
document.getElementById('cli_compras').style.display = "block";
document.getElementById('cli_pagamentos').style.display = "none";
document.getElementById('cli_contatos').style.display = "none";
}
if(n==3){
document.getElementById('bt1').style.opacity = "0.4";
document.getElementById('bt2').style.opacity = "0.4";
document.getElementById('bt3').style.opacity = "1";
document.getElementById('bt4').style.opacity = "0.4";

document.getElementById('cli_agendamentos').style.display = "none";
document.getElementById('cli_compras').style.display = "none";
document.getElementById('cli_pagamentos').style.display = "block";
document.getElementById('cli_contatos').style.display = "none";
}
if(n==4){
document.getElementById('bt1').style.opacity = "0.4";
document.getElementById('bt2').style.opacity = "0.4";
document.getElementById('bt3').style.opacity = "0.4";
document.getElementById('bt4').style.opacity = "1";

document.getElementById('cli_agendamentos').style.display = "none";
document.getElementById('cli_compras').style.display = "none";
document.getElementById('cli_pagamentos').style.display = "none";
document.getElementById('cli_contatos').style.display = "block";
}
}

function lAt(){
var id = document.getElementById("esp").value;
$.getJSON('retespecialidade.php?tp=5&id='+id, function(atData){
	var nome = [];
	var idat = [];//id do atendente
	$(atData).each(function(key, value){
		nome.push(value.nome);
		idat.push(value.idat);
	});
	var atende = "<b>Atendente</b><select name='atendente' required><option value=''>Selecione</option>";
	nome.forEach(atenderao);
	function atenderao(at,i){
		if(at != ""){
		atende = atende+"<option value='"+idat[i]+"'>"+at+"</option>";
		}
	};
	atende = atende+"</select>";
	document.getElementById("listaAtende").innerHTML = atende;
});
}
function nvagendamento(cli){
	mostraMascara();			
	document.getElementById("agCliente").style.display = "block";
	}
function fechaNvagendamento(){
	escondeMascara();
	document.getElementById("agCliente").style.display = "none";
	}

function nvagendamento1(cli){
		mostraMascara();			
		link = 'nvagenda.php?cli='+cli;
		window.open(link, 'Historicos', 'STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=NO, TOP=200, LEFT=200, WIDTH=500, HEIGHT=400');	
	}
</script>
