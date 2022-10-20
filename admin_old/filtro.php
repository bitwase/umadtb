<?php
$filtra = $_POST[ft];
if($filtra){
	$congregacao = $_POST[congregacao];
	mysql_query("update usuarios set filtro = '$congregacao' where id = $cod_us");
	echo "<meta http-equiv='refresh' content='0;URL=index.php?pg=v.inscritos'>";
}

?>
<span class="tt_pg"><b>Selecionar Congregação Para Consultas</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#22/06/2016{
	-Desenvolvido;
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}

$removeInscrito = $_POST[removeInscrito];
if($removeInscrito){
	mysql_query("delete from tb_inscricao where inscrito = '$_POST[idInscricao]' and evento = '$_POST[idEvento]'");
}

$pagaInscrito = $_POST[pagaInscrito];
if($pagaInscrito){
	mysql_query("update tb_inscricao set pg = '1' where inscrito = '$_POST[idPagar]' and evento = '$_POST[idEventoPagar]'") or die(mysql_error());
}

$altClientes = $_POST[altClientes];
if($altClientes == 1){
$dt = $_POST[nascimento];// 01 34 6789
if($dt != ""){
$dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
}
else{
	$dt = "0000-00-00";
}
	mysql_query("update tb_inscritos set nome = '$_POST[nome]', rg='$_POST[rg]', cpf='$_POST[cpf]', nascimento = '$dt', rua = '$_POST[end3]', num = '$_POST[num]', bairro =	'$_POST[bairro3]', cidade = '$_POST[cidade3]', uf = upper('$_POST[uf3]'),cep =	'$_POST[cep3]', tel1 = '$_POST[tel1]', tel2 = '$_POST[tel2]', email= '$_POST[email]',sit = '$_POST[sit]', congregacao = '$_POST[congregacao2]' where id = '$_POST[idCli]'") or die(mysql_error());

echo "<script>alert('Dados alterados com sucesso.');</script>";
}

?>

<form action="#" method="POST">
<input type="hidden" name="ft" value="1">
<label class="iden"><b>Congregação</b></label><select name="congregacao">
<option value="">Selecione</option>
<?php
$le = mysql_query("select * from tb_congregacoes order by congregacao");
while($l = mysql_fetch_assoc($le)){
	echo "<option value='$l[congregacao]'>$l[congregacao]</option>";
}
?>
</select><input type="submit" value="Filtrar Congregação">
</form>