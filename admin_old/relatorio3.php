<?php
error_reporting(~E_ALL);
include 'conexao.php';
$cidade = $_REQUEST[c];
$ev = $_REQUEST[e];
$c = $_REQUEST[cn];
$pg = $_REQUEST[pg];


if($c != ""){
	$congr = "AND congregacao = '$c'";
	$cpl = "_$c";
}


if($pg == 1){
	$status = "PAGO";
	$stpg = "com pagamento confirmado";
}

if($pg == 0){
	$status = "PENDENTE";
	$stpg = "com pagamento pendente";
}


$evento = mysql_fetch_assoc(mysql_query("select * from tb_eventos where id = '$ev'"));
$evento = $evento[evento];

$m1=mysql_query("
select *, upper(nome) as 'nome' from tb_inscritos where id in(select inscrito from tb_inscricao where evento = '$ev' and pg = '$pg') $congr order by nome
");
$evt = mysql_fetch_assoc(mysql_query("select evento from tb_eventos where id = $ev"));
$tt = $evt[evento];
$tt = str_replace(" ","_",$tt);
include('arquivos/mpdf/mpdf.php');
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
 'P');  // L - landscape, P - portrait
//$mpdf = new mPDF();
$titulo = "<div style='position:relative; font-size:14px;'>$evt[evento] <br>Lista de inscritos $stpg</div><hr>";
$cima="
<b>
<div style='position:relative; font-size:12px;margin-left:0px; z-index:1;margin-top:0px;'>Nome</div>
<div style='position:relative;font-size:12px;margin-left:275px;z-index:1;margin-top:-17px;'>RG</div>
<div style='position:relative;font-size:12px;margin-left:360px;z-index:1;margin-top:-17px;'>Telefone</div>
<div style='position:relative;font-size:12px;margin-left:560px;z-index:1;margin-top:-17px;'>Pagamento</div>
</b><hr>
";
$inscritos = "";
while($m = mysql_fetch_assoc($m1)){
if($m[cpf] == ""){
$m[cpf] = "--";
}

if($m[rg] == ""){
$m[rg] = "--";
}
$nome_inscrito = $m[nome];
if($m[tel1] != "" && $m[tel2] == ""){
	$telefone = $m[tel1];
}
if($m[tel1] == "" && $m[tel2] != ""){
	$telefone = $m[tel2];
}
if($m[tel1] != "" && $m[tel2] != ""){
	$telefone = "$m[tel1] ou $m[tel2]";
}
if($m[tel1] == "" && $m[tel2] == ""){
	$telefone = "Não informado";
}

$nome_inscrito .= "";
$inscritos .= "
<div style='position:relative; font-size:12px;margin-left:0px; z-index:1;margin-top:0px;'>$nome_inscrito</div>
<div style='position:relative;font-size:12px;margin-left:275px;z-index:1;margin-top:-17px;'>$m[rg]</div>
<div style='position:relative;font-size:12px;margin-left:360px;z-index:1;margin-top:-17px;'>$telefone</div>
<div style='position:relative;font-size:12px;margin-left:560px;z-index:1;margin-top:-17px;'> $status</div>
";
}

$mpdf->AddPage('P');
$mpdf->WriteHTML($titulo);
$mpdf->WriteHTML($cima);
$mpdf->WriteHTML($inscritos);
if($pg == 1){
$mpdf->Output('pagos_'.$evento.''.$cpl.'.pdf',d);
}
if($pg == 0){
$mpdf->Output('pendentes_'.$evento.''.$cpl.'.pdf',d);
}

?>
