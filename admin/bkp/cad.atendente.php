<span class="tt_pg"><b>Cadastra Atendentes</b></span><br><br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$salva = $_POST[salva];

$nome = $_POST[nome];
$cor = $_POST[cor];
if($salva == 1){
	reg_atendente();
}
function reg_atendente(){
	mysql_query("INSERT INTO atendentes (nome,cor,situacao) VALUES('$_POST[nome]','$_POST[cor]','1')");
	alerta();
}
function alerta(){
	?>
	<script type='javascript'>
	alert("Registro realizado com sucesso.");
	</script>
	<?php
}
?>
<form action="#" method="POST">
<input type="hidden" name="salva" value="1">
<b>Nome</b><br>
<input type="text" name="nome" size="30" style="text-transform:uppercase;"><br>
<b>Selecione uma cor para agendamentos:</b> <input type="color" name="cor">(cor mostrada na agenda)<br>
<br><input type="submit" value="Gravar">
</form>