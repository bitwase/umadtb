<span class="tt_pg"><b>Cadastro de Eventos</b></span>
<?php
	## RECEBER DADOS E INSERIR NA TABELA tb_eventos ##
$salva = $_POST[salva];
if($salva){
	$ev = $_POST[evento];
	$dtev = $_POST[dtEvento];//01/34/6789
	$dtev = $dtev[6].$dtev[7].$dtev[8].$dtev[9]."-".$dtev[3].$dtev[4]."-".$dtev[0].$dtev[1];
	$local = $_POST[local];
	$qtLimite = $_POST[qtEvento];
	if($qtLimite == ""){
		$qtLimite = 0;
	}
	$dtlm = $_POST[dtLmEvento];//01/34/6789
	if($dtlm != ""){
		$dtlm = $dtlm[6].$dtlm[7].$dtlm[8].$dtlm[9]."-".$dtlm[3].$dtlm[4]."-".$dtlm[0].$dtlm[1];
	}
	else{
		$dtlm = "null";
	}
	$obs = addslashes($_POST[obs]);

//	echo "insert into tb_eventos (evento, data, local, qtLimite, dtLimite, obs, st) values('$ev','$dtev','$local','$qtLimite',$dtlm,'$obs','1')";
mysql_query("insert into tb_eventos (evento, data, local, qtLimite, dtLimite, obs, st) values('$ev','$dtev','$local','$qtLimite','$dtlm','$obs','1')") or die(mysql_error());
}
?>
<form action="#" method="POST">
<input type="hidden" name="salva" value="1">
<b>Evento:</b> <input type="text" size="20" name="evento" required><br>
<b>Data:</b> <input type="text" size="11" name="dtEvento" class="date" required><br>
<b>Local:</b> <input type="text" size="15" name="lcEvento"><br>
<b>Quantidade Limite de Inscrição:</b><input type="number" size="5" name="qtEvento"><br>  
<b>Data Limite Para Inscrição:</b><input type="text" size="11" class="date" name="dtLmEvento"><br>
<b>Observações:</b><br>
<textarea name="obs" rows="5" cols="40"></textarea><br>
<input type="submit" value="Salvar">  
</form>
