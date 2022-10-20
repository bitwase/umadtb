<?php
$class_dir = '../class';

require('function.php');


geraCod("eb13");

function geraCod($text2display){
$code = "128";
$filename = "code".$code;
$output =1;
$thickness =30 ;
$res =1;
$font =0;//tamanho da fonte de texto
$a1 ='';
$a2 = '';
echo "<img src='image.php?code=$filename&o=$output&t=$thickness&r=$res&text=$text2display&f=$font&a1=$a1&a2=$a2'/>";
}
?>
