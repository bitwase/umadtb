<span class="tt_pg"><b>Lista Cursos</b></span><br><br>
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
$cr1 = mysql_query("
select t.titulo,substring(t.assunto,1,30) as 'assunto', t.assunto as 'ass', date_format(a.data_inicio,'%d/%m/%Y %H:%i') as 'data', c.nome from tb_matricula m
inner join tb_agenda a on m.agendamento = a.id
inner join tb_cursos t on a.curso = t.id
inner join tb_colaboradores c on m.colaborador = c.id
where date_format(now(),'%Y-%m-%d %H:%i') < a.data_inicio AND a.situacao > 0 AND m.situacao > 0
order by data, t.titulo, c.nome
");

while($cr = mysql_fetch_assoc($cr1)){
$ass = addslashes($cr[ass]);
$ass = nl2br($ass);
$ass = preg_replace('/\s/',' ',$ass);
?>

	["<?php echo $cr[data];?>","<?php echo $cr[titulo]; ?>","<a href='#' class='dcontexto'><?php echo addslashes($cr[assunto]); ?><span><?php echo $ass;?></span></a>","<?php echo $cr[nome]; ?>"],
<?php
}
?>
];
 
$(document).ready(function() {
    $('#example').DataTable( {
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "Data" },
            { title: "Curso" },
            { title: "Assunto" },
	{ title: "Colaborador" },
 ]
    } );
} );
</script>
<script>
jQuery(function($){
   $("#data_ad").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
}
</script>
