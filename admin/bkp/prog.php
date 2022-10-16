<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$id = $_REQUEST[id];
$ag1 = mysql_query("
select c.titulo, c.assunto, date_format(a.data_inicio,'%d/%m/%Y') as 'dt_inicio', date_format(a.data_inicio,'%H:%i') as 'hr_inicio', date_format(a.data_inicio,'%Y/%m/%d') as 'dt', a.local
from tb_agenda a
inner join tb_cursos c on a.curso = c.id
where a.id = '$id'
"); 
$ag = mysql_fetch_assoc($ag1);
//verificar se pode ou não editar ainda
$ver_ed = date('Y-m-d');
$in = strtotime($ag[dt]);
$fn = strtotime($ver_ed);
$dia =  $in-$fn;

echo "<span class='tt_pg'><b>$ag[titulo]</b></span><br><br>";


/*if($nv_acesso > 3){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}*/

$salva = $_POST[salva];

if($salva == 1){
}
$ass = $ag[assunto];
$ass = nl2br($ass);
$ass = preg_replace('/\s/',' ',$ass);

echo "<br><br>
<b>Data:</b> $ag[dt_inicio]<br>
<b>Horário:</b> $ag[hr_inicio]<br>
<b>Local:</b> $ag[local]<br><br>
<b>Descrição</b><br><br>
$ass<br><hr>
";
//listar inscritos
echo "<b>Inscritos </b><br><br>";
$in1 = mysql_query("
select c.id, c.nome,c.matricula, s.setor, s.lider from tb_matricula m
inner join tb_colaboradores c on m.colaborador = c.id
left join tb_setor s on c.setor = s.id
where m.agendamento = '$id' AND m.situacao > '0'
order by s.setor,c.nome
");
echo "<section ondrop=\"dragDrop(event,'inscrito')\" 
     ondragover=\"dragOver(event)\"><ul class='colaborador'>";
$n = 0;
while($in = mysql_fetch_assoc($in1)){

if($in[lider] == $us_id && $nv_acesso == 3){
$drag = 'true';
}
if($nv_acesso < 3){
$drag = 'true';
}

if(($in[lider] != $us_id && $nv_acesso == 3) || $nv_acesso == 4 || $dia<=0){
$drag = 'false';
}
echo "<li>
<img src='arquivos/colaboradores/fotos/$in[matricula].jpg' class='col_foto' id='$in[id]' draggable=\"$drag\" ondragstart=\"dragStart(event,'inscrito')\"/><br>
$in[nome]<br>
<b>$in[setor]</b>
</li>";
}
echo "</ul></section>";
//listar inscrever
if($nv_acesso < 4 && $dia > 0){
echo "<hr><b>Inscrever</b><br><br>";
if($nv_acesso < 3){
$nv1 = mysql_query("
select c.*,s.setor from tb_colaboradores c
inner join tb_setor s on c.setor = s.id 
where c.id not in(
select colaborador from tb_matricula where agendamento = '$id' AND situacao > '0'
) AND c.id not in(
select distinct colaborador from tb_matricula where situacao = '1' AND agendamento in (
select id from tb_agenda where curso = (select curso from tb_agenda where id = '$id'))
)
AND c.id not in(
select distinct colaborador from tb_matricula where situacao = '1' AND agendamento in (
select id from tb_agenda where curso = (select curso from tb_agenda where id = '$id'))
)
order by s.setor,c.nome 
");
}
if($nv_acesso == 3){
$nv1 = mysql_query("
select c.*,s.setor from tb_colaboradores c
inner join tb_setor s on c.setor = s.id 
where c.id not in(
select colaborador from tb_matricula where agendamento = '$id' AND situacao > '0'
)
AND c.id not in(
select distinct colaborador from tb_matricula where situacao = '1' AND agendamento in (
select id from tb_agenda where curso = (select curso from tb_agenda where id = '$id'))
)
AND c.id not in
(select distinct colaborador from tb_matricula where situacao = '2' AND agendamento in 
(select id from tb_agenda where curso = (
select a.curso from tb_agenda a 
inner join tb_cursos c on a.curso = c.id
where a.id = '$id'
and (date_add(a.data_fim, interval c.validade month)) > (now())
)
)
)
AND
c.setor in(
select id from tb_setor where lider = '$us_id'
)
order by s.setor,c.nome 
");
}

echo "<section ondrop=\"dragDrop(event,'inscrever')\" 
     ondragover=\"dragOver(event)\"><ul class='colaborador'>";
while($nv = mysql_fetch_assoc($nv1)){
echo "<li>
<img src='arquivos/colaboradores/fotos/$nv[matricula].jpg' class='col_foto' id='$nv[id]'  draggable=\"$drag2\" ondragstart=\"dragStart(event,'inscrever')\"/><br>
$nv[nome]<br>
<b>$nv[setor]</b>
</li>";
}
echo "</ul></section>";
}//se acesso for diferente de colaborador
?>
<script>
      function dragStart(ev,atual) {
    ev.dataTransfer.effectAllowed = 'move';
    ev.dataTransfer.setData('Text', ev.target.getAttribute('id'));
    ev.dataTransfer.setData('atual', atual);
    //ev.dataTransfer.setDragImage(ev.target, 100, 100);
    //return true;
}
function dragEnter(ev) {
    ev.preventDefault();
    //return true;
}
function dragOver(ev) {
//    event.preventDefault();
ev.preventDefault();
}
function dragDrop(ev,id) {
    var data = ev.dataTransfer.getData('Text');
    var atual = ev.dataTransfer.getData('atual');
var dest = id;
var ag = <?php echo $id;?>;
if(dest != atual){
	if(atual == 'inscrever'){
		location.href="index.php?pg=atcurso&a=1&ag="+ag+"&c="+data;
	}
	if(atual == 'inscrito'){
		location.href="index.php?pg=atcurso&a=2&ag="+ag+"&c="+data;
	}
//alert('Arrastou '+data+' de '+atual+' para' +dest);
    ev.target.appendChild(document.getElementById(data));
    ev.stopPropagation();
}
    //return false;
ev.preventDefault();
}
      //@ sourceURL=pen.js
    </script>

    <script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>


<?php
/*
select distinct colaborador from tb_matricula where situacao = '2' AND agendamento in 
(select id from tb_agenda where curso = (
select a.curso from tb_agenda a 
inner join tb_cursos c on a.curso = c.id
where a.id = '$id'
and (date_add(a.data_fim, interval c.validade month)) > (now())
)
)

-----------
tentar usar essa lógica abaixo

select a.curso from tb_agenda a 
inner join tb_cursos c on a.curso = c.id
where a.id = '$id'
and (date_add(a.data_fim, interval (
select validade from tb_agenda where id = '1'
 ) month)) > (now())



select a.curso, date_add('a.data_fim', interval 
c.validade month) as 'data'
 from tb_agenda a 
inner join tb_cursos c on a.curso = c.id
where a.id = '2'

date_add('a.data_fim', interval (
select validade from tb_agenda where id = '1'
 ) month)
 
 
 

*/
?>
