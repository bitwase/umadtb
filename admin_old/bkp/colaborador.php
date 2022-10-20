<span class="tt_pg"><b></b></span><br><br>
<?php
//verifica se tem permissão de adm para lsitar dados
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$id = $_REQUEST['id'];
//verificar se usuário em questão é lider
$vl = mysql_num_rows(mysql_query("select * from tb_setor where lider = '$id'"));
if($vl == 0){
$lider = false;
}
if($vl > 0){
$lider = true;
$id_foto = "id='lider'";
}

//pegar dados do colaborador selecionado
$cl = mysql_fetch_assoc(mysql_query("
select c.nome, c.setor as 'id_setor', c.matricula, c.email, c.situacao, date_format(c.admissao,'%d/%m/%Y') as 'adm', e.empresa, s.setor from tb_colaboradores c
inner join tb_empresa e on c.empresa = e.id
inner join tb_setor s on c.setor = s.id
where c.id = '$id'
"));
if($cl[situacao] == 1){
$sit = "ATIVO";
}
if($cl[situacao] == 0){
$sit = "INATIVO";
}

echo "
<div id='col_dados'>
	<div id='col_dados_foto'><img src='arquivos/colaboradores/fotos/$cl[matricula].jpg' class='foto_colaborador' $id_foto draggable=\"false\" />
	</div>
	<div id='dados_inf'>
<b>Nome:</b> $cl[nome]<br>
<b>Matrícula:</b> $cl[matricula]<br>
<b>Admissão:</b> $cl[adm]<br>
<b>Empresa:</b> $cl[empresa]<br>
<b>Setor:</b> $cl[setor]<br>
<b>Email:</b> $cl[email]<br>
<b>Situação:</b> $sit<br>
	</div>
<div class='clear'></div>
</div>";
//mostrar equipe
echo "<b><p class='esconde' data-element='#equipe'>Equipe</p></b>";
//primeiro mostrar lider
$ld = mysql_fetch_assoc(mysql_query("
select l.id as 'id_lider',l.nome, l.matricula from tb_colaboradores l
where id = (
	select lider from tb_setor
	where id = $cl[id_setor]
	)
"));
//mostrar dados dos membros da equipe
if(!$lider){
$cl1 = mysql_query("
select c.id as 'id_cl', c.nome, c.matricula, s.setor, c.situacao  from tb_colaboradores c
inner join tb_setor s on c.setor = s.id
where c.id not in($id,$ld[id_lider])
AND c.setor = $cl[id_setor]
AND c.situacao = 1
order by c.nome
");
}
if($lider){
$cl1 = mysql_query("
select c.id as 'id_cl', c.nome, c.matricula, s.setor, c.situacao  from tb_colaboradores c
inner join tb_setor s on c.setor = s.id
where c.id not in($id,$ld[id_lider])
AND c.setor in (
select id from tb_setor where lider = '$id'
)
AND c.situacao = 1
order by s.setor, c.nome
");
}
echo "
<span id='equipe'>
<ul class='grade_colaborador2'>";
if($ld[id_lider] != $id && $ld[id_lider] != 0){
echo"
<br><a href='?pg=colaborador&id=$ld[id_lider]'><li>
<img src='arquivos/colaboradores/fotos/$ld[matricula].jpg' class='col_foto' id='lider' draggable=\"false\" /><br>
$ld[nome]<br>
<b>Líder</b>
</li></a>
";
}
while($cl = mysql_fetch_assoc($cl1)){
echo "<a href='?pg=colaborador&id=$cl[id_cl]'><li>
<img src='arquivos/colaboradores/fotos/$cl[matricula].jpg' class='col_foto' id='$in[id]' draggable=\"false\" /><br>
$cl[nome]<br>
<b>$cl[setor]</b>
</li></a>";
}
echo "</ul>
</span>";
//mostrar calendário do colaborador
echo "<b><p class='esconde' data-element='#calendar'>Agenda</p></b>
	<div id='calendar'></div>
";
//mostrar cursos do colaborador
echo "<b><p class='esconde' data-element='#crs'>Cursos</p></b>";
?><span id="crs">
<table id="cursos" class="display" width="100%"></table>

<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$cr1 = mysql_query("
select t.titulo,substring(t.assunto,1,30) as 'assunto', t.assunto as 'ass', date_format(a.data_inicio,'%d/%m/%Y %H:%i') as 'data', c.nome, m.situacao from tb_matricula m
inner join tb_agenda a on m.agendamento = a.id
inner join tb_cursos t on a.curso = t.id
inner join tb_colaboradores c on m.colaborador = c.id
where colaborador = '$id'
order by data, t.titulo, c.nome
");

while($cr = mysql_fetch_assoc($cr1)){
switch($cr[situacao]){
case 0:
	$sit_insc = 'Ins. Cancelada';
	break;
case 1:
	$sit_insc = 'Incrito';
	break;
case 2:
	$sit_insc = 'Realizado';
	break;
case 3:
	$sit_insc = 'Não Compareceu';
	break;
case 4:
	$sit_insc = 'Curso Cancelado';
	break;
}
$ass = addslashes($cr[ass]);
$ass = nl2br($ass);
$ass = preg_replace('/\s/',' ',$ass);


?>
	["<?php echo $cr[data];?>","<?php echo $cr[titulo]; ?>","<a href='#' class='dcontexto'><?php echo $cr[assunto]; ?><span><?php echo $ass;?></span></a>","<?php echo $sit_insc; ?>","LINK"],
<?php
}
?>
];
 
$(document).ready(function() {
    $('#cursos').DataTable( {
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "Data" },
            { title: "Curso" },
            { title: "Assunto" },
	{ title: "Situação" },
	{ title: "" },
 ]
    } );
} );
</script>
</span>
<?php //gerar calendário do usuário selecionado com base nas inscrições?>
<link href='arquivos/fc/fullcalendar.css' rel='stylesheet' />
<link href='arquivos/fc/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='arquivos/fc/lib/moment.min.js'></script>
<!--script src='arquivos/fc/lib/jquery.min.js'></script-->
<script src='arquivos/fc/fullcalendar.js'></script>
<script>
<?php 
$sql = "
select a.id, t.titulo, date_format(a.data_inicio,'%Y-%m-%dT%H:%i:%s') as 'inicio', date_format(a.data_fim,'%Y-%m-%dT%H:%i:%s') as 'termino' from tb_matricula m
inner join tb_agenda a on m.agendamento = a.id
inner join tb_cursos t on a.curso = t.id
where colaborador = '$id' AND m.situacao > 0
order by inicio";


$res = mysql_query($sql);
$hoje = date("Y-m-d");

?>
	$(document).ready(function() {
		
		$('#calendar').fullCalendar({
		    		header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},

			defaultDate:<?php echo "'$hoje'"; ?>,
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: [
			<?php 
			while ($r = mysql_fetch_assoc($res)){
				echo "{
					title: '$r[titulo]',
					start: '$r[inicio]',
					end: '$r[termino]',
					url: 'index.php?pg=prog&id=$r[id]'
				},
				";
			}
			?>
			]
		});
		
	});

</script>
<style>
	#calendar {
		max-width: 500px;
		margin: 0;
	}

</style>

<script>
$(function(){
    $(".esconde").click(function(e){
        e.preventDefault();
        el = $(this).data('element');
        $(el).toggle();
    });
});
</script>

