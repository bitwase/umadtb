<span class="tt_pg"><b>Registro de Vendas</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#11/05/2016{
	-Desenvolvido;
}
#17/05/2016{
	-Ajsutado botão 'continuar' par não ter ação, ficar somente a ação de 'change'
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}

?>
<form action="#" method="POST" style="input{padding:1px;}" >
<input type="hidden" name="status" value="<?php echo $st;?>">
<b>Cliente</b> <input type="text" name="cliente" id="cli" size="40" value="" onfocusout="troca()">
<br><input type="button" value="Continuar">
</form>
<script>
 $(function() {
var cli = [
      <?php
	  $cl = mysql_query("SELECT c.id, upper(c.nome) as 'nome' FROM clientes c where c.situacao = 1 ORDER BY c.nome");
	  while($cli = mysql_fetch_assoc($cl)){
		  echo "'$cli[id] - $cli[nome]',";
	  }	
	  ?>
	  ];
$( "#cli" ).autocomplete({
      source: cli
    });
		});

shortcut.add('enter',function() 
{
troca();
});

function troca(){
	var id = parseInt(document.getElementById("cli").value);
	if(!isNaN(id)){
	window.location="index.php?pg=cn.venda&a=1&c="+id;	
	}
	else if(isNaN(id)){
	alert("Cliente inválido. Favor verificar.");	
	}
}
</script>
