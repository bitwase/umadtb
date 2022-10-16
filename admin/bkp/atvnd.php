<?php
include 'seguranca.php';
$a= $_REQUEST[a];//ação
$i = $_REQUEST[i];//item
$v = $_REQUEST[v];//venda
//necessário verificar se está sendo visto um orçamento ou uma compra.. orçamentos não alteram quantidades em estoque;
$orc = mysql_num_rows(mysql_query("select * from vendas where id = $v and st = 2"));
if($orc == 0){
	$or = false;
}
else if($orc == 1){
	$or = true;
}

if($a == 1){//ação onde soma 1
		mysql_query("update vndpdt set qt = qt+1 where pdt = $i and vnd = $v");
		if(!$or){
			mysql_query("update produtos set qt = qt-1 where id = $i");
		}
	}
	
else if($a == 2){//ação onde subtrai 1
		mysql_query("update vndpdt set qt = qt-1 where pdt = $i and vnd = $v");
		$vqt = mysql_fetch_assoc(mysql_query("select qt from vndpdt where pdt = $i and vnd = $v"));
		if($vqt[qt] == 0){
		mysql_query("delete from vndpdt where pdt = $i and vnd = $v");
		}
		if(!$or){
		mysql_query("update produtos set qt = qt+1 where id = $i");
		}
}
else if($a == 3){//ação onde remove item
		$qt = mysql_fetch_assoc(mysql_query("select qt from vndpdt where pdt = $i and vnd = $v"));
		mysql_query("delete from vndpdt where pdt = $i and vnd = $v");
		if(!$or){
		mysql_query("update produtos set qt = qt+$qt[qt] where id = $i");
		}
}

else if($a == 4){//SALVAR COMO ORÇAMENTO
//necessário alterar situação da venda para 2 (orçamento)
//nessecário pegar todos os itens e adicionar novamente no estoque
mysql_query("update vendas set st = 2 where id = $v");//altera status
$it1 = mysql_query("select * from vndpdt where vnd = $v");//consulta itens
while($it = mysql_fetch_assoc($it1)){
	mysql_query("update produtos set qt = qt+$it[qt] where id = $it[pdt]");
}
}

else if($a == 5){//ação onde deixará quantidade igual à disponível em estoque
	//pegar quantidade disponível em estoque
	//ajsutar quantidade do produto em questao para  quantidade disponível em estoque
	//se quantidade atual for igual a zero, deverá remover este item da venda atual
	$vqt = mysql_fetch_assoc(mysql_query("select qt from produtos where id = $i"));
	if($vqt[qt] > 0){
	mysql_query("update vndpdt set qt = $vqt[qt] where pdt = $i and vnd = $v");
}
else if($vqt[qt] == 0){
	mysql_query("delete from vndpdt where pdt = $i and vnd = $v");
}
}
else if($a == 6){//ação que transformará novamente em comrpra... deverá ajsutar situação para 1, e subtrair estoque dos itens informados, conforme a quantidade
	mysql_query("update vendas set st = 1 where id = $v");//altera status
	$it1 = mysql_query("select * from vndpdt where vnd = $v");//consulta itens
	while($it = mysql_fetch_assoc($it1)){
		mysql_query("update produtos set qt = qt-$it[qt] where id = $it[pdt]");
	
}
}

else if($a == 7){//cancelar venda
mysql_query("update vendas set st = 5 where id = $v");//altera status
$it1 = mysql_query("select * from vndpdt where vnd = $v");//consulta itens
while($it = mysql_fetch_assoc($it1)){
	mysql_query("update produtos set qt = qt+$it[qt] where id = $it[pdt]");
}
}

else if($a == 8){//ajustar histórico
//deve pegar item a item na venda, verificar as quantidades
//inserir na tb mvprodutos
$it2 = mysql_query("select * from vndpdt where vnd = $v");//consulta itens
while($it3 = mysql_fetch_assoc($it2)){
	$qtat = mysql_fetch_assoc(mysql_query("select qt from produtos where id = '$it3[pdt]'"));
	mysql_query("insert into mvprodutos (data,us,pdt,qt,qtat,tp,acao)
	values(now(),'$cod_us','$it3[pdt]','$it3[qt]','$qtat[qt]','2','VENDA Nº $v')");
}
}
?>
