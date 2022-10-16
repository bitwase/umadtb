<span class="tt_pg"><b>Registro de Compras</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#11/05/2016{
	-Desenvolvido;
}
#20/05/2016{
	-ajustar para direcinar agendamentos para venda
	-
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$salva = $_POST[salva];
if($salva == 1){
	$fornecedor = $_POST[fornecedor2];
	$dt = $_POST[data];
	$datapg = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
	//index.php?pg=cn.compra&a=1&c="+id
	mysql_query("insert into compra (fornecedor,comprador,st,data,datapg) values ('$fornecedor','$cod_us', '1',now(),'$datapg')");
$vn1 = mysql_fetch_assoc(mysql_query("select max(id) as 'vn' from compra"));
if($vn1[vn] == ""){
	$vn1[vn] = 1;
}
 echo "<META http-equiv='refresh' content='0;URL=index.php?pg=cn.compra&vn=$vn1[vn]'>";
}
?>
<form action="#" method="POST" style="input{padding:1px;}" >
<input type="hidden" name="salva" value="1">
<input type="hidden" name="fornecedor2" id="fornecedor2" value="">
<b>Fornecedor</b> <input type="text" name="fornecedor" id="fornecedor" size="40" value="" required onfocusout="troca()"><br>
<b>Data prevista p/ pagamento</b> <input type="text" name="data" id="data" class="date" size="10" required>
<br><input type="submit" value="Continuar">
</form>
<script>
 $(function() {
var fornecedor = [
      <?php
	  $cl = mysql_query("SELECT c.id, upper(c.fornecedor) as 'fornecedor' FROM fornecedores c 
	  ORDER BY c.fornecedor");
	  while($cli = mysql_fetch_assoc($cl)){
		  echo "'$cli[id] - $cli[fornecedor]',";
	  }	
	  ?>
	  ];
$( "#fornecedor" ).autocomplete({
      source: fornecedor
    });
		});
		
function troca(){
	var id = document.getElementById("fornecedor").value;
	if(id != ""){
	id = parseInt(document.getElementById("fornecedor").value);
	}
	if(id != ""){
	if(!isNaN(id)){
		document.getElementById('fornecedor2').value = id;
		document.getElementById('data').focus();
	//window.location="index.php?pg=cn.compra&a=1&c="+id;	
	}
	else if(isNaN(id)){
	alert("Fornecedor inválido. Favor verificar.");	
	document.getElementById('fornecedor').value = "";
		document.getElementById('fornecedor').focus();
	}
	}
}
</script>
