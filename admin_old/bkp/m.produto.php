<span class="tt_pg"><b>Lista de Movimentações de Produtos</b></span><br><br>
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

if($id != ""){
	$filtro = "m.pdt = $id";
	$di = $_POST[di];//01-34-6789
	$df = $_POST[df];
	if($di != ""){
		$di = $di[6].$di[7].$di[8].$di[9]."-".$di[3].$di[4]."-".$di[0].$di[1]." 00:00:00";
		$filtro = "$filtro AND m.data >= '$di'";
	}
	if($df != ""){
		$df = $df[6].$df[7].$df[8].$df[9]."-".$df[3].$df[4]."-".$df[0].$df[1]." 23:59:59";
		$filtro = "$filtro AND m.data <= '$df'";
	}
}
$pdt= mysql_fetch_assoc(mysql_query("select p.*, um.um from produtos p
inner join unidademedida um on p.um = um.id
 where p.id = $id"));

$mvt1= mysql_query("select date_format(m.data,'%d/%m/%Y %H:%i') as 'data', p.id as 'cod', p.descricao, m.acao, m.qt, m.qtat, u.nome from mvprodutos m
inner join produtos p on m.pdt = p.id
inner join usuarios u on m.us = u.id
 where $filtro");
 ?>
<form action="#" method="POST">
<b>Produto:</b><select name="id" required><br>
<option value="">Selecione</option>
<?php
$pd1 = mysql_query("select * from produtos order by descricao");
while($pd2 = mysql_fetch_assoc($pd1)){
	echo "<option value='$pd2[id]'>$pd2[descricao]</option>";
} 
?>
</select><br>
<b>Data Inicial:</b><input type="text" name="di" class="date" size="11"><br>
<b>Data Final:</b><input type="text" name="df" class="date" size="11">
<br><input type="submit" value="Filtrar">
</form>
<hr>
<?php
if($id != ""){
 ?>

<table id="produtos" class="display" width="100%"></table>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
 
 $od = 0;
while($mvt = mysql_fetch_assoc($mvt1)){
$od++;//define ordem
	echo "
	['$od','$mvt[data]','$mvt[cod] - $mvt[descricao]','$mvt[acao]','$mvt[qt]','$mvt[qtat]','$mvt[nome]'],";
}
?>
];
 
$(document).ready(function() {
    $('#produtos').DataTable( {
		 "scrollX": true,
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
        ],
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "" },
			{ title: "Data" },
            { title: "Produto" },
            { title: "Movimentação" },
            { title: "Qt. Mov." },
            { title: "Qt. Atual" },
            { title: "Usuário" }
			]
    } );
} );
</script>
<?php } //fim caso exista filtro ?>
