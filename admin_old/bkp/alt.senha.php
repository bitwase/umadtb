<?php
include 'seguranca.php';
if($cont>0){ 

    $usuario = $_COOKIE['usuario'];
    $atual = hash('whirlpool', $_POST['atual']);
    $nova = hash('whirlpool',$_POST['nova']);
    
    if($atual != '19fa61d75522a4669b44e39c1d2e1726c530232130d407f89afee0964997f7a73e83be698b288febcf88e3e03c4f0757ea8964e59b63d93708b138cc42a66eb3'){   
    $sql= "UPDATE  tb_acessos SET  senha = '$nova' WHERE usuario = '$usuario' AND senha = '$atual'";
    $rel= mysql_query($sql);
    header('Location:alt.senha.php');
    }
    $titulo ="Controle de Usuários";
    $subtitulo ="Alteração de Senhas";
    include 'cima.php';
?>
<div id="meio">
<br /><br />
    <form id='alt_senha' name='alt_senha' action='alt.senha.php' method='post'>
    <input type='text' name='atual' value='Senha Atual' size='10' onFocus="if(this.value=='Senha Atual') { this.value=''; this.type='password'}" onBlur="if(this.value==''){this.value='Senha Atual'; this.type='text'}"></br>
    <input type='text' name='nova' value='Nova Senha' size='10' onFocus="if(this.value=='Nova Senha') { this.value=''; this.type='password'}" onBlur="if(this.value==''){this.value='Nova Senha'; this.type='text'}"></br>
    <input type='text' name='nova_repete' value='Repete Nova' size='10' onFocus="if(this.value=='Repete Nova') { this.value=''; this.type='password'}" onBlur="if(this.value==''){this.value='Repete Nova'; this.type='text'}"></br>
    <input type='submit' value='Salvar' onClick='return altSenha()'>
    </form>
    </div>
<?php }
else if($cont==0){
    header('Location:loga.php');
}
echo $nome;
?>