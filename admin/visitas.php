<span class="tt_pg"><b>Histórico de Visitas</b></span><br><br>
<?php
/*
-Pegar ID do jovem
-Mostrar todos os dados do Jovem
-Mostrar em ordem cronológica as visitas realizadas separando por linha..
Visita realizada em DATA EM NEGRITO <br><br>
Descrição<br>
<hr>
*/

$j = $_REQUEST[id];

$ld = mysql_fetch_assoc(mysql_query("select * from tb_inscritos where id = $j"));

echo "
Nome: <b>$ld[nome]</b><br>
Tel.: <b>$ld[tel1] - $ld[tel2]</b><br>
Email: <b>$ld[email]</b><br>
Endereço: <b>$ld[rua], $ld[num] - $ld[bairro] | 
$ld[cidade] - $ld[uf]</b>
<br><br>
";

$lv1  = mysql_query("select date_format(data,'%d/%m/%Y') as 'data', obs from tb_visitas where jovem = '$j' order by id");

while($lv = mysql_fetch_assoc($lv1)){
echo "
<hr>
Visita realizada em <b>$lv[data]</b><br><br>
$lv[obs] <br>
";
}

?>
