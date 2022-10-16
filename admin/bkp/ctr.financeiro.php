<span class="tt_pg"><b>Controle Financeiro</b></span><br>
<p class="tt"><?php echo "$tt" ?></p>
<?php
//dados para filtro
$filtrar = $_POST[filtrar];//verifica se realiza filtro
$ft = " f.id > 0";

if($filtrar == 1){
$di = $_POST[di];//data inicial registro
if($di != ""){
$di = $di[6].$di[7].$di[8].$di[9]."-".$di[3].$di[4]."-".$di[0].$di[1];
	$ft .= " AND f.data >= '$di 00:00:00'";
}
$df = $_POST[df];//data final registro
if($df != ""){
$df = $df[6].$df[7].$df[8].$df[9]."-".$df[3].$df[4]."-".$df[0].$df[1];
	$ft .= " AND f.data <= '$df 23:59:59'";
}
$dia = $_POST[dia];//data inicial agendamento
if($dia != ""){
$dia = $dia[6].$dia[7].$dia[8].$dia[9]."-".$dia[3].$dia[4]."-".$dia[0].$dia[1];
	$ft .= " AND f.dt_ag >= '$dia'";
}
$dfa = $_POST[dfa];//data final agendamento
if($dfa != ""){
$dfa = $dfa[6].$dfa[7].$dfa[8].$dfa[9]."-".$dfa[3].$dfa[4]."-".$dfa[0].$dfa[1];
	$ft .= " AND f.dt_ag <= '$dfa'";
}
$us = $_POST[us];//usuário de registro
if($us != ""){
		$ft .= " AND f.us = '$us'";
}
$tipo = $_POST[tpmov];//tipo de movimentação, caso exista
if($tipo != ""){
	$ft .= " AND f.tipo = '$tipo'";
}
$motivo = $_POST[mtmov];//motivo Atendimento Compra Venda contas a Pagar contas a Receber
if($motivo != ""){
	$ft .= " AND f.tipo2 like '$motivo%'";
}
$sit = $_POST[sit];//agendado , realizado, cancelado
if($sit != ""){
	$ft .= " AND f.sit = $sit";
}
}
$maat = date('m/Y');
if($ft == " f.id > 0"){
	$ft .= " AND date_format(f.data,'%m/%Y') like '%$maat'";
}
////////////////////

/// SALDO GERAL SEM FILTRO ///

$entt = mysql_fetch_assoc(mysql_query("select sum(f.valor) as 'ent' from financeiro f where f.tipo = 1 and f.sit != 3"));
$entt = number_format($entt[ent],2,".","");
$sait = mysql_fetch_assoc(mysql_query("select sum(f.valor) as 'sai' from financeiro f where f.tipo = 2 and f.sit != 3"));
$sait = number_format($sait[sai],2,".","");
if($sait == "")
	$sait = "0.00";
$saldot = number_format($entt - $sait,2,".","");

$entt1 = mysql_fetch_assoc(mysql_query("select sum(f.valor) as 'ent' from financeiro f where f.tipo = 1 and f.sit = 2"));
$entt1 = number_format($entt1[ent],2,".","");
$sait1 = mysql_fetch_assoc(mysql_query("select sum(f.valor) as 'sai' from financeiro f where f.tipo = 2 and f.sit = 2 "));
$sait1 = number_format($sait1[sai],2,".","");
if($sait1 == "")
	$sait1 = "0.00";
$saldot1 = number_format($entt1 - $sait1,2,".","");

/// SALDO COM FILTRO ///
$ent = mysql_fetch_assoc(mysql_query("select sum(f.valor) as 'ent' from financeiro f where $ft AND f.tipo = 1 and f.sit != 3"));
$ent = number_format($ent[ent],2,".","");
$sai = mysql_fetch_assoc(mysql_query("select sum(f.valor) as 'sai' from financeiro f where $ft AND f.tipo = 2 and f.sit != 3"));
$sai = number_format($sai[sai],2,".","");
if($sai == "")
	$sai = "0.00";
$saldo = number_format($ent - $sai,2,".","");

$ent1 = mysql_fetch_assoc(mysql_query("select sum(f.valor) as 'ent' from financeiro f where $ft AND f.tipo = 1 and f.sit = 2"));
$ent1 = number_format($ent1[ent],2,".","");
$sai1 = mysql_fetch_assoc(mysql_query("select sum(f.valor) as 'sai' from financeiro f where $ft AND f.tipo = 2 and f.sit = 2 "));
$sai1 = number_format($sai1[sai],2,".","");
if($sai1 == "")
	$sai1 = "0.00";
$saldo1 = number_format($ent1 - $sai1,2,".","");

?>
<b>Filtro</b><br>
<form action="#" method="POST">
<input type="hidden" name="filtrar" value="1">
<b>Registro </b>entre <input type="text" size="11" class="date" name="di"> e <input type="text" size="11" class="date" name="df"><img src="arquivos/icones/45.png" class="bt_p" title="Caso não seja informado um período, irá mostrar todas as transações.  Se for informado apenas data inicial, mostrará todos os valores a partir da data informada.  Informando apenas data final, mostrará todos os valores até a data informada."><br>
<b>Agendamento </b>entre <input type="text" size="11" class="date" name="dia"> e <input type="text" size="11" class="date" name="dfa"><img src="arquivos/icones/45.png" class="bt_p" title="Caso não seja informado um período, irá mostrar todas as transações.  Se for informado apenas data inicial, mostrará todos os valores a partir da data informada.  Informando apenas data final, mostrará todos os valores até a data informada."><br>
<b>Usuário de Registro</b> <select name="us">
<option value="" selected disabled>Selecione</option>
<?php
$us1 = mysql_query("select * from usuarios order by nome");
while($us = mysql_fetch_assoc($us1)){
	echo "<option value='$us[id]'>$us[nome]</option>";
}
?>
</select><br>
<b>Tipo de Movimentação</b><select name="tpmov">
<option value="" selected disabled>Selecione</option>
<option value="1">Entrada</option>
<option value="2">Saída</option>
</select><br>
<b>Motivo de Movimentação</b> <select name="mtmov">
<option value="" selected disabled>Selecione</option>
<option value="A">Atendimento</option>
<option value="V">Vendas</option>
<option value="C">Compras</option>
<option value="P">Contas a Pagar</option>
<option value="R">Contas a Receber</option>
</select><br>
<b>Situação</b> <select name="sit">
<option value="" selected disabled>Selecione</option>
<option value="1">Agendado</option>
<option value="2">Realizado</option>
<option value="3">Cancelado</option>
</select><br>
<input type="submit" value="Filtrar">
</form>
<br><br>
<div class="saldo" style="position:relative; float:left;">
<b>Total</b><br>
Confirmado<br>
<span style="color:green"> <b>Entradas:</b> R$<?php echo $entt1;?></span> <span style="color:red"><b>Saídas:</b> R$<?php echo $sait1;?></span> <b>Saldo:</b> R$<?php echo $saldot1;?> 
<br>
Previsão<br>
<span style="color:green"> <b>Entradas:</b> R$<?php echo $entt;?></span> <span style="color:red"><b>Saídas:</b> R$<?php echo $sait;?></span> <b>Saldo:</b> R$<?php echo $saldot;?> 
</div>
<div class="saldo" style="position:relative; float:right;">
<b>Período</b><br>
Confirmado<br>
<span style="color:green"> <b>Entradas:</b> R$<?php echo $ent1;?></span> <span style="color:red"><b>Saídas:</b> R$<?php echo $sai1;?></span> <b>Saldo:</b> R$<?php echo $saldo1;?> 
<br>
Previsão<br>
<span style="color:green"> <b>Entradas:</b> R$<?php echo $ent;?></span> <span style="color:red"><b>Saídas:</b> R$<?php echo $sai;?></span> <b>Saldo:</b> R$<?php echo $saldo;?> 
</div>
<div id="mObs" style="display:none;">
<br>
</div>
<table id="tabela" class="display" width="100%"></table>

<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$fin = mysql_query("
SELECT f.id, date_format(f.data,'%d/%m/%Y %H:%i') as 'data', date_format(f.dt_ag,'%d/%m/%Y') as 'ag', f.tipo, f.valor, f.motivo, u.nome, f.sit, f.obs FROM financeiro f
inner join usuarios u on f.us = u.id 
where $ft
order by f.data desc
");
$ord = 0;
while($fn = mysql_fetch_assoc($fin)){
$ord++;
	if($fn[tipo] == 1){
		$tipo = "Entrada";
		$cor = "green";
	}
	else if($fn[tipo] == 2){
		$tipo = "Saída";
		$cor = "red";
	}
	
	if($fn[sit] == 1)
		$situacao = "Agendado";
	elseif($fn[sit] == 2)
		$situacao = "Realizado";
	elseif($fn[sit] == 3)
		$situacao = "Cancelado";
		
	if($fn[obs] != ""){
	$obs = "<img src=\'arquivos/icones/45.png\' class=\'bt\' id=\'btObs$fn[id]\' title=\'\' onMouseOver=\'mostraObs($fn[id]);\' onMouseOut=\'escondeObs($fn[id]);\'>";
	}
	else if($fn[obs] == ""){
		$obs = "";
	}
	echo "
	['$ord','$fn[data]','$tipo','<span style=\"color:$cor\">R$$fn[valor]</span>','$fn[motivo]','$fn[nome]','$situacao','$fn[ag]','$obs'],
	";
}
?>
];
 
$(document).ready(function() {
    $('#tabela').DataTable( {
	 "scrollX": true,
	"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }],
         "paging": false,
	"filter": false,
		data: dataSet,
        columns: [
            { title: "" },
            { title: "Data" },
            { title: "Tipo Mov." },
            { title: "Valor" },
            { title: "Motivo" },
            { title: "Usuário" },
            { title: "Situação" },
            { title: "Data Ag." },
            { title: "Obs." }
        ]
    } );
} );

function mostraObs(id){
//esta função deverá retornar por meio de jSon a observação e inserir isto na div mObs
	mostraMascara();
	
	$.getJSON('retfinanceiro.php?id='+id, function(pagaData){
	var obs = [];
	
	$(pagaData).each(function(key, value){
		obs.push(value.obs);
	});
	
	//escrever os dados
	document.getElementById("mObs").innerHTML = "<b>Observação</b>: <br>"+obs;
});
	document.getElementById("btObs"+id).className = "btC";
	document.getElementById("mObs").style.display = "block";
}
function escondeObs(id){
	escondeMascara();
	document.getElementById("btObs"+id).className = "bt";
	document.getElementById("mObs").style.display = "none";
}
</script>
