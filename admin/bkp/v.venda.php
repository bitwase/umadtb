<span class="tt_pg"><b>Lista de Vendas</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#14/05/2016{
	-Desenvolvido;
	-listar as vendas existentes e seus status. Se sem filtro, mostra somente as que estão em aberto.
	
}
#21/05/2016{
	-ajustado para mostrar situação 3 (concluído)
}
#24/05/2016{
	-ajustado para mostrar situação 5 (cancleado)
	1 - Em Aberto
	2 - Orçamento
	3 - Concluído
	4 - Pagamento Agendado
	5 - Cancelado
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
select v.id, date_format(v.data,'%d/%m/%Y %H:%i') as 'data', v.st, c.nome as 'cliente', vn.nome as 'vendedor' from vendas v
inner join clientes c on v.cliente = c.id
inner join usuarios vn on v.vendedor = vn.id
");
 $od = 0;
while($pdt = mysql_fetch_assoc($vnd)){
$od++;//define ordem
$qti = mysql_fetch_assoc(mysql_query("select sum(qt) as 'qt', sum(qt*vlu) as 'vl' from vndpdt where vnd = $pdt[id]"));
switch($pdt[st]){
	case 1:
	$st = "Em Aberto";
	$lk = "<a href=\'index.php?pg=cn.venda&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
	case 2:
	$st = "Orçamento";
	$lk = "<a href=\'index.php?pg=cn.venda&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
	case 3:
	$st = "Concluído";
	$lk = "<a href=\'index.php?pg=cn.venda&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
	case 4:
	$st = "Pagamento Agendado";
	$lk = "<a href=\'index.php?pg=cn.venda&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
	case 5:
	$st = "Cancelado";
	$lk = "<a href=\'index.php?pg=cn.venda&vn=$pdt[id]\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	break;
}
	echo "
	['$od','$pdt[id]','$pdt[data]','$pdt[cliente]','$pdt[vendedor]','$qti[qt]','R$$qti[vl]','$st','$lk',],";
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
            { title: "Cliente" },
            { title: "Vendedor" },
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
	  $cl = mysql_query("SELECT c.id, upper(c.nome) as 'nome' FROM clientes c 
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
