<?php
//$class_dir = '../class';
//error_reporting(~E_ALL);
include 'function.php';

//$nome = "Wellington Ulisses Santos";
//criar neste arquivo função para chamar a impressão da etiqueta
//geraCod($cb,$nome);
//$barras = "<img src='image.php?code=code128&o=1&t=30&r=1&text=07500846932&f=2&a1=&a2='/>";
function geraCod($text2display,$nome1){
$code = "128";
$filename = "code".$code;
$output =1;//tipo de saída 1- png 2-jpg
$thickness =30;//altura do código de barras
$res =1;//espaçamento, largura
$font =2;//tamanho da fonte de texto
$a1 ='';
$a2 = '';

//echo "$nome<br><img src='image.php?code=$filename&o=$output&t=$thickness&r=$res&text=$text2display&f=$font&a1=$a1&a2=$a2'/>";
}
?>
<!--div id="pagina" style="background:#ccc;width:210mm; height:297mm;page-break-before: auto; page-break-inside: avoid;"-->

</div>
