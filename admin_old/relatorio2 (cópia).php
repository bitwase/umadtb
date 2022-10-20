<?php
error_reporting(~E_ALL);
include 'conexao.php';
$cidade = $_REQUEST[c];
$ev = $_REQUEST[e];
$c = $_REQUEST[cn];

if($c != ""){
	$congr = "AND congregacao = '$c'";
	$cpl = "_$c";
}



$evento = mysql_fetch_assoc(mysql_query("select * from tb_eventos where id = '$ev'"));
$evento = $evento[evento];

$m1=mysql_query("
select * from tb_inscritos where id in(select inscrito from tb_inscricao where evento = '$ev') $congr order by nome
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
$titulo = "<div style='position:relative; font-size:14px;'>$evt[evento] <br>Lista de inscritos</div><hr>";
$cima="
<b>
<div style='position:relative; font-size:14px;margin-left:0px; z-index:1;margin-top:0px;'>Nome</div>
<div style='position:relative;font-size:14px;margin-left:315px;z-index:1;margin-top:-19px;'>RG</div>
<div style='position:relative;font-size:14px;margin-left:420px;z-index:1;margin-top:-19px;'>CPF</div>
<div style='position:relative;font-size:14px;margin-left:560px;z-index:1;margin-top:-19px;'>Assinatura</div>
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
$inscritos .= "
<div style='position:relative; font-size:14px;margin-left:0px; z-index:1;margin-top:0px;'>$m[nome]</div>
<div style='position:relative;font-size:14px;margin-left:315px;z-index:1;margin-top:-19px;'>$m[rg]</div>
<div style='position:relative;font-size:14px;margin-left:420px;z-index:1;margin-top:-19px;'>$m[cpf]</div>
<div style='position:relative;font-size:14px;margin-left:560px;z-index:1;margin-top:-19px;'> __________________</div>
";
}

$mpdf->AddPage('P');
$mpdf->WriteHTML($titulo);
$mpdf->WriteHTML($cima);
$mpdf->WriteHTML($inscritos);

$mpdf->Output('inscritos_'.$evento.''.$cpl.'.pdf',d);

?>
