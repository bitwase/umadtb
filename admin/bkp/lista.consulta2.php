<span class="tt_pg"><b>Lista Agendamentos</b></span><br><br>
<p class="tt"><?php echo "$tt" ?></p>
<table id="example" class="display" width="100%"></table>

<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$con = mysql_query("select c.id,date_format(c.data,'%d/%m/%Y') as 'dt', c.hr_inicio as 'hr', upper(p.nome) as 'cliente', upper(m.nome) as 'atendente', c.situacao, upper(e.especialidade) as 'especialidade'
from consultas c
inner join clientes p on c.paciente = p.id
inner join atendentes m on c.atendente = m.id
inner join especialidades e on c.especialidade = e.id");

while($cn = mysql_fetch_assoc($con)){
switch($cn[situacao]){
	case 1:
		$st = "Agendado";
		$rea = "<a href=\'#\' onclick=\'reagendar($cn[id])\' title=\'Reagendar\'><img src=\'arquivos/icones/1112.png\' class=\'bt_p\' ></a>";
		$ate = "<a href=\'index.php?pg=atende&id=$cn[id]\' title=\'Atendimento\'><img src=\'arquivos/icones/1111.png\' class=\'bt_p\'></a>";
		break;
	case 2:
		$st = "Reagendado";
		$rea = "";
		$ate = "";
		break;
	case 3:
		$st = "Em Andamento";
		$rea = "";
		$ate = "<a href=\'index.php?pg=atende&id=$cn[id]\' title=\'Atendimento\'><img src=\'arquivos/icones/1111.png\' class=\'bt_p\'></a>";
		break;
	case 4:
		$st = "Realizado";
		$rea = "";
		$ate = "<a href=\'index.php?pg=atende&id=$cn[id]\' title=\'Atendimento\'><img src=\'arquivos/icones/1111.png\' class=\'bt_p\'></a>";
		break;
}
	echo "
	['$cn[dt] $cn[hr]','$cn[cliente]','$cn[atendente]','$cn[especialidade]','$st','$rea','$ate'],
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
            { title: "Cliente" },
            { title: "Atendente" },
            { title: "Especialidade" },
            { title: "Status" },
            { title: "Alt" },
            { title: "C" }
        ]
    } );
} );
</script>
<script>
function reagendar(id){
	mostraMascara();
	window.open("reagendar.php?id="+id, "Reagendar", "toolbar=no,scrollbars=no,resizable=no,top=100,left=500,width=400,height=400");
}
</script>
