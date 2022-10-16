<?php

error_reporting(~E_ALL);
include 'seguranca.php';
//usar daqui pra baixo....

$c = $_REQUEST["c"];

certificado($fl,$c);

function certificado($fl,$c){
if($fl == ""){//se não for informado se é A4 ou A3, pegar por padrão A4
$fl = 'A4';
}

$c = explode(".",$c);

$m1 = mysql_fetch_assoc(mysql_query("
select i.*, e.evento as 'nomeEvento', date_format(e.data, '%d/%m/%Y') as 'dataEvento', date_format(ins.data, '%d/%m/%Y %H:%i') as 'dataInscricao', ins.id as 'idV', ins.inscrito as 'inscritoV', ins.evento as 'eventoV', date_format(ins.data,'%Y%d%m%H%i$s') as 'dataV' from tb_inscricao ins 
inner join tb_inscritos i on ins.inscrito = i.id 
inner join tb_eventos e on ins.evento = e.id 
where ins.id = $c[0] and ins.inscrito = $c[1] and ins.evento = $c[2] and date_format(ins.data,'%Y%d%m%H%i$s') = '$c[3]'
"));

//$m = mysql_fetch_assoc($m1);
$nome="comprovante_$m1[nome]_$m1[nomeEvento].pdf";//certificados_nome_regional...

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


if($fl == 'A4'){
//$mpdf = new mPDF();
//while($m = mysql_fetch_assoc($m1)){
$mpdf->AddPage('L');
//$mpdf->Image('arquivos/imagens/certificado.jpg',-1,0,298,210,'jpg','',true, false);
//dados que deverão sair no certificado...

//ajustar nome para tamanho correto...

$cima = "
<div style='position:absolute;text-align:justify;top:10px;left:311px;width:1135px;font-size:25;text-indent: 2.0em;'>COMPROVANTE DE INSCRIÇÃO</div>";
$dir = "http://$_SERVER[HTTP_HOST]";
$chave = "$dir/jovens/valida.php?c=$m1[idV].$m1[inscritoV].$m1[eventoV].$m1[dataV]";
$chave2 = "$m1[idV].$m1[inscritoV].$m1[eventoV].$m1[dataV]";
$Code = "<barcode code='".$chave."' type='QR' class='barcode' size='2' error='M' disableborder='1' />";

$meio = "
<div style='position:absolute;text-align:justify;top:60px;left:211px;width:1135px;font-size:25;text-indent: 2.0em;'><br>
Nome: $m1[nome]<br>
Evento: $m1[nomeEvento]<br>
Data do Evento: $m1[dataEvento]<br>
Data de Inscrição: $m1[dataInscricao]<br><br>
Chave validação: <br>$Code <br>$chave2<br>
</div>";

echo "$cima";
echo "$meio";
exit();
######### INSERIR NA tb_certificado ##########
####### INSERE DADOS NA PÁGINA...

//qrCode();

$mpdf->SetXY(40, 70);
//$mpdf->WriteHTML($msg);
//$mpdf->WriteHTML($dt);
//$mpdf->WriteHTML($ass);
$mpdf->WriteHTML($cima);
$mpdf->WriteHTML($meio);
//$mpdf->WriteHTML($Code);
//$mpdf->WriteHTML($nome2);

//}//fim enquanto...
}

$mpdf->Output($nome,d);
//$mpdf->Output("certificados/$nome"); //salvar na pasta...

}//fim função certificado
?>