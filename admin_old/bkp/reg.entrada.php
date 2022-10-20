<span class="tt_pg"><b>Registro de Entrada Financeira</b></span>
<br>
<br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//verifica se tem permissão de adm para incluir dados
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
    $obs = $_POST[just];
    $dt = $_POST[data];//01/34/6789
    $dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
    if($dt != $hj2){
        $sit = 1;
    }
    else if($dt == $hj2){
        $sit = 2;
    }
    mysql_query("
        insert into financeiro (data,tipo,valor,motivo,us,sit,dt_ag,obs) VALUES
        (now(),'1','$vlr','Entrada Manual','$cod_us','$sit','$dt','$obs')
        ");
	echo "<script type='text/javascript'>alert('Entrada Realizada com Sucesso.');</script>";
	echo "<META http-equiv='refresh' content='0;URL=index.php?pg=reg.entrada'>";
}
?>

<form action="#" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="salva" value="1">
    <b>Data/Hora: </b><?php echo "$agr"; ?><br>
    <b>Valor: </b><input type="text" name="vlr" class="vlr" size="6"><br>
    <b>Data Programada: </b><input type="text" class="date" size="9" name="data" value="<?php echo $hj;?>"><br>
    <b>Justificativa: </b><br><textarea name="just" rows="3" cols="40" maxlenght="200" required></textarea><br>
	<input type="submit" value="Salvar">
</form>