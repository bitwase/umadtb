<span class="tt_pg"><b>Cadastra Especialidades</b></span><br><br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
# LISTAR ATENDENTES P/ ATRIBUIR #
$atd = array();
$atd_nome = array();
$aux = 0;
$atd1 = mysql_query("select * from atendentes where situacao = 1 order by nome");
while($at = mysql_fetch_assoc($atd1)){
	$atd[$aux] = $at[id];
	$atd_nome[$aux] = $at[nome];
	$aux++;
}
$salva = $_POST[salva];

if($salva == 1){
	mysql_query("insert into especialidades (especialidade,valor,situacao) values(upper('$_POST[esp]'),'$_POST[vlr]','1')") or die(mysql_error());
	$uid = mysql_fetch_assoc(mysql_query("select id from especialidades order by id desc limit 0,1"));
//verificar se atribui para algum aendente
foreach($atd as $id){
	$atn = "a$id";
	$$atn = $_POST[$atn];
if($$atn == 1){
		mysql_query("insert into atende (atendente,especialidade) values ('$id','$uid[id]')");
	}
}	
}
?>
<form action="#" method="POST" style="input{padding:1px;}">
<input type="hidden" name="salva" value="1">
<b>Especialidade</b> <input type="text" name="esp" size="20" style="text-transform:uppercase;"><br>
<b>Valor</b> <input type="text" class="vlr" name="vlr" id="vlr" size="10" maxlength="10"><br><br>
<b>Atribuir Atendimento</b><br><br>
<?php
	foreach(array_combine($atd,$atd_nome) as $id => $nm){
		echo "
		<input type='checkbox' $ckd name='a$id' id='a$id' value='1'><label for='a$id'>$nm</label><br>
		";
	}
?>
<br><input type="submit" value="Gravar">
</form>