<span class="tt_pg"><b>Cadastra Setores</b></span><br><br>

<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}


$agora=date('dmYHis');

$salva = $_POST[salva];

if($salva == 1){
$setor = $_POST[setor];

mysql_query("INSERT INTO tb_setor 
(setor,situacao) VALUES
('$setor','1')
");

echo "<script type='text/javascript'>alert('Cadastro Realizado com Sucesso.');</script>";
echo "<META http-equiv='refresh' content='0;URL=index.php?pg=cad.setor.php'>"; 
}
?>

<form action="#" method="POST" enctype="multipart/form-data">
<input type="hidden" name="salva" value="1">
<b>Setor</b><br>
<input type="text" name="setor" size="20" required><br><br>
<input type="submit" value="Salvar">
</form>
