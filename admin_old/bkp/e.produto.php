<span class="tt_pg"><b>Entrada Manual de Produtos</b></span><br><br>
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
$id = $_REQUEST[id];//cod do item a ser registrado entrada;
$salva = $_POST[salva];

$pdt= mysql_fetch_assoc(mysql_query("select p.*, um.um from produtos p
inner join unidademedida um on p.um = um.id
 where p.id = $id"));
 
if($salva == 1){
	$qt = $_POST[qt];
	$nqt = $pdt[qt]+$qt;
	$rs = mysql_query("update produtos set qt = $nqt where id = $id");
	mysql_query("insert into mvprodutos (data,us,pdt,qt,qtat,tp,acao) values (now(),'$cod_us','$id','$qt','$nqt','1','ENTRADA MANUAL')");
if($rs){
	echo "<script>alert('Soma realizada com sucesso.');</script>";
	echo "<META http-equiv='refresh' content='0;URL=index.php?pg=v.produto'>";
}
if(!$rs){
	echo "Se o erro persistir, informar a seguinte mensagem ao suporte:<br> <b>".mysql_error()."</b><br>";
	echo "<script>alert('Erro ao realizar operação. $erro');</script>";	
}
}

if($id != ""){
	echo "
	<b>Código:</b> $pdt[id] <b>Descrição:</b> $pdt[descricao]<br>
	<b>Unidade de Medida:</b> $pdt[um]<br>
	<b>Quantidade em Estoque:</b> $pdt[qt]<br>
	<b>Quantidade Mínima:</b> $pdt[qtmin]<br>
	";
}
?>
<form action="#" method="POST" style="input{padding:1px;}">
<?php if($id != ""){?>
<input type="hidden" name="salva" value="1">
<input type="hidden" name="id" value="<?php echo $pdt[id]?>">
<b>Quantidade a Entrar:</b><input type="text" name="qt" required class="numero" size="5">
<br><input type="submit" value="Gravar">
<?php }//fim se já for informado um produto ?>
<?php if($id == ""){ ?>
<b>Produto:</b><select name="id" required>
<option value="">Selecione</option>
<?php
$pd1 = mysql_query("select * from produtos where st = 1 order by descricao");
while($pd2 = mysql_fetch_assoc($pd1)){
	echo "<option value='$pd2[id]'>$pd2[descricao]</option>";
} 
?>
</select>
<br><input type="submit" value="Continuar">
<?php }//fim se já for informado um produto ?>
</form>