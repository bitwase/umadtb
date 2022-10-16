<span class="tt_pg"><b>Cadastro de Produtos</b></span><br><br>
<?php
if($nv > 2){
echo "<script language='javascript'>
alert('Você não possui acesso a este módulo. Se necessário, entre em contato com a TI.');
</script>";
echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
}
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
/* ---------------------------------------------------------- *\
   -Nilson inicia preenchimento, onde deve ser preenchuido od dados:
	-Cod. Serdia(*)
	-Produto(*)
	-Modelo(*)
	-Descrição(*)
	-Descrição PB(*)
	-Observações(**)
	-Portaria de Aprovação(**)
	-Portaria de PPB(**)
	
   -Situação automática para "Em Edição";
   -Após preenchimento, enviar email para Jana, informando sobre esta inclusão, para que possa informar os seguintes campos:
	-NCM.
	-IPI.
	-ICMS(%).
	
\* ---------------------------------------------------------- */
$salva = $_POST[salva];
if($salva == 1){
//inserir dados e gerar um id para o processo;
//enviar email para Fiscal
$codserdia = $_POST[codserdia];
$mdl = $_POST[mdl];
$cliente = $_POST[cliente];
$desc = $_POST[desc];
$descppb = $_POST[descpbp];
$obs = $_POST[obs];
$pa = $_POST[pa];
$pppb = $_POST[pppb];

//situação 1 = em edição;
$rt = mysql_query("INSERT INTO tb_processos (codserdia, descricao, modelo, cliente, descppb, obs, pa, pppb, st) VALUES ('$codserdia', '$desc', '$mdl', '$cliente', '$descppb', '$obs', '$pa', '$pppb', '1')");

if($rt){
echo "<script language='javascript'>
alert('Cadastro realizado com sucesso.');
</script>";
}
if(!$rt){
echo "<script language='javascript'>
alert('Erro ao cadastrar.');
</script>";
}
}
?>

<form action="#" method="POST">
<input type="hidden" name="salva" value="1">
<b>Cód. Serdia:</b> <input type="text" name="codserdia" size="5" required><br>
<b>Descrição:</b><br>
<textarea name="desc" rows="3" cols="50" id="descricao"></textarea><br>
<b>Modelo:</b> <input type="text" name="mdl" size="20" id="modelo"><br>
<b>Cliente:</b><select name="cliente" required>
<option value="">Selecione</option>
<?php 
$cl1 = mysql_query("select * from tb_cliente where situacao = 1 order by cliente asc");
while($cl = mysql_fetch_assoc($cl1)){
echo "<option value='$cl[id]'>$cl[cliente]</option>";
}
?>
</select><br>
<b>Descrição PPB:</b><br>
<textarea name="descppb" rows="3" cols="50" id="axl"></textarea><br>
<b>Observações:</b><br>
<textarea name="obs" rows="3" cols="50"></textarea><br>
<b>Portaria de Aprovação:</b><br>
<textarea name="pa" id="pa" rows="3" cols="50"></textarea><br>
<b>Portaria de PPB:</b><br>
<textarea name="pppb" rows="3" cols="50" id="pppb"></textarea><br>
<br><input type='submit' value='Salvar'>
</form><br>

<script type="text/javascript" src="arquivos/jquery/autocomplete.js"></script>
