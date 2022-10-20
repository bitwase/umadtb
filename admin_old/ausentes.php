<?php
include 'seguranca.php';
/*
Gerar cron para poder rodar este script todos os dias às 7h.
-verificar com base no dia atual, se existe ausencia do dia anterior;
-para cada ausencia, chamar a função que fará o envio do sms;
-junto com o envio, registrar os dados e horário do envio no banco de dados;
*/
//inserir na tb_cron
mysql_query("insert into tb_cron (data) values(now())");

$dia = date('d/m');
//$va = mysql_query("select * from tb_ausente where data = date_sub(date_format(now(),'%Y-%m-%d'), interval 1 day)");//pegando todos os ausentes do dia anterior
$va = mysql_query("select * from tb_ausente where envio = 0");//pegando todos os ausentes do dia anterior

//$vb = mysql_query("select a.id, a.jovem, i.tel2 from tb_ausente a inner join tb_inscritos i on a.jovem = i.id where a.data = date_sub(date_format(now(),'%Y-%m-%d'), interval 1 day)");//pegando todos os ausentes do dia anterior
$vb = mysql_query("select a.id, a.jovem, i.tel2 from tb_ausente a inner join tb_inscritos i on a.jovem = i.id where a.envio = 0");//pegando todos os ausentes sem envio
$vq = mysql_num_rows($va);//contando quantidade 

if($vq > 0){//se houver ao menos um...
while($v = mysql_fetch_assoc($vb)){
	$j = $v[jovem];//id do jovem
	$n = $v[tel2];//telefone 2 (celular)
	$n = str_replace("(","",$n);
	$n = str_replace(")","",$n);
	$n = str_replace("-","",$n);
	$n = str_replace(" ","",$n);

$idmen = $v[id];

mandasms($j,$n);//chama  a função
//mysql_query("update tb_ausente set envio = 1, dataEnvio = now() where id = '$v[id]'");
mysql_query("update tb_ausente set envio = 1, dataEnvio = now() where id = $idmen");
}//fim while
}//fim se houver
//mandasms(1,41996826197);
function mandasms($j,$n){
	$mensagem = "SENTIMOS SUA FALTA NO CULTO. VOCÊ É MUITO IMPORTANTE PARA NÓS E ESPERAMOS PODER TE VER NO NOSSO PRÓXIMO CULTO. DEUS TE ABENÇOE! FAMÍLIA UMADTB.";
	$mensagem = str_replace(" ","+",$mensagem);
//inserir no banco de dados sms
//pegar os dados do banco pra ter o id
//o id vai ser umadtbZ

mysql_query("insert into tb_sms (jovem,data,num,mensagem) values('$j',now(),'$n','$mensagem')");
$rd = mysql_fetch_assoc(mysql_query("select * from tb_sms order by id desc limit 1"));//pegando dados do último 
$id = $rd[id];

$ch = curl_init();
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
//curl_setopt($ch, CURLOPT_URL, "http://54.173.24.177/shortcode/api.ashx?action=sendsms&lgn=41996826197&pwd=141291&msg=$mensagem&numbers=$n");
curl_setopt($ch, CURLOPT_URL, "http://api.gtisms.com/gti/API/send.aspx?user=wellington.santos@bitwase.com&senha=san13eto&msg=$mensagem&n=$n&id=$id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_ENCODING, "");
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);
//curl_setopt($ch, CURLINFO_HEADER_OUT, true);

//CURLOPT_FOLLOWLOCATION => true,

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