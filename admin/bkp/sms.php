<?php
//Arquivo responsável pela comunicação com API de envio de sms BitWase
error_reporting(~E_ALL);
include 'conexao.php';
include 'config.php';

/*
TP - 	1 novo agendamento
	2 reagendamento
CAMPOS NECESARIO MODIFICAR
OP 1-Agendamento ... necessário nome,data e hora
#NOME#
#DATA#
#HORA#
*/
$tp = $_REQUEST[tp];
/*************************
tp 1 - novo agendamento
tp 2 - cancelamento
tp 3 - lembrete de pagamento
tp 4 - aniversário
tp 5 - callback
**************************/
$ag = $_REQUEST[ag];//agendamento programado
$id = $_REQUEST[id];


if($tp == 1){
//se for novo agendamento, pegar ID do último, pegar com base no cliente os dados necessáros para envio de sms
//pegar apenaso primeiro nome para envio.
$ua1 = mysql_query("select * from consultas where st = 0");//seleciona todos onde ainda está em zero.. processando

while($ua = mysql_fetch_assoc($ua1)){
$dd =mysql_fetch_assoc(mysql_query("select date_format(a.data,'%Y-%m-%d') as 'data', date_format(a.data,'%d/%m/%Y') as 'data2', date_format(a.data,'%H:%i') as 'hr', a.paciente, c.nome, c.tel2 from consultas a 
inner join clientes c on a.paciente = c.id
where a.id = $ua[id]"));

$mens = mysql_fetch_assoc(mysql_query("select * from tb_sms order by id desc limit 1"));//pegando dados da tb_sms o último

mysql_query("update tb_sms set ag = '$ua[id]' where id = '$mens[id]'");//atualiza para ID da consulta a qual se refere.. necessário para poder cancelar envios posteriormente

if($dd[tel2] == ""){
	mysql_query("delete from tb_sms where id = $mens[id]");//se não tiver cel cadastrado exclui da tb mensagem
}
else if($dd[tel2] != ""){//se telefone diferente de vazio
$nome = explode(" ",$dd[nome]);
$nome = $nome[0];//pegar somente primeiro nome

$dest = $dd[tel2]; 
$dest = str_replace("(","",$dest);
$dest = str_replace(")","",$dest);
$dest = str_replace("-","",$dest);


$sms = $mens[mensagem];
$sms = str_replace("#NOME#","$nome",$sms);
$sms = str_replace("#DATA#","$dd[data2]",$sms);
$sms = str_replace("#HORA#","$dd[hr]",$sms);
$sms = str_replace(" ","+",$sms);
//OK
//fazer update da mensagem no BD
mysql_query("update tb_sms set mensagem = '$sms' where id = '$mens[id]'") or die(mysql_error());
//comparar data atual com data de agendamento... se agendamento for inferior a data aual, alterar no bd
$ag = strtotime($mens[dt_en]);
$hj = strtotime(date('Y-m-d'));
if($ag < $hj){
	mysql_query("update tb_sms set dt_en = now() where id = '$mens[id]'");
}
//após alterar a mensagem, alterar a data de envio, neessário alterar a situação para 1 - agendado envio
mysql_query("update tb_sms set st = '1' where id = '$mens[id]'");

//feito todas as alterações, deve ser enviado estas informações para sms bitwase
$manda = mysql_fetch_assoc(mysql_query("select * from tb_sms where id = '$mens[id]'"));
//chamar via ajax
$url = "http://bitwase.com/sistemas/bw/sms.php?ac=1&c=$cnf_contrato&d=$dest&m=$manda[mensagem]&a=$manda[dt_ag]&e=$manda[dt_en]&i=$mens[id]";

echo $url;

//mandaServidor($url);
}//se tel2 diferente de vazio
}//enquanto for igual a zero
/*

http://portal.gtisms.com:2000/gti/API/send.aspx?user=wellington.santos@bitwase.com&senha=san13eto&msg=Bom+dia+Sandra,+só+lembrando+de+seu+agendamento+para+amanhã+12/08/2016+às+14:30.+Se+não+puder+comparecer,+favor+entrar+em+contato.+Sempre+Bella.&n=4299901711&id=2


Bom dia $nome, só lembrando de seu agendamento para amanhã $data2. Se não puder comparecer, favor entrar em contato. Sempre Bella.";

http://portal.gtisms.com:2000/gti/API/check.aspx?user=wellington.santos@bitwase.com&senha=san13eto&id=2

*/
}

if($tp == 2){
//se for tipo = 2...
//verificar se ainda está agendado..
//se estiver agendado, alerar para 3 cancelado
//retornar valor que enviará solicitação de cancelamento para bitwase
//página que chamou faz o cancelamento
mysql_query("update tb_sms set st = 3 where ag = $ag");
$mens = mysql_fetch_assoc(mysql_query("select * from tb_sms where ag = $ag"));//para pegar id da sms cliente
//$url = "http://bitwase.com/sistemas/bw/sms.php?ac=2&c=$cnf_contrato&i=$mens[id]";
//echo $url;
}

// TIPO 3 - LEMBRETE DE PAGaMENTO

if($tp == 3){//inserir email a ser enviado para lembrete de pagamento
//se for novo agendamento, pegar ID do último, pegar com base no cliente os dados necessáros para envio de sms
//pegar apenaso primeiro nome para envio.


$mens1 = mysql_query("select * from tb_sms where st = 0");//pegando dados do ultimo email registrado...

while($mens = mysql_fetch_assoc($mens1)){
$dd =mysql_fetch_assoc(mysql_query("select c.nome, c.tel2 from clientes c 
where c.id = $mens[cliente]"));

if($dd[tel2] == ""){
	mysql_query("delete from tb_sms where id = $mens[id]");//se não tiver email cadastrado exclui da tb email
}
else if($dd[tel2] != ""){//se email diferente de vazio
$nome = explode(" ",$dd[nome]);
$nome = $nome[0];//pegar somente primeiro nome

$dest = $dd[tel2]; 
$dest = str_replace("(","",$dest);
$dest = str_replace(")","",$dest);
$dest = str_replace("-","",$dest);

//mysql_query("update tb_sms set email = '$dest' where id = '$mens[id]'") or die(mysql_error());
$tipoM = substr($mens[ag],0,1);
$idF = substr($mens[ag],1);
if($tipoM == "F"){
$dd2 = mysql_fetch_assoc(mysql_query("select valor from financeiro where id = '$idF'"));
$valr = number_format($dd2[valor],2,'.','');
}
$sms = $mens[mensagem];
$sms = str_replace("#NOME#","$nome",$sms);
$sms = str_replace("#VALOR#","$valr",$sms);
$sms = str_replace(" ","+",$sms);

//OK
//fazer update da mensagem no BD
mysql_query("update tb_sms set mensagem = '$sms' where id = '$mens[id]'") or die(mysql_error());
//comparar data atual com data de agendamento... se agendamento for inferior a data aual, alterar no bd
$ag = strtotime($mens[dt_en]);
$hj = strtotime(date('Y-m-d'));
if($ag < $hj){
	mysql_query("update tb_sms set dt_en = now() where id = '$mens[id]'");
}
//após alterar a mensagem, alterar a data de envio, neessário alterar a situação para 1 - agendado envio
mysql_query("update tb_sms set st = '1' where id = '$mens[id]'");
//feito todas as alterações, deve ser enviado estas informações para sms bitwase
$manda = mysql_fetch_assoc(mysql_query("select * from tb_sms where id = '$mens[id]'"));

$url = "http://bitwase.com/sistemas/bw/sms.php?ac=1&c=$cnf_contrato&d=$dest&m=$manda[mensagem]&a=$manda[dt_ag]&e=$manda[dt_en]&i=$mens[id]";

echo $url;
}
}
}

if($tp == 4){//sms de aniversario


$mens1 = mysql_query("select * from tb_sms order by id desc limit 1");//pegando dados do ultimo email registrado...
while($mens = mysql_fetch_assoc($mens1)){
$dd =mysql_fetch_assoc(mysql_query("select c.nome, c.tel2 from clientes c 
where c.id = $mens[cliente]"));

if($dd[tel2] == ""){
	mysql_query("delete from tb_sms where id = $mens[id]");//se não tiver email cadastrado exclui da tb email
}
else if($dd[tel2] != ""){//se cel diferente de vazio
$nome = explode(" ",$dd[nome]);
$nome = $nome[0];//pegar somente primeiro nome

$dest = $dd[tel2]; 
$dest = str_replace("(","",$dest);
$dest = str_replace(")","",$dest);
$dest = str_replace("-","",$dest);

//mysql_query("update tb_sms set email = '$dest' where id = '$mens[id]'") or die(mysql_error());

$sms = $mens[mensagem];
$sms = str_replace("#NOME#","$nome",$sms);
$sms = str_replace(" ","+",$sms);

//OK
//fazer update da mensagem no BD
mysql_query("update tb_sms set mensagem = '$sms' where id = '$mens[id]'") or die(mysql_error());
//comparar data atual com data de agendamento... se agendamento for inferior a data aual, alterar no bd
$ag = strtotime($mens[dt_en]);
$hj = strtotime(date('Y-m-d'));
if($ag < $hj){
	mysql_query("update tb_sms set dt_en = now() where id = '$mens[id]'");
}
//após alterar a mensagem, alterar a data de envio, neessário alterar a situação para 1 - agendado envio
mysql_query("update tb_sms set st = '1' where id = '$mens[id]'");
//feito todas as alterações, deve ser enviado estas informações para sms bitwase
$manda = mysql_fetch_assoc(mysql_query("select * from tb_sms where id = '$mens[id]'"));

$url = "http://bitwase.com/sistemas/bw/sms.php?ac=1&c=$cnf_contrato&d=$dest&m=$manda[mensagem]&a=$manda[dt_ag]&e=$manda[dt_en]&i=$mens[id]";

echo $url;
}
}
}
if($tp == 5){//se tipo for 5, é pq deu certo e BW chamou a API
	mysql_query("update tb_sms set st = 2 where id = '$id'");
}

/*
if($acao == 1){
mysql_query("insert into tb_sms (dest,mensagem,cliente,dt_ag,dt_en,st) values ('$dest','$mensagem','$cliente','$dt_ag','$dt_env','1')");
}
*/
?>
