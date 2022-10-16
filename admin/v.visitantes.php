<span class="tt_pg"><b>Lista de Visitantes</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#22/06/2016{
	-Desenvolvido;
}
#11/05/2017{
	-necessário colocar opção, ao passar mouse ou clicar em última visita, mostrar todas as datas de visita;
	-desenvolver aruqivo que fará o envio do sms;
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nivel > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$salva = $_POST[salva];
$altClientes = $_POST[altClientes];

$rVisita = $_POST[regVisita];

if($rVisita){
$id = $_POST[idV];//id do visitante
$dt = $_POST[dtRVisita];//data registro da visita
$dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
mysql_query("insert into tb_visitasVisitantes (jovem,data,envio,dataEnvio) values ('$id','$dt','0','0000-00-00 00:00:00')") or die(mysql_error());
}

?>
<table id="produtos" class="display" width="100%"></table>
<div id="adVisita" style="display:none">
<form id="adVisita" method="POST">
<span class="tt_pg">Registra Visita</span><br><br>
<b>Visitante:</b><span id="nmVisitante"></span><br><br>
<input type="hidden" name="regVisita" value="1">
<input type="hidden" id="idV" name="idV" value="">
<b>Data da Visita:</b><input type="text" class="data" name="dtRVisita" id="dtRVisita" size="12" required placeholder="DD/MM/AAAA"><br><br>
<input type="submit" value="Salvar"><input type="button" value="Cancelar" onclick="fechaRegVisita()">
</form>
</div>

<script language="javascript" type="text/javascript">
var dataSet = [
<?php

if($nivel == 1){
	$cl1= mysql_query("select id, nome, evangelico, onde, tel1, tel2, email from tb_visitantes where sit = 1 order by nome");
}
if($nivel == 2){
	$cl1= mysql_query("select id, nome, evangelico, onde, tel1, tel2, email from tb_visitantes where congregacao = '$congregacao_nivel' and sit = 1 order by nome");
}

 $od = 0;
while($cli = mysql_fetch_assoc($cl1)){
$od++;//define ordem
$vs2 = mysql_query("select date_format(data,'%d/%m/%Y') as 'data' from tb_visitasVisitantes where jovem = '$cli[id]' order by id desc limit 1");
$vs3 = mysql_num_rows(mysql_query("select * from tb_visitasVisitantes where jovem = '$cli[id]'"));
$vs1 = mysql_fetch_assoc($vs2);
$vs = $vs1[data];
	if($cli[situacao] == 0){
		$st = "Inativo";
	}
	if($cli[situacao] == 1){
		$st = "Ativo";
	}
	if($cli[evangelico] == 1){
		$evan = "Sim";
	}
	if($cli[evangelico] == 0){
		$evan = "Não";
	}
	$telefone = "$cli[tel1]<br>$cli[tel2]";
	$lk = "";
	$lk .= " <a href=\'#\' onclick=\'mostraregVisita($cli[id],\"$cli[nome]\")\' title=\'Adicionar Visita\'><img src=\'arquivos/icones/plus.png\' class=\'bt_p\'></a>";
//	$lk .= " <a href=\'#\' onclick=\'mostraEditaCliente($cli[id])\' title=\'Altera Dados\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
//	$lk .= " <a href=\'?pg=visitas&id=$cli[id]\' title=\'Histórico de Visitas\'><img src=\'arquivos/icones/lista2.png\' class=\'bt_p\'></a>";
	echo "
	['$od','$cli[nome]','$evan','$cli[onde]','$telefone','$cli[email]','$vs','$vs3','$lk',],";
}
?>
];
 
$(document).ready(function() {
    $('#produtos').DataTable( {
		 "scrollX": true,
		"scrollY": "55vh",//esta media vh, representa x(60) % da altura (height)
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false,
            },
        ],
	"order": [1,'asc'],
         "paging":         false,
		data: dataSet,
        columns: [
        { title: "" },
	{ title: "Nome" },
	{ title: "Evang." },
	{ title: "Congrega em:" },
    { title: "Telefone" },
    { title: "Email" },
    { title: "Últ. Visita" },
    { title: "Qt. Visitas" },
    { title: "" }
        ]
    } );
} );
function mostraregVisita(v,nome){
mostraMascara();
document.getElementById("nmVisitante").innerHTML = nome;
document.getElementById("idV").value = v;
document.getElementById("adVisita").style.display = "block";
}


function fechaRegVisita(){
escondeMascara();
document.getElementById("nmVisitante").innerHTML = "";
document.getElementById("idV").value = "";
document.getElementById("adVisita").style.display = "none";
}

$( function() {
    $( "#dtRVisita" ).datepicker();
  } );

</script>
