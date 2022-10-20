<?php
//incluir arquivo de conexão com banco de dados...
//este arquivo será chamado via curl

include 'conexao.php';
$cons = 3;//dias consecutivos
//pegar as 3 últimas datas
$ud1 = mysql_query("select distinct date_format(data,'%Y-%m-%d') as 'data', date_format(data,'%d/%m/%Y') as 'data2' from tb_ausente order by data desc limit $cons;");
$dt = "";//inicializa a variável que receberá as 3 últimas datas registradas no sistema
$dt4 = "";
while($ud = mysql_fetch_assoc($ud1)){
	$dt .= "'$ud[data]',";
	$dt4 .= "$ud[data2]<br>";
}
$dt = substr($dt,0,-1);
$dt4 = substr($dt4,0,-4);
//faz a busca pegando somente os ID dos 
//selecionar todos os que estão com 3 ou mais dias consecutivos de ausência
//selecionar contando quantidade de ID
$lj1 = mysql_query("select jovem, count(jovem) as 'qt' from tb_ausente where data in ($dt) group by jovem");
while($lj = mysql_fetch_assoc($lj1)){
	if($lj[qt] == $cons){
		//se contagem for igual a quantidade consecutiva...
		//verifica se já existe registro do jovem nesta tabela e se uma das datas consta na coluna 'datas'
$dt2 = str_replace("'","",$dt);
$dt2 = explode(",",$dt2);
$cls = "";
if($dt2[0] != ""){
$cls .= "datas like '%$dt2[0]%'";
}
if($dt2[1] != ""){
$cls .= " or datas like '%$dt2[1]%'";
}
if($dt2[2] != ""){
$cls .= " or datas like '%$dt2[2]%'";
}
	$v1 = mysql_num_rows(mysql_query("select * from tb_consecutivo where jovem = $lj[jovem] and ($cls)"));
	    //se não constar, insere novo registro
		if($v1 == 0){//não consta
		$dt3 = addslashes($dt);
		mysql_query("insert into tb_consecutivo (jovem,datas,datas2,obs,ciente) values('$lj[jovem]','$dt3','$dt4','','0')") or die(mysql_error());
		}

	}
}
?>
