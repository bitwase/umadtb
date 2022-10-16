<div id="meio">
<?php
include 'arquivos.php';
$cont=0;
$e=$_GET['e'];
if($cont>0){
   // header('Location:gravacookie.php');
}
if($cont==0){
    $tt=$_GET['tt'];
?>
<?php if($e==1){ 
echo "<script type='text/javascript'>
alert('Dados Incorretos, favor verificar.');
</script>
";
}?>
<form name='loga' id='loga' action='valida.php' method='post'>
<input type='text' name='usuario' size='8' placeholder="Usuário" required></br>
<input type='password' name='senha' size='8' placeholder='Senha' required onFocus="if(this.value=='') this.value=''; this.type='password'" onBlur="if(this.value=='') this.value='';if(this.value=='Senha') this.type='text'"></br><? #ver para alterar tipo de campo ao clicar?>
<input type='submit' value='Entrar'>
</form>
<?php
}
?>
</div>
<?php  #encerra verificação se logado ou não ?>