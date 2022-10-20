<?php
$salva = $_POST[salva];

$ev = $_REQUEST[evento];
if($ev == ""){
	$ev = $_POST[idEvento];
}

if($ev){
	$ddev = mysql_fetch_assoc(mysql_query("select * from tb_eventos where id = '$ev'"));
	$evento = "$ddev[evento]";
}


if($salva){
//recebe o cod
$cod = $_REQUEST[cod];
$cod = str_pad($cod, 5, "0", STR_PAD_LEFT);

$v = mysql_num_rows(mysql_query("select * from tb_presenca where date_format(data,'%Y-%m-%d') = date_format(now(),'%Y-%m-%d') and inscrito = '$cod'"));

if($v == 0){
	$c = true;
}
else{
	$c = false;
}
if(!$c){
echo "<script>alert('Presença já registrada para esta pessoa.');</script>";
}
if($c){
//cpf deve ser igual a 3 primeiro numeros, insere ponto, conta mais 3, insere ponto, conta mais 3 insere traço 
$f = $cod;
//$f = $f[0].$f[1].$f[2].".".$f[3].$f[4].$f[5].".".$f[6].$f[7].$f[8]."-".$f[9].$f[10];
$did = mysql_fetch_assoc(mysql_query("select * from tb_inscritos where id = '$f'"));
$dqt = mysql_num_rows(mysql_query("select * from tb_inscricao where inscrito = '$f' and evento = '$ev'"));
if($dqt == 0){
	echo "<script>alert('Registro não encontrado.');</script>";
}
if($dqt > 0){
mysql_query("insert into tb_presenca (data,inscrito,idins,reg,evento) values (now(),'$cod','$did[id]','$cod_us','$ev')") or die(mysql_error());
}
}
}

?>
<?php if($ev == ""){ ?>
<form action="#" method="POST">
<input type="hidden" name="ft" value="1">
<label class="iden"><b>Evento</b></label><select name="evento" id="selEv" required onchange="selEvento()">
<option value="">Selecione</option>
<?php
$le = mysql_query("select * from tb_eventos where st = 1 order by evento");
while($l = mysql_fetch_assoc($le)){
	echo "<option value='$l[id]'>$l[evento]</option>";
}
?>
</select><input type="submit" value="Filtrar">
</form>
<?php } ?>

<?php if($ev != ""){ ?>
<form id="ler" action="#" method="POST">
<input type="hidden" name="salva" value="1">
<input type="hidden" name="evento" value="<?php echo $ev;?>">
<b>Cod.:</b><input type="text" size="10" name="cod" id="cod"> <input type="Submit" value="Enviar">
</form><hr>
<span class='tt_pg'><b>Lista de Presentes Hoje - Evento: <?php echo "$evento";?></b></span><br>
<table id="produtos" class="display" width="100%"></table>
<?php } ?>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$dia = date("d/m/Y");

$cl1= mysql_query("select i.nome, i.cidade, date_format(p.data,'%d/%m/%Y %H:%i') as 'reg' from tb_presenca p inner join tb_inscritos i on p.inscrito = i.id where p.evento = '$ev' and date_format(p.data,'%d/%m/%Y') = '$dia' order by p.data asc");
 $od = 0;
while($cli = mysql_fetch_assoc($cl1)){
$od++;//define ordem
	if($cli[situacao] == 0){
		$st = "Inativo";
	}
	if($cli[situacao] == 1){
		$st = "Ativo";
	}
	$lk = "";
	$lk .= " <a href=\'etiquetas.php?t=2&id=$cli[id]\' target=\'_blank\' title=\'Reimprimir Etiqueta\'><img src=\'arquivos/icones/print.png\' class=\'bt_p\'></a>";
	echo "
	['$od','$cli[nome]','$cli[cidade]','$cli[reg]'],";
}
?>
];
 
$(document).ready(function() {
    $('#produtos').DataTable( {
		 "scrollX": true,
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
        ],
	"order": [0,'asc'],
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "" },
			{ title: "Nome" },
            { title: "Cidade" },
	{ title: "Registro Presença" },
        ]
    } );
} );

document.getElementById("cod").focus();
    $("#cod").keyup(function(event){
        if($(this).val().length==5){
            $("#ler").submit();
        }
    });    
</script>
