<?php
$titulob="Cadastro de Usuários";

$us=$_COOKIE['usuario'];

$chamado=$_POST['chamado'];
$usuario=$_POST['usuario'];
$dt_ab=$_POST['dt_abertura'];
$dt_ab=$dt_ab[6].$dt_ab[7].$dt_ab[8].$dt_ab[9]."-".$dt_ab[3].$dt_ab[4]."-".$dt_ab[0].$dt_ab[1];
$dt_abertura=$dt_ab;
$hr_abertura=$_POST['hr_abertura'];
$mn_abertura=$_POST['mn_abertura'];
$prioridade=$_POST['prioridade'];

$abertura="$dt_abertura $hr_abertura";

$data=date("Y-m-d H:i:s");// data atual
//grava dados do usuário


$sql_loc="SELECT * FROM chamados WHERE chamado = '$chamado'";
$res_loc=mysql_query($sql_loc);
$num_loc=mysql_num_rows($res_loc);

$sql_ext = "SELECT * FROM tb_us_externos ORDER BY nome ASC";
    $res_ext = mysql_query($sql_ext);

if($num_loc == 0){
if($chamado!="" && $abertura!="" && $abertura!=" :" && $abertura!=":" && $prioridade!=""){
$sql="INSERT INTO chamados VALUES('','$chamado','$usuario','$abertura','$prioridade','ABERTO','','')";
$res=mysql_query($sql);
$sql_hist = "INSERT INTO tb_historico VALUES('','$us','$chamado','$data','Registrado o protocolo \'$chamado\' com prioridade \'$prioridade\'. Resp. externo: $usuario')";
$rel_hist = mysql_query($sql_hist);
echo "<div id='cadastrado'>Chamado <i>'$chamado'</i> registrado com sucesso.</div>";
}
else{
    //echo "<div id='erro'>Preencher TODOS os campos.</div>";
}
}
else{
    echo "<div id='erro'>Chamado '<i>$chamado</i>' já registrado.</div>";
}
?>
<form name='cad_usuario' id='cad_empresa' action='index.php?pg=reg.usuario.php' method='POST'>
Nome:<input type="text" name="nome" size="20" maxlength="50"><br>
Matrícula:<input type="text" id="matricula" name="matricula" size="9" maxlength="10"><br>
Setor: ### FAZER SELECT ###<br>
Usuário Linux:<input type="text" name="linux" size="6"><br />
Senha Linux:<input type="password" name="pass_linux" id="pass_linux" size="6"><span id="ver" onclick="javascript: if(pass_linux.type == 'password') pass_linux.type ='text';" onmouseout="javascript: if(pass_linux.type == 'text') pass_linux.type ='password';">Ver</span><br />
<br />
Usuário Nitens: ### TENTAR FAZER PARA PROCURAR NO CONTROLE DE USUÁRIOS NITENS ###<br />
IP Estação: <input type="text" name="ip_est" id="ip_est" size="15"><br />
Email:<br /><textarea cols="20" rows="3" name="email" title="Informar 1 por linha"></textarea><br />
Ramal:<br />
<textarea cols="20" rows="3" name="ramal" title="Informar 1 por linha"></textarea><br />
<input type="submit" value="Salvar">
</form>
<script>
    $("#hrmn").mask("99:99");
</script>