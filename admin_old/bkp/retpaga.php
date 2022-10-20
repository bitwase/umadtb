<?php
include 'seguranca.php';
$id = $_REQUEST[id];
$tp = $_REQUEST[tp];//tipo 1 retornar dados de pagamento
			//tipo 2 retornar quantidades de parcelas anteriores em atraso
if($tp == 1){
$pa = mysql_fetch_assoc(mysql_query("select id, date_format(dt_ag,'%d/%m/%Y') as 'vct', motivo, valor, obs from financeiro where id = $id"));
if($pa){
echo json_encode($pa);
}
}

if($tp == 2){
$cp = mysql_fetch_assoc(mysql_query(
	"select id, date_format(dt_ag,'%d/%m/%Y') as 'vct', dt_ag as 'ag', tipo2, motivo, valor, obs from financeiro where id = $id"
	));

	//verificar parcela anterior em aberto;
$ver = mysql_num_rows(mysql_query("select * from financeiro where tipo2 = '$cp[tipo2]' and sit = 1 and dt_ag < '$cp[ag]'"));
$pa = array();
$pa[0] = array("qtAnt"=>"$ver");
echo json_encode($pa);
}
?>
