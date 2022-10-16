<span class="tt_pg"><b>Agenda Atendimento</b></span><br><br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$salva = $_POST[salva];
$ctr = $_POST[ctr];
$paciente = $_POST[paciente];
$especialidade = $_POST[especialidade];
$esp = mysql_fetch_assoc(mysql_query("SELECT upper(especialidade) as 'especialidade' FROM especialidades where id = $especialidade"));
$atendente = intval($_POST[atendente]);
$dt = $_POST[data];//dd/mm/aaaa 01 34 6789
$data = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
$hora = $_POST[hora];
$hora_t = $_POST[hora_t];
$pg = $_POST[ip];//1-informar /2- agendar

if($salva == 1){
	$paciente = intval($paciente);
	$c1 = mysql_num_rows(mysql_query("select * from consultas where situacao = 1 AND date_format(data,'%Y-%m-%d') = '$data' and atendente = '$atendente' and  ((hr_inicio between '$hora' and subtime('$hora_t','00:01')) or (hr_fim between addtime('$hora','00:01') and '$hora_t'))"));
if($c1 > 0){
echo "<script type='text/javascript'>
alert('AGENDAMENTO NÃO REALIZADO. Horário já ocupado para este atendente.');
</script>
";
}
if($c1 == 0){	
mysql_query("INSERT INTO consultas  
	(data,paciente,especialidade,atendente,situacao,hr_inicio,hr_fim)
	VALUES(
	'$data $hora',
	'$paciente',
	'$especialidade',
	'$atendente',
	'1',
	'$hora',
	'$hora_t')") or die(mysql_error());

//pegar último id p/ chamar a função se informa pagamento ou agenda pagamento
$ua = mysql_fetch_assoc(mysql_query("select max(id) as 'id' from consultas"));
	echo "<META http-equiv='refresh' content='0;URL=index.php?pg=pagamentos&tp=$pg&cn=$ua[id]'>";
}

	/* FUNÇÃO PARA LEAD TIME*/
	/*$lt2 = mysql_query("SELECT time_to_sec(tempo) as 'sec' 
	FROM atendimento  a
	inner join consultas c on a.atendimento = c.id
	where c.atendente = $atendente AND c.especialidade = $especialidade
	order by a.id desc limit 0,5");
	if(mysql_num_rows($lt2) == 5){
	while($lt = mysql_fetch_assoc($lt2)){
echo "$lt1 = ($lt1+$lt[sec])<br>";	
	$lt1 = ($lt1+$lt[sec]);
	}
	$ltF = intval($lt1/5);
	}
	else $ltF = 1200;
$at = 
	$at = mysql_fetch_assoc(mysql_query("select max(id) as 'at' from consultas"));
$at = $at[at];
	mysql_query("UPDATE consultas set hr_fim = sec_to_time(time_to_sec(hr_inicio)+$ltF) 
	where id=$at");*/
//	header("Location:index.php?pg=reg.consulta");
	//echo "<META http-equiv='refresh' content='0;URL=index.php?pg=reg.consulta'>";
}
?>
<form action="#" method="POST" name="rc">
<b>Cliente</b> <input type="text" name="paciente" id="paciente" size="50" value="<?php echo $paciente;?>" style="text-transform:uppercase"><br>
<b>Especialidade</b> <select name="especialidade">
<?php
if($especialidade == '') echo "<option>Selecione...";
if($especialidade != '') echo "<option value='$especialidade'>$esp[especialidade]";
?>
<?php
$es = mysql_query("select id, upper(especialidade) as 'esp' from especialidades order by especialidade");
while($esp = mysql_fetch_assoc($es)){
	echo "<option value='$esp[id]'>$esp[esp]";
}
?>
</select> <br>
<?php
if($ctr == '') {
	?>
	<input type='hidden' name='ctr' value='1'>
	<input type='submit' value='>>' onclick="return validar1()" >
	
	<?php
}
elseif($ctr > 0) {
	?>
<b>Atendente</b> <input type="text" name="atendente" id="atendente" size="50" style="text-transform:uppercase" required><br> 
<b>Data</b> <input type="text" name="data" class="date" id="dt1" size="10" required ><br>
<b>Hora Início</b> <input type="text" name="hora" class="hora" id="dt1" size="6" required ><br>
<b>Hora Término</b> <input type="text" name="hora_t" class="hora" id="dt1" size="6" ><br><br>
<input type="radio" name="ip" value="1" id="ip1"required><label for="ip1"><b>Informar Pagamento</b></label>
<input type="radio" name="ip" value="2" id="ip2"><label for="ip2"><b>Agendar Pagamento</b></label>
<br><input type="hidden" name="salva" value="1">

<br><input type="submit" value="Gravar" onclick="return validar2()">
<?php } ?>
</form>
<script>

</script>
<script language="javascript" type="text/javascript">
  $(function() {
    var pct = [
      <?php
	  $pc = mysql_query("SELECT p.id, upper(p.nome) as 'nome' FROM clientes p ORDER BY nome");
	  while($pac = mysql_fetch_assoc($pc)){
		  echo "'$pac[id] - $pac[nome]',";
	  }	
	  ?>
	  ];
  	var mdc = [
      <?php
	  $md = mysql_query("SELECT a.id, upper(a.nome) as 'nome' FROM atende at 
	  inner join atendentes a on at.atendente = a.id
	  where at.especialidade = '$especialidade'
	  ORDER BY a.nome");
	  while($med = mysql_fetch_assoc($md)){
		  echo "'$med[id] - $med[nome]',";
	  }	
	  ?>
	  ];
    $( "#paciente" ).autocomplete({
      source: pct
    });
    $( "#atendente" ).autocomplete({
      source: mdc
    });

	});
  
  function validar1(){
	var paciente = rc.paciente.value;
	var especialidade = rc.especialidade.value;
	if(paciente == '' || paciente == '0'){
		alert('Informar corretamente o paciente.');
		rc.paciente.focus();
		return false;
	}
	if(especialidade == 'Selecione...'){
		alert('Informar especialidade.');
		rc.especialidade.focus();
		return false;
	}
}

  function validar2(){
	var atendente = rc.atendente.value;
	var data = rc.data.value;
	<?php
	echo "var inicio = rc.inicio.value;
	var fim = rc.fim.value;
	alert(fim);
	return false;
	";
/*	$c1 = mysql_num_rows(mysql_query("select * from consultas where hr_inicio > "?>inicio<?php" and hr_fim < "?>fim<?php "))";*/
?>
	if(atendente == '' || atendente == '0'){
		alert('Informar corretamente o atendente.');
		rc.atendente.focus();
		return false;
	}
	if(data < data.getDate()){
		alert('Data não pode ser inferior a data atual.');
		rc.data.focus();
		return false;
	}
}
</script>
