<?php
//fluxo para receber dados de reagendamento....
/*
colocar regra para somente mostrar  aopção de "atender" caso o usuário logado seja o especialista que deve realizar o atendimento, ou filtrar por atendentes neste aplicativo
*/
$reagendar = $_POST[reagenda];
$id = $_POST[id];//id que será ajustado
$cancela = $_POST[cancela];

if($cancela == 1){
mysql_query("update consultas set situacao = 5 where id = '$id'");

$ap2 = mysql_query("select * from financeiro where tipo2 = 'A$id'");
while($ap3 = mysql_fetch_assoc($ap2)){
//alterar situação do pagamento para cancelado 3

mysql_query("update financeiro set sit = 3, obs = 'Atendimento cancelado. $ap3[obs]' where id= $ap3[id]");
}
//chamar função para cancelar sms
cancSms($id);
cancEmail($id);
//chamar função para cancelar email
}


if($reagendar == 1){

$dt = $_POST[ndata];//dd/mm/aaaa 01 34 6789
$data = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
$hora = $_POST[hora];
$hora_t = $_POST[hora_t];

$ag = mysql_fetch_assoc(mysql_query(
	"select a.id, date_format(a.data,'%d/%m/%Y %H:%i') as 'data', date_format(a.hr_fim,'%H:%i') as 'hr_fim', cl.nome as 'cliente', e.especialidade, at.nome as 'atendente', a.atendente as 'idat', a.paciente as 'idcli', a.especialidade as 'idesp' from consultas a 
	inner join clientes cl on a.paciente = cl.id
	inner join especialidades e on a.especialidade = e.id
	inner join atendentes at on a.atendente = at.id
	where a.id = $id"
	));

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


//pegar último id p/ ajustar financeiro
$ua = mysql_fetch_assoc(mysql_query("select max(id) as 'id' from consultas"));
$atu = $ua[id];

//regra para poder ajsutar pagamento...
$ap1 = mysql_query("select * from financeiro where tipo2 = 'A$id'");
while($ap = mysql_fetch_assoc($ap1)){
//inserir novos dados com ID atual na mesma situação que estava o anterior
//alterar situação do pagamento para cancelado 3
$mtv = explode('atendimento', $ap[motivo]);
$motivo = $mtv[0]."atendimento $atu";

mysql_query("insert into financeiro (data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
(now(),1,'A$ua[id]','$ap[valor]','$motivo','$cod_us','$ap[sit]','$ap[dt_ag]','Reagendamento do Atendimento $id - $ap[obs]')") or die(mysql_error());
mysql_query("update financeiro set sit = 3, obs = 'Atendimento reagendado para $atu. $ap[obs]' where id= $ap[id]");
}
//chamar função para cancelar sms
cancSms($id);
cancEmail($id);
//chamar função para cancelar email
//se envia sms ativo
if($cnf_sms){//verifica se está habilitado para enviar sms
if($cnf_smsConsulta){//verifica se está habilitado para envio de sms lembrete de consulta
	mandaSms($cnf_mSmsConsulta,$cliente,$data);
}
}
if($cnf_email){//verifica se permite enviar email
if($cnf_emailConsulta){//verificar se permite enviar email de consulta
	mandaEmail($cnf_mEmailConsulta,$cliente,$data);
}
}

	echo "<script>alert('Reagendamento Realizado.');
	</script>
<meta http-equiv='refresh' content='0;'>
";

}
}

//envios e cancelamentos de sms e email
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

##### CANCELAMENTOS #####
function cancSms($id){
//id - numero do agendamento atual que está sendo cancelado...
$qtSmsP = mysql_num_rows(mysql_query("select * from tb_sms where st = 1 and ag = $id"));
if($qtSmsP > 0){//se existir sms a ser enviado
$smsUrl = "sms.php?tp=2&ag=$id";
?>
<script>
$.ajax({
      url:'<?php echo $smsUrl;?>',
      complete: function (response) {
	//alert("SMS Cancelado.");
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
}// fim se existir sms a enviar
}//fim verifica sms

function cancEmail($id){
//id - numero do agendamento atual que está sendo cancelado...
$qtEmailP = mysql_num_rows(mysql_query("select * from email where st = 1 and ag = $id"));
if($qtEmailP > 0){
	mysql_query("update email set st = 3 where ag = $id");
}
}// fim função cancela email


?>
<span class="tt_pg"><b>Lista Agendamentos</b></span><br><br>
<p class="tt"><?php echo "$tt" ?></p>
<table id="example" class="display" width="100%"></table>

<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$con = mysql_query("select c.id,date_format(c.data,'%d/%m/%Y') as 'dt', c.hr_inicio as 'hr', upper(p.nome) as 'cliente', upper(m.nome) as 'atendente', c.situacao, upper(e.especialidade) as 'especialidade'
from consultas c
inner join clientes p on c.paciente = p.id
inner join atendentes m on c.atendente = m.id
inner join especialidades e on c.especialidade = e.id");

while($cn = mysql_fetch_assoc($con)){
switch($cn[situacao]){
	case 1:
		$st = "Agendado";
		$rea = "<a href=\'#\' onclick=\'reagendar($cn[id])\' title=\'Reagendar\'><img src=\'arquivos/icones/1112.png\' class=\'bt_p\' ></a>";
		$rem = "<a href=\'#\' onclick=\'cancAgenda($cn[id])\' title=\'Cancelar Agendamento\'><img src=\'arquivos/icones/116.png\' class=\'bt_p\' ></a>";
		$ate = "<a href=\'index.php?pg=atende&id=$cn[id]\' title=\'Atendimento\'><img src=\'arquivos/icones/1111.png\' class=\'bt_p\'></a>";
		
		break;
	case 2:
		$st = "Reagendado";
		$rea = "";
		$ate = "";
		$rem = "";
		break;
	case 3:
		$st = "Em Andamento";
		$rea = "";
		$rem = "";
		$ate = "<a href=\'index.php?pg=atende&id=$cn[id]\' title=\'Atendimento\'><img src=\'arquivos/icones/1111.png\' class=\'bt_p\'></a>";
		break;
	case 4:
		$st = "Realizado";
		$rea = "";
		$rem = "";
		$ate = "<a href=\'index.php?pg=atende&id=$cn[id]\' title=\'Atendimento\'><img src=\'arquivos/icones/1111.png\' class=\'bt_p\'></a>";
		break;
	case 5:
		$st = "Cancelado";
		$rea = "";
		$ate = "";
		$rem = "";
		break;
}
	echo "
	['$cn[id]','$cn[dt] $cn[hr]','$cn[cliente]','$cn[atendente]','$cn[especialidade]','$st','$rea $rem $ate'],
	";
}
?>
];
 
$(document).ready(function() {
    $('#example').DataTable( {
	 "scrollX": true,
         "paging":         false,
		data: dataSet,
	"order": [0,'desc'],
        columns: [
            { title: "ID" },
	    { title: "Data" },
            { title: "Cliente" },
            { title: "Atendente" },
            { title: "Especialidade" },
            { title: "Status" },
            { title: "Atendimento" }
        ]
    } );
} );
</script>
<div id="reagendar" style="display:none;">

</div>

<div id="cancAgenda" style="display:none;">

</div>
<script>
function reagendar(id){
	mostraMascara();
$.getJSON('retreagendar.php?id='+id, function(reagendaData){
	var data = [];
	var hrf = [];
	var cli = [];
	var esp = [];
	var atendente = [];
	
	
	$(reagendaData).each(function(key, value){
		data.push(value.data);
		hrf.push(value.hr_fim);
		cli.push(value.cliente);
		esp.push(value.especialidade);
		atendente.push(value.atendente);
	});
	
	//escrever os dados
	var form = "<form action='#' method='POST'><input type='hidden' name='reagenda' value='1'><input type='hidden' name='id' value='"+id+"'><b>Nova Data</b> <input type='text' name='ndata' size='10' class='date' required><br><b>Hora Início</b> <input type='text' name='hora' class='hora' id='dt1' size='4' required ><br><b>Hora Término</b> <input type='text' name='hora_t' class='hora' id='dt1' size='4' ><br><br><input type='submit' value='Salvar'><input type='button' value='Cancelar' onclick='canReagendar()'></form>";
	document.getElementById("reagendar").innerHTML = "<span class='tt_pg'><b>Reagendamento</b></span><br>Dados Atuais<br><b>Data</b>: "+data+" - "+hrf+"<br><b>Cliente</b>: "+cli+"<br><b>Especialidade</b>: "+esp+"<br><b>Atendente</b>: "+atendente+"<br><br>"+form;
});
	document.getElementById("reagendar").style.display= "block";

//abaixo para chamar as funções para permitir alterar

}
function canReagendar(){
	escondeMascara();
	document.getElementById("reagendar").style.display = "none";
}
function cancAgenda(id){
	mostraMascara();
$.getJSON('retreagendar.php?id='+id, function(reagendaData){
	var data = [];
	var hrf = [];
	var cli = [];
	var esp = [];
	var atendente = [];
	
	
	$(reagendaData).each(function(key, value){
		data.push(value.data);
		hrf.push(value.hr_fim);
		cli.push(value.cliente);
		esp.push(value.especialidade);
		atendente.push(value.atendente);
	});
	
	//escrever os dados
	var form = "<form action='#' method='POST'><input type='hidden' name='cancela' value='1'><input type='hidden' name='id' value='"+id+"'><b>Deseja Cancelar Este Agendamento?</b><br> <input type='submit' value='Sim'> <input type='button' value='Não' onclick='canReagendar()'></form>";
	document.getElementById("reagendar").innerHTML = "<span class='tt_pg'><b>Reagendamento</b></span><br>Dados Atuais<br><b>Data</b>: "+data+" - "+hrf+"<br><b>Cliente</b>: "+cli+"<br><b>Especialidade</b>: "+esp+"<br><b>Atendente</b>: "+atendente+"<br><br>"+form;
});
	document.getElementById("reagendar").style.display= "block";

//abaixo para chamar as funções para permitir alterar

}
</script>
