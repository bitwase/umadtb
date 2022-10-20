<?php
include 'conexao.php';

//Procurar os aniversariantes do dia
//procurar onde fazem aniversario no dia, e são do setor 2
$la = mysql_query("select *, date_format(nascimento,'%Y') as 'ano' from tb_inscritos where date_format(nascimento,'%m-%d') = date_format(now(),'%m-%d') and setor = '2 - Uvaranas'");
$an = "";
$q = 0;
while($l = mysql_fetch_assoc($la)){
	$atual = date("Y");//ano atual
	$nasc = $l[ano];
	$idade = $atual-$nasc;
	$an .= "*$l[nome]* - $idade anos \n";
	$q++;
}
$an = substr($an,0,-2);
//01/04 - cleiton | 28/11 rafael

function sendMessage($an) {
	
	$tk = "64D0656030F893A09B0E82D8FEE7AC20";
	$id_envio = date("dmYHis");
	$numeros = "41996826197,42998354418";
	
	$mens = "*Aniversariantes do Dia - UMADPG Uvaranas* \n\n";
	$mens .= "$an";
	echo "... $mens ..."; 
	$mens = str_replace("á","a",$mens);
	$mens = str_replace("à","a",$mens);
	$mens = str_replace("â","a",$mens);
	$mens = str_replace("ã","a",$mens);
	$mens = str_replace("é","e",$mens);
	$mens = str_replace("è","e",$mens);
	$mens = str_replace("ê","e",$mens);
	$mens = str_replace("ì","i",$mens);
	$mens = str_replace("í","i",$mens);
	$mens = str_replace("ó","o",$mens);
	$mens = str_replace("ò","o",$mens);
	$mens = str_replace("ô","o",$mens);
	$mens = str_replace("õ","o",$mens);
	$mens = str_replace("ú","u",$mens);
	$mens = str_replace("ù","u",$mens);
	$mens = str_replace("û","u",$mens);
	$mens = str_replace("ü","u",$mens);
	$mens = str_replace("ç","c",$mens);

	$cr = curl_init();
	//definindo a url de busca 
	curl_setopt($cr, CURLOPT_URL, "http://api.gtisms.com/rest/api/WA/EnviarWA");
	//definindo a url de busca 
	curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
	//definino que o método de envio, será POST
	curl_setopt($cr, CURLOPT_POST, TRUE);
	//definindo os dados que serão enviados
	curl_setopt($cr, CURLOPT_POSTFIELDS, "email=wellington.santos@bitwase.com&token=$tk&id=wa$id_envio&numeros=$numeros&mensagem=$mens");
	 
	//definindo uma variável para receber o conteúdo da página...
	$retorno = curl_exec($cr);
	 
	//fechando-o para liberação do sistema.
	$erro = curl_error($cr);
	curl_close($cr); //fechamos o recurso e liberamos o sistema...

}
echo "$q";//1987-04-01
if($q > 0){
$response = sendMessage($an);
}
?>