<span class="tt_pg"><b>Contas a Receber</b></span>
<br>
<br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//verifica se tem permissão de adm para incluir dados
if ($nv_acesso > 2) {
	echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
	echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}

$aLink = $_REQUEST[aL];

$agora = date('dmYHis');
$agr = date("d/m/Y H:i");
$salva = $_POST[salva];
$hj = date("d/m/Y");
$hj2 = date("Y-m-d");

if ($salva == 1) {
    $vlr = $_POST[vlr];
    $obs = nl2br($_POST[obs]);
	$obs = str_replace($obs,"<br />","<br>");
	$desc = $_POST[desc];
    $dt = $_POST[venc];//01/34/6789
    $dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
    mysql_query("
        insert into financeiro (data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
        (now(),'1','R','$vlr','Contas a Receber - $desc','$cod_us','1','$dt','$obs')
        ");
	echo "<script type='text/javascript'>alert('Agendamento Realizado com Sucesso.');</script>";
	echo "<META http-equiv='refresh' content='0'>";
}

//******************** INFORMANDO PAGaMENTO OU CANCELANDO ALGO AGENDADO *******************
$id = $_REQUEST[id];
$acao = $_POST[acao];
$cp = mysql_fetch_assoc(mysql_query(
	"select id, date_format(dt_ag,'%d/%m/%Y') as 'vct', dt_ag as 'ag', tipo2, motivo, valor, obs from financeiro where id = $id"
	));

	//verificar parcela anterior em aberto;
$ver = mysql_num_rows(mysql_query("select * from financeiro where tipo2 = '$cp[tipo2]' and sit = 1 and dt_ag < '$cp[ag]'"));
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
//colocar regras para tipo de pagamento em cheques
	$hj = date("d/m/Y H:i");
	$data = $_POST[data];
	$obs = $cp[obs]." <br> Realizado por $nome_ em $hj. Recebimento em: $data";
	$tppg = $_POST[tppg];
	if($tppg == 1){//se for em dinheiro
		$obs .= "<br>Recebimento em dinheiro.";
	}
	if($tppg == 2){//se for em dinheiro
		$dt_cheque = $_POST[dt_cheque];
		$nm_cheque = $_POST[nm_cheque];
		$doc_cheque = $_POST[doc_cheque];
		$num_cheque = $_POST[num_cheque];
		$bc_cheque = $_POST[bc_cheque];
		$ag_cheque = $_POST[ag_cheque];
		$ct_cheque = $_POST[ct_cheque];
		$obs .= "<br>Recebimento em Cheque.<br>
		<b>Bom para:</b>$dt_cheque<br>
		<b>Nome:</b>$nm_cheque<br>
		<b>Documento:</b>$doc_cheque<br>
		<b>Num. Cheque:</b>$num_cheque<br>
		<b>Banco:</b>$bc_cheque<br>
		<b>Agência:</b>$ag_cheque<br>
		<b>Conta:</b>$ct_cheque<br>
		";
	}
	if($tppg == 3){//se for em débito
		$obs2 = addslashes($_POST[card]);
		$obs .= "<br>Recebimento em débito.";
		$obs .= "<br>".$obs2;
	}
	if($tppg == 4){//se for em crédito
		$obs2 = addslashes($_POST[card]);
		$obs .= "<br>Recebimento em crédito.";
		$obs .= "<br>".$obs2;
	}
	mysql_query("update financeiro set sit = '2', obs = '$obs' where id = $id")or die(mysql_error());
	$ver2 = mysql_num_rows(mysql_query("select * from financeiro where tipo2 = '$cp[tipo2]' and sit = 1 "));
	if($ver2 == 0){
		$tpa = substr($cp[tipo2],0,1);//tipo de ação A gendamento; V enda;
		$tpnum = substr($cp[tipo2],1);
		if($tpa == "V"){
			//se for venda, tem que mudar status para 3
			mysql_query("update vendas set st = 3 where id = '$tpnum'");
		}
	}
	echo "<script>alert('Recebimento Concluído.');</script>";
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
<input type='button' value='Informar Pagamento' onclick='infPagamento()' id='btInfPg'> <input type='button' value='Cancelar Pagemento' onclick='cancelaPagamento()' id='btCanPg'>   

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
<?php $dtPG = date("d/m/Y");?>
<b>Data de Pagamento:</b> <input type="text" name="data" class="date" id="data" size="10" value="<?php echo $dtPG;?>" required><br>
<b>Tipo de Pagamento:</b> 
<input type="radio" name="tppg" id="tppg1" value="1" required onclick="mtppg(1)"><label for="tppg1" >Dinheiro</label>
<input type="radio" name="tppg" id="tppg2" value="2" required><label for="tppg2" onclick="mtppg(2)">Cheque</label>
<input type="radio" name="tppg" id="tppg3" value="3" required><label for="tppg3" onclick="mtppg(3)">Débito</label>
<input type="radio" name="tppg" id="tppg4" value="4" required><label for="tppg4" onclick="mtppg(4)">Crédito</label><br><br>
<div id="pgd" style="display:none;">
<b>Dinheiro</b><br><br>
<b>Total:</b> <input type="text" name="vlt" id="vltd" size="5" disabled><br>
<b>Recebido:</b> <input type="text" name="vlr" id="vlrd" size="5" required class="vlr" onchange="troco()"><br>
<b>Troco:</b> <input type="text" name="vltr" id="vltrd" size="5"><br>
</div>

<div id="pgch" style="display:none;">
<b>Cheque bom Para: </b><input type="text" class="date" name="dt_cheque" id="dt_cheque" size="11" required><br>
<b>Nome:</b> <input type="text" name="nm_cheque" id="nm_cheque" required size="25" /><br>
<b>CPF/CNPJ:</b> <input type="text" name="doc_cheque" id="doc_cheque" required size="15" /><br>
<b>Nº. Cheque:</b> <input type="text" name="num_cheque" id="num_cheque" required size="9" /><br>
<b>Banco:</b> <input type="text" name="bc_cheque" id="bc_cheque" required size="3" />
<b>Agência:</b> <input type="text" name="ag_cheque" id="ag_cheque" required size="6" />
<b>Conta:</b> <input type="text" name="ct_cheque" id="ct_cheque" required size="6" />
</div>
<div id="cartao" style="display:none;">
<b>Cartão</b><br><br>
<b>Observação:</b> <textarea name="card" id="card" rows="4" cols="50"></textarea><br>
</div>
<input type="submit" value="Salvar" style="background:#3CB371" id="cnt" title="Salvar"> 
</form>
</div>

</form>
</div>
<table id="apagar" class="display" width="100%"></table>

<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$cap = mysql_query(
	"select id, date_format(dt_ag,'%d/%m/%Y') as 'vct', motivo, valor, obs from financeiro where tipo = 1 and sit = 1 order by dt_ag"
	);
$ord = 0;
while($cp = mysql_fetch_assoc($cap)){
$ord++;
	$lk = "<a href=\'#\' onclick=\'paga($cp[id])\'><img src=\'arquivos/icones/44.png\' class=\'bt\' /></a>";
	$obs = str_replace($cp[obs],"<br />","<br>");
	echo "
	['$ord','$cp[vct]','$cp[motivo]','R$$cp[valor]','$obs','$lk'],
	";
}
?>
];
 
$(document).ready(function() {
    $('#apagar').DataTable( {
	 "scrollX": true,
	"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }],
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "Ord" },
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
	document.getElementById("pgdd").innerHTML = "<span class='tt_pg'><b>Informar Recebimento</b></span><br><b>Vencimento</b>: "+vct+"<br><b>Descrição</b>: "+motivo+"<br><b>Valor</b>: R$"+valor+"<br><b>Observação</b>: "+obs+"<br><br>";
	var vlrdin = parseFloat(valor).toFixed(2);
	document.getElementById("vltd").value = vlrdin;

});

$.getJSON('retpaga.php?id='+id+'&tp=2', function(qtAntData){
	var qtAnt = [];
	
	$(qtAntData).each(function(key, value){
		qtAnt.push(value.qtAnt);
	});
	
	if(qtAnt == 0){
	document.getElementById("pagamento").style.display= "block";
	document.getElementById("id1").value = id;
	document.getElementById("id2").value = id;
	document.getElementById("btInfPg").disabled = false;
	document.getElementById("btCanPg").disabled = false;
	naocancela();
	document.getElementById("paga").style.display = "none";
	}
	else if(qtAnt > 0){
	document.getElementById("pagamento").style.display= "block";
	alert("Existe parcela anterior sem pagamento. Favor verificar.");
	document.getElementById("id1").value = id;
	document.getElementById("id2").value = id;
	document.getElementById("btInfPg").disabled = true;
	document.getElementById("btCanPg").disabled = true;
	naocancela();
	document.getElementById("paga").style.display = "none";
	}
});
//abaixo para chamar as funções para permitir alterar
}
function canPaga(){
	escondeMascara();
	document.getElementById("pagamento").style.display = "none";
	desPagamento();
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
	window.open("receber.php?id="+id, "Receber", "toolbar=no,scrollbars=no,resizable=no,top=100,left=500,width=400,height=400");
}

function desCheque(){
	document.getElementById("dt_cheque").disabled = true;
	document.getElementById("nm_cheque").disabled = true;
	document.getElementById("doc_cheque").disabled = true;
	document.getElementById("num_cheque").disabled = true;
	document.getElementById("bc_cheque").disabled = true;
	document.getElementById("ag_cheque").disabled = true;
	document.getElementById("ct_cheque").disabled = true;
	document.getElementById("bc_cheque").disabled = true;
}

function desPagamento(){
	//chama função que habilita pagamento com cartão, com isso desabilita todas as mais complexas
	mtppg(3);
	//agora desabilita o que for de cartão
	document.getElementById("cartao").style.display="none";
	document.getElementById("card").required=false;
}

function mtppg(id){
	//tp 1 pagameto
	if(id == 1){
		document.getElementById("vlrd").disabled = false;
		document.getElementById("pgd").style.display = "block";
		document.getElementById("pgch").style.display = "none";
		troco();//chama função calcula troco
		desCheque();//desabilita campos de cheque
		document.getElementById("cartao").style.display="none";
		document.getElementById("card").required=false;
	}
	if(id == 2){//cheque
	//desabilita campo de dinheiro
		document.getElementById("vlrd").disabled = true;
		document.getElementById("dt_cheque").disabled = false;
	document.getElementById("nm_cheque").disabled = false;
	document.getElementById("doc_cheque").disabled = false;
	document.getElementById("num_cheque").disabled = false;
	document.getElementById("bc_cheque").disabled = false;
	document.getElementById("ag_cheque").disabled = false;
	document.getElementById("ct_cheque").disabled = false;
	document.getElementById("bc_cheque").disabled = false;	
	document.getElementById("pgd").style.display = "none";
	document.getElementById("pgch").style.display = "block";
	document.getElementById("cnt").disabled=false;
	document.getElementById("cnt").title="Salvar";
	document.getElementById("cartao").style.display="none";
	document.getElementById("card").required=false;
	}
	else if(id == 3 || id == 4){
		document.getElementById("vlrd").disabled = true;
		document.getElementById("pgd").style.display = "none";
		document.getElementById("pgch").style.display = "none";
		desCheque();//desabilita campos de cheque
		document.getElementById("cartao").style.display="block";
		document.getElementById("card").required=true;
	}
}

function troco(){
	//pegar valor informado em vlpd
	//pegar valor de recebimento vlrd
	//atribuir diferença em vltrd
	var tt = document.getElementById("vltd").value;
	var tr = document.getElementById("vlrd").value;
	var troco = tr-tt;
	troco = parseFloat(troco).toFixed(2);
	if(troco < 0){
			document.getElementById("vltrd").style.color = "red";
				document.getElementById("cnt").disabled=true;
				document.getElementById("cnt").title="Não é possível continuar. Valor informado deve ser maior ou igual ao valor a ser pago";
	}
	if(troco >= 0){
			document.getElementById("vltrd").style.color = "#000";
			document.getElementById("cnt").disabled=false;
				document.getElementById("cnt").title="Salvar";
	}
	document.getElementById("vltrd").value = troco;
}
</script>
<?php //chamar função para pagamento quando vier requisição via alertas
if($aLink != ""){
	echo "<script>paga($aLink);</script>";
}
?>
