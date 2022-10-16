<span class="tt_pg"><b>Lista de Compras</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#14/05/2016{
	-Desenvolvido;
	-listar as vendas existentes e seus status. Se sem filtro, mostra somente as que estão em aberto.
	
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
?>
<table id="produtos" class="display" width="100%"></table>
<hr>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$vnd= mysql_query("
select v.id, date_format(v.data,'%d/%m/%Y %H:%i') as 'data', v.st, c.fornecedor as 'fornecedor', vn.nome as 'comprador' from compra v
inner join fornecedores c on v.fornecedor = c.id
inner join usuarios vn on v.comprador = vn.id
");
 $od = 0;
while($pdt = mysql_fetch_assoc($vnd)){
$od++;//define ordem
$qti = mysql_fetch_assoc(mysql_query("select sum(qt) as 'qt', sum(qt*vlu) as 'vl' from cmppdt where vnd = $pdt[id]"));
switch($pdt[st]){
	case 1:
	$st = "Em Aberto";
	$lk = "<a href=\'index.php?pg=cn.compra&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
	case 2:
	$st = "Orçamento";
	$lk = "<a href=\'index.php?pg=cn.compra&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
	case 3:
	$st = "Concluído";
	$lk = "<a href=\'index.php?pg=cn.compra&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
	case 4:
	$st = "Pagamento Agendado";
	$lk = "<a href=\'index.php?pg=cn.compra&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
	case 5:
	$st = "Cancelado";
	$lk = "<a href=\'index.php?pg=cn.compra&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
}
	echo "
	['$od','$pdt[id]','$pdt[data]','$pdt[fornecedor]','$pdt[comprador]','$qti[qt]','R$$qti[vl]','$st','$lk',],";
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
			{ title: "Venda" },
            { title: "Data" },
            { title: "Fornecedor" },
            { title: "Comprador" },
            { title: "Qt. Itens" },
            { title: "Vlr Total" },
            { title: "Situação" },
            { title: "" }
        ]
    } );
} );
</script>

<script>
 $(function() {
var cli = [
      <?php
	  $cl = mysql_query("SELECT c.id, upper(c.nome) as 'nome' FROM pacientes c 
	  ORDER BY c.nome");
	  while($cli = mysql_fetch_assoc($cl)){
		  echo "'$cli[id] - $cli[nome]',";
	  }	
	  ?>
	  ];
$( "#cli" ).autocomplete({
      source: cli
    });
		});
</script>
