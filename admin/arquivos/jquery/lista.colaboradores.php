<span class="tt_pg"><b>Lista Colaboradores</b></span><br><br>
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
$cl1 = mysql_query("
select c.nome, c.matricula,c.situacao, date_format(c.admissao,'%d/%m/%Y') as 'admissao',e.empresa,s.setor  from tb_colaboradores c
inner join tb_empresa e on c.empresa = e.id
inner join tb_setor s on c.setor = s.id
order by c.nome
");

while($cl = mysql_fetch_assoc($cl1)){
if($cl[situacao] == 1){
$sit = "ATIVO";
}
if($cl[situacao] == 0){
$sit = "INATIVO";
}
echo "
	['$cl[nome]','$cl[matricula]','$cl[admissao]','$cl[empresa]','$cl[setor]','$sit','LINK'],
	";
}
?>
];
 
$(document).ready(function() {
    $('#example').DataTable( {
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "Nome" },
            { title: "Matrícula" },
            { title: "Admissão" },
            { title: "Empresa" },
            { title: "Setor" },
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
