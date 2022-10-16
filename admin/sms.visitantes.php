<?php
include 'seguranca.php';
/*
Gerar cron para poder rodar este script todos os dias às 9h.
-verificar com na base de dados de visitante se há algum que não foi enviado sms;
-para cada um que houver, insere na tb_sms e envia;
-junto com o envio, registrar os dados e horário do envio no banco de dados;
*/
//inserir na tb_cron
mysql_query("insert into tb_cron (data) values(now())");

$dia = date('d/m');
//selecionar da tabela visitantes tb_visitaVisitantes
$va = mysql_query("select * from tb_visitasVisitantes where envio = 0");
$vb = mysql_query("select v.*, d.tel2 from tb_visitasVisitantes v
inner join tb_visitantes d on v.jovem = d.id where v.envio = 0");
$vq = mysql_num_rows($va);//contando quantidade de visitantes...

if($vq > 0){//se houver ao menos um...
while($v = mysql_fetch_assoc($vb)){
	$j = $v[jovem];//id do jovem
	$n = $v[tel2];//telefone 2 (celular)
	$n = str_replace("(","",$n);
	$n = str_replace(")","",$n);
	$n = str_replace("-","",$n);
	$n = str_replace(" ","",$n);
echo "Alerta 2";
mandasms2($j,$n);//chama  a função
mandasms1($j,$n);//chama  a função

mysql_query("update tb_visitasVisitantes set envio = 1, dataEnvio = now() where id = $v[id]");
echo "Alerta 4";
}//fim while
}//fim se houver
//mandasms(1,41996826197);
function mandasms1($j,$n){
	$mensagem = "Muito obrigado pela visita e volte sempre. A Familia UMADTB  Esta de braços abertos para te receber de novo...";
	$mensagem = str_replace(" ","+",$mensagem);
echo "Alerta 3";

mysql_query("insert into tb_sms (jovem,data,num,mensagem) values('$j',now(),'$n','$mensagem')");
$rd = mysql_fetch_assoc(mysql_query("select * from tb_sms order by id desc limit 1"));//pegando dados do último 
$id = $rd[id];

$ch = curl_init();
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_URL, "http://api.gtisms.com/gti/API/send.aspx?user=wellington.santos@bitwase.com&senha=san13eto&msg=$mensagem&n=$n&id=$id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_ENCODING, "");
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);

curl_exec($ch);
$resposta = curl_getinfo($ch);

echo  "<pre>";
print_r($resposta);
echo  "</pre>";
var_dump(curl_getinfo($ch,CURLINFO_HEADER_OUT));
echo "<br>";
print curl_error($ch);

}//fim manda sms parte 1

function mandasms2($j,$n){
	$mensagem = "...Se precisar de algo estamos a disposicao 42 99835-4418 OU 99128 0743. Deus abencoe.";
	$mensagem = str_replace(" ","+",$mensagem);
echo "Alerta 3";

mysql_query("insert into tb_sms (jovem,data,num,mensagem) values('$j',now(),'$n','$mensagem')");
$rd = mysql_fetch_assoc(mysql_query("select * from tb_sms order by id desc limit 1"));//pegando dados do último 
$id = $rd[id];

$ch = curl_init();
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_URL, "http://api.gtisms.com/gti/API/send.aspx?user=wellington.santos@bitwase.com&senha=san13eto&msg=$mensagem&n=$n&id=$id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_ENCODING, "");
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);

curl_exec($ch);
$resposta = curl_getinfo($ch);

echo  "<pre>";
print_r($resposta);
echo  "</pre>";
var_dump(curl_getinfo($ch,CURLINFO_HEADER_OUT));
echo "<br>";
print curl_error($ch);

}//fim manda sms
?>