<div id="meio_loga">
    <?php
    include 'arquivos.php';
    $lk = ($_REQUEST['lk'] ?? "");
    $cont = 0;
    $e = ($_GET['e'] ?? "");
    if ($cont > 0) {
        // header('Location:gravacookie.php');
    }
    if ($cont == 0) {
        $tt = ($_GET['tt'] ?? "");
    ?>
        <?php if ($e == 1) { //dados errados.
            echo "<script type='text/javascript'>
alert('Dados Incorretos, favor verificar.');
</script>
";
        }
        if ($e == 2) { //usuário inativo
            echo "<script type='text/javascript'>
                alert('Usuário inativo. Favor entrar em contato com o responsável.');
</script>
";
        }

        if ($e == 3) { //sem acesso ao sistema 
            echo "<script type='text/javascript'>
alert('Usuário sem acesso ao sistema. Favor entrar em contato com o responsável.');
</script>
";
        }

        if ($e == 4) { //muitas tentativas 
            echo "<div class='alert alert-danger' role='alert'>
            <h4 class='alert-heading'>MUITAS TENTATIVAS DE ACESSO</h4>
            <hr>
            <p class='mb-0'>Se esqueceu a senha, fale com um administrador do sistema.</p>
          </div>
";
        }

        if ($e != 4) {
        ?>

            <body id="bodyLogin" style="width: 100%; heigth: 100%;">
                <form name='loga' id='loga' action='valida.php' method='post'><br>
                    <img src="arquivos/imagens/logo.png" width="250px"><br><br>
                    <input type='hidden' name='lk' value='<?php echo $lk; ?>'>
                    <div class="form-group">
                        <input type="text" class="form-control" required name="usuario" id="usuario" placeholder="Usuário">
                        <input type="password" class="form-control" required name="senha" id="senha" placeholder="Senha">
                        <button type="submit" name="" id="" class="btn btn-primary btn-lg btn-block">Entrar</button>
                    </div>
                    <br><br><br><br>

                </form>
            </body>
    <?php
        }
    }
    ?>
</div>
<script>
    carregou();
</script>