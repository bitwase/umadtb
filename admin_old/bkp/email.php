<?php
//Arquivo responsável pela comunicação com API de envio de sms BitWase
error_reporting(~E_ALL);
include 'conexao.php';
include 'config.php';

/*
TP - 	1 novo agendamento
	2 reagendamento
	3 -
CAMPOS NECESARIO MODIFICAR
OP 1-Agendamento ... necessário nome,data e hora
#NOME#
#DATA#
#HORA#
*/
$tp = $_REQUEST[tp];

if($tp == 1){
//se for novo agendamento, pegar ID do último, pegar com base no cliente os dados necessáros para envio de sms
//pegar apenaso primeiro nome para envio.
$ua = mysql_fetch_assoc(mysql_query("select max(id) as 'id' from consultas"));

$dd =mysql_fetch_assoc(mysql_query("select date_format(a.data,'%Y-%m-%d') as 'data', date_format(a.data,'%d/%m/%Y') as 'data2', date_format(a.data,'%H:%i') as 'hr', a.paciente, c.nome, c.email from consultas a 
inner join clientes c on a.paciente = c.id
where a.id = $ua[id]"));

$mens = mysql_fetch_assoc(mysql_query("select * from email order by id desc limit 1"));//pegando dados do ultimo email registrado...

mysql_query("update email set ag = '$ua[id]' where id = '$mens[id]'");//atualiza para ID da consulta a qual se refere.. necessário para poder cancelar envios posteriormente

if($dd[email] == ""){
	mysql_query("delete from email where id = $mens[id]");//se não tiver email cadastrado exclui da tb email
}
else if($dd[email] != ""){//se email diferente de vazio
$nome = explode(" ",$dd[nome]);
$nome = $nome[0];//pegar somente primeiro nome

$dest = $dd[email]; 

mysql_query("update email set email = '$dest' where id = '$mens[id]'") or die(mysql_error());

$sms = $mens[mensagem];
$sms = str_replace("#NOME#","$nome",$sms);
$sms = str_replace("#DATA#","$dd[data2]",$sms);
$sms = str_replace("#HORA#","$dd[hr]",$sms);

//OK
//fazer update da mensagem no BD
mysql_query("update email set mensagem = '$sms' where id = '$mens[id]'") or die(mysql_error());
//comparar data atual com data de agendamento... se agendamento for inferior a data aual, alterar no bd
$ag = strtotime($mens[dt_en]);
$hj = strtotime(date('Y-m-d'));
if($ag < $hj){
	mysql_query("update email set dt_en = now() where id = '$mens[id]'");
}
//após alterar a mensagem, alterar a data de envio, neessário alterar a situação para 1 - agendado envio
mysql_query("update email set st = '1' where id = '$mens[id]'");

}
}

if($tp == 3){//inserir email a ser enviado para lembrete de pagamento
//se for novo agendamento, pegar ID do último, pegar com base no cliente os dados necessáros para envio de sms
//pegar apenaso primeiro nome para envio.


$mens = mysql_fetch_assoc(mysql_query("select * from email order by id desc limit 1"));//pegando dados do ultimo email registrado...

$dd =mysql_fetch_assoc(mysql_query("select c.nome, c.email from clientes c 
where c.id = $mens[cliente]"));

if($dd[email] == ""){
	mysql_query("delete from email where id = $mens[id]");//se não tiver email cadastrado exclui da tb email
}
else if($dd[email] != ""){//se email diferente de vazio
$nome = explode(" ",$dd[nome]);
$nome = $nome[0];//pegar somente primeiro nome

$dest = $dd[email]; 

mysql_query("update email set email = '$dest' where id = '$mens[id]'") or die(mysql_error());
$tipoM = substr($mens[ag],0,1);
$idF = substr($mens[ag],1);
if($tipoM == "F"){
$dd2 = mysql_fetch_assoc(mysql_query("select valor from financeiro where id = '$idF'"));
$valr = number_format($dd2[valor],2,'.','');
}
$sms = $mens[mensagem];
$sms = str_replace("#NOME#","$nome",$sms);
$sms = str_replace("#VALOR#","$valr",$sms);

//OK
//fazer update da mensagem no BD
mysql_query("update email set mensagem = '$sms' where id = '$mens[id]'") or die(mysql_error());
//comparar data atual com data de agendamento... se agendamento for inferior a data aual, alterar no bd
$ag = strtotime($mens[dt_en]);
$hj = strtotime(date('Y-m-d'));
if($ag < $hj){
	mysql_query("update email set dt_en = now() where id = '$mens[id]'");
}
//após alterar a mensagem, alterar a data de envio, neessário alterar a situação para 1 - agendado envio
mysql_query("update email set st = '1' where id = '$mens[id]'");
}
}

if($tp == 4){//aniversário.
//pegar nome, dados de contato

$mens = mysql_fetch_assoc(mysql_query("select * from email order by id desc limit 1"));//pegando dados do ultimo email registrado...

$dd =mysql_fetch_assoc(mysql_query("select c.nome, c.email from clientes c 
where c.id = $mens[cliente]"));

if($dd[email] == ""){
	mysql_query("delete from email where id = $mens[id]");//se não tiver email cadastrado exclui da tb email
}
else if($dd[email] != ""){//se email diferente de vazio
$nome = explode(" ",$dd[nome]);
$nome = $nome[0];//pegar somente primeiro nome

$dest = $dd[email]; 

mysql_query("update email set email = '$dest' where id = '$mens[id]'") or die(mysql_error());

$sms = $mens[mensagem];
$sms = str_replace("#NOME#","$nome",$sms);
//$sms = str_replace("#VALOR#","$valr",$sms);

//OK
//fazer update da mensagem no BD
mysql_query("update email set mensagem = '$sms' where id = '$mens[id]'") or die(mysql_error());
//comparar data atual com data de agendamento... se agendamento for inferior a data aual, alterar no bd
$ag = strtotime($mens[dt_en]);
$hj = strtotime(date('Y-m-d'));
if($ag < $hj){
	mysql_query("update email set dt_en = now() where id = '$mens[id]'");
}
//após alterar a mensagem, alterar a data de envio, neessário alterar a situação para 1 - agendado envio
mysql_query("update email set st = '1' where id = '$mens[id]'");
}
}

?>
