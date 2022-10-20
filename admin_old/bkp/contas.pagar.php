<span class="tt_pg"><b>Contas a Pagar</b></span>
<br>
<br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//verifica se tem permissão de adm para incluir dados
/*
inserir regra para que automaticamente altere situação de 4 pagamento agendado para concluído quando informado pagamento da última parcela
*/

if ($nv_acesso > 2) {
	echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
	echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}

$agora = date('dmYHis');
$agr = date("d/m/Y H:i");
$salva = $_POST[salva];
$hj = date("d/m/Y");
$hj2 = date("Y-m-d");

if ($salva == 1) {
    $vlr = $_POST[vlr];
    $obs = nl2br($_POST[obs]);
	//$obs = str_replace("<br />","<br>",$obs);
	$desc = $_POST[desc];
    $dt = $_POST[venc];//01/34/6789
    $dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
    mysql_query("
        insert into financeiro (data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
        (now(),'2','P','$vlr','Contas a Pagar - $desc','$cod_us','1','$dt','$obs')
        ") or die(mysql_error());
	echo "<script type='text/javascript'>alert('Agendamento Realizado com Sucesso.');</script>";
	echo "<META http-equiv='refresh' content='0'>";
}

//******************** INFORMANDO PAGaMENTO OU CANCELANDO ALGO AGENDADO *******************
$id = $_REQUEST[id];
$acao = $_POST[acao];
$cp = mysql_fetch_assoc(mysql_query(
	"select id, date_format(dt_ag,'%d/%m/%Y') as 'vct', motivo, dt_ag as 'ag', tipo2, valor, obs from financeiro where id = $id"
	));

	//verificar parcela anterior em aberto;
$ver = mysql_num_rows(mysql_query("select * from financeiro where tipo2 = '$cp[tipo2]' and sit = 1 and dt_ag < '$cp[ag]'"));

//verifica quantos existem ainda a ser pago... se for mais de um, não faz nada, se for 1, pega 
$ver2 = mysql_num_rows(mysql_query("select * from financeiro where tipo2 = '$cp[tipo2]' and sit = 1"));

if($ver > 0){
	echo "<script>
	alert('Existe parcela anterior com pendência.');
	fecha();
	</script>";
}	
if($ver == 0){	
if($acao == 1){//cancelar
	$hj = date("d/m/Y H:i");
	$mtv = $_POST[mtv];
	$obs = $cp[obs]." <br> Cancelado por $nome_ em $hj. Motivo: $mtv";
	mysql_query("update financeiro set sit = '3', obs = '$obs' where id = $id");
	echo "<script>alert('Agendamento de Pagamento cancelado.');</script>";
}

if($acao == 2){//realizar
	$hj = date("d/m/Y H:i");
	$data = $_POST[data];
	$obs = $cp[obs]." <br> Realizado por $nome_ em $hj. Pago em: $data";
	mysql_query("update financeiro set sit = '2', obs = '$obs' where id = $id");
	echo "<script>alert('Agendamento de Pagamento Concluído.');
	</script>";
}
//verificar se é compra ou venda
	if($ver2 == 1){
	$tpm = substr($cp[tipo2],0,1);//TiPo Movimentação
	$nmv = substr($cp[tipo2],1);//numero da movimentação
	if($tpm == "C"){
		mysql_query("update compra set st = 3 where id = '$nmv'");
	}
	if($tpm == "V"){
		mysql_query("update vendas set st = 3 where id = '$nmv'");
	}
	}	
}
?>
<form action="#" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="salva" value="1">
    <b>Valor: </b><input type="text" name="vlr" class="vlr" size="6" required><br>
    <b>Vencimento: </b><input type="text" class="date" size="9" name="venc" required><br>
    <b>Descrição: </b><input type="text" name="desc" size="30" required /><br>
	<b>Obsevações: </b><br>
	<textarea name="obs" rows="3" cols="40" maxlenght="200"></textarea>
	<input type="submit" value="Salvar">
</form>
<hr>
<div id="pagamento" style="display:none">
<img src="arquivos/icones/116.png" class="bt" style="position:absolute; top:5px; right:5px;" onclick="canPaga()">
<div id="pgdd"></div>
<input type='button' value='Informar Pagamento' onclick='infPagamento()'> <input type='button' value='Cancelar Pagemento' onclick='cancelaPagamento()'>   

<div id="cancPagamento" style="display:none">
<b>Deseja realmente cancelar este agendamento?</b><br>
<form action="#" method="POST">
<input type="hidden" name="id" id="id1" value="">
<input type="hidden" name="acao" value="1">
<b>Motivo:</b> <input type="text" name="mtv" id="mtv" size="35" required><br>
<input type="submit" value="Sim" style="background:#3CB371"> 
<input type="button" value="Não" style="background:#FF4500" onclick="naocancela()">
</form>
</div>

<div id="paga" style="display:none">
<b>Informar Pagamento</b><br>
<form action="#" method="POST">
<input type="hidden" name="id" id="id2" value="">
<input type="hidden" name="acao" value="2">
<b>Data de Pagamento:</b> <input type="text" name="data" class="date" id="data" size="10" required><br>
<input type="submit" value="Salvar" style="background:#3CB371"> 
</form>
</div>

</form>
</div>
<table id="apagar" class="display" width="100%"></table>

<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$cap = mysql_query(
	"select id, date_format(dt_ag,'%d/%m/%Y') as 'vct', motivo, valor, obs from financeiro where tipo = 2 and sit = 1"
	);

while($cp = mysql_fetch_assoc($cap)){
	$lk = "<a href=\'#\' onclick=\'paga($cp[id])\'><img src=\'arquivos/icones/44.png\' class=\'bt\' /></a>";
	$obs2 = $cp[obs];
	$obs2 = str_replace("\n","<br>",$obs2);
	$obs2 = str_replace("<br />","<br>",$obs2);
	$obs2 = str_replace("\n\r","<br>",$obs2);
	$obs2 = str_replace("\r\n,","<br>",$obs2);
	$obs2 = str_replace("<br>"," ",$obs2);
	echo "
	['$cp[vct]','$cp[motivo]','R$$cp[valor]','','$lk'],
	";
}
?>
];
 
$(document).ready(function() {
    $('#apagar').DataTable( {
	 "scrollX": true,
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "Vencimento" },
            { title: "Descrição" },
            { title: "Valor" },
            { title: "Obs" },
            { title: "" },
        ]
    } );
} );
</script>
<script>
function paga(id){
	mostraMascara();
$.getJSON('retpaga.php?id='+id+'&tp=1', function(pagaData){
	var vct = [];
	var motivo = [];
	var valor = [];
	var obs = [];
	
	$(pagaData).each(function(key, value){
		vct.push(value.vct);
		motivo.push(value.motivo);
		valor.push(value.valor);
		obs.push(value.obs);
	});
	
	//escrever os dados
	var form = "";
	document.getElementById("pgdd").innerHTML = "<span class='tt_pg'><b>Informar Pagamento</b></span><br><b>Vencimento</b>: "+vct+"<br><b>Descrição</b>: "+motivo+"<br><b>Valor</b>: R$"+valor+"<br><b>Observação</b>: "+obs+"<br><br>";
});
	document.getElementById("pagamento").style.display= "block";
	document.getElementById("id1").value = id;
	document.getElementById("id2").value = id;

//abaixo para chamar as funções para permitir alterar

}
function canPaga(){
	escondeMascara();
	document.getElementById("pagamento").style.display = "none";
}

function cancelaPagamento(){
	document.getElementById("cancPagamento").style.display = "block";
	document.getElementById("paga").style.display = "none";
	document.getElementById("data").required = false;
	document.getElementById("mtv").required = true;
}
function naocancela(){
	document.getElementById("cancPagamento").style.display = "none";
	document.getElementById("mtv").required = false;
}
function infPagamento(){
	naocancela();
	document.getElementById("paga").style.display = "block";
	document.getElementById("data").required = true;
}

function paga1(id){
	mostraMascara();
	window.open("pagar.php?id="+id, "_blank", "toolbar=no,scrollbars=no,resizable=no,top=100,left=500,width=400,height=400");
}
</script>
