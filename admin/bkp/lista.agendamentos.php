<span class="tt_pg"><b>Cursos Agendados</b></span><br><br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//verifica se tem permissão de adm para lsitar dados
?>
<table id="example" class="display" width="100%"></table>

<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$ag1 = mysql_query("
select c.titulo, date_format(a.data_inicio,'%d/%m/%Y') as 'data', date_format(a.data_inicio,'%H:%i') as 'hrin' ,date_format(a.data_fim,'%H:%i') as 'hrfn' , a.situacao from tb_agenda a
inner join tb_cursos c on a.curso = c.id
order by data
");

while($ag = mysql_fetch_assoc($ag1)){
if($ag[situacao] == 1){
$sit = "AGENDADO";
}
if($ag[situacao] == 0){
$sit = "CANCELADO";
}
if($ag[situacao] == 2){
$sit = "REALIZADO";
}
echo "
	['$ag[data]','$ag[titulo]','$ag[hrin]','$ag[hrfn]','$sit','LINK'],
	";
}
?>
];
 
$(document).ready(function() {
    $('#example').DataTable( {
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "Data" },
            { title: "Título" },
            { title: "Início" },
            { title: "Término" },
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
