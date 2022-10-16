<span class="tt_pg"><b>Registro de Vendas</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#14/05/2016{
	-Desenvolvido;
	-//inserer produtos e mostra dados do cliente
}
#17/05/2016{
	-inserir agendamentos com pdt zero, inserir tp como A, para agendamento... neste caso pegar os dados do agendamento em questão;
}
#27/05/2016{
	-ajsutar para poder informar pagamento somente quando quantidade de itens for maior que zero;
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
mysql_query("insert into vendas (cliente,vendedor,st,data) values ('$cli','$cod_us', '1',now())");

$vn1 = mysql_fetch_assoc(mysql_query("select max(id) as 'vn' from vendas"));
// echo "<META http-equiv='refresh' content='0;URL=index.php?pg=cn.venda&vn=$vn1[vn]'>";
}

if($vn == ""){//se vn for vazio, pega o resultado da busca, pois só sera vazio quando for inclusãoda COMPTA
$vn = $vn1[vn];
}

$alteraCmp = $_POST[alteraCmp];
if($alteraCmp == 1){
	$nvl = $_POST[nvlr];
	$apl = $_POST[aplica];
	$i = $_POST[idpdt];
	if($apl == 1){//somente na venda
		mysql_query("update vndpdt set vlu = '$nvl' where pdt = $i AND vnd = $vn");
	}
	if($apl == 2){//venda e produto
		mysql_query("update vndpdt set vlu = '$nvl' where pdt = $i AND vnd = $vn");
		mysql_query("update produtos set vlr = '$nvl' where id = $i");
	}
	//script para emitir alerta, fechar a pagina e atyalzar a original
	$url = "index.php?pg=cn.venda&vn=$v";
	echo "<script>
	alert('Valor atualizado com sucesso.');
	</script>";
}

$ddv = mysql_fetch_assoc(mysql_query("select v.id, v.st, c.nome, u.nome as 'vendedor', date_format(v.data,'%d/%m/%Y %H:%i') as 'data', c.id as 'idcli', c.end, c.num, c.compl, c.bairro, c.cidade, c.uf from vendas v
inner join clientes c on v.cliente = c.id
inner join usuarios u on v.vendedor = u.id
where v.id = $vn"));

$st = $_POST[st];//st é a situação, onde 1 continua inserindo produtos

if($st == 1){
$pdt = $_POST[pdt];
$qt = $_POST[qt];
if($qt == ""){
$qt = 1;
}

$vq = mysql_num_rows(mysql_query("select * from produtos where id = $pdt and qt >= $qt"));//verifica se quantidade disponível é suficiente;

if($vq == 0 && $ddv[st] == 1){
	echo "<script>alert('ATENÇÃO!!! Produto não incluso, devido a quantidade disponível ser menor que quantidade informada. Favor verificar.');</script>";
}
if($vq > 0 || $ddv[st] == 2){
$vr = mysql_num_rows(mysql_query("select * from vndpdt where vnd = $vn and pdt = $pdt"));//verifica se já existe, se sim soma na quantidade, caso contratio inserr
if($vr == 0){
	mysql_query("insert into vndpdt (vnd,pdt,qt,vlu,tp) values ('$vn','$pdt','$qt',(select vlr from produtos where id = $pdt),'1')");
}
else if($vr > 0){
	mysql_query("update vndpdt set qt = qt+$qt where vnd = $vn and pdt = $pdt");
}
if($ddv[st] == 1){
mysql_query("update produtos set qt = qt-$qt where id = $pdt");// baixa no estoque
}//se for diferente de orçamento
}//se quantidade for suficiente
}

$pd1 = mysql_query("select * from produtos where qt > 0 and st = 1 order by descricao asc");

switch($ddv[st]){
	case 1:
		$sit = "Em Aberto";
		break;
	case 2:
		$sit = "Orçamento";
		break;
	case 3:
		$sit = "Concluído";
		break;
	case 4:
		$sit = "Pagamento Agendado";
		break;
	case 5:
		$sit = "Cancelado";
		break;
}
?>
<b><?php if($ddv[st] == 2){ echo "Orçamento de ";}?>Venda Nº:</b> <?php echo "$ddv[id]"; ?><br>
<b>Cliente: </b><?php echo "$ddv[idcli] - $ddv[nome]";?><br>
<b>Endereço: </b><?php echo "$ddv[end], $ddv[num] $ddv[compl], $ddv[bairro], $ddv[cidade] - $ddv[uf]";?><br>
<b>Vendedor: </b><?php echo $ddv[vendedor]; ?><br>
<b>Data: </b><?php echo $ddv[data]; ?><br>
<b>Situação: </b><?php echo $sit; ?>
<hr>
<form action="#" method="POST" style="input{padding:1px;}" id="produtos2" style="display:block" >
<input type="hidden" name="st" value="1">
<input type="hidden" name="vn" value="<?php echo $vn; ?>">
<b>Produto</b> <select name="pdt" required>
<option value="">Selecione</option>
<?php
while($pd = mysql_fetch_assoc($pd1)){
echo "<option value='$pd[id]'>$pd[id]-$pd[descricao][$pd[qt]]</option>";
}
?>
</select><br>
<b>Quantidade:</b> <input type="text" name="qt" size="5">
<br><input type="submit" value="Continuar">
</form>
<div id="cancela"><br><b>Deseja realmente cancelar esta venda?</b><br>
<input type="button" value="SIM" onclick="simCancela()"> <input type="button" value="NÃO" onclick="naoCancela()">
</div>
<div id="atvl" style="display:none;"></div>
<table id="produtos" class="display" width="100%"></table>
<hr>
<?php
$vltot = mysql_fetch_assoc(mysql_query("select sum(qt) as 'qttot', sum(qt*vlu) as 'total' from vndpdt where vnd = $vn"));
?>
<?php if($ddv[st] == 1){ ?><input type="button" value="Salvar Como Orçamento" onclick="acao(4,0)"> <?php if($vltot[qttot] > 0){ ?><a href="index.php?pg=pagamentos&tp=1&vn=<?php echo $vn;?>" ><input type="button" value="Informar Pagamento"></a> <?php } ?><input type="button" value="Cancelar Venda" onclick="cancela()" ><?php }?>
<?php if($ddv[st] == 2 && $pv == 0){ ?><input type="button" id="btgv" value="Gerar Venda" onclick="acao(6,0)"><?php }?>
<span style="position:absolute;right:5px;"><b>Total:</b><?php echo "R$".number_format($vltot[total],'2','.',''); ?></span><br><br><br>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$pvnda= mysql_query("SELECT pv.pdt, p.descricao, um.um, pv.qt, pv.vlu, pv.qt*pv.vlu as 'vlt' from vndpdt pv inner join produtos p on pv.pdt = p.id
inner join unidademedida um on p.um = um.id
where pv.vnd = $vn");
 $od = 0;
 $pv = 0;//contagem para permitir ou não uma venda, se for zero, permite, caso maior que zero, não permite. Se este valor for diferente de zero, é pq existe quantidade orçada maior que quanitdade disponível em estoque... nest ecaso necessário o vendedor ajsutar a quantidade.
while($pdt = mysql_fetch_assoc($pvnda)){
$od++;//define ordem
//pegar quantidade atual do item em questão
$qtat1 = mysql_fetch_assoc(mysql_query("select qt from produtos where id = $pdt[pdt]"));
$qtat = $qtat1[qt];
if($qtat1[qt] >= $pdt[qt]){
$qtitem = $pdt[qt];
}
else if($qtat1[qt] < $pdt[qt]){
	if($ddv[st] == 2){
		$pv++;
$qtitem = "<span style=\'color:#f00\' >$pdt[qt] <img src=\'arquivos/icones/71.png\' class=\'bt_p\' title=\'ATENÇÃO! Quantidade em orçamento ($pdt[qt]) maior que a quantidade disponível ($qtat1[qt]). Clique aqui para ajustar esta quantidade para $qtat1[qt].\' onclick=\'acao(5,$pdt[pdt])\'> </style>";
}
else{
	$qtitem = $pdt[qt];
}
}
$vu = number_format("$pdt[vlu]","2",".","");
$vt = number_format("$pdt[vlt]","2",".","");
if($qtat > 0){
	$lk = "";
	$lk .= "<img src=\'arquivos/icones/117.png\' class=\'bt_p\' title=\'Adicionar 1\' onclick=\'acao(1,$pdt[pdt])\'>";
	$lk .= " <img src=\'arquivos/icones/118.png\' class=\'bt_p\' title=\'Remover 1\' onclick=\'acao(2,$pdt[pdt])\'>";
$lk .= " <img src=\'arquivos/icones/116.png\' class=\'bt_p\' title=\'Remover Este Produto\' onclick=\'acao(3,$pdt[pdt])\'>";
$lk .= " <img src=\'arquivos/icones/44.png\' class=\'bt_p\' title=\'Atualizar Valor de Venda\' onclick=\'atvl($pdt[pdt])\'>";
}

if($ddv[st] == 3 || $ddv[st] == 4 || $ddv[st] == 5){
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
<?php if($pv > 0){
	echo "<script>
	document.getElementById('btgv').style.display = 'none';
	</script>";
}?>
<script>
 $(function() {
var cli = [
      <?php
	  $cl = mysql_query("SELECT c.id, upper(c.nome) as 'nome' FROM clientes c where c.situacao = 1 ORDER BY c.nome");
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
      url:'atvnd.php?a='+a+'&i='+i+'&v='+<?php echo $vn; ?>,
      complete: function (response) {
//alert(response.responseText);
	location.href = 'index.php?pg=cn.venda&vn='+<?php echo $vn; ?>;
      },
      error: function () {
          alert('Erro');
      }
  });
}
function atvl(i){
//pegar via json as informações usando ret produto
	mostraMascara();
//json para retornar os valores necessários e montar o form

$.getJSON('retproduto.php?id='+i, function(pagaData){
	var descricao = [];
	var vlr = [];
	
	$(pagaData).each(function(key, value){
		descricao.push(value.descricao);
		vlr.push(value.vlr);
	});
	
	var vlr1 = parseFloat(vlr);
	vlr1 = vlr1.toFixed(2);
	
//escrever os dados
	var form = "<br><form action='#' method='post'><input type='hidden' name='alteraCmp' value='1'><input type='hidden' name='idpdt' value='"+i+"'> <b>Novo Valor de Venda:</b><input type='text' name='nvlr' class='vlr' required size='6'><br><input type='radio' name='aplica' id='ap1' value='1' checked><label for='ap1'>Aplicar Somente Para esta Venda</label><br><input type='radio' name='aplica' id='ap2' value='2'><label for='ap2'>Atualizar Para Produto</label><br> <input type='submit' value='Gravar'></form>";

	document.getElementById("atvl").innerHTML = "<img src='arquivos/icones/116.png' class='bt' style='position:absolute; top:5px; right:5px;' onclick='fechaatvl()'><span class='tt_pg'><b>Altera Preço de Compra</b></span><br><b>Cod.:</b> "+i+" <b>Descrição:</b> "+descricao+"<br><b>Preço Atual:</b> R$"+vlr1+form;
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
      url:'atvnd.php?a=7&v='+<?php echo $vn; ?>,
      complete: function (response) {
//alert(response.responseText);
	location.href = 'index.php?pg=cn.venda&vn='+<?php echo $vn; ?>;
      },
      error: function () {
          alert('Erro');
      }
  });
}
<?php if($ddv[st] == 3 || $ddv[st] == 4 || $ddv[st] == 5) {echo " document.getElementById('produtos2').style.display = 'none';";}
?>
</script>
