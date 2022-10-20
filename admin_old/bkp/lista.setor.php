<span class="tt_pg"><b>Lista Setores</b></span><br><br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//verifica se tem permissão de adm para lsitar dados
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}

$agora=date('dmYHis');


?>
<table id="example" class="display" width="100%"></table>

<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$st1 = mysql_query("
select s.setor, s.situacao, c.nome as 'lider' from tb_setor s
left join tb_colaboradores c on s.lider = c.id
order by s.setor
");

while($st = mysql_fetch_assoc($st1)){
if($st[situacao] == 1){
$sit = "ATIVO";
}
if($st[situacao] == 0){
$sit = "INATIVO";
}
echo "
	['$st[setor]','$st[lider]','$sit','LINK'],
	";
}
?>
];
 
$(document).ready(function() {
    $('#example').DataTable( {
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "Setor" },
            { title: "Líder" },
            { title: "Situação" },
	{ title: "" },
        ]
    } );
} );
</script>
<script>
jQuery(function($){
   $("#data_ad").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
}
</script>
