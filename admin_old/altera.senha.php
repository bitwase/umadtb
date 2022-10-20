<?php
    $titulob="Altera Senha";

    $usuario = $_COOKIE['usuario'];
    $atual = hash('whirlpool', $_POST['atual']);
    $nova = hash('whirlpool',$_POST['nova']);
    
        if($atual != '19fa61d75522a4669b44e39c1d2e1726c530232130d407f89afee0964997f7a73e83be698b288febcf88e3e03c4f0757ea8964e59b63d93708b138cc42a66eb3'){   
    $sql= "UPDATE  usuarios SET  senha = '$nova' WHERE usuario = '$usuario' AND senha = '$atual'";
    $rel= mysql_query($sql);
    header('Location:index.php?pg=altera.senha.php');
    }
    ?>
      <form id='alt_senha' name='alt_senha' action='#' method='post' style="width:300px;left:0;right:0;margin:auto;">
    <input type='password' name='atual' value='' size='15' required placeholder="Senha Atual"></br>
    <input type='password' name='nova' value='' size='15' required placeholder="Nova Senha"></br>
    <input type='submit' value='Salvar'>
    </form>
