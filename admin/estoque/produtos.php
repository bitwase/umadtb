<h3>Produtos</h3>

<?php

############# CARREGAMENTO DE GRID ####################

$idsGrid = "21"; //se houver mais de uma, separar por vírgula.

$lgrid = $pdo->query("select * from tb_grid where id in($idsGrid)");
while ($l = $lgrid->fetch()) {
    //enquanto houver... procurar se existe um padrao para o usuário na tabela tb_gridUsr
    $ve = $pdo->query("select * from tb_gridusuario where grid = '$l[id]' and usuario = '$cod_us'")->rowCount();
    if ($ve) { //se houver...
        $vg = $pdo->query("select * from tb_gridusuario where grid = '$l[id]' and usuario = '$cod_us'")->fetch();
        $grid1 = $vg['padrao'];
    }
    if (!$ve) { //se não houver...
        $grid1 = $l['padrao'];
    }
?>
    <script>
        var xpt = <?php echo "$grid1"; ?>;
        //alert(xpt);
        xpt = JSON.stringify(xpt);
        //alert(xpt);
        localStorage["<?php echo $l['grid']; ?>"] = xpt;
    </script>
<?php }

############# CARREGAMENTO DE GRID ####################

?>

<div id="addProdutos" class="d-hidden">
    <h3>Incluir Novo Produto</h3>

    <div class="row">
        <div class="col-md-12 col-xs-2">
            <div class="form-group">
                <label for="pdtDescricao">Descrição</label>
                <input type="text" class="form-control" name="pdtDescricao" id="pdtDescricao">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-xs-2">
            <div class="form-group">
                <label for="">Grupo</label>
                <input list="listaGrupo" class="form-control" name="grupo" id="grupoLista" aria-describedby="helpId" onchange="validaGrupo()" autocomplete="off">
            </div>
        </div>

        <div class="col-md-4 col-xs-2">
            <div class="form-group">
                <label for="">Subgrupo</label>
                <input list="listaSubgrupo" class="form-control" name="subgrupo" id="subLista" aria-describedby="helpId" onchange="validaSubgrupo()" autocomplete="off">
            </div>
        </div>

        <div class="col-md-4 col-xs-2">
            <div class="form-group">
                <label for="pdtUnidade">Unidade</label>
                <select class="form-control select2-selection select2-selection--multiple" name="pdtUnidade" id="pdtUnidade" style="width:100%">
                    <option value="" selected></option>
                    <?php
                    $lc = $pdo->query("select * from est_unidades where st = 1 order by unidade");
                    while ($l = $lc->fetch()) {
                        echo "<option value='$l[id]'>$l[unidade]</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 col-xs-2">
            <button type="button" name="" id="" class="btn btn-primary btn-block" onclick="insereDados()">Salvar</button>
        </div>
        <div class="col-md-2 col-xs-2">
            <button type="button" name="" id="" class="btn btn-danger btn-block" onclick="fechaAdd()">Voltar</button>
        </div>
    </div>

    <datalist id="listaGrupo">
        <?php
        $lcat1 = $pdo->query("select * from est_grupo where st = 1 order by grupo");
        $catAceita = "";
        while ($lcat = $lcat1->fetch()) {
            echo "<option value='$lcat[grupo]' data-value='$lcat[id]'></option>";
        }
        //$catAceita = substr($catAceita, 0, -1);
        //$catAceita = ("var catAceitas = [" . $catAceita . "],\nregex = new RegExp('\\\\b' + catAceitas.join(" . Chr(34) . "\\\\b|\\\\b" . Chr(34) . ") + '\\\\b', 'i');");

        ?>
    </datalist>

    <datalist id="listaSubgrupo">

    </datalist>
</div>

<div id="gridProdutos"></div>

<script>
    function dadosTabela() {
        $(document).ready(function() {

            var dataSource = new kendo.data.DataSource({
                transport: {
                    read: function(options) {
                        // make JSONP request to https://demos.telerik.com/kendo-ui/service/products
                        $.ajax({
                            url: "estoque/dadosProdutos.php",
                            dataType: "json", // "jsonp" is required for cross-domain requests; use "json" for same-domain requests
                            data: {
                                a: 'lista'
                            },
                            success: function(result) {
                                // notify the data source that the request succeeded
                                options.success(result);
                            },
                            error: function(result) {
                                // notify the data source that the request failed
                                options.error(result);
                            }
                        });
                    },
                    schema: {
                        model: {
                            fields: {}
                        }
                    },
                }
            });
            dataSource.fetch(function() {
                /* The result can be observed in the DevTools(F12) console of the browser. */
                console.log(dataSource.view().length); // displays "77"
            });
            //ver: https://docs.telerik.com/kendo-ui/knowledge-base/auto-resize-grid-when-hiding-and-showing-columns
            $("#gridProdutos").kendoGrid({
                toolbar: ["novo", "pdf", "excel"],
                pdf: {
                    fileName: "produtos.pdf",
                    allPages: true,
                    avoidLinks: true,
                    paperSize: "A4",
                    margin: {
                        top: "2cm",
                        left: "1cm",
                        right: "1cm",
                        bottom: "1cm"
                    },
                    landscape: true,
                    repeatHeaders: true,
                    template: $("#page-template").html(),
                    scale: 0.8
                },
                excel: {
                    fileName: "produtos.xlsx",
                    filterable: true
                },

                autoBind: false,
                height: "550px",
                groupable: true,
                sortable: true,
                columnMenu: true,
                reorderable: true,
                resizable: true,
                filterable: true,
                pageable: {
                    refresh: false,
                    pageSizes: false,
                    buttonCount: false,
                    previousNext: false,
                    info: false,
                    numeric: false
                },
                //persistSelection: true,
                dataSource: dataSource,
                //		change: onDataBound,
                persistSelection: true,
                columns: [
                    //	{ selectable: true, width: "50px" },

                    {
                        field: "ID",
                        title: "ID",
                        hidden: false,
                        width: 90,
                        template: "#= ID #"
                    },
                    {
                        field: "Descricao",
                        title: "Descrição",
                        width: 200,
                    },
                    {
                        field: "Grupo",
                        title: "Grupo",
                        width: 150,
                    },
                    {
                        field: "Subgrupo",
                        title: "Subgrupo",
                        width: 150
                    },
                    {
                        field: "Un",
                        title: "Un",
                        width: 100
                    },
                    {
                        field: "Situacao",
                        title: "Situação",
                        width: 150
                    },
                    {
                        field: "DataRegistro",
                        title: "Data Registro",
                        width: 170,
                        format: "{0:dd/MM/yyyy HH:mm}",
                        template: "#= kendo.toString(DataRegistro, \"dd/mm/YYYY HH:mm\") #"
                    }
                ],
                excelExport: function(e) { //remover HTML de grids
                    var rows = e.workbook.sheets[0].rows;
                    for (var ri = 0; ri < rows.length; ri++) {
                        var row = rows[ri];
                        if (row.type == "data") {
                            for (var ci = 0; ci < row.cells.length; ci++) {
                                var cell = row.cells[ci];
                                //se existir o atributo
                                if (typeof cell.value != "undefined" && cell.value != "" && cell.value != null && cell.value instanceof Date == false) {
                                    var val = cell.value.replace(/<[^>]+>/g, '');
                                    cell.value = val;
                                }
                            }
                        }
                    }
                },
                columnReorder: function(e) {
                    setTimeout(salvar, 5);
                },
                columnShow: function(e) {
                    setTimeout(salvar, 5); //setGridWidth(e);
                },
                columnHide: function(e) {
                    setTimeout(salvar, 5); //setGridWidth(e);
                },
                columnResize: function(e) {
                    setTimeout(salvar, 5); //console.log(e.column.field, e.newWidth, e.oldWidth);
                }
            });
            var grid = $("#gridProdutos").data("kendoGrid");
            /*$("#sort").click(function() {
                grid.dataSource.sort(grid.dataSource.options.sort);
            });*/

            $("#save").click(function(e) {
                salvar();
            });

            function salvar() {
                //         e.preventDefault();
                //var xpt = kendo.stringify(grid.getOptions());
                var options = grid.getOptions();
                var xpt = kendo.stringify(options);
                //						document.getElementById("stateNovo").value = xpt;
                localStorage["kendo-grid-options-produtos"] = xpt;
                //  alert(xpt);

                //abaixo, g: é o id da grid na base de dados, u é id do usuário e s: o stete novo
                var u = "<?php echo $cod_us; ?>";
                $.ajax({
                    method: "POST",
                    url: "atualizaGrid.php",
                    data: {
                        g: "21",
                        u: u,
                        s: xpt
                    },
                    success: function(response) {
                        //alert(response);
                        //		       			value = response.company;
                    }
                });

            }

            load();
            $("#load").click(function(e) {
                load();
            });

            function load() {
                var options = localStorage["kendo-grid-options-produtos"];
                if (options) {
                    var parsedOptions = JSON.parse(options);
                    grid.setOptions({
                        columns: parsedOptions.columns,
                        group: parsedOptions.group
                    });

                    grid.dataSource.fetch();

                }
            }
            dataSource.read();
        });

        setTimeout(atualizaBotao, 1000);
        //ação e customização botão novo
    }

    dadosTabela();

    function atualizaBotao() {
        $(".k-grid-novo").html("+ NOVO");
        $(".k-grid-novo").attr("onclick", "mostraAdd()");
    }

    function mostraAdd() {
        $("#addProdutos").fadeIn("slow");
        $("#mascara").fadeIn("fast");
    }

    function fechaAdd() {
        $("#addProdutos").fadeOut("slow");
        $("#mascara").fadeOut("fast");
    }

    $(document).ready(function() {
        $('#pdtUnidade').select2();
    });

    function validaGrupo() {
        var grp = $("#grupoLista");
        var grpAux = $("#grupoLista").val();
        var grupo = $('#listaGrupo option[value="' + $("#grupoLista").val() + '"]').attr('data-value');
        //validar se existe...
        $.getJSON("estoque/retgrp.php?a=1&c=" + grupo, function(atData) {
            var valid = [];
            $(atData).each(function(key, value) {
                valid.push(value.valid); //retorna subgrupo...
            });

            if (valid == "false" && grpAux != "") {

                var retAlerta = "<div class='alert alert-warning'><strong>Atenção!</strong> Deve ser informado um grupo válido antes de continuar.</div><input type='button' class='btn btn-warning btn-block' value='VOLTAR' onclick='fechaAlertaCC(\"addProdutos\")'>";
                $("#alerta").html(retAlerta);
                $("#alerta").fadeIn('slow');
                $("#addProdutos").fadeOut("fast");

                grp.val("");
                grp.focus();
            }
        });

        //aqui deve preparar uma lista com as subgrupos válidas, e formar um datalist.
        $.getJSON("estoque/retgrp.php?c=" + grupo, function(atData) {
            var lista = [];
            $(atData).each(function(key, value) {
                lista.push(value.lista); //retorna subgrupo...
            });
            $("#listaSubgrupo").html(lista);
        });
    }

    function validaSubgrupo() {
        var grp = $("#grupoLista").val();
        var subgrupo = $("#subLista");
        var sub = subgrupo.val();
        $.getJSON('estoque/retsub.php?c=' + grp + '&s=' + sub, function(atData) {
            //var id = [];
            var qt = [];
            $(atData).each(function(key, value) {
                qt.push(value.qt); //retorna subgrupo...
                //id.push(value.id);//retorna id da subgrupo...
            });
            if (qt == 0) {

                var retAlerta = "<div class='alert alert-warning'><strong>Atenção!</strong> Subgrupo inexistente ou não vinculado com a grupo atual.<br>Deve ser informado um subgrupo válido antes de continuar.</div><input type='button' class='btn btn-warning btn-block' value='VOLTAR' onclick='fechaAlertaCC(\"addProdutos\")'>";
                $("#alerta").html(retAlerta);
                $("#alerta").fadeIn('slow');
                $("#addProdutos").fadeOut("fast");

                subgrupo.val("");
                subgrupo.focus();
            }
        });

    }

    function insereDados() {
        //validar se os campos estão todos preenchidos...
        if ($("#pdtDescricao").val() == "") {
            var retAlerta = "<div class='alert alert-warning'><strong>Atenção!</strong> Deve ser informado uma descrição antes de continuar.</div><input type='button' class='btn btn-warning btn-block' value='VOLTAR' onclick='fechaAlertaCC(\"addProdutos\")'>";

            $("#alerta").html(retAlerta);
            $("#alerta").fadeIn('slow');
            $("#addProdutos").fadeOut("fast");

            return false;
        }

        if ($("#grupoLista").val() == "") {
            var retAlerta = "<div class='alert alert-warning'><strong>Atenção!</strong> Deve ser informado um grupo antes de continuar.</div><input type='button' class='btn btn-warning btn-block' value='VOLTAR' onclick='fechaAlertaCC(\"addProdutos\")'>";

            $("#alerta").html(retAlerta);
            $("#alerta").fadeIn('slow');
            $("#addProdutos").fadeOut("fast");

            return false;
        }

        if ($("#subLista").val() == "") {
            var retAlerta = "<div class='alert alert-warning'><strong>Atenção!</strong> Deve ser informado um subgrupo antes de continuar.</div><input type='button' class='btn btn-warning btn-block' value='VOLTAR' onclick='fechaAlertaCC(\"addProdutos\")'>";

            $("#alerta").html(retAlerta);
            $("#alerta").fadeIn('slow');
            $("#addProdutos").fadeOut("fast");

            return false;
        }

        if ($("#pdtUnidade").val() == "") {
            var retAlerta = "<div class='alert alert-warning'><strong>Atenção!</strong> Deve ser informado uma unidade antes de continuar.</div><input type='button' class='btn btn-warning btn-block' value='VOLTAR' onclick='fechaAlertaCC(\"addProdutos\")'>";

            $("#alerta").html(retAlerta);
            $("#alerta").fadeIn('slow');
            $("#addProdutos").fadeOut("fast");

            return false;
        }

        //se chegou aqui, deu tudo certo, agora envia para dadosFinanceiro
        let grp = $('#listaGrupo option[value="' + $("#grupoLista").val() + '"]').attr('data-value');

        let sub = $('#listaSubgrupo option[value="' + $("#subLista").val() + '"]').attr('data-value');

        $.post('estoque/dadosProdutos.php', {
            a: 1,
            desc: $("#pdtDescricao").val(),
            grupo: grp,
            subgrupo: sub,
            unidade: $("#pdtUnidade").val()
        }, function(response) {
            // alert("success");
            //chamar função atribuiCentroCusto(c,l);

            //limpar todos os campos e esconder a tela

            $("#addProdutos").fadeOut("slow");
            $("#mascara").fadeOut("slow");
            //atValores();
            setTimeout(dadosTabela, 1000);

            limpaCampos();
            //}
        });
        //$("#formContaPagar").submit();
    }

    function limpaCampos(){
        $("#addProdutos input").val("");
        $("#addProdutos select").val("");
    }

    function fechaAlertaCC(t) {
        $("#alerta").fadeOut('slow');

        $("#" + t).fadeIn('fast');
    }
</script>