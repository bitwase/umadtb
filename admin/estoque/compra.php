<?php
############# CARREGAMENTO DE GRID ####################

$idsGrid = "24"; //se houver mais de uma, separar por vírgula.

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


## RECEBENDO DADOS DE COMPRA ##
$add = $_POST['addCompra'];
if ($add) {
    $fornecedor = $_POST['cmpFornecedor'];
    $prevEntrega = $_POST['prevEntrega'];

    $c = new regCompra($pdo, $fornecedor, $prevEntrega, $cod_us);
    $c = $c->registraCompra();
}
if (!$add) { //se não foi informado para adicionar
    $c = $_REQUEST['c']; //id da compra vindo da tela anterior
}

if ($c == "") {
    echo "<script>$('#alerta').html('<h2>Compra não informada ou inexistente.</h2><br><a href=\"?pg=compras\"><button type=\"button\" class=\"btn btn-primary btn-block\">Voltar</button></a>');
	$('#alerta').fadeIn('slow');
	$('#mascara').fadeIn('slow');
	</script>";
}
?>

<div id="campoAcoes">
    <div class="row">
        <div class="col-md-2 col-xs-2">
            <div class="row">
                <div class="col-md-12 col-xs-2">
                    <div class="form-group">
                        <button type="button" name="" id="btEditar" class="btn btn-primary btn-block" onclick="editarCompra()">Editar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>
<div class="row">
    <div class="col-md-9">
        <h4 class="cmp_titulo">...</h4>
    </div>
    <div class="col-md-3" style="text-align: right;">
        <h4 class="cmp_status">...</h4>
    </div>
</div>
<div class="accordion">

    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGeral" aria-expanded="true" aria-controls="collapseGeral">
                Geral
            </button>
        </h2>
        <div id="collapseGeral" class="accordion-collapse collapse show" aria-labelledby="headingOne">
            <div class="accordion-body">

                <div class="row">
                    <div class="col-md-3 col-xs-2">
                        <div class="form-group">
                            <label for="numCompra">Compra</label>
                            <input type="text" class="form-control" name="numCompra" id="numCompra" disabled>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-2">
                        <div class="form-group">
                            <label for="orCompra">Origem</label>
                            <input type="text" class="form-control" name="orCompra" id="orCompra" disabled>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-2">
                        <div class="form-group">
                            <label for="refCompra">Ref. Compra</label>
                            <input type="text" class="form-control podeEditar" name="refCompra" id="refCompra" disabled>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-2">
                        <div class="form-group">
                            <label for="dtEntrega">Data Entrega</label>
                            <input type="date" class="form-control" name="dtEntrega" id="dtEntrega" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-xs-2">
                        <div class="form-group">
                            <label for="cmpFornecedor">Fornecedor</label>
                            <input type="text" class="form-control podeEditar" list="listaFornecedor" name="cmpFornecedor" id="cmpFornecedor" disabled>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-2">
                        <div class="form-group">
                            <label for="refFornecedor">Ref. Fornecedor</label>
                            <input type="text" class="form-control podeEditar" name="refFornecedor" id="refFornecedor" disabled>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-2">
                        <div class="form-group">
                            <label for="prevEntrega">Prev. Entrega</label>
                            <input type="date" class="form-control podeEditar" name="prevEntrega" id="prevEntrega" disabled>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <datalist id="listaFornecedor">
        <?php
        $l1 = $pdo->query("select id, doc, fantasia from est_fornecedores order by fantasia");
        while ($l = $l1->fetch()) {
            echo "<option value='$l[fantasia] - $l[doc]' data-value='$l[id]'>$l[fantasia] - $l[doc]</option>";
        }
        ?>
    </datalist>

    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseItens" aria-expanded="true" aria-controls="collapseItens">
                Itens
            </button>
        </h2>
        <div id="collapseItens" class="accordion-collapse collapse show" aria-labelledby="headingOne">
            <div class="accordion-body">
                <div id="gridItens"></div>
                <hr>
                <div class="row">
                    <div class="col-md-3 btEditar">
                        <button type="button" name="" id="" class="btn btn-primary btn-block btEditarItem" onclick="editarItem()" disabled><i class="fa fa-edit" aria-hidden="true"></i> Editar</button>
                        <button type="button" name="" id="" class="btn btn-primary btn-block d-hidden btSalvarItem" onclick="salvarItem()"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                    </div>

                    <div class="col-md-3">
                        <button type="button" name="" id="" class="btn btn-primary btn-block d-hidden"> <i class="fa fa-times" aria-hidden="true"></i></button>
                        <button type="button" name="" id="" class="btn btn-primary btn-block btCancelaItem d-hidden" onclick="cancelaAlteracaoItem()"> <i class="fa fa-times" aria-hidden="true"></i> Descartar Alteração</button>
                    </div>

                    <div class="col-md-3 removerLinha d-hidden">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="" id="rmLinha" value="1">
                                Remover esta linha
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" id="idLinhaItens">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="linhaItens">Linha</label>
                            <input type="text" class="form-control" name="linhaItens" id="linhaItens" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="codItens">Código</label>
                            <input type="text" class="form-control podeEditar" name="codItens" id="codItens" aria-describedby="helpId" placeholder="" disabled autocomplete="off" list="listaCod" onchange="selItem2('cod', this)">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="desItens">Descrição</label>
                            <input type="text" class="form-control podeEditar" name="desItens" id="desItens" aria-describedby="helpId" placeholder="" disabled autocomplete="off" list="listaDes" onchange="selItem2('des', this)">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="unItens">Unidade</label>
                            <input type="text" class="form-control podeEditar" name="unItens" id="unItens" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="qtSolItens">Qt. Solicitado</label>
                            <input type="text" class="form-control podeEditar" name="qtSolItens" id="qtSolItens" aria-describedby="helpId" placeholder="" disabled onchange="validaQuantidades('qtSol')">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="qtRecItens">Qt. Recebido</label>
                            <input type="text" class="form-control" name="qtRecItens" id="qtRecItens" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="qtCanItens">Qt. Cancelado</label>
                            <input type="text" class="form-control podeEditar" name="qtCanItens" id="qtCanItens" aria-describedby="helpId" placeholder="" disabled onchange="validaQuantidades('qtCan')">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="qtPenItens">Qt. Pendente</label>
                            <input type="text" class="form-control" name="qtPenItens" id="qtPenItens" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dtPrevItens">Prev. Entrega</label>
                            <input type="date" class="form-control podeEditar" name="dtPrevItens" id="dtPrevItens" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ultEntregaItens">Últ. Entrega</label>
                            <input type="date" class="form-control" name="ultEntregaItens" id="ultEntregaItens" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="vlUnItens">Vl. Unitário</label>
                        <div class="input-group">
                            <div class="input-group-text">R$</div>
                            <input type="text" class="form-control podeEditar" name="vlUnItens" id="vlUnItens" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="vlTotItens">Vl. Total</label>

                        <div class="input-group">
                            <div class="input-group-text">R$</div>
                            <input type="text" class="form-control" name="vlTotItens" id="vlTotItens" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="usInclusaoItens">Us. Inclusão</label>
                            <input type="text" class="form-control" name="usInclusaoItens" id="usInclusaoItens" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dtInclusaoItens">Data Inclusão</label>
                            <input type="datetime-local" class="form-control" name="dtInclusaoItens" id="dtInclusaoItens" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="usAlteracaoItens">Us. Alteração</label>
                            <input type="text" class="form-control" name="usAlteracaoItens" id="usAlteracaoItens" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dtAlteracaoItens">Data Alteração</label>
                            <input type="datetime-local" class="form-control" name="dtAlteracaoItens" id="dtAlteracaoItens" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFormaPagamento" aria-expanded="true" aria-controls="collapseFormaPagamento">
                Formas de Pagamento
            </button>
        </h2>
        <div id="collapseFormaPagamento" class="accordion-collapse collapse show" aria-labelledby="headingOne">
            <div class="accordion-body">

            </div>
        </div>
    </div>
</div>

<div id="incluiItem" class="d-hidden">
    <h4>Incluir Item</h4><br>

    <div class="row">
        <div class="col-md-3 col-xs-2">
            <div class="form-group">
                <label for="itemCod">Cód.</label>
                <input or="text=" list="listaCod" autocomplete="off" class="form-control" name="itemCod" id="itemCod" onchange="selItem('cod', this)">
            </div>
        </div>
        <div class="col-md-9 col-xs-2">
            <div class="form-group">
                <label for="itemDes">Descrição</label>
                <input type="text" list="listaDes" autocomplete="off" class="form-control" name="itemDes" id="itemDes" onchange="selItem('des', this)">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="itemQt">Quantidade</label>
                <input type="number" class="form-control" step="0.1" name="itemQt" id="itemQt">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="itemUn">Unidade</label>
                <input type="text" class="form-control" name="itemUn" id="itemUn">
            </div>
        </div>

        <div class="col-md-4">
            <label for="itemVl">Valor Unitário</label>
            <div class="input-group">
                <div class="input-group-text">R$</div>
                <input type="text" class="form-control" name="itemVl" id="itemVl">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="itemUn"><br></label>
                <button type="button" name="" id="" class="btn btn-success btn-block" onclick="setItem()">Incluir</button>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="itemUn"><br></label>
                <button type="button" name="" id="" class="btn btn-danger btn-block" onclick="fechaAddItem()">Voltar</button>
            </div>
        </div>
    </div>

    <datalist id="listaCod">
        <?php
        $l1 = $pdo->query("select id, descricao from est_produtos");
        while ($l = $l1->fetch()) {
            echo "<option value='$l[id] - $l[descricao]' data-value='$l[id]'>$l[id] - $l[descricao]</option>";
        }
        ?>
    </datalist>

    <datalist id="listaDes">
        <?php
        $l2 = $pdo->query("select id, descricao from est_produtos");
        while ($l = $l2->fetch()) {
            echo "<option value='$l[id] - $l[descricao]' data-value='$l[descricao]'>$l[id] - $l[descricao]</option>";
        }
        ?>
    </datalist>
</div>

<script>
    function carregaItens() {
        $(document).ready(function() {
            let c = "<?php echo $c; ?>";
            var dataSource = new kendo.data.DataSource({
                transport: {
                    read: function(options) {
                        // make JSONP request to https://demos.telerik.com/kendo-ui/service/products
                        $.ajax({
                            url: "estoque/dadosCompra.php",
                            dataType: "json", // "jsonp" is required for cross-domain requests; use "json" for same-domain requests
                            data: {
                                a: 'itens',
                                c: c, //compra
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
                            fields: {
                                Linha: {
                                    editable: false
                                },
                                Cod: {
                                    type: "number"
                                }
                            }
                        }
                    }
                }
            });
            dataSource.fetch(function() {
                /* The result can be observed in the DevTools(F12) console of the browser. */
                //console.log(dataSource.view().length); // displays "77"
            });
            //ver: https://docs.telerik.com/kendo-ui/knowledge-base/auto-resize-grid-when-hiding-and-showing-columns
            $("#gridItens").kendoGrid({
                //editable: true,
                toolbar: ["INCLUIR", "pdf", "excel"],
                /*items: [
                    {
                	 type: "buttonGroup",
                	 buttons: [
                	   { text: "foo" },
                	   { text: "bar" },
                	   { text: "baz" }
                    ]
                    }
                  ],*/
                pdf: {
                    fileName: "item_compra.pdf",
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
                    fileName: "item_compra.xlsx",
                    filterable: true
                },

                autoBind: false,
                height: "300px",
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
                        hidden: true,
                        width: 100,
                        type: "number",
                    },
                    {
                        field: "Linha",
                        title: "Linha",
                        width: 100,
                    },
                    {
                        field: "Cod",
                        title: "Cód.",
                        width: 100,
                    },
                    {
                        field: "Descricao",
                        title: "Descrição",
                        width: 300
                    },
                    {
                        field: "Qt",
                        title: "Quantidade",
                        width: 100
                    },
                    {
                        field: "UnMed",
                        title: "U. Medida",
                        width: 100
                    },
                    {
                        field: "VlUn",
                        title: "Vl. Unit.",
                        width: 100
                    },
                    {
                        field: "VlTot",
                        title: "Vl. Total",
                        width: 100
                    },
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
                },
                save: function(e) {
                    console.log(e);

                    let uid = e.model.uid;
                    let item = dataSource.getByUid(uid);
                    console.log(uid);
                    console.log(item.ID);
                    if (e.values.name !== "") {
                        // the user changed the name field
                        if (e.values.name !== e.model.name) {
                            /* The result can be observed in the DevTools(F12) console of the browser. */
                            console.log("name is modified");
                        }
                    } else {
                        e.preventDefault();
                        /* The result can be observed in the DevTools(F12) console of the browser. */
                        console.log("name cannot be empty");
                    }
                }
            });

            var grid = $("#gridItens").data("kendoGrid");
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
                localStorage["kendo-grid-options-compraItens"] = xpt;
                //  alert(xpt);

                //abaixo, g: é o id da grid na base de dados, u é id do usuário e s: o stete novo
                var u = "<?php echo $cod_us; ?>";
                $.ajax({
                    method: "POST",
                    url: "atualizaGrid.php",
                    data: {
                        g: "24",
                        u: u,
                        s: xpt
                    },
                    success: function(response) {
                        //alert(response);
                        //		       			value = response.company;
                    }
                });

            }

            //load();
            $("#load").click(function(e) {
                load();
            });

            function load() {
                var options = localStorage["kendo-grid-options-compraItens"];
                if (options) {
                    var parsedOptions = JSON.parse(options);
                    grid.setOptions({
                        columns: parsedOptions.columns,
                        group: parsedOptions.group
                    });

                    //		                       grid.refresh();
                    grid.dataSource.fetch();
                    //  $("#grid").data("kendoGrid").dataSource.read();                  
                }
            }
            //DEPOIS QUE RECARREGA OS DADOS, AÍ SIM PODE SER CHAMADO A FUNÇÃO QUE IRÁ TER AÇÃO QUANDO CLICAR NA LINHA...
            $(grid.tbody).on("click", "td", function(e) {
                var row = $(this).closest("tr");
                var curRowIdx = $("tr", grid.tbody).index(row);
                var colIdx = $("td", row).index(this);
                var item = grid.dataItem(row);
                var id = item.ID;
                //json para retornar a observação...
                //alert(item.Status);
                getItem(item.ID);
                //mostraObs(id);
            });

            dataSource.read();
        });
        //cursor da grid
        setTimeout(atualizaCursor, 500);

        function atualizaCursor(){
            $(".k-grid-content td[role='gridcell']").css("cursor", "pointer");
        }
    }

    /* HABILITAR EDIÇÃO */

    function editarItem() {
        $("#collapseItens .podeEditar").removeAttr("disabled");

        $(".btEditarItem").addClass("d-hidden");
        $(".btSalvarItem").removeClass("d-hidden");
        $(".btCancelaItem").removeClass("d-hidden");
        $(".removerLinha").removeClass("d-hidden");

        alertaSair();

        //alterar cursor em grid
        //bloquear grid?
        $(".k-grid-content td[role='gridcell']").css("cursor", "not-allowed");
        $(".k-grid-content td[role='gridcell']").css("pointer-events", "none");
        $(".k-grid-content td[role='gridcell']").attr("disabled", true);
    }

    function salvarItem() {
        let x = $("#idLinhaItens").val();
        $("#collapseItens .podeEditar").attr("disabled", true);

        $(".btEditarItem").removeClass("d-hidden");
        $(".btSalvarItem").addClass("d-hidden");
        $(".btCancelaItem").addClass("d-hidden");
        $(".removerLinha").addClass("d-hidden");
        //chmar função apra atualizar dados e atualizar tela, bloqueando os campos e chamando editaItem com mesmo id

        let c = "<?php echo $c; ?>";
        if ($("#rmLinha").is(":checked") == true) {
            var remove = true;
        }
        if ($("#rmLinha").is(":checked") == false) {
            var remove = false;
        }
        let codItens = $("#codItens").val();
        let unItens = $("#unItens").val();
        let qtSolItens = $("#qtSolItens").val();
        let qtCanItens = $("#qtCanItens").val();
        let dtPrevItens = $("#dtPrevItens").val();
        let vlUnItens = $("#vlUnItens").val();

        $.post('estoque/dadosCompra.php', {
            a: '3',
            c: c,
            id: x,
            codItens: codItens,
            unItens: unItens,
            qtSolItens: qtSolItens,
            qtCanItens: qtCanItens,
            dtPrevItens: dtPrevItens,
            vlUnItens: vlUnItens,
            remove: remove
        }, function(response) {
            getDadosCompra();
            getItem(x);
        });

//        $(".k-grid-content td[role='gridcell']").css("pointer-events", "");
    }

    function cancelaAlteracaoItem() {
        let x = $("#idLinhaItens").val();
        $("#collapseItens .podeEditar").attr("disabled", true);

        $(".btEditarItem").removeClass("d-hidden");
        $(".btSalvarItem").addClass("d-hidden");
        $(".btcancelaItem").addClass("d-hidden");
        $(".removerLinha").addClass("d-hidden");
        //chmar função apra atualizar dados e atualizar tela, bloqueando os campos e chamando editaItem com mesmo id
        getDadosCompra();
        getItem(x);

    }

    function alertaSair() {
        $(window).bind("beforeunload", function() {
            //if (0 == 0)
            return "Do you really want to close?";
        });
    }

    function editarCompra() {
        //liberar campos permitidos e mudar nome do botão para salvar.. incluir opção "Descartar Alterações"
        $("#collapseGeral .podeEditar").removeAttr("disabled");
        $("#btEditar").html("Salvar");
        $("#btEditar").attr("onclick", "salvarCompra()");

        alertaSair();
    }

    function salvarCompra() {
        let c = "<?php echo $c; ?>";
        let refCompra = $("#refCompra").val();
        let fornecedor = $('#listaFornecedor option[value="' + $("#cmpFornecedor").val() + '"]').attr('data-value'); //buscar data-value
        let refFornecedor = $("#refFornecedor").val();
        let prevEntrega = $("#prevEntrega").val();

        //chamar o post e atualizar a tela
        $.post('estoque/dadosCompra.php', {
            a: '2',
            c: c,
            refCompra: refCompra,
            fornecedor: fornecedor,
            refFornecedor: refFornecedor,
            prevEntrega: prevEntrega //valor em padrão nacional
        }, function(response) {
            getDadosCompra();
            $(".podeEditar").attr("disabled", true);
            $("#btEditar").html("Editar");
            $("#btEditar").attr("onclick", "editarCompra()");
        });
    }

    function setItem() {
        //regras de validação dos campos
        var cod = $("#itemCod").val();
        var c = "<?php echo $c; ?>";
        var qt = $("#itemQt").val();
        var un = $("#itemUn").val();
        var vl = $("#itemVl").val();

        if (cod == "") {
            var retAlerta = "<div class='alert alert-warning'><strong>Atenção!</strong> Deve ser informado um produto antes de continuar.</div><input type='button' class='btn btn-warning btn-block' value='VOLTAR' onclick='fechaAlertaCC()'>";
            $("#alerta").html(retAlerta);
            $("#alerta").fadeIn('slow');

            $("#incluiItem").fadeOut('fast');

            return false;
        }
        if (qt == "") {
            var retAlerta = "<div class='alert alert-warning'><strong>Atenção!</strong> Deve ser informado uma quantidade antes de continuar.</div><input type='button' class='btn btn-warning btn-block' value='VOLTAR' onclick='fechaAlertaCC()'>";
            $("#alerta").html(retAlerta);
            $("#alerta").fadeIn('slow');

            $("#incluiItem").fadeOut('fast');

            return false;
        }
        if (un == "") {
            var retAlerta = "<div class='alert alert-warning'><strong>Atenção!</strong> Deve ser informado uma unidade antes de continuar.</div><input type='button' class='btn btn-warning btn-block' value='VOLTAR' onclick='fechaAlertaCC()'>";
            $("#alerta").html(retAlerta);
            $("#alerta").fadeIn('slow');

            $("#incluiItem").fadeOut('fast');

            return false;
        }
        if (vl == "") {
            var retAlerta = "<div class='alert alert-warning'><strong>Atenção!</strong> Deve ser informado um valor antes de continuar.</div><input type='button' class='btn btn-warning btn-block' value='VOLTAR' onclick='fechaAlertaCC()'>";
            $("#alerta").html(retAlerta);
            $("#alerta").fadeIn('slow');

            $("#incluiItem").fadeOut('fast');

            return false;
        }

        $.post('estoque/dadosCompra.php', {
            a: '1',
            c: c,
            item: cod,
            qt: qt,
            un: un,
            vl: vl //valor em padrão nacional
        }, function(response) {
            fechaAddItem();
            //setTimeout(fechaAlerta, 5000);
            getDadosCompra();

        });
    }

    function fechaAlertaCC(t) {
        $("#alerta").fadeOut('slow');

        $("#incluiItem").fadeIn('fast');
    }

    $(document).ready(function() {
        $("#itemVl").mask("#.##0,00", {
            reverse: true
        });
        $("#vlUnItens").mask("#.##0,00", {
            reverse: true
        });
        $("#vlTotItens").mask("#.##0,00", {
            reverse: true
        });
    });

    function selItem(ref, x) {
        //let val = $('#nf_cod').val();
        if (ref == "cod") {
            $("#itemDes").val($('#listaDes option[value="' + x.value + '"]').attr('data-value'));
            $("#itemCod").val($('#listaCod option[value="' + x.value + '"]').attr('data-value'));
        }
        if (ref == "des") {
            $("#itemCod").val($('#listaCod option[value="' + x.value + '"]').attr('data-value'));
            $("#itemDes").val($('#listaDes option[value="' + x.value + '"]').attr('data-value'));
        }
        //chamar função para mostrar as unidades

        //incluir regra para validrproduto de uso da unidade selecionada
    }

    function selItem2(ref, x) {
        //let val = $('#nf_cod').val();
        if (ref == "cod") {
            $("#desItens").val($('#listaDes option[value="' + x.value + '"]').attr('data-value'));
            $("#codItens").val($('#listaCod option[value="' + x.value + '"]').attr('data-value'));
        }
        if (ref == "des") {
            $("#codItens").val($('#listaCod option[value="' + x.value + '"]').attr('data-value'));
            $("#desItens").val($('#listaDes option[value="' + x.value + '"]').attr('data-value'));
        }
    }

    setTimeout(atualizaBotao, 500);

    function atualizaBotao() {
        $(".k-grid-incluir").html("+ INCLUIR");
        $(".k-grid-incluir").attr("onclick", "mostraAddItem()");
    }

    function mostraAddItem() {
        $("#incluiItem").fadeIn("slow");
        $("#mascara").fadeIn("fast");
    }

    function fechaAddItem() {
        $("#incluiItem").fadeOut("slow");
        $("#mascara").fadeOut("fast");
    }

    /* DADOS DA COMPRA */
    getDadosCompra();

    function getDadosCompra() {
        let c = "<?php echo $c; ?>";
        $.getJSON("estoque/dadosCompra.php", {
            a: "dados",
            c: c
        }, function(atData) {
            var compra = [];
            var fornecedor = [];
            var codFornecedor = [];
            var refFornecedor = [];
            var resumoValor = [];
            var origem = [];
            var previsaoEntrega = [];
            var refCompra = [];
            var dtEntrega = [];
            var status = [];

            $(atData).each(function(key, value) {
                compra.push(value.compra);
                fornecedor.push(value.fornecedor);
                codFornecedor.push(value.codFornecedor);
                refFornecedor.push(value.refFornecedor);
                resumoValor.push(value.resumoValor);
                origem.push(value.origem);
                previsaoEntrega.push(value.previsaoEntrega);
                refCompra.push(value.refCompra);
                dtEntrega.push(value.dtEntrega);
                status.push(value.status);
            });

            $(".cmp_titulo").html(compra + " - " + fornecedor + " - R$" + resumoValor);
            $(".cmp_status").html(status);
            $("#numCompra").val(compra);
            $("#orCompra").val(origem);

            $('#cmpFornecedor').val($("#listaFornecedor").find("[data-value='" + codFornecedor + "']").val());

            $("#refFornecedor").val(refFornecedor);
            $("#prevEntrega").val(previsaoEntrega);
            $("#refCompra").val(refCompra);
            $("#dtEntrega").val(dtEntrega);
        });
        carregaItens();

    }

    function getItem(x) {
        //desabilitar a edição
        $("#collapseItens .podeEditar").attr("disabled", true);

        $(".btEditarItem").removeClass("d-hidden");
        $(".btSalvarItem").addClass("d-hidden");
        $(".btcancelaItem").addClass("d-hidden");
        $(".removerLinha").addClass("d-hidden");
        $(".removerLinha input[type='checkbox']").prop("checked", false);
        $(".removerLinha input[type='checkbox']").removeAttr("disabled");


        let c = "<?php echo $c; ?>";
        $(".btEditarItem").removeAttr("disabled");
        $.getJSON("estoque/dadosCompra.php", {
            a: "especifico",
            c: c,
            i: x
        }, function(atData) {
            var linha = [];
            var cod = [];
            var descricao = [];
            var qt = [];
            var qtRecebido = [];
            var qtCancelado = [];
            var unMed = [];
            var vlUn = [];
            var vlTot = [];
            var prevEntrega = [];
            var dataRecebimento = [];
            var usAlteracao = [];
            var dataAlteracao = [];
            var usInclusao = [];
            var inclusao = [];

            $(atData).each(function(key, value) {
                linha.push(value.linha);
                cod.push(value.cod);
                descricao.push(value.descricao);
                qt.push(value.qt);
                qtRecebido.push(value.qtRecebido);
                qtCancelado.push(value.qtCancelado);
                unMed.push(value.unMed);
                vlUn.push(value.vlUn);
                vlTot.push(value.vlTot);
                prevEntrega.push(value.prevEntrega);
                dataRecebimento.push(value.dataRecebimento);
                usAlteracao.push(value.usAlteracao);
                dataAlteracao.push(value.dataAlteracao);
                usInclusao.push(value.usInclusao);
                inclusao.push(value.inclusao);
            });

            let pendente = (parseFloat(qt) - parseFloat(qtRecebido) - parseFloat(qtCancelado));

            if (qtRecebido > 0) {
                $(".removerLinha input[type='checkbox']").attr("disabled", true);
            }

            $("#idLinhaItens").val(x);
            $("#linhaItens").val(linha);
            $("#codItens").val(cod);
            $("#desItens").val(descricao);
            $("#unItens").val(unMed);
            $("#qtSolItens").val(qt);
            $("#qtRecItens").val(qtRecebido);
            $("#qtCanItens").val(qtCancelado);
            $("#qtPenItens").val(pendente);
            $("#vlUnItens").val(vlUn);
            $("#vlTotItens").val(vlTot);
            $("#dtPrevItens").val(prevEntrega);
            $("#ultEntregaItens").val(dataRecebimento);
            $("#usInclusaoItens").val(usInclusao);
            $("#dtInclusaoItens").val(inclusao);
            $("#usAlteracaoItens").val(usAlteracao);
            $("#dtAlteracaoItens").val(dataAlteracao);

            //difinir mínimo e máximo para solicitado e cancelado
            //mínimo solicitado: cancelado + recebido
            $("#qtSolItens").attr("min", parseFloat(qtRecebido) + parseFloat(qtCancelado));
            //$(".cmp_titulo").html(compra + " - " + fornecedor + " - R$" + resumoValor);
        });
    }
    /* REGRA PARA ACCORDION  */
    atAccordion();

    function validaQuantidades(c) {
        let qtSol = $("#qtSolItens").val();
        let qtRecebido = $("#qtRecItens").val();
        let qtCancelado = $("#qtCanItens").val();
        if (c == "qtSol") {
            //validar se quantidade informada é igual ou superior a quantidade recebido mais cancelado
            if (parseFloat(qtSol) < (parseFloat(qtRecebido) + parseFloat(qtCancelado))) {
                //emitir alerta
                $('#alerta').html('<h3>Quantidade solicitada deve ser maior que a soma da quantidade recebida com a quantidade cancelada. Alterado para: ' + (parseFloat(qtRecebido) + parseFloat(qtCancelado)) + '</h3><br><button type="button" class="btn btn-primary btn-block" onclick="fechaAlerta()">Voltar</button>');
                $('#alerta').fadeIn('slow');
                $('#mascara').fadeIn('slow');
                //definir para a quantidade mínima
                $('#qtSolItens').val(parseFloat(qtRecebido) + parseFloat(qtCancelado));
                $('#qtSolItens').focus;
                return false;
            }
        }

        if (c == "qtCan") {
            //validar se quantidade informada é igual ou superior a quantidade recebido mais cancelado
            if (parseFloat(qtCancelado) > (parseFloat(qtSol) - parseFloat(qtRecebido))) {
                //emitir alerta
                $('#alerta').html('<h3>Quantidade cancelada não pode ser maior que a subtração da quantidade solicitada com a quantidade recebida. Alterado para: ' + (parseFloat(qtSol) - parseFloat(qtRecebido)) + '</h3><br><button type="button" class="btn btn-primary btn-block" onclick="fechaAlerta()">Voltar</button>');
                $('#alerta').fadeIn('slow');
                $('#mascara').fadeIn('slow');
                //definir para a quantidade mínima
                $('#qtCanItens').val((parseFloat(qtSol) - parseFloat(qtRecebido)));
                return false;
            }
        }
    }

    function atAccordion() {

        $.getJSON("accordion.php?a=2&p=compra", function(atData) {
            var campo = [];
            var vis = [];
            $(atData).each(function(key, value) {
                campo.push(value.campo);
                vis.push(value.vis);
            });
            //rodar um each aqui, ver os valores e ajustar
            campo.forEach(mostra);

            function mostra(i, v) {
                if (vis[v] == "hidde") {
                    $("#" + i).removeClass("show");
                    $("[data-bs-target='#" + i + "']").addClass("collapsed");
                }
                if (vis[v] == "show") {
                    $("#" + i).addClass("show");
                    $("[data-bs-target='#" + i + "']").removeClass("collapsed");
                }
            }
        });
    }

    $(document).ready(function() {
        $(".accordion-button").click(function(r) {
            var id = r.target.getAttribute('aria-controls');
            //verificar se mostra ou esconde...
            var sit = $("[data-bs-target='#" + id + "']").hasClass("collapsed");
            if (sit == true) { //esconde
                var valor = "hidde";
            }
            if (sit == false) { //mostra
                var valor = "show";
            }

            //chamar atualização de status
            $.post('accordion.php', {
                a: 1,
                p: "compra",
                i: id,
                v: valor,
            }, function(response) {

            });

        });

    });
</script>