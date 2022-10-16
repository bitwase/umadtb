<span class="tt_pg"><b>Cadastra Produtos</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#10/05/2016{
	-Desenvolvido;
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$salva = $_POST[salva];

if($salva == 1){
$desc = strtoupper($_POST[desc]);
$um = $_POST[um];
$qtmin = $_POST[qtmin];
$vlr = $_POST[vlr];
$vlrcmp = $_POST[vlrcmp];
$rs = mysql_query("insert into produtos (descricao,um,qtmin,vlr,vlrcmp,st) VALUES ('$desc','$um','$qtmin','$vlr','$vlrcmp','1')");
if($rs){
	echo "<script>alert('Produto cadastrado com sucesso.');</script>";
	echo "<META http-equiv='refresh' content='0;URL=index.php?pg=cad.produto'>";
}
if(!$rs){
	echo "Se o erro persistir, informar a seguinte mensagem ao suporte:<br> <b>".mysql_error()."</b><br>";
	echo "<script>alert('Erro ao realizar cadastrado. $erro');</script>";	
}
}
?>
<form action="#" method="POST" style="input{padding:1px;}">
<input type="hidden" name="salva" value="1">
<b>Descrição</b><img src="arquivos/icones/45.png" class="bt_p" title="Descrição clara e objetiva do produto. Este campo não pode ter mais que 150 caracteres."> 
<input type="text" name="desc" size="50" maxlength="150" title="Descrição clara e objetiva do produto. Este campo não pode ter mais que 150 caracteres." required style="text-transform:'uppercase';"><br>
<b>Unidade de Medida</b><img src="arquivos/icones/45.png" class="bt_p" alt="Unidade de Medida do produto. Deve ser selecionado uma opção.">
 <select name="um" required title="Unidade de Medida do produto. Deve ser selecionado uma opção.">
<option value="">Selecione</option>
<?php
$um1 = mysql_query("select * from unidademedida where st = 1 order by um asc");
while($um = mysql_fetch_assoc($um1)){
	echo "<option value='$um[id]'>$um[um]</option>";
}
?>
</select><br>
<b>Quantidade Mínima p/ Estoque</b><img src="arquivos/icones/45.png" class="bt_p" title="A quantidade mínima para estoque define quando deve ser gerado uma solicitação de compras para o produto. Caso não seja informado esta quantidade, não será solicitado compra automaticamente."> <input type="text" name="qtmin" size="5" title="A quantidade mínima para estoque define quando deve ser gerado uma solicitação de compras para o produto. Caso não seja informado esta quantidade, não será solicitado compra automaticamente."><br>
<b>Preço de Venda</b><img src="arquivos/icones/45.png" class="bt_p" title="Se o produto for de venda, deverá ser informado o preço, para que o sistema possa calcular automaticamente os valores das vendas."> <input type="text" name="vlr" size="7" class="vlr" title="Se o produto for de venda, deverá ser informado o preço, para que o sistema possa calcular automaticamente os valores das vendas.">
<br>
<b>Preço de Compra</b><img src="arquivos/icones/45.png" class="bt_p" title="Este valor será utilizado ao realizar uma compra."> <input type="text" name="vlrcmp" size="7" class="vlr" title="Este valor será utilizado ao realizar uma compra.">
<br><input type="submit" value="Gravar">
</form>