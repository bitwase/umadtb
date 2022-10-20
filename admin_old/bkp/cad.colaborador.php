<span class="tt_pg"><b>Cadastra Colaborador</b></span><br><br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//verifica se tem permissão de adm para incluir dados
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}

$agora=date('dmYHis');

$salva = $_POST[salva];

if($salva == 1){
$nome = $_POST[nome];
$matricula = $_POST[matricula];
$dt = $_POST[data_ad];//0123456789
$data_ad = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
$empresa = $_POST[empresa];
$setor = $_POST[setor];
$acesso = $_POST[acesso];
$email = $_POST[email];
$senha = hash('whirlpool','ser1988');
mysql_query("INSERT INTO tb_colaboradores 
(matricula,nome,admissao,empresa,setor,senha,acesso,situacao) VALUES
('$matricula','$nome','$data_ad','$empresa','$setor','$senha','$acesso','1')
");

$foto =  $_FILES['arquivo']['name'];
if(!empty($foto)){
// Pasta onde o arquivo vai ser salvo
$_UP['pasta'] = 'arquivos/colaboradores/fotos/';
// Tamanho máximo do arquivo (em Bytes)
$_UP['tamanho'] = 3000000 * 30000000 * 2; // 10Mb
// Array com as extensões permitidas
$_UP['extensoes'] = array('jpg', 'png');
$imagens=array('jpg', 'png');
// Renomeia o arquivo?
$_UP['renomeia'] = true;
// Array com os tipos de erros de upload do PHP
$_UP['erros'][0] = 'Não houve erro';
$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
$_UP['erros'][4] = 'Não foi feito o upload do arquivo';

// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
if ($_FILES['arquivo']['error'] != 0) {
die("Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$_FILES['arquivo']['error']]);
exit; // Para a execução do script
}
// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
// Faz a verificação da extensão do arquivo
$extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
if (array_search($extensao, $_UP['extensoes']) === false) {
}
// Faz a verificação do tamanho do arquivo
else if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
echo "O arquivo enviado é muito grande, envie arquivos de até 30Mb.";
}
// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
else {
// Primeiro verifica se deve trocar o nome do arquivo
if ($_UP['renomeia'] == true) {
// Cria um nome com a data e hora do envio (para ser arquivo único)
   $nome_final = $matricula.".$extensao";
}
else {
// Mantém o nome original do arquivo
$nome_final = $_FILES['arquivo']['name'];
}
// Depois verifica se é possível mover o arquivo para a pasta escolhida
if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
echo "Upload efetuado com sucesso!";
} else {
// Não foi possível fazer o upload, provavelmente a pasta está incorreta
echo "Não foi possível enviar o arquivo, tente novamente";
}
}
}
echo "<script type='text/javascript'>alert('Cadastro Realizado com Sucesso.');</script>";
echo "<META http-equiv='refresh' content='0;URL=index.php?pg=cad.colaborador.php'>"; 
}
?>

<form action="#" method="POST" enctype="multipart/form-data">
<input type="hidden" name="salva" value="1">
<b>Nome</b><br>
<input type="text" name="nome" size="40" required><br>
<b>Matrícula</b><br>
<input type="text" name="matricula" size="10" required><br>
<b>Data de Admissão</b><br>
<input type="text" name="data_ad" id="data_ad" class="date" size="10"><br>
<b>Empresa</b><br>
<select required name="empresa">
<option value="0" disabled selected>Selecione
<?php 
$em1 = mysql_query("select * from tb_empresa where situacao = '1' ORDER BY empresa");
while($em = mysql_fetch_assoc($em1)){
	echo "<option value='$em[id]'>$em[empresa]";
}
?>
</select><br>
<b>Setor</b><br>
<select required name="setor">
<option value="0" disabled selected>Selecione
<?php 
$st1 = mysql_query("select * from tb_setor where situacao = '1' ORDER BY setor");
while($st = mysql_fetch_assoc($st1)){
	echo "<option value='$st[id]'>$st[setor]";
}
?>
</select><br>
<label><b>Foto</b></label><br>
<input type="file" name="arquivo"/>
<input type="hidden" name="MAX_FILE_SIZE" value="300000000000"><br>
<b>Nível de Acesso</b><br>
<select required name="acesso">
<option value="0" disabled selected>Selecione
<?php 
$ac1 = mysql_query("select * from tb_acessos ORDER BY nivel");
while($ac = mysql_fetch_assoc($ac1)){
	echo "<option value='$ac[id]'>$ac[nivel]";
}
?>
</select><br>
<b>Email</b><br>
<input type="text" name="email" placeholder="exemplo@serdia.com.br" size="30" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"><br>
<br>
<input type="submit" value="Salvar">
</form>
<script>
jQuery(function($){
   $("#data_ad").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
}
</script>
