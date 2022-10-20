<h3>Diretórios</h3>
<?php
############# CARREGAMENTO DE GRID ####################

$idsGrid = "18"; //se houver mais de uma, separar por vírgula.

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

//adiciona novo

$add = $_POST['add'];
if ($add) {
    $arquivo = $_POST['arquivo'];
    $diretorio = $_POST['diretorio'];
    $titulo = $_POST['titulo'];

    try {
        $pdo->query("insert into diretorios (arquivo, diretorio, titulo) values(
        '$arquivo',
        '$diretorio',
        '$titulo'
    )");
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>

<form action="#" method="POST">
    <div class="row">
        <input type="hidden" name="add" value="1">

        <div class="col-md-2 col-xs-2">
            <div class="form-group">
                <label for="add_arquivo">Arquivo</label>
                <input type="text" class="form-control" name="arquivo" id="add_arquivo">
            </div>
        </div>

        <div class="col-md-2 col-xs-2">
            <div class="form-group">
                <label for="add_diretorio">Diretório</label>
                <input type="text" class="form-control" name="diretorio" id="add_diretorio">
            </div>
        </div>

        <div class="col-md-2 col-xs-2">
            <div class="form-group">
                <label for="add_titulo">Título</label>
                <input type="text" class="form-control" name="titulo" id="add_titulo">
            </div>
        </div>

        <div class="col-md-2 col-xs-2">
            <div class="form-group">
                <label for="add_titulo"><br></label>
                <button type="submit" name="" id="" class="btn btn-primary  btn-block">Adiciona</button>
            </div>
        </div>
    </div>
</form>


<div id="grid"></div>

<script>
    $(document).ready(function() {
        var dataSource = new kendo.data.DataSource({
            data: [
                <?php
                $cl1 = $pdo->query("select * from diretorios order by diretorio, titulo");
                $od = 0;
                while ($cli = $cl1->fetch()) {
                    $od++; //define ordem



                    $id = $cli['id'];

                    echo "{
		ID: '$id',
		Acao: '',
		Arquivo: '$cli[arquivo]',
		Diretorio:'$cli[diretorio]',
		Titulo:'$cli[titulo]'
		},";
                    $od++;
                }

                ?>

            ]
        }); //fim dos dados dataSource

        dataSource.fetch(function() {
            /* The result can be observed in the DevTools(F12) console of the browser. */
            console.log(dataSource.view().length); // displays "77"
        });
        //ver: https://docs.telerik.com/kendo-ui/knowledge-base/auto-resize-grid-when-hiding-and-showing-columns

        $("#grid").kendoGrid({

            /*excelExport: function(e) {
            	var rows = e.workbook.sheets[0].rows;

            	for (var ri = 0; ri < rows.length; ri++) {
            		var row = rows[ri];

            		for (var ci = 0; ci < row.cells.length; ci++) {
            			var cell = row.cells[ci];
            			if (cell.value && ($(cell.value).text() != "")) {
            				// Use jQuery.fn.text to remove the HTML and get only the text
            				cell.value = $(cell.value).text();
            				// Set the alignment
            				cell.hAlign = "right";
            			}
            		}
            	}
            },*/
            //    toolbar: ["pdf","excel",],
            toolbar: [{
                    name: "pdf",
                },
                {
                    name: "excel",
                },
            ],
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
                fileName: "diretorios.pdf",
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
                scale: 0.8,
            },
            excel: {
                fileName: "diretorios.xlsx",
                filterable: true
            },

            autoBind: false,
            height: "500px",
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
            //dataBound: onDataBound,
            columns: [{
                    field: "ID",
                    title: "ID",
                    hidden: true,
                    type: "number",
                },
                {
                    field: "Acao",
                    title: " ",
                    width: 80,
                    template: "#=Acao#",
                    exportable: {
                        pdf: false,
                        excel: false
                    }
                },
                {
                    field: "Arquivo",
                    title: "Arquivo",
                },
                {
                    field: "Diretorio",
                    title: "Diretorio",
                },
                {
                    field: "Titulo",
                    title: "Titulo",
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
        //grid.bind("dataBound", onDataBound);
        grid.dataSource.fetch();

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
            localStorage["kendo-grid-options-diretorios"] = xpt;
            //  alert(xpt);

            //abaixo, g: é o id da grid na base de dados, u é id do usuário e s: o stete novo
            var u = "<?php echo $cod_us; ?>";
            $.ajax({
                method: "POST",
                url: "atualizaGrid.php",
                data: {
                    g: "18",
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
            var options = localStorage["kendo-grid-options-diretorios"];
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
        /*$(grid.tbody).on("click", "td", function(e) {
        	var row = $(this).closest("tr");
        	var curRowIdx = $("tr", grid.tbody).index(row);
        	var colIdx = $("td", row).index(this);
        	var item = grid.dataItem(row);
        	var id = item.ID;
        	//json para retornar a observação...
        	//alert(item.Status);
        	mostraObs(id);
        });*/
        // $("#grid").data("kendoGrid").dataSource.read();
        //  $("#grid").getKendoGrid().dataSource.read();

        dataSource.read();
    });
</script>