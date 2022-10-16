<span class="tt_pg"><b>Cadastra Fornecedores</b></span><br><br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$salva = $_POST[salva];

if($salva == 1){

	mysql_query("INSERT INTO fornecedores  
	(fornecedor,end,num,compl,bairro,cidade,uf,cep,situacao)
	VALUES(
	'$_POST[fornecedor]',
	'$_POST[end]',
	'$_POST[num]',
	'$_POST[compl]',
	'$_POST[bairro]',
	'$_POST[cidade]',
	upper('$_POST[uf]'),
	'$_POST[cep]',
	'1')") or die(mysql_error());

}
?>
<form action="#" method="POST" style="input{padding:1px;}">
<input type="hidden" name="salva" value="1">
<b>Fornecedor</b> <input type="text" name="fornecedor" size="30" required style="text-transform:'uppercase';"><br><br>
<b>Endereço</b> <input type="text" name="end" size="30"><br>
<b>Nº.</b> <input type="text" name="num" size="5"> 
<b>Compl. </b> <input type="text" name="compl" size="9"><br>
<b>Bairro</b> <input type="text" name="bairro" size="30"><br>
<b>Cidade</b> <input type="text" name="cidade" size="30"><br>
<b>UF</b> <input type="text" class="uf" name="uf" style="text-transform:uppercase"; size="2"> 
<b>CEP</b> <input type="text" name="cep" size="13" class="cep"><br>
<br><input type="submit" value="Gravar">
</form>