<?php
include 'seguranca.php';
/*
Gerar cron para poder rodar este script todos os dias às 7h.
-verificar com base no dia/mês atual, se existe aniversariante;
-para cada aniversariante, chamar a função que fará o envio do sms;
-junto com o envio, registrar os dados e horário do envio no banco de dados;
*/
//inserir na tb_cron
mysql_query("insert into tb_cron (data) values(now())");

$dia = date('d/m');

$va = mysql_query("select * from tb_inscritos where date_format(nascimento,'%d/%m') = '$dia' and cidade = 'Telêmaco Borba' and sit = 1");//pegando somente os ativos, nascidos no dia atual, de telêmaco borba
$vb = mysql_query("select * from tb_inscritos where date_format(nascimento,'%d/%m') = '$dia' and cidade = 'Telêmaco Borba' and sit = 1");//pegando somente os ativos, nascidos no dia atual, de telêmaco borba

$vc = mysql_query("select * from tb_inscritos where date_format(nascimento,'%d/%m') = '$dia' and cidade = 'Telêmaco Borba' and sit = 1");//pegando somente os ativos, nascidos no dia atual, de telêmaco borba
$vq = mysql_num_rows($va);//contando quantidade de aniversariantes...


//depois de enviar o sms para o aniversariante... insere na tb_push...
//push irá rodar 1h após o envio do sms para o jovem...
//grava para push
$push = "";

if($vq > 0){//se houver ao menos um...
while($vm = mysql_fetch_assoc($vc)){
	$push .= "$vm[nome] -";
}
$push .= "&notification_url=http://umadtb.bitwase.com/jovens";
$lk = "notification_title=Aniversariantes do Dia&notification_message=$push";

mysql_query("insert into tb_push (date,link,envio) value (now(),'$lk','0')");


while($v = mysql_fetch_assoc($vb)){
	$j = $v[id];//id do jovem
	$n = $v[tel2];//telefone 2 (celular)
	$n = str_replace("(","",$n);
	$n = str_replace(")","",$n);
	$n = str_replace("-","",$n);
	$n = str_replace(" ","",$n);
echo "Alerta 2";
mandasms($j,$n);//chama  a função
echo "Alerta 4";
}//fim while
}//fim se houver
//mandasms(1,41996826197);
function mandasms($j,$n){
	$mensagem = "Desejamos um Feliz Aniversário repleto de bênçãos, que todos seus projetos realizem com sucesso conforme à vontade do Senhor. Felicidades!!! Família UMADTB.";
	$mensagem = str_replace(" ","+",$mensagem);
echo "Alerta 3";
//inserir no banco de dados sms
//pegar os dados do banco pra ter o id
//o id vai ser umadtbZ

mysql_query("insert into tb_sms (jovem,data,num,mensagem) values('$j',now(),'$n','$mensagem')");
$rd = mysql_fetch_assoc(mysql_query("select * from tb_sms order by id desc limit 1"));//pegando dados do último 
$id = $rd[id];

//envio com curl
//$url = "http://portal.gtisms.com:2000/gti/API/send.aspx?user=wellington.santos@bitwase.com&senha=san13eto&msg=$mensagem&n=41996826197&id=$id";
//echo "<img src='http://portal.gtisms.com:2000/gti/API/send.aspx?user=wellington.santos@bitwase.com&senha=san13eto&msg=$mensagem&n=41996826197&id=$id'>";
//$url = "http://portal.gtisms.com:2000/gti/API/send.aspx?user=wellington.santos@bitwase.com&senha=san13eto&msg=$mensagem&n=41996826197&id=$id";
//echo "<meta http-equiv='refresh' content='0;url=$url'>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
//curl_setopt($ch, CURLOPT_URL, "http://portal.gtisms.com:2000/gti/API/send.aspx?user=wellington.santos@bitwase.com&senha=san13eto&msg=$mensagem&n=41996826197&id=$id");
//curl_setopt($ch, CURLOPT_URL, "http://portal.gtisms.com:100");
//curl_setopt($ch, CURLOPT_URL, "http://54.173.24.177/shortcode/api.ashx?action=sendsms&lgn=41996826197&pwd=141291&msg=$mensagem&numbers=$n");
//http://portal.gtisms.com/gti/API/send.aspx?user=wellington.santos@bitwase.com&senha=san13eto&msg=Teste+de+envio&n=41996826197&id=144
//curl_setopt($ch, CURLOPT_URL, "191.33.169.23:2000/gti/API/send.aspx?user=wellington.santos@bitwase.com&senha=san13eto&msg=Teste+de+envio&n=41996826197&id=144");
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


/*
$cURL = curl_init('http://portal.gtisms.com:2000/gti/API/send.aspx?user=wellington.santos@bitwase.com&senha=san13eto&msg=$mensagem&n=$n&id=$id');
  curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
  // Seguir qualquer redirecionamento que houver na URL
  curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
  $resultado = curl_exec($cURL);
  // Pega o código de resposta HTTP
  $resposta = curl_getinfo($cURL, CURLINFO_HTTP_CODE);
  curl_close($cURL);
  if ($resposta == '404') {
	mysql_query("update tb_sms set resultado = 'O site está fora do ar (ERRO 404)!' where id = '$id'");
	echo "deu ruim";
  } else {
	mysql_query("update tb_sms set resultado = 'Parece que deu boa' where id = '$id'");
	echo "deu boa";
	echo "$resposta";
  }
*/
//http://portal.gtisms.com:2000/gti/API/send.aspx?user=wellington.santos@bitwase.com&senha=san13eto&msg=Bom+dia+Sandra,+só+lembrando+de+seu+agendamento+para+amanhã+12/08/2016+às+14:30.+Se+não+puder+comparecer,+favor+entrar+em+contato.+Sempre+Bella.&n=4299901711&id=2
}//fim manda sms
?>
