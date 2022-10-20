<h3>Inscritos</h3>

<?php
############# CARREGAMENTO DE GRID ####################

$idsGrid = "3"; //se houver mais de uma, separar por vírgula.

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

<div id="grid"></div> 

<script language="javascript" type="text/javascript">
    dadosCadastros();

    function dadosCadastros() {
        $(document).ready(function() {

            var dataSource = new kendo.data.DataSource({
                transport: {
                    read: function(options) {
                        // make JSONP request to https://demos.telerik.com/kendo-ui/service/products
                        $.ajax({
                            url: "eventos/dadosInscritos.php",
                            dataType: "json", // "jsonp" is  required for cross-domain requests; use "json" for same-domain requests
                            data: {
                                a: 'lista',
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
                                Nascimento: {
                                    type: 'date',
                                },
                                DataRegistro: {
                                    type: 'date',
                                }
                            }
                        }
                    },
                }
            });
            dataSource.fetch(function() {
                /* The result can be observed in the DevTools(F12) console of the browser. */
                console.log(dataSource.view().length); // displays "77"
            });
            //ver: https://docs.telerik.com/kendo-ui/knowledge-base/auto-resize-grid-when-hiding-and-showing-columns
            $("#grid").kendoGrid({
                toolbar: ["pdf", "excel"],
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
                    fileName: "inscritos.pdf",
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
                    fileName: "inscritos.xlsx",
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
                        hidden: true,
                        width: 100,
                        type: "number",
                    },
                    {
                        field: "Acao",
                        title: " ",
                        width: 100,
                        template: "#=Acao#",
                        exportable: {
                            pdf: false,
                            excel: false
                        }
                    },
                    {
                        field: "Nome",
                        title: "Nome",
                        width: 250
                    },
                    {
                        field: "Nascimento",
                        title: "Nascimento",
                        width: 140,
                        //type: "date",
                        format: "{0:dd/MM/yyyy}",
                        template: "#= kendo.toString(kendo.parseDate(Nascimento, \"yyyy-mm-dd\"),\"dd/mm/yyyy\") #",
                    },
                    {
                        field: "RG",
                        title: "RG",
                        width: 100
                    },
                    {
                        field: "Telefone",
                        title: "Telefone",
                        width: 150
                    },
                    {
                        field: "Email",
                        title: "Email",
                        width: 150
                    },
                    {
                        field: "DataRegistro",
                        title: "Data Inscrição",
                        width: 170,
                        format: "{0:dd/MM/yyyy HH:mm}",
                        template: "#= kendo.toString(kendo.parseDate(DataRegistro, \"yyyy-mm-dd\"),\"dd/mm/yyyy\") #",
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

            var grid = $("#grid").data("kendoGrid");
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
                localStorage["kendo-grid-options-inscritos"] = xpt;
                //  alert(xpt);

                //abaixo, g: é o id da grid na base de dados, u é id do usuário e s: o stete novo
                var u = "<?php echo $cod_us; ?>";
                $.ajax({
                    method: "POST",
                    url: "atualizaGrid.php",
                    data: {
                        g: "3",
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
                var options = localStorage["kendo-grid-options-inscritos"];
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
            dataSource.read();
        });
    }
</script>