<h3>Configurações</h3>
<?php
//echo "<pre>";
//print_r($config);
?>

<h3>Configurações de Email</h3>

<div class="row">
    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="mailEmail">Email de Envio / Responder Para</label>
            <input type="text" class="form-control" name="mailEmail" id="mailEmail" onchange="atualiza(this.id)" value="<?php echo $config['mailEmail']; ?>">
        </div>
    </div>

    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="mailNome">Nome Email</label>
            <input type="text" class="form-control" name="mailNome" id="mailNome" onchange="atualiza(this.id)" value="<?php echo $config['mailNome']; ?>">
        </div>
    </div>

    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="mailChave">Email - Key API (Sendinblue)</label>
            <input type="text" class="form-control" name="mailChave" id="mailChave" onchange="atualiza(this.id)" value="<?php echo $config['mailChave']; ?>">
        </div>
    </div>

    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="destinatarioCursoVencido">Nome Destinatário de alerta: Vencimento de Cursos</label>
            <input type="text" class="form-control" name="destinatarioCursoVencido" id="destinatarioCursoVencido" onchange="atualiza(this.id)" value="<?php echo $config['destinatarioCursoVencido']; ?>">
        </div>
    </div>

    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="emailCursoVencido">Email Destinatário de alerta: Vencimento de Cursos</label>
            <input type="text" class="form-control" name="emailCursoVencido" id="emailCursoVencido" onchange="atualiza(this.id)" value="<?php echo $config['emailCursoVencido']; ?>">
        </div>
    </div>

    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="destinatarioProjetoVencido">Nome Destinatário de alerta: Projetos Atrasadas</label>
            <input type="text" class="form-control" name="destinatarioProjetoVencido" id="destinatarioProjetoVencido" onchange="atualiza(this.id)" value="<?php echo $config['destinatarioProjetoVencido']; ?>">
        </div>
    </div>

    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="emailProjetoVencido">Email Destinatário de alerta: Projetos Atrasadas</label>
            <input type="text" class="form-control" name="emailProjetoVencido" id="emailProjetoVencido" onchange="atualiza(this.id)" value="<?php echo $config['emailProjetoVencido']; ?>">
        </div>
    </div>
    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="destinatarioBkp">Nome Destinatário de alerta: Backup Concluído</label>
            <input type="text" class="form-control" name="destinatarioBkp" id="destinatarioBkp" onchange="atualiza(this.id)" value="<?php echo $config['destinatarioBkp']; ?>">
        </div>
    </div>

    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="emailBkp">Email Destinatário de alerta: Backup Concluído</label>
            <input type="text" class="form-control" name="emailBkp" id="emailBkp" onchange="atualiza(this.id)" value="<?php echo $config['emailBkp']; ?>">
        </div>
    </div>

    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="qtAlertaErroLogin">Quantidade de tentativas de acesso errado.</label>
            <input type="text" class="form-control" name="qtAlertaErroLogin" id="qtAlertaErroLogin" onchange="atualiza(this.id)" value="<?php echo $config['qtAlertaErroLogin']; ?>">
        </div>
    </div>

    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="nomeAlertaLogin">Nome Destinatário de alerta: Tentativas de Acesso</label>
            <input type="text" class="form-control" name="nomeAlertaLogin" id="nomeAlertaLogin" onchange="atualiza(this.id)" value="<?php echo $config['nomeAlertaLogin']; ?>">
        </div>
    </div>

    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="emailAlertaLogin">Email Destinatário de alerta: Tentativas de Acesso</label>
            <input type="text" class="form-control" name="emailAlertaLogin" id="emailAlertaLogin" onchange="atualiza(this.id)" value="<?php echo $config['emailAlertaLogin']; ?>">
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-8 col-xs-2">
        <div class="form-group">
            <label for="">Categoria Padrão Para Centro de Custo "Projetos"</label>
            <select class="custom-select" name="catProjeto" id="catProjeto" onchange="atualiza(this.id)">
                <?php
                $lc = $pdo->query("select * from tb_catcentrocusto where st = 1 order by categoria");
                while ($l = $lc->fetch()) {
                    $sel = "";
                    if ($config['catProjeto'] == $l['id']) {
                        $sel = "selected";
                    }
                    echo "<option value='$l[id]' $sel>$l[categoria]</option>";
                }
                ?>
            </select>
        </div>
    </div>
</div>

<div class="col-md-8 col-xs-2">
    <div class="form-group">
        <label for="">Categoria de Centro de Custo "Engerede"</label>
        <select class="custom-select" name="centroEngerede" id="centroEngerede" onchange="atualiza(this.id)">
            <?php
            $lc = $pdo->query("select * from tb_catcentrocusto where st = 1 order by categoria");
            while ($l = $lc->fetch()) {
                $sel = "";
                if ($config['centroEngerede'] == $l['id']) {
                    $sel = "selected";
                }
                echo "<option value='$l[id]' $sel>$l[categoria]</option>";
            }
            ?>
        </select>
    </div>
</div>
<div class="col-md-8 col-xs-2">
    <div class="form-group">
        <label for="">Conta Padrão Para "Banco"</label>
        <select class="custom-select" name="contaBanco" id="contaBanco" onchange="atualiza(this.id)">
            <?php
            $lc = $pdo->query("select * from fin_conta where st = 1 order by conta");
            while ($l = $lc->fetch()) {
                $sel = "";
                if ($config['contaBanco'] == $l['id']) {
                    $sel = "selected";
                }
                echo "<option value='$l[id]' $sel>$l[conta]</option>";
            }
            ?>
        </select>
    </div>
</div>
<h3>Contas a Pagar Automático - XML Importado</h3>
<div class="row">

    <div class="col-md-2 col-xs-2">
        <input type="hidden" id="aux_contaPagarAutomatico">
        <div class="form-group">
            <label for="custoT">Habilita</label><br>
            <input type="checkbox" name="hab_contaPagarAutomatico" id="hab_contaPagarAutomatico" data-on="Sim" data-off="Não" onchange="atualiza(this.id)" data-toggle="toggle" <?php if ($config['fatAutomaticaNfImportada']) {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                } ?>>
        </div>

    </div>

    <div class="col-md-3 col-xs-2">
        <div class="form-group">
            <label for="">Categoria</label>
            <select class="custom-select" name="catFatura" id="catFatura" onchange="atualiza(this.id)">
                <option value="">Não Informado</option>
                <?php
                $lc = $pdo->query("select * from fin_catfin where st = 1 order by categoria");
                while ($l = $lc->fetch()) {
                    $sel = "";
                    if ($config['catFatura'] == $l['id']) {
                        $sel = "selected";
                    }
                    echo "<option value='$l[id]' $sel>$l[categoria]</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="col-md-3 col-xs-2">
        <div class="form-group">
            <label for="">Subcategoria</label>
            <select class="custom-select" name="subCatFatura" id="subCatFatura" onchange="atualiza(this.id)">
                <option value="">Não Informado</option>
                <?php
                $lc = $pdo->query("select * from fin_subcatfin where st = 1 order by sub");
                while ($l = $lc->fetch()) {
                    $sel = "";
                    if ($config['subCatFatura'] == $l['id']) {
                        $sel = "selected";
                    }
                    echo "<option value='$l[id]' $sel>$l[sub]</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="col-md-3 col-xs-2">
        <div class="form-group">
            <label for="">Centro de Custo</label>
            <select class="custom-select" name="centroFatura" id="centroFatura" onchange="atualiza(this.id)">
                <option value="">Não Informado</option>
                <?php
                $lc = $pdo->query("select * from tb_centrocusto where st = 1 order by centro");
                while ($l = $lc->fetch()) {
                    $sel = "";
                    if ($config['centroFatura'] == $l['id']) {
                        $sel = "selected";
                    }
                    echo "<option value='$l[id]' $sel>$l[centro]</option>";
                }
                ?>
            </select>
        </div>
    </div>
</div>

<h3>Alertas</h3>

<div class="row">
    <div class="col-md-3 col-xs-2">
        <div class="form-group">
            <label for="">Dias de Alerta Vencimento Curso</label>
            <input type="text" class="form-control" name="diasVencimentoCurso" id="diasVencimentoCurso" aria-describedby="helpId" placeholder="" onchange="atualiza(this.id)" value="<?php echo $config['diasVencimentoCurso']; ?>">
        </div>
    </div>
    <div class="col-md-3 col-xs-2">
        <div class="form-group">
            <label for="">% de Orçamento Utilizado</label>
            <input type="text" class="form-control" name="percAlertaOrcamento" id="percAlertaOrcamento" aria-describedby="helpId" placeholder="" onchange="atualiza(this.id)" value="<?php echo $config['percAlertaOrcamento']; ?>">
        </div>
    </div>
</div>
<br>
<br>
<br>
<br>
<script>
    function atualiza(x) {
        var alerta;
        var campo;

        var valor = $("#" + x).val();

        switch (x) {
            case "mailEmail":
                alerta = "<strong>Email</strong>";
                campo = "mailEmail";
                break;
            case "mailNome":
                alerta = "<strong>Nome Email</strong>";
                campo = "mailNome";
                break;
            case "mailChave":
                alerta = "<strong>Chave Sendinblue (email)</strong>";
                campo = "mailChave";
                break;

            case "destinatarioCursoVencido":
                alerta = "<strong>Nome Destinatário: Vencimento de Curso</strong>";
                campo = "destinatarioCursoVencido";
                break;

            case "emailCursoVencido":
                alerta = "<strong>Email Destinatário: Vencimento de Curso</strong>";
                campo = "emailCursoVencido";
                break;

            case "destinatarioProjetoVencido":
                alerta = "<strong>Nome Destinatário: Projetos em Atraso</strong>";
                campo = "destinatarioProjetoVencido";
                break;

            case "emailBkp":
                alerta = "<strong>Email Destinatário: Backup Concluído</strong>";
                campo = "emailBkp";
                break;

            case "destinatarioBkp":
                alerta = "<strong>Nome Destinatário: Backup Concluído</strong>";
                campo = "destinatarioBkp";
                break;

            case "emailProjetoVencido":
                alerta = "<strong>Email Destinatário: Projetos em Atraso</strong>";
                campo = "emailProjetoVencido";
                break;

            case "diasVencimentoCurso":
                alerta = "<strong>Dias Para Alerta de Vencimento de Curso</strong>";
                campo = "diasVencimentoCurso";
                break;

            case "percAlertaOrcamento":
                alerta = "<strong>% de Orçamento Utilizado</strong>";
                campo = "percAlertaOrcamento";
                break;

            case "catProjeto":
                alerta = "<strong>Categoria Padrão de Projetos</strong>";
                campo = "catProjeto";
                break;

            case "centroEngerede":
                alerta = "<strong>Centro de Custo Padrão 'Engerede'</strong>";
                campo = "centroEngerede";
                break;

            case "contaBanco":
                alerta = "<strong>Conta Padrão 'Banco'</strong>";
                campo = "contaBanco";
                break;

            case "hab_contaPagarAutomatico":
                if ($("#" + x).is(":checked")) {
                    valor = "1";
                } else if (!$("#" + x).is(":checked")) {
                    valor = "0";
                }
                alerta = "<strong>Habilita gerar contas a receber de NF importada</strong>";
                campo = "fatAutomaticaNfImportada";
                break;

            case "catFatura":
                alerta = "<strong>Categoria Padrão de Fatura Para NF-e Importada</strong>";
                campo = "catFatura";
                break;

            case "subCatFatura":
                alerta = "<strong>Subcategoria Padrão de Fatura Para NF-e Importada</strong>";
                campo = "subCatFatura";
                break;

            case "centroFatura":
                alerta = "<strong>Centro de Custo Padrão de Fatura Para NF-e Importada</strong>";
                campo = "centroFatura";
                break;

            case "qtAlertaErroLogin":
                alerta = "<strong>Quantidade de Tentativas Sem Sucesso de Login Para Alerta</strong>";
                campo = "qtAlertaErroLogin";
                break;

            case "nomeAlertaLogin":
                alerta = "<strong>Nome Destinatário: Tentativas de Acesso</strong>";
                campo = "nomeAlertaLogin";
                break;

            case "emailAlertaLogin":
                alerta = "<strong>Email Destinatário: Tentativas de Acesso</strong>";
                campo = "emailAlertaLogin";
                break;
        }

        $.post('dadosConfig.php', {
            campo: campo,
            valor: valor,
            msgLog: alerta
        }, function(response) {
            $("#alertaRealizado").html(alerta + " alterado com sucesso.");
            $("#alertaRealizado").fadeIn("slow");
            setTimeout(fechaAlerta, 5000);

            function fechaAlerta() {
                $("#alertaRealizado").fadeOut(3000);
            }
        });
    }
</script>