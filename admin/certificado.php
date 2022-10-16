<?php
/*Necsesário filtrar somente os pagos, separando por regional, ordenando por congregação...
select * from tb_inscritos where st = 2 or st = 3 group by regional order by congregacao,nome
*/
error_reporting(~E_ALL);
include 'seguranca.php';
//usar daqui pra baixo....

$fl = $_REQUEST[fl];//recebe tamanho de folha
$gerar = $_POST[gerar];
$regional = $_POST[regional];
$ev = $_REQUEST[ev];//evento, recebido por parâmetro
$cn = $_REQUEST[cn];

$t = $_REQUEST[t];//tipo..se for 1, só selecionados
if($t == 1){
	//receber aqui os ids dos inscritos
	$ids = $_REQUEST[id];
	$fti = " and ins.inscrito in($ids)";
}

if($regional != ""){
	$ft = " and regional = '$regional'";
}

//recebe tamanho

//if($gerar){
//colocar aqui um while pra listar todos...
//selecionar todos os inscritos, agrupado por regional...
//$si1 = mysql_query("select distinct regional from tb_inscritos where st > 1 $ft ") or die (mysql_error());
//enquanto houver... passar por parâmetro para a função...
//while($si = mysql_fetch_assoc($si1)){
//	$rg = $si[regional];//nome da regional...
	certificado($fl,$ev,$cn,$fti,$t);
//}
//}//fim dados preenchidos

function certificado($fl,$ev,$cn,$fti,$t){//rg = regional
if($fl == ""){//se não for informado se é A4 ou A3, pegar por padrão A4
$fl = 'A4';
}

if($cn != ""){
	$ftcn = "AND i.congregacao = '$cn'";
}

if($t == ""){
	$m1=mysql_query("
	select i.* from tb_inscricao ins inner join tb_inscritos i on ins.inscrito = i.id where ins.pg is null and ins.evento = $ev $ftcn order by nome
	");
}
if($t == "1"){
	
	$m1=mysql_query("
	select i.* from tb_inscricao ins inner join tb_inscritos i on ins.inscrito = i.id where ins.evento = $ev $ftcn $fti order by nome
	");
}
//$m = mysql_fetch_assoc($m1);
$nome="certificados_$cn.pdf";//certificados_nome_regional...
//$data = $m[fim];
/*
switch($data[3].$data[4]){
case 1:
$mes="Janeiro";
break;
case 2:
$mes="Fevereiro";
break;
case 3:
$mes="Março";
break;
case 4:
$mes="Abril";
break;
case 5:
$mes="Maio";
break;
case 6:
$mes="Junho";
break;
case 7:
$mes="Julho";
break;
case 8:
$mes="Agosto";
break;
case 9:
$mes="Setembro";
break;
case 10:
$mes="Outubro";
break;
case 11:
$mes="Novembro";
break;
case 12:
$mes="Dezembro";
break;
} */
include('arquivos/mpdf/mpdf.php');//arquivo na pasta arquivos gerar da intranet

if($fl == 'A4'){//adiciona esta parte apenas 1x

$mpdf = new mPDF(
'',    // mode - default ''
'A4',    // format - A4, for example, default ''
 20,     // font size - default 0
 '',    // default font family
 15,    // margin_left
 15,    // margin right
 16,     // margin top
 16,    // margin bottom
 9,     // margin header
 9,     // margin footer
 'L');  // L - landscape, P - portrait
}

if($fl == 'A3'){
$mpdf = new mPDF(
'',    // mode - default ''
'A3',    // format - A4, for example, default ''
 20,     // font size - default 0
 '',    // default font family
 15,    // margin_left
 15,    // margin right
 16,     // margin top
 16,    // margin bottom
 9,     // margin header
 9,     // margin footer
 'L');  // L - landscape, P - portrait
//$mpdf = new mPDF();
}//fim se for A3 

if($fl == 'A4'){
//$mpdf = new mPDF();
while($m = mysql_fetch_assoc($m1)){
$mpdf->AddPage('L');
//$mpdf->Image('arquivos/imagens/certificado.jpg',-1,0,298,210,'jpg','',true, false);
//dados que deverão sair no certificado...

//ajustar nome para tamanho correto...
$nom = "";
//$nm = mb_strtoupper($m[nome]);//transforma tudo em minúsculo...
$nm = mb_strtolower($m[nome]);//transforma tudo em minúsculo...
$nm1 = explode(" ",$nm);
foreach($nm1 as $n){
	if($n != "do" && $n != "dos" && $n != "da" && $n != "das" && $n != "de" && $n != "del"){
		$n = ucfirst($n);
		$nom .= "$n ";//adiciona $n(com primeira maiúsculo e um espaço no final...
	}
	else{
		$nom .= "$n ";//adiciona $n(com primeira maiúsculo e um espaço no final...
	}
}
//linhas abaixo se tiver menos de 28 caracteres no nome...
//$nome1 = "<tocentry content='A4 landscape' />
//<div style='position:absolute;text-align:left;left:400px;top:326px;width:570px;font-family:Arial Black;font-size:24;text-indent: 2.0em;'> <b>$nom</b></div>";
$nome1 = "<tocentry content='A4 landscape' />
<div style='position:absolute;text-align:left;left:490px;top:385px;width:553px;font-family:Arial Black;font-size:24;text-indent: 2.0em;'> <b>$nom</b></div>";
######### INSERIR NA tb_certificado ##########
mysql_query("insert into tb_certificado (inscrito,data,evento) values ('$m[id]',now(),'$ev')");
####### INSERE DADOS NA PÁGINA...
$mpdf->SetXY(40, 70);
//$mpdf->WriteHTML($msg);
//$mpdf->WriteHTML($dt);
//$mpdf->WriteHTML($ass);
$mpdf->WriteHTML($nome1);
//$mpdf->WriteHTML($nome2);

}//fim enquanto...
}

if($fl == 'A3'){
//$mpdf = new mPDF();
while($m = mysql_fetch_assoc($m1)){
$mpdf->AddPage('L');
//$mpdf->Image('arquivos/imagens/certificado.jpg',-1,0,421,297,'jpg','',true, false);
//dados que deverão sair no certificado...

//ajustar nome para tamanho correto...
$nom = "";
$nm = mb_strtolower($m[nome]);//transforma tudo em minúsculo...
$nm1 = explode(" ",$nm);
foreach($nm1 as $n){
	if($n != "do" && $n != "dos" && $n != "da" && $n != "das" && $n != "de" && $n != "del"){
		$n = ucfirst($n);
		$nom .= "$n ";//adiciona $n(com primeira maiúsculo e um espaço no final...
	}
	else{
		$nom .= "$n ";//adiciona $n(com primeira maiúsculo e um espaço no final...
	}
}

$dt = "<div style='position:absolute;bottom:472px;text-align:right;right:155px;font-size:28;'>Curitiba, $data[0]$data[1] de $mes de $data[6]$data[7]$data[8]$data[9].</div>";

$emp = "<div style='position:absolute;bottom:155px;text-align:left;right:211px;font-size:20;'>
Serdia - Eletrônica Industrial Ltda.<br>
Rua José Altair Possebom, 435 - CIC<br>
Fone: (41)3239-8888<br>
CEP 81270-185 - Curitiba - PR
</div>";

$ass = "<div style='position:absolute;bottom:155px;text-align:center;left:211px;font-size:26;'>
_________________________________________<br>
Instrutor<br><br>
_________________________________________<br>
Gerência<br><br>
_________________________________________<br>
Diretoria<br>

</div>";

$msg = "
<div style='position:absolute;text-align:justify;top:390px;left:211px;width:1135px;font-size:25;text-indent: 2.0em;'>A Empresa <b>SERDIA ELETRÔNICA INDUSTRIAL LTDA</b>, certifica  o (a) funcionário (a) <b>$nom</b>, que realizou no período de $m[inicio] à $m[fim] o <b>$m[titulo]</b>. Com duração de $m[tempo] HORAS.</div>";

####### INSERE DADOS NA PÁGINA...

$mpdf->SetXY(40, 70);
$mpdf->WriteHTML($msg);
$mpdf->WriteHTML($dt);
$mpdf->WriteHTML($ass);
$mpdf->WriteHTML($emp);

}//fim enquanto...
}
/*
if($fl == 'A3'){
$mpdf = new mPDF(
'',    // mode - default ''
'A3',    // format - A4, for example, default ''
 20,     // font size - default 0
 '',    // default font family
 15,    // margin_left
 15,    // margin right
 16,     // margin top
 16,    // margin bottom
 9,     // margin header
 9,     // margin footer
 'L');  // L - landscape, P - portrait
//$mpdf = new mPDF();
$mpdf->AddPage('L');
$mpdf->Image('arquivos/imagens/certificado.jpg',-1,0,421,297,'jpg','',true, false);
}//fim se for A3
*/
//$mpdf->SetProtection(array('copy','print'), 'serdia', 'ser1988');
$mpdf->Output($nome,d);
//$mpdf->Output("certificados/$nome"); //salvar na pasta...

}//fim função certificado
?>
<form action='#' method='POST' style='margin:0 auto;height:100px;width:200px;background:#fff;'>
<b>Emissão de Certificados</b><br>
<b>Regional:</b><select name='regional'>
<option value=''>SELECIONE</option>
<option value='BAIRRO NOVO'>BAIRRO NOVO</option>
<option value='BOA VISTA'>BOA VISTA</option>
<option value='BOQUEIRÃO'>BOQUEIRÃO</option>
<option value='CAJURU'>CAJURU</option>
<option value='CIC'>CIC</option>
<option value='MATRIZ'>MATRIZ</option>
<option value='PINHEIRINHO'>PINHEIRINHO</option>
<option value='PORTÃO'>PORTÃO</option>
<option value='STA FELICIDADE'>STA FELICIDADE</option>
<option value='TATUQUARA'>TATUQUARA</option>
<option value='OUTRA CIDADE'>OUTRA CIDADE</option>
</select>
<input type='hidden' name='gerar' value='1'>
<!--b>Tamanho da Folha</b><br>
<input type='radio' name='fl' value='A4' id='A4' required><label for='A4'>A4</label> 
<input type='radio' name='fl' value='A3' id='A3' ><label for='A3'>A3</label><br-->
<input type='submit' value='Gerar'>
</form>
