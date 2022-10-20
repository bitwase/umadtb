<?php
$nAlertas = 0;
//conta aniversariantes
$va = mysql_num_rows(mysql_query("select * from clientes where date_format(dt_nasc, '%d/%m') in (date_format(now(),'%d/%m'), date_format(date_add(now(), INTERVAL 1 day),'%d/%m'),date_format(date_add(now(), INTERVAL 2 day),'%d/%m'))"));
$nAlertas += $va;
//contas a receber
$cr = mysql_num_rows(mysql_query("select * from financeiro where date_format(dt_ag, '%Y-%m-%d') < date_format(now(),'%Y-%m-%d') and sit = 1 and tipo = 1"));
$nAlertas += $cr;
//contas a pagar vencidas
$cp = mysql_num_rows(mysql_query("select * from financeiro where date_format(dt_ag, '%Y-%m-%d') < date_format(now(),'%Y-%m-%d') and sit = 1 and tipo = 2"));
$nAlertas += $cp;
//estoque minimo
$em = mysql_num_rows(mysql_query("select * from produtos where qt < qtmin and st = 1"));
$nAlertas += $em;

## REGRAS PARA ENVIO DE SMS E EMAIL QUANDO ATRASADO A XX DIAS ##
//verificar se existe a receber com atraso de mais de XX DIAS
//pegar o tipo2 do ID em atraso..
//verificar quem é o cliente...
//registrar o envio do sms e chamar função para enviar imediatamente...

$qtSmsAlerta = mysql_num_rows(mysql_query("select * from financeiro where date_format(date_add(dt_ag,interval $cnf_dAtrasoSms day ), '%Y-%m-%d') < date_format(now(),'%Y-%m-%d') and sit = 1 and tipo = 1"));

if($qtSmsAlerta > 0){//mensagem de contas a pagar
//verifica se lembrete de pagamento está ativo
if($cnf_smsPagamento || $cnf_emailPagamento){//verifica se email ou sms de pagamento está ativo
//faz consulta no sistema de quais são
$aSmsAlerta = mysql_query("select * from financeiro where date_format(date_add(dt_ag,interval $cnf_dAtrasoSms day ), '%Y-%m-%d') < date_format(now(),'%Y-%m-%d') and sit = 1 and tipo = 1 AND (concat('F',id) not in(
select ag from email where ag like 'F%') OR concat('F',id) not in(
select ag from tb_sms where ag like 'F%'))");
while($mnd = mysql_fetch_assoc($aSmsAlerta)){
$dt = date("Y-m-d");
	//verifica quem é o destinatário
	//para isso, pegar o TIPO2, letra, e número... verificar de acordo com o tipo se é venda ou atendimento A ou V
	$aTp = substr($mnd[tipo2],0,1);//pega apenas primeiro carcter (tipo)
	$aNum = substr($mnd[tipo2],1);//pega segundo carcter em diante
	if($aTp == "A"){//se for A, procurar em consultas quem é o cliente
		$cli = mysql_fetch_assoc(mysql_query("select paciente as 'cli' from consultas where id = '$aNum'"));
		$Icli = $cli[cli];//cliente
	}
	else if($aTp == "V"){//se for V, procurar em VENDAS quem é o cliente
		$cli = mysql_fetch_assoc(mysql_query("select cliente as 'cli' from vendas where id = '$aNum'"));
		$Icli = $cli[cli];//cliente
	}
	$ag = "F".$mnd[id];//id financeiro
if($cnf_sms){//verifica se está habilitado para enviar sms
if($cnf_smsPagamento){//verifica se está habilitado para envio de sms lembrete de consulta
	mandaSmsA($cnf_mSmsPagamento,$Icli,$dt,$ag);
}
}
if($cnf_email){//verifica se permite enviar email
if($cnf_emailPagamento){//verificar se permite enviar email de consulta
	mandaEmailA($cnf_mEmailPagamento,$Icli,$dt,$ag);
}
}
}//enquanto...

// FUNÇÕES PARA ENVIO

/* FUNÇÃO PARA AGENDAMENTO DE ENVIO DE SMS */

}//se email OU sms ativo
}//se existir algo a ser enviado

if($va > 0){//se existir aniversariante

$agAno = date("Y");
if($cnf_smsAniversario || $cnf_emailAniversario){//se permitido envio de email OU sms
$ddAn = mysql_query("select * from clientes where 
date_format(dt_nasc, '%d/%m') = date_format(now(),'%d/%m')
AND 
(concat('AN',id,date_format(now(),'%Y')) not in(
     select ag from email where ag like 'AN%') 
 AND 
 concat('AN',id,date_format(now(),'%Y')) not in(
     select ag from tb_sms where ag like 'AN%')
)");

while($mAn = mysql_fetch_assoc($ddAn)){
$ag = "AN$mAn[id]$agAno";//AN12016
$dt = date('Y-m-d');
if($cnf_sms){//verifica se está habilitado para enviar sms
if($cnf_smsAniversario){//verifica se está habilitado para envio de sms de aniversario
	mandaSmsB($cnf_mSmsAniversario,$mAn[id],$dt,$ag);
}
}
if($cnf_email){//verifica se permite enviar email
if($cnf_emailAniversario){//verificar se permite enviar email de aniversario
	mandaEmailB($cnf_mEmailAniversario,$mAn[id],$dt,$ag);
}
}

}//while
}//se um ou outro
}// se existir aniversariante

function mandaSmsA($mensagem,$cliente,$dt_env,$ag){
//dt_env tem que subtrair 1 na inserção..
//dt_ag deve ser igual a data atual
//st neste momento deve ser 0
mysql_query("insert into tb_sms (mensagem,cliente,dt_ag,dt_en,ag,st) values ('$mensagem','$cliente',now(), DATE_SUB('$dt_env',INTERVAL 1 day),'$ag','0')");
//tp = 1 novo agendamento
//script abaixo, insere na tb do cliente, se retorno positivo, insere na tb bw
?>

<script>
$.ajax({
      url:'sms.php?tp=3',
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

function mandaEmailA($mensagem,$cliente,$dt_env,$ag){
mysql_query("insert into email (cliente,assunto,mensagem,data,dt_en,ag,st) 
values ('$cliente','Lembrete de Pagamento','$mensagem',now(), DATE_SUB('$dt_env',INTERVAL 1 day),'$ag','0')") or die(mysql_error());
//tp = 1 novo agendamento
//script abaixo, insere na tb do cliente, se retorno positivo, insere na tb bw
//seguir mesma logica de sms....
?>
<script>
$.ajax({
      url:'email.php?tp=3',
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

function mandaSmsB($mensagem,$cliente,$dt_env,$ag){
//dt_env tem que subtrair 1 na inserção..
//dt_ag deve ser igual a data atual
//st neste momento deve ser 0
mysql_query("insert into tb_sms (mensagem,cliente,dt_ag,dt_en,ag,st) values ('$mensagem','$cliente',now(), DATE_SUB('$dt_env',INTERVAL 1 day),'$ag','0')");
//tp = 1 novo agendamento
//script abaixo, insere na tb do cliente, se retorno positivo, insere na tb bw
?>

<script>
$.ajax({
      url:'sms.php?tp=4',
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

function mandaEmailB($mensagem,$cliente,$dt_env,$ag){
mysql_query("insert into email (cliente,assunto,mensagem,data,dt_en,ag,st) 
values ('$cliente','Feliz Aniversário','$mensagem',now(), DATE_SUB('$dt_env',INTERVAL 1 day),'$ag','0')") or die(mysql_error());
//tp = 1 novo agendamento
//script abaixo, insere na tb do cliente, se retorno positivo, insere na tb bw
//seguir mesma logica de sms....
?>
<script>
$.ajax({
      url:'email.php?tp=4',
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

## ENVIO DE EMAIL ##

## CHAMA PÁGINA PARA ENVIO DE EMAIL CASO EXISTA PENDENCIA ##

$mPen = mysql_num_rows(mysql_query("select * from email where st = 1 and dt_en <= now()"));

if($mPen > 0){
?>
<script>
$.ajax({
      url:'env.mail.php',
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

## ENVIO DE SMS ##
$mPenS = mysql_num_rows(mysql_query("select * from tb_sms where st = 1 and dt_en <= now()"));

if($mPenS > 0){
//enviar sms pela api gti
$mm1 = mysql_query("select s.*, c.tel2 from tb_sms s inner join clientes c on s.cliente = c.id where st = 1 and dt_en <= now()");

$gti_usr ="";//"teste@gtisms";//"wellington.santos@bitwase.com";
$gti_senha = "";//"san13eto";

while($mm = mysql_fetch_assoc($mm1)){
$sms = $mm[mensagem];//sms a ser enviado

$dest = $mm[tel2]; 
$dest = str_replace("(","",$dest);
$dest = str_replace(")","",$dest);
$dest = str_replace("-","",$dest);//destinatário

$idsms = "DEM".$mm[id];

//$url = "http://portal.gtisms.com:2000/gti/API/send.aspx?user=$gti_usr&senha=$gti_senha&msg=$sms&n=$dest&id=$idsms";
// ajax para enviar a sms
//colocar cURL para enviar para BW
?>
<script>
$.ajax({
      url:'<?php echo $url;?>',
      complete: function (response) {
//alert(response.responseText);		
      },
      error: function () {
         // alert('Erro');
      }
  });
</script>
<?php
//colocar cURL para enviar para BW

$urlbw = "http://bitwase.com/sistemas/bw/env.sms.php?d=$dest&m=$sms&c=$cnf_contrato&i=$idsms";

$ch = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt($ch, CURLOPT_URL, "$urlbw");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 2);

// Send the request & save response to $resp
$resp = curl_exec($ch);
// Close request to clear up some resources
curl_close($ch);
var_dump($resp);

//fazer update na tb_sms para alterar para 2 - enviado
mysql_query("update tb_sms set st = 2 where id = '$mm[id]'");

}
}

?>
