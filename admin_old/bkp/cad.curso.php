<span class="tt_pg"><b>Cadastra Curso</b></span><br><br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//verifica se tem permissão de adm para incluir dados
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}

$agora=date('dmYHis');

$salva = $_POST[salva];

if($salva == 1){

// recebe setores
$set_todos = $_POST[set_todos];
if($set_todos == "T"){//verifica se é pra todos os setores
$setores = "T";
}
else{//se nao for pra todos pega um a um
$setores = "";
$set1 = mysql_query("select * from tb_setor where situacao = '1' ORDER BY setor");
	while($set = mysql_fetch_assoc($set1)){
	$set_.$set[id] = $_POST['set_'.$set[id]];
		if($set_.$set[id] != ""){
		$setores = "$setores $set_$set[id],";
		}
	}
}
/*if($setores == ""){
	echo "
<script language='javascript'>
alert('Necessário Informar no mínimo 1 setor.');
window.setTimeout('history.go(-2)', 0);
</script>
";
}*/
$titulo = addslashes($_POST[titulo]);
$assunto = addslashes($_POST[assunto]);
$tempo = $_POST[tempo];
$obg = $_POST[obg];//verificar se é obrigatório ou não
$validade = $_POST[validade];
$tipo = $_POST[tipo];

mysql_query("INSERT INTO tb_cursos 
(titulo,assunto,tempo,setores,validade,tipo,situacao) VALUES
('$titulo','$assunto','$tempo','$setores','$validade','$tipo','1')
");
echo "<script type='text/javascript'>alert('Cadastro Realizado com Sucesso.');</script>";
//echo "<META http-equiv='refresh' content='0;URL=index.php?pg=cad.curso'>"; 
echo "INSERT INTO tb_cursos 
(titulo,assunto,tempo,setores,validade,tipo,situacao) VALUES
('$titulo','$assunto','$tempo','$setores','$validade','$tipo','1')";
}
?>

<form action="#" method="POST" enctype="multipart/form-data">
<input type="hidden" name="salva" value="1">
<b>Título</b><br>
<input type="text" name="titulo" size="50" maxlength="200" required><br>
<b>Assunto</b><br>
<textarea name="assunto" rows="6" cols="50"></textarea><br>
<b>Tempo de Curso <span type="font-size:8px;">(horas)</span></b><br>
<input type="text" name="tempo" size="5" maxlength="5"><br>
<b>Setores</b><br>
<input type="checkbox" name="set_todos" id="set_todos" value="T"><label for="set_todos">TODOS</label><br><br>
<?php 
$set1 = mysql_query("select * from tb_setor where situacao = '1' ORDER BY setor");
while($set = mysql_fetch_assoc($set1)){
echo "
<input type='checkbox' name='set_$set[id]' id='set_$set[id]' value='$set[id]'><label for='set_$set[id]'>$set[setor]</label><br>
";
}
// fazer um for para os setores em checkbox, ordenado alfabeticamente?><br>
<b>Validade <span type="font-size:8px;">(meses)</span></b><br>
<input type="text" name="validade" size="3" maxlength="3"><br>
<b>Tipo de Treinamento</b><br>
<select required aria-required="true" name="tipo">
<option disabled selected>Selecione
<?php
$tp1 = mysql_query("select * from tb_tipo where situacao = '1' ORDER BY tipo");
while($tp = mysql_fetch_assoc($tp1)){
echo "
<option value='$tp[id]'>$tp[tipo]
";
}
?>
</select>
<input type="submit" value="Salvar"><br><br>
</form>
<script>
jQuery(function($){
   $("#data_ad").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
}
</script>
