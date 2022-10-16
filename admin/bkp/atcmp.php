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
		mysql_query("update cmppdt set qt = qt+1 where pdt = $i and cmp = $v");
	}
	
else if($a == 2){//ação onde subtrai 1
		mysql_query("update cmppdt set qt = qt-1 where pdt = $i and cmp = $v");
		$vqt = mysql_fetch_assoc(mysql_query("select qt from cmppdt where pdt = $i and cmp = $v"));
		if($vqt[qt] == 0){
		mysql_query("delete from cmppdt where pdt = $i and cmp = $v");
		}
}
else if($a == 3){//ação onde remove item
		mysql_query("delete from cmppdt where pdt = $i and cmp = $v");
}

else if($a == 4){//transforma em orçamento
		mysql_query("update compra set st = 2 where id = '$v'");
}

else if($a == 6){//transforma em compra novamente
		mysql_query("update compra set st = 1 where id = '$v'");
}

else if($a == 7){//cancelar compra
		mysql_query("update compra set st = 5 where id = '$v'");
}
?>
