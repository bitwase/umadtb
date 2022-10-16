<?php
include 'seguranca.php';
$id = $_REQUEST[id];
$pa = mysql_fetch_assoc(mysql_query("select a.id, date_format(a.data,'%d/%m/%Y %H:%i') as 'data', date_format(a.hr_fim,'%H:%i') as 'hr_fim', cl.nome as 'cliente', e.especialidade, at.nome as 'atendente', a.atendente as 'idat', a.paciente as 'idcli', a.especialidade as 'idesp' from consultas a 
	inner join clientes cl on a.paciente = cl.id
	inner join especialidades e on a.especialidade = e.id
	inner join atendentes at on a.atendente = at.id
	where a.id = $id"));
if($pa){
echo json_encode($pa);
}
?>
