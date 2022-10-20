<span class="tt_pg"><b>Lista de Ausências Consecutivas</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#26/04/2017{
	-Desenvolvido;
	-necessário verificar na tabela tb_consecutivo todos os ausentes;
	-listar primeiro os que ainda não consta o ok de ciente do líder;
	-desconsiderar filtro;
	-segunda ordem é ID
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$filtrar = $_POST[filtrar];
$ft = "";
$ft = "where c.id > 0 ";
if($filtrar){
$di = $_POST[di];//01-34-6789
if($di != ""){
$di = $di[6].$di[7].$di[8].$di[9]."-".$di[3].$di[4]."-".$di[0].$di[1]; 
$ft .= "and a.data >= '$di'";
}
$df = $_POST[df];
if($df != ""){
$df = $df[6].$df[7].$df[8].$df[9]."-".$df[3].$df[4]."-".$df[0].$df[1]; 
$ft .= "and a.data <= '$df'";
}
}

$conf = $_POST[conf];
if($conf){//se confirma...
mysql_query("update tb_consecutivo set ciente = 1 where id = $_POST[idAus]");
}
?>
<!--form action="#" method="POST">
<input type="hidden" name="filtrar" value="1">
<b>Data Inicial</b> <input type="text" class="date" name="di" size="11"> 
<b>Data Final</b> <input type="text" class="date" name="df" size="11">  
<input type="submit" value="Filtrar">
</form-->
<div id="ciente" style="display:none">
<span class="tt_pg">Confirma?</span>
<form action="#" method="POST">
<input type="hidden" name="conf" value="1">
<input type="hidden" id="idAus" name="idAus" value="">
<input type="submit" value="Sim">
<input type="button" value="Não" onclick="fechaCiente()">
</form>
</div>
<table id="produtos" class="display" width="100%"></table>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
if($ft != ""){
$cl1= mysql_query("select i.nome, c.id, c.jovem, c.datas, c.datas2, c.ciente from tb_consecutivo c
inner join tb_inscritos i on c.jovem = i.id
$ft
order by c.ciente,c.id");
}
$od = 0;
while($cli = mysql_fetch_assoc($cl1)){
$od++;//define ordem
$lk = "";
if(!$cli[ciente]){
	$lk .= "<a href=\'#\' onclick=\'ciente($cli[id])\' ><img src=\'arquivos/icones/ok.png\' class=\'bt\'></a>";
}
	echo "
	['$od','$cli[nome]','$cli[datas2]','$cli[obs]','$lk'],";
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
       {},
        ],
	"order": [1,'asc'],
         "paging":         false,
		data: dataSet,
        columns: [
        { title: "" },
	{ title: "Nome" },
	{ title: "Ausências" },
    { title: "Observações" },
    { title: "" },
        ]
    } );
} );

//função para dar ciência de ausência...

function ciente(id){
	document.getElementById("idAus").value = id;
	mostraMascara();
	document.getElementById("ciente").style.display = "block";
}
function fechaCiente(id){
	document.getElementById("idAus").value = "";
	escondeMascara();
	document.getElementById("ciente").style.display = "none";
}
</script>
