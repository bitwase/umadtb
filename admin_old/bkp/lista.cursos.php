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
select c.titulo,substring(c.assunto,1,30) as 'assunto', c.assunto as 'ass', c.tempo, c.validade,t.tipo,c.situacao from tb_cursos c
inner join tb_tipo t on c.tipo = t.id
order by c.titulo
");

while($cr = mysql_fetch_assoc($cr1)){
if($st[situacao] == 1){
$sit = "ATIVO";
}
if($st[situacao] == 0){
$sit = "INATIVO";
}
$ass = addslashes($cr[ass]);
$ass = nl2br($ass);
$ass = preg_replace('/\s/',' ',$ass);
?>
	["<?php echo $cr[titulo];?>","<a href='#' class='dcontexto'><?php echo addslashes($cr[assunto]); ?><span><div style='position:relative;height:auto;'><?php echo $ass;?></div></span>","<?php echo $cr[tempo]; ?>","<?php echo $cr[validade]; ?>","<?php echo $cr[tipo]; ?>","<?php echo $sit; ?>","<?php echo 'LINK'; ?>"],
<?php
}
?>
];
 
$(document).ready(function() {
    $('#example').DataTable( {
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "Título" },
            { title: "Assunto" },
            { title: "Tempo(h)" },
	{ title: "Validade(meses)" },
	{ title: "Tipo" },
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
