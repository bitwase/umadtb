<form action="#" method="POST" enctype="multipart/form-data">

    <h3>Importação de XML's Anteriores</h3><br>

    <div class="alert alert-warning" role="alert">
        -O arquivo importado obrigatoriamente deve ser "xml.zip".<br>
        -Dentro deste arquivo obrigatoriamente deve existir uma pasta com o nome "xml" onde todos os arquivos devem estar dentro dela.<br>
        -Somente serão processados arquivos xml de notas de produtos.<br>
        -Os arquivos importados ficarão com status "Atendido" na consulta de notas. <br>
        -Esta importação NÃO gera contas a pagar. <br>
    </div>

    <input type="hidden" name="continua" value="1">
    <div class="row">
        <div class="col-md-6 col-xs-2">
            <div class="form-group">
                <input type="file" class="custom-file-input" id="file_xml" name="file_xml" accept=".zip">
                <label class="custom-file-label" for="file_xml" id="file_xml_label">Escolher Arquivo</label>
            </div>
        </div>
        <div class="col-md-3 col-xs-2">
            <button type="submit" name="" id="" class="btn btn-primary btn-block">Importar</button>
        </div>
    </div>

</form>

<?php

$continua = $_POST['continua'];

if ($continua) {

    if (isset($_FILES['file_xml']['name'])) {
        $filename = $_FILES['file_xml']['name'];
        $location = "antigo/$filename";

        //remover arquivo anterior se existir
        if (is_file($location)) {
            unlink($location);
        }

        $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);

        /* Valid extensions */
        $valid_extensions = array("zip");

        $response = 0;
        /* Check file extension */
        //if (in_array(strtolower($imageFileType), $valid_extensions)) {
        //ignorando validação, aceitar qualquer formato
        /* Upload file */
        if (move_uploaded_file($_FILES['file_xml']['tmp_name'], $location)) {
            $response = $location;
        }
    }
?>

    <div id="retorno" style="width: 100%; max-height:250px; overflow:auto; font-family: SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;">
        <i class="fa fa-hourglass-start"></i> Iniciando o processo...<br>
    </div>
<?php }
?>

<script>
    <?php if ($continua) { ?>
        setTimeout(chamaFuncao, 2000, "verificaAnterior");
    <?php } ?>

    $("#file_xml").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings("#file_xml_label").addClass("selected").html(fileName);
    });

    function chamaFuncao(a) {
        if (a == "verificaAnterior") {
            $("#retorno").append("<i class='fa fa-folder-magnifying-glass'></i> Verificando existência de arquivos anteriores...<br>");
        }
        $.post('antigo/scriptAtualiza.php', {
            a: a,
        }, function(response) {
            if (a != "verificaAnterior" && a != "analisa" && a != "descompacta") {
                $("#retorno").append(response);
            }
            if (a == "verificaAnterior") {
                var aux = response.split(" .==. ");
                if (aux[0] == "0") {
                    setTimeout(chamaFuncao, 2000, "limpa");
                }

                if (aux[0] == "1") {
                    setTimeout(chamaFuncao, 2000, "analisa");
                }
                $("#retorno").append(aux[1]);
            }

            if (a == "limpa") {
                setTimeout(chamaFuncao, 2000, "analisa");
            }

            if (a == "analisa") {
                $("#retorno").append("<i class='fa fa-print-magnifying-glass'></i> Validando arquivo enviado...<br>");
                var aux = response.split(" .==. ");
                if (aux[0] == "0") {
                    $("#retorno").append("<i class='fa fa-arrows-retweet'></i>Tente novamente.");
                }

                if (aux[0] == "1") {
                    setTimeout(chamaFuncao, 2000, "descompacta");
                    $("#retorno").append(aux[1]);
                    $("#retorno").append("<i class='fa fa-folder-tree'></i> Descompactando arquivo enviado...<br>");
                }
            }

            if (a == "descompacta") {
                var aux = response.split(" .==. ");
                if (aux[0] == "0") {
                    $("#retorno").append("<i class='fa fa-arrows-retweet'></i>Ocorreu algum erro. Tente novamente.");
                }

                if (aux[0] == "1") {
                    setTimeout(chamaFuncao, 2000, "lerArquivos");
                    $("#retorno").append(aux[1]);
                    $("#retorno").append("<i class='fa fa-print-magnifying-glass'></i> Processando arquivos...<br>");
                }
            }

            if (a == "lerArquivos") {

            }
        });
    }
</script>