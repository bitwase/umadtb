<h3>Compras</h3>

<?php

############# CARREGAMENTO DE GRID ####################

$idsGrid = "23"; //se houver mais de uma, separar por vírgula.

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

<!--div id="campoAcoes">
    <div class="row">
        <div class="col-md-2 col-xs-2">
            <div class="row">
                <div class="col-md-12 col-xs-2">
                    <div class="form-group">
                        <button type="button" name="" id="" class="btn btn-primary btn-block">Editar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div-->

<div id="registraCompra" class="d-hidden">
    <h3>Registra Compra</h3>

    <form action="?pg=compra" id="regCompra" method="POST">
        <div class="row">
            <input type="hidden" name="addCompra" value="1">
            <div class="col-md-12 col-xs-2">
                <div class="form-group">
                    <label for="cmpFornecedor">Fornecedor</label>
                    <input type="text" value="" list="listaFornecedor" class="form-control" name="cmpFornecedor" autocomplete="off" id="cmpFornecedor" aria-describedby="helpId">
                </div>
            </div>

            <datalist id="listaFornecedor">
                <?php
                    $l1 = $pdo->query("select id, doc, fantasia from est_fornecedores order by fantasia");
                    while($l = $l1->fetch()){
                        echo "<option value='$l[fantasia] - $l[doc]' data-value='$l[id]'>$l[fantasia] - $l[doc]</option>";
                    }
                ?>
            </datalist>
            <div class="col-md-4 col-xs-2">
                <div class="form-group">
                    <label for="prevEntrega">Previsão de Entrega</label>
                    <input type="date" class="form-control" id="prevEntrega" name="prevEntrega" autocomplete="off" aria-describedby="helpId">
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-3 col-xs-1">
                <button type="button" name="" id="" onclick="registraCompra()" class="btn btn-primary btn-block">Continuar</button>
            </div>
            <div class="col-md-3 col-xs-1">
                <button type="button" name="" id="" class="btn btn-danger btn-block" onclick="fechaAdd()">Voltar</button>
            </div>

        </div>
    </form>
</div>

<div class="d-hidden">
    <form action="?pg=compra" id="dirCompra" method="POST">
        <input type="hidden" name="c" id="compraDir">
    </form>
</div>
<div id="gridCompras"></div>

<script>
    $(window).bind("beforeunload", function() {
        console.log("length", $("#cmpFornecedor").val().length);
        if ($("#cmpFornecedor").val().length > 0)
            return "Do you really want to close?";
    });

    function registraCompra(){
        var fornecedor = $('#listaFornecedor option[value="' + $("#cmpFornecedor").val() + '"]').attr('data-value');
        $("#cmpFornecedor").val(fornecedor);
        $("#regCompra").submit();
    }

    function dadosTabela() {
        $(document).ready(function() {

            var dataSource = new kendo.data.DataSource({
                transport: {
                    read: function(options) {
                        // make JSONP request to https://demos.telerik.com/kendo-ui/service/products
                        $.ajax({
                            url: "estoque/dadosCompra.php",
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
            $("#gridCompras").kendoGrid({
                toolbar: ["nova", "pdf", "excel"],
                pdf: {
                    fileName: "compras.pdf",
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
                    fileName: "compras.xlsx",
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
                        field: "Compra",
                        title: "Compra",
                        hidden: false,
                        width: 90,
                        template: "#= Compra #"
                    },
                    {
                        field: "Fornecedor",
                        title: "Fornecedor",
                        width: 200,
                    },
                    {
                        field: "PrevisaoEntrega",
                        title: "Previsão Entrega",
                        width: 170,
                        format: "{0:dd/MM/yyyy HH:mm}",
                        template: "#= kendo.toString(PrevisaoEntrega, \"dd/mm/YYYY HH:mm\") #"
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
                    },
                    {
                        field: "UsuarioRegistro",
                        title: "Usuário",
                        width: 170,
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
            var grid = $("#gridCompras").data("kendoGrid");
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
                localStorage["kendo-grid-options-compras"] = xpt;
                //  alert(xpt);

                //abaixo, g: é o id da grid na base de dados, u é id do usuário e s: o stete novo
                var u = "<?php echo $cod_us; ?>";
                $.ajax({
                    method: "POST",
                    url: "atualizaGrid.php",
                    data: {
                        g: "23",
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
                var options = localStorage["kendo-grid-options-compras"];
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
        $(".k-grid-nova").html("+ NOVA");
        $(".k-grid-nova").attr("onclick", "mostraAdd()");
    }

    function mostraAdd() {
        $("#registraCompra").fadeIn("slow");
        $("#mascara").fadeIn("fast");
    }

    function fechaAdd() {
        $("#registraCompra").fadeOut("slow");
        $("#mascara").fadeOut("fast");
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

    function limpaCampos() {
        $("#addProdutos input").val("");
        $("#addProdutos select").val("");
    }

    function fechaAlertaCC(t) {
        $("#alerta").fadeOut('slow');

        $("#" + t).fadeIn('fast');
    }

    function direcionaCompra(x) {

        $("#compraDir").val(x);

        $("#dirCompra").submit();
    }
</script>