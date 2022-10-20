<?php
include '../../seguranca.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='../fullcalendar.css' rel='stylesheet' />
<link href='../fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='../lib/moment.min.js'></script>
<script src='../lib/jquery.min.js'></script>
<script src='../fullcalendar.min.js'></script>
<script>
<?php 
$sql = "select c.*,p.nome from consultas c
inner join pacientes p
on c.paciente = p.id
order by data ASC";
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
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: [
			<?php 
			while ($r = mysql_fetch_assoc($res)){
				if($r[hr_fim] == ""){
					$hr_fim = $r[hr_inicio];
				}
				else{
					$hr_fim = $r[hr_fim];
				}
				echo "{
					title: '$r[nome]',
					start: '$r[data]T$r[hr_inicio]',
					end: '$r[data]T$hr_fim'
				},
				";
			}
			?>
			]
		});
		
	});

</script>
<style>

	body {
		margin: 40px 10px;
		padding: 0;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		font-size: 14px;
	}

	#calendar {
		max-width: 600px;
		margin: 0 auto;
	}

</style>
</head>
<body>

	<div id='calendar'></div>

</body>
</html>
