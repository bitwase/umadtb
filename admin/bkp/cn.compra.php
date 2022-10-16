<span class="tt_pg"><b>Registro de Compras</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#18/05/2016{
	-criado
	-inserir campo para poder atualizar o valor de compra deste produto
}
#26/05/2016{
	-ajustar para poder informar pagamento, ou concluir compra.
	-definir regras ao concluir venda;
}
#17/06/2016{
	-Ajustar para que somente mostre opção para ingormar pagamento quando qt de itens for maior que zero;
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$vn = $_REQUEST[vn];
if($vn == ""){
$a = $_REQUEST[a];
}

if($a == 1){//a = 1 momento em que registra a venda.... a=2 moento em que insere itens;
$cli = $_REQUEST[c];//recebe valor inteiro representando o cliente
mysql_query("insert into compra (fornecedor,comprador,st,data) values ('$vn','$cod_us', '1',now())");
$vn1 = mysql_fetch_assoc(mysql_query("select max(id) as 'vn' from compra"));
if($vn1[vn] == ""){
	$vn1[vn] = 1;
}
 echo "<META http-equiv='refresh' content='0;URL=index.php?pg=cn.compra&vn=$vn1[vn]'>";
}

if($vn == ""){//se vn for vazio, pega o resultado da busca, pois só sera vazio quando for inclusãoda COMPTA
$vn = $vn1[vn];
}


$alteraVnd = $_POST[alteraVnd];
if($alteraVnd == 1){
	$nvl = $_POST[nvlr];
	$apl = $_POST[aplica];
	$i = $_POST[idpdt];
	if($apl == 1){//somente na compra
		mysql_query("update cmppdt set vlu = '$nvl' where pdt = $i AND cmp = $vn");
	}
	if($apl == 2){//compra e produto
		mysql_query("update cmppdt set vlu = '$nvl' where pdt = $i AND cmp = $vn");
		mysql_query("update produtos set vlrcmp = '$nvl' where id = $i");
	}
	//script para emitir alerta, fechar a pagina e atyalzar a original
	$url = "index.php?pg=cn.compra&vn=$v";
	echo "<script>
	alert('Valor atualizado com sucesso.');
	</script>";
}

$ddv = mysql_fetch_assoc(mysql_query("select v.id, v.st, c.fornecedor, u.nome as 'comprador', date_format(v.data,'%d/%m/%Y %H:%i') as 'data', date_format(v.datapg,'%Y-%m-%d') as 'dt_pg', date_format(v.datapg,'%d/%m/%Y') as 'dtpg', c.id as 'idfor', c.end, c.num, c.compl, c.bairro, c.cidade, c.uf, v.st from compra v
inner join fornecedores c on v.fornecedor = c.id
inner join usuarios u on v.comprador = u.id
where v.id = $vn"));

$dt_pg = $ddv[dt_pg];
//dados para verificar se será possivel a compra ou não
$ent1 = mysql_fetch_assoc(mysql_query("select sum(valor) as 'ent' from financeiro where dt_ag <= '$dt_pg' AND tipo = 1 and sit = 2"));
$ent1 = number_format($ent1[ent],2,".","");
$sai1 = mysql_fetch_assoc(mysql_query("select sum(valor) as 'sai' from financeiro where dt_ag <= '$dt_pg' and tipo = 2 and sit = 2"));
if($sai1[sai] == null){
	$sai1[sai] = 0;
}
$sai1 = number_format($sai1[sai],2,".","");
if($sai1 == "")
	$sai1 = "0.00";
$saldo1 = number_format(($ent1-$sai1),2,".","");

// fim verificar saldo dispon

$st = $_POST[st];//st é a situação, onde 1 continua inserindo produtos

if($st == 1){
$pdt = $_POST[pdt];
$qt = $_POST[qt];
if($qt == ""){
$qt = 1;
}

$vr = mysql_num_rows(mysql_query("select * from cmppdt where cmp = $vn and pdt = $pdt"));//verifica se já existe, se sim soma na quantidade, caso contratio inserr
if($vr == 0){
	mysql_query("insert into cmppdt (cmp,pdt,qt,vlu) values ('$vn','$pdt','$qt',(select vlrcmp from produtos where id = $pdt))");
}
else if($vr > 0){
	mysql_query("update cmppdt set qt = qt+$qt where cmp = $vn and pdt = $pdt");
}
}

//verifica valor total da compra
$vltot = mysql_fetch_assoc(mysql_query("select sum(qt*vlu) as 'total' from cmppdt where cmp = $vn"));

if($vltot[total] > $saldo1){
	$vermelho = true;
}
else{
	$vermelho = false;
}

$pd1 = mysql_query("select * from produtos where st = 1 order by descricao asc");
?>
<b><?php if($ddv[st] == 2){ echo "Orçamento de ";}?>Compra Nº:</b> <?php echo "$ddv[id]"; ?><br>
<b>Fornecedor: </b><?php echo "$ddv[idfor] - $ddv[fornecedor]";?><br>
<b>Endereço: </b><?php echo "$ddv[end], $ddv[num] $ddv[compl], $ddv[bairro], $ddv[cidade] - $ddv[uf]";?><br>
<b>Comprador: </b><?php echo $ddv[comprador]; ?><br>
<b>Data de Compra: </b><?php echo $ddv[data]; ?><br>
<?php if($vermelho){echo "<span style='color:#f00;'>";}?><b>Data prevista para Pagamento: </b><?php echo $ddv[dtpg]; if($vermelho){ echo "<img src='arquivos/icones/8.png' class='bt_p' title='ATENÇÃO!!! Saldo previsto para a data de pagamento é inferior ao valor desta compra. Se continuar o orçamento pode ficar comprometido.'></span>";}?><br>
<b>Situação: </b><?php
switch($ddv[st]){
	case 1:
		echo "Em Aberto";
		break;
	case 2:
		echo "Orçamento";
		break;
	case 3:
		echo "Concluído";
		break;
	case 4:
		echo "Pagamento Agendado";
		break;
	case 5:
		echo "Cancelado";
		break;
}
?>
<hr>
<form action="#" method="POST" style="input{padding:1px;}" >
<input type="hidden" name="st" value="1">
<input type="hidden" name="vn" value="<?php echo $vn; ?>">
<?php if($ddv[st] == 1){?><b>Produto</b> <select name="pdt" required>
<option value="">Selecione</option>
<?php
while($pd = mysql_fetch_assoc($pd1)){
echo "<option value='$pd[id]'>$pd[id]-$pd[descricao][$pd[qt]]</option>";
}
?>
</select><br>
<b>Quantidade:</b> <input type="text" name="qt" size="5">
<br><input type="submit" value="Continuar">
<?php }?>
</form>
<div id="atvl" style="display:none;"></div>
<div id="cancela"><br><b>Deseja realmente cancelar esta compra?</b><br>
<input type="button" value="SIM" onclick="simCancela()"> <input type="button" value="NÃO" onclick="naoCancela()">
</div>
<table id="produtos" class="display" width="100%"></table>
<hr>
<?php if($ddv[st] == 1){ ?><input type="button" value="Salvar Como Orçamento" onclick="acao(4,0)">
<input type="button" value="Cancelar" onclick="cancela()">
<?php ## Verificar se tem itens ##
$vqt = mysql_fetch_assoc(mysql_query("select sum(qt) as 'qt' from cmppdt where cmp = '$vn'"));
if($vqt[qt] > 0){?>
<input type="button" value="Agendar/Informar Pagamento" id="agPg" onclick="pagar()"><?php }}?>
<?php if($ddv[st] == 2){ ?><input type="button" id="btgv" value="Gerar Venda" onclick="acao(6,0)"><?php }?>

<span style="position:absolute;right:5px;"><?php if($vermelho){echo "<span style='color:#f00;'> <img src='arquivos/icones/8.png' class='bt_p' title='ATENÇÃO!!! Saldo previsto para a data de pagamento é inferior ao valor desta compra. Se continuar o orçamento pode ficar comprometido.'>";}?><b>Total:</b><?php echo "R$".number_format($vltot[total],'2','.','');?><?php if($vermelho){echo "</span>";}?></span><br><br><br>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$pvnda= mysql_query("SELECT pv.pdt, p.descricao, um.um, pv.qt, pv.vlu, pv.qt*pv.vlu as 'vlt' from cmppdt pv inner join produtos p on pv.pdt = p.id
inner join unidademedida um on p.um = um.id
where pv.cmp = $vn");
 $od = 0;
while($pdt = mysql_fetch_assoc($pvnda)){
$od++;//define ordem
//pegar quantidade atual do item em questão
$qtat1 = mysql_fetch_assoc(mysql_query("select qt from produtos where id = $pdt[pdt]"));
$qtat = $qtat1[qt];
if($qtat1[qt] >= $pdt[qt]){
$qtitem = $pdt[qt];
}
else if($qtat1[qt] < $pdt[qt]){
	$qtitem = $pdt[qt];
}
$vu = number_format("$pdt[vlu]","2",".","");
$vt = number_format("$pdt[vlt]","2",".","");

	$lk= "<img src=\'arquivos/icones/117.png\' class=\'bt_p\' title=\'Adicionar 1\' onclick=\'acao(1,$pdt[pdt])\'>";
$lk = "$lk <img src=\'arquivos/icones/118.png\' class=\'bt_p\' title=\'Remover 1\' onclick=\'acao(2,$pdt[pdt])\'>";
$lk = "$lk <img src=\'arquivos/icones/116.png\' class=\'bt_p\' title=\'Remover Este Produto\' onclick=\'acao(3,$pdt[pdt])\'>";
$lk = "$lk <img src=\'arquivos/icones/44.png\' class=\'bt_p\' title=\'Atualizar Valor de Compra\' onclick=\'atvl($pdt[pdt])\'>";
if($ddv[st] == 5 || $ddv[st] == 4 || $ddv[st] == 3){
	$lk = "";
}
	echo "
	['$od','$pdt[pdt]','$pdt[descricao]','$pdt[um]','$qtitem','R$$vu','R$$vt','$lk',],";
}
?>
];
 $(document).ready(function() {
    $('#produtos').DataTable( {
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
        ],
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "" },
			{ title: "Cód." },
            { title: "Descrição" },
            { title: "UM" },
            { title: "Qt" },
            { title: "Vlr Un." },
            { title: "Vlr. Total" },
            { title: "" }
        ]
    } );
} );
</script>
<script>
 $(function() {
var cli = [
      <?php
	  $cl = mysql_query("SELECT c.id, upper(c.nome) as 'nome' FROM pacientes c 
	  ORDER BY c.nome");
	  while($cli = mysql_fetch_assoc($cl)){
		  echo "'$cli[id] - $cli[nome]',";
	  }	
	  ?>
	  ];
$( "#cli" ).autocomplete({
      source: cli
    });
		});

function acao(a,i) {//ação que vai remover, incluir ou cagar na porra toda
//onde a é a ação, 1 soma mais 1,2 remove 1, 3 zera
//i é o item em questão
   $.ajax({
      url:'atcmp.php?a='+a+'&i='+i+'&v='+<?php echo $vn; ?>,
      complete: function (response) {
//alert(response.responseText);
	location.href = 'index.php?pg=cn.compra&vn='+<?php echo $vn; ?>;
      },
      error: function () {
          alert('Erro');
      }
  });
}
/*
function atvl(i){
	var link = 'atvl.php?i='+i+'&v='+<?php echo $vn; ?>;
window.open(link, 'Altera Preço Compra', 'STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=NO, TOP=200, LEFT=200, WIDTH=500, HEIGHT=360');
}
*/
function atvl(i){
//pegar via json as informações usando ret produto
	mostraMascara();
//json para retornar os valores necessários e montar o form

$.getJSON('retproduto.php?id='+i, function(pagaData){
	var descricao = [];
	var vlrcmp = [];
	
	$(pagaData).each(function(key, value){
		descricao.push(value.descricao);
		vlrcmp.push(value.vlrcmp);
	});
	
	var vlr = parseFloat(vlrcmp);
	vlr = vlr.toFixed(2);
	
//escrever os dados
	var form = "<br><form action='#' method='post'><input type='hidden' name='alteraVnd' value='1'><input type='hidden' name='idpdt' value='"+i+"'> <b>Novo Valor de Compra:</b><input type='text' name='nvlr' class='vlr' required size='6'><br><input type='radio' name='aplica' id='ap1' value='1' checked><label for='ap1'>Aplicar Somente Para esta Compra</label><br><input type='radio' name='aplica' id='ap2' value='2'><label for='ap2'>Atualizar Para Produto</label><br> <input type='submit' value='Gravar'></form>";

	document.getElementById("atvl").innerHTML = "<img src='arquivos/icones/116.png' class='bt' style='position:absolute; top:5px; right:5px;' onclick='fechaatvl()'><span class='tt_pg'><b>Altera Preço de Compra</b></span><br><b>Cod.:</b> "+i+" <b>Descrição:</b> "+descricao+"<br><b>Preço Atual:</b> R$"+vlr+form;
});
	document.getElementById("atvl").style.display="block";	
}
function fechaatvl(i){
//pegar via json as informações usando ret produto
	escondeMascara();
	document.getElementById("atvl").style.display="none";
}

function cancela(){
	mostraMascara();
	document.getElementById("cancela").style.display = 'block';
}
function naoCancela(){
	escondeMascara();
	document.getElementById("cancela").style.display = 'none';
}

function simCancela(){
   $.ajax({//chamar página para cancelar venda
      url:'atcmp.php?a=7&v='+<?php echo $vn; ?>,
      complete: function (response) {
//alert(response.responseText);
	location.href = 'index.php?pg=cn.compra&vn='+<?php echo $vn; ?>;
      },
      error: function () {
          alert('Erro');
      }
  });
}

function pagar(){
	location.href = "index.php?pg=pagamentos&tp=1&cm=<?php echo $vn;?>";
}
</script>
