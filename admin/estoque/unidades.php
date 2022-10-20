<h3>Unidades de Medida</h3>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#22/06/2016{
	-Desenvolvido;
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
############# CARREGAMENTO DE GRID ####################

$idsGrid = "22, 24, 25"; //se houver mais de uma, separar por vírgula.

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

$salva = $_POST['salva'];

$adicionarCat = $_POST['adicionarCat'];
if ($adicionarCat) {
	//classe para cadastrar categoria
	$sql = "insert into est_unidades (unidade, descricao, st, us, inclusao) values(upper('$_POST[categoria]'),'$_POST[descricao]','1','$cod_us',now())";
	//	echo $sql;
	//verificar existencias
	$ve = $pdo->query("select * from est_unidades where unidade = upper('$_POST[categoria]')")->rowCount();
	if (!$ve) {
		$rc = new regDados($pdo, $sql);
		$r = $rc->registra();
		echo $r;
	}
	//	mysql_query("insert into tb_centrocusto (centro, st, us, inclusao) values(upper('$_POST[centro]'),'1','$cod_us',now())")or die(mysql_error());
}

$desativa = $_POST['desativa'];

if ($desativa) {
	//se desativar...

	// inativar centro de custo...
	$sql = "update est_unidades set st = 0 where id = '$_POST[idCat]'";
	//	echo $sql;
	$rc = new regDados($pdo, $sql);
	$r = $rc->registra();
	echo $r;

	//mysql_query("update tb_centrocusto set st = 0 where id = '$_POST[idCat]'") or die(mysql_error());
	//inativar as subcategorias...
	//mysql_query("update tb_subCatFin set st = 0 where cat = '$_POST[idCat]'") or die(mysql_error());

	echo "<script>
document.getElementById('alertaTxt').innerHTML = 'Unidade inativada.';//limpa alerta
mostraAlerta();
</script>";
}

?>

<div id="addCategoria" style="display:none;">
	<span class="tt_pg">Cadastra Unidade</span><br><br>
	<form action="#" method="POST">
		<input type="hidden" name="adicionarCat" value="1">
		<div class="form-group">
			<label for="unUnidade">Unidade</label>
			<input type="text" class="form-control" name="categoria" id="unUnidade" aria-describedby="helpId" placeholder="" required>
		</div>

		<div class="form-group">
			<label for="unDescricao">Descrição</label>
			<input type="text" class="form-control" name="descricao" id="unDescricao" aria-describedby="helpId" placeholder="" required>
		</div>
		<input type="submit" value="Gravar" class="btn btn-success"> <input type="button" value="Cancelar" class="btn btn-danger" onclick="fechaCadCategoria()">
	</form>
</div>

<div id="addConv">
	<h3>Adicionar Conversões</h3>

</div>

<div id="gridCat"></div>
<hr>
<h3>Conversões Universais</h3>
<div id="gridConvUniversal"></div>

<hr>
<h3>Conversões Específicas</h3>
<div id="gridConvEspecifica"></div>

<div id="inativaUnidade" style="display:none">
	<span class="tt_pg">Desativar Unidade</span><br>
	<b>Unidade:</b><span id='nCat'></span><br>
	<span class="red">Atenção!<br> Desativando uma unidade, torna impossível o uso. Informamos também que após desativar não será possível reativar.<br> Apenas continue se tiver certeza desta decisão.</span><br><br>
	<form action="#" method="post">
		<input type="hidden" name="desativa" value="1">
		<input type="hidden" name="idCat" id="idCat" value="">
		<br>
		<input type="checkbox" name="confirma" required value="1" id="ckconfirma"><label for="ckconfirma">Estou ciente que não poderei mais utilizar este centro de custo, e também não poderei reativá-lo futuramente.</label><br>
		<input type="submit" value="Inativar" class="btn btn-success"> <input type="button" value="Cancelar" class="btn btn-warning" onclick="fechaDesativaCentro()">
	</form>
</div>

<script language="javascript" type="text/javascript">
	/* GRID unidadeS */
	$(document).ready(function() {
		var dataSource = new kendo.data.DataSource({
			data: [
				<?php
				$cl1 = $pdo->query("select c.id, c.unidade, c.descricao, c.st, date_format(c.inclusao,'%d/%m/%Y %H:%i') as 'inclusao' from est_unidades c order by c.unidade");
				$od = 0;
				while ($cli = $cl1->fetch()) {
					$od++; //define ordem
					$unid = "<a href=\'#\' onclick=\'filtraUnidade(\"$cli[unidade]\")\'>" . strtoupper($cli['unidade']) . "</a>";

					$centro = strtoupper($cli['unidade']);

					$lk = "";
					if ($cli['st'] == 0) {
						$st = "Inativo";
						$lk = "";
					}
					if ($cli['st'] == 1) {
						$st = "Ativo";
						$lk = "<i class=\'fa fa-circle-xmark fa-lg\' title=\'Desativar Categoria de Centro de Custo\' onclick=\'mostraDesativaCentro($cli[id],\"$centro\")\'></i>";
					}

					$id = $cli['id'];

					echo "{
		ID: '$id',
		Unidade: '$unid',
		Descricao: '$cli[descricao]',
		DtInclusao:'$cli[inclusao]',
		UltMovimentacao:'$uMv[mov]',
		Status: '$st',
		Lk: '$lk'
		},";
					$od++;
				}

				?>

			]
		}); //fim dos dados dataSource

		//ver: https://docs.telerik.com/kendo-ui/knowledge-base/auto-resize-grid-when-hiding-and-showing-columns
		$("#gridCat").kendoGrid({
			toolbar: ["incluir", "pdf", "excel"],
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
				fileName: "unidades.pdf",
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
				fileName: "unidades.xlsx",
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
			schema: {
				model: {
					fields: {}
				}
			},
			columns: [{
					field: "ID",
					title: "ID",
					hidden: true,
					type: "number",
				},
				{
					field: "Unidade",
					title: "Unidade",
					width: 150,
					template: "#=Unidade#"
				},
				{
					field: "Descricao",
					title: "Descrição",
					width: 200
				},
				{
					field: "DtInclusao",
					title: "Data Inclusao",
					//template: "#=Cor#",
					//	groupable: false,
					//	sortable: false,
					width: 150
				},
				{
					field: "UltMovimentacao",
					title: "Última Movimentação",
					width: 150
				},
				{
					field: "Status",
					title: "Status",
					width: 150
				},
				{
					field: "Lk",
					title: " ",
					width: 50,
					template: "#=Lk#",
					groupable: false,
					sortable: false,
					filterable: false,
					exportable: {
						pdf: false,
						excel: false
					}
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

		var gridCat = $("#gridCat").data("kendoGrid");
		/*$("#sort").click(function() {
			grid.dataSource.sort(grid.dataSource.options.sort);
		});*/

		$("#save").click(function(e) {
			salvar();
		});

		function salvar() {
			//         e.preventDefault();
			//var xpt = kendo.stringify(grid.getOptions());
			var options = gridCat.getOptions();
			var xpt = kendo.stringify(options);
			//						document.getElementById("stateNovo").value = xpt;
			localStorage["kendo-grid-options-listaUnidade"] = xpt;
			//  alert(xpt);

			//abaixo, g: é o id da grid na base de dados, u é id do usuário e s: o stete novo
			var u = "<?php echo $cod_us; ?>";
			$.ajax({
				method: "POST",
				url: "atualizaGrid.php",
				data: {
					g: "22",
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
			var options = localStorage["kendo-grid-options-listaUnidade"];
			if (options) {
				var parsedOptions = JSON.parse(options);
				gridCat.setOptions({
					columns: parsedOptions.columns,
					group: parsedOptions.group
				});

				//gridCat.refresh();
				gridCat.dataSource.fetch();
				//  $("#grid").data("kendoGrid").dataSource.read();                  
			}
		}
		//DEPOIS QUE RECARREGA OS DADOS, AÍ SIM PODE SER CHAMADO A FUNÇÃO QUE IRÁ TER AÇÃO QUANDO CLICAR NA LINHA...
		/*	$(grid.tbody).on("click", "td", function (e) { 
				var row = $(this).closest("tr");
				var curRowIdx = $("tr", grid.tbody).index(row);
				var colIdx = $("td", row).index(this);
				var item = grid.dataItem(row); 
				var id = item.ID;
				//json para retornar a observação...
		//			mostraObs(id);
		//	alert(id);
				mostraMascara();
			    paga(id);
			});*/
		// $("#grid").data("kendoGrid").dataSource.read();
		//  $("#grid").getKendoGrid().dataSource.read();

		dataSource.read();
	});

	//conversões universais
	conversoesUniversais();

	function conversoesUniversais(x) {
		$(document).ready(function() {

			var dataSource = new kendo.data.DataSource({
				transport: {
					read: function(options) {
						// make JSONP request to https://demos.telerik.com/kendo-ui/service/products
						$.ajax({
							url: "estoque/dadosUnidades.php",
							dataType: "json", // "jsonp" is required for cross-domain requests; use "json" for same-domain requests
							data: {
								a: 'universal',
								x: x
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
			$("#gridConvUniversal").kendoGrid({
				toolbar: ["incluir", "pdf", "excel", "limpar"],
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
					fileName: "conversoesUniversais.pdf",
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
					fileName: "conversoesUniversais.xlsx",
					filterable: true
				},

				autoBind: false,
				height: "350px",
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
						type: "number",
						width: 90,
					},
					{
						field: "Acao",
						title: " ",
						width: 90,
						template: "#=Acao#",
						exportable: {
							pdf: false,
							excel: false
						}
					},
					{
						field: "De",
						title: "De",
						width: 80,
					},
					{
						field: "Para",
						title: "Para",
						width: 80,
					},
					{
						field: "Fator",
						title: "Fator",
						width: 80,
					},
					{
						field: "Referencia",
						title: "Referência",
						width: 80,
						template: "#=Referencia#",
					},
					{
						field: "Usuario",
						title: "Usuário",
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

			var grid = $("#gridConvUniversal").data("kendoGrid");
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
				localStorage["kendo-grid-options-conversoesUniversais"] = xpt;
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
				var options = localStorage["kendo-grid-options-conversoesUniversais"];
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

			    //alert(id);

			    //var venc = new Date(item.Vencimento);
			    var venc = item.Vencimento;
			    //                var venc = venc.getDate() + "/" + (venc.getMonth() + 1) + "/" + venc.getFullYear();

			    //                if (item.ID) {
			    //alert(item.Vencimento);
			    $("#idCanc").val(item.ID);
			    $(".descAcaoPg").html(item.Descricao);
			    $(".valorAcaoPg").html(item.Valor);
			    $(".vencAcaoPg").html(venc);
			    $(".catAcaoPg").html(item.Categoria);
			    $(".subCatAcaoPg").html(item.Subcategoria);
			    $(".centroAcaoPg").html(item.CentroCusto);

			    $("#mascara").fadeIn();
			    $("#acoesPg").fadeIn("slow");
			    //              }
			    //		    paga(id);
			});*/
			// $("#grid").data("kendoGrid").dataSource.read();
			//  $("#grid").getKendoGrid().dataSource.read();

			dataSource.read();
		});
	}

	conversoesEspecificas();

	function conversoesEspecificas(x) {
		$(document).ready(function() {

			var dataSource = new kendo.data.DataSource({
				transport: {
					read: function(options) {
						// make JSONP request to https://demos.telerik.com/kendo-ui/service/products
						$.ajax({
							url: "estoque/dadosUnidades.php",
							dataType: "json", // "jsonp" is required for cross-domain requests; use "json" for same-domain requests
							data: {
								a: 'especifica',
								x: x
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
			$("#gridConvEspecifica").kendoGrid({
				toolbar: ["incluir", "pdf", "excel", "limpar"],
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
					fileName: "conversoesEspecifica.pdf",
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
					fileName: "conversoesEspecifica.xlsx",
					filterable: true
				},

				autoBind: false,
				height: "350px",
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
						type: "number",
						width: 90,
					},
					{
						field: "Cod",
						title: "Cód",
						width: 80,
					},
					{
						field: "Descricao",
						title: "Descrição",
						width: 150,
					},
					{
						field: "De",
						title: "De",
						width: 80,
					},
					{
						field: "Para",
						title: "Para",
						width: 80,
					},
					{
						field: "Fator",
						title: "Fator",
						width: 80,
					},
					{
						field: "Referencia",
						title: "Referência",
						width: 80,
						template: "#=Referencia#",
					},
					{
						field: "Usuario",
						title: "Usuário",
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

			var grid = $("#gridConvEspecifica").data("kendoGrid");
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
				localStorage["kendo-grid-options-conversoesEspecifica"] = xpt;
				//  alert(xpt);

				//abaixo, g: é o id da grid na base de dados, u é id do usuário e s: o stete novo
				var u = "<?php echo $cod_us; ?>";
				$.ajax({
					method: "POST",
					url: "atualizaGrid.php",
					data: {
						g: "25",
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
				var options = localStorage["kendo-grid-options-conversoesEspecifica"];
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

			    //alert(id);

			    //var venc = new Date(item.Vencimento);
			    var venc = item.Vencimento;
			    //                var venc = venc.getDate() + "/" + (venc.getMonth() + 1) + "/" + venc.getFullYear();

			    //                if (item.ID) {
			    //alert(item.Vencimento);
			    $("#idCanc").val(item.ID);
			    $(".descAcaoPg").html(item.Descricao);
			    $(".valorAcaoPg").html(item.Valor);
			    $(".vencAcaoPg").html(venc);
			    $(".catAcaoPg").html(item.Categoria);
			    $(".subCatAcaoPg").html(item.Subcategoria);
			    $(".centroAcaoPg").html(item.CentroCusto);

			    $("#mascara").fadeIn();
			    $("#acoesPg").fadeIn("slow");
			    //              }
			    //		    paga(id);
			});*/
			// $("#grid").data("kendoGrid").dataSource.read();
			//  $("#grid").getKendoGrid().dataSource.read();

			dataSource.read();
		});
	}

	function filtraUnidade(x) {
		conversoesUniversais(x);
		conversoesEspecificas(x);
	}

	function limparFiltro() {
		conversoesUniversais();
		conversoesEspecificas();
	}

	setTimeout(atualizaBotao, 500);

	function atualizaBotao() {
		$("#gridCat .k-grid-incluir").html("+ Nova Unidade");
		$("#gridCat .k-grid-incluir").attr("onclick", "mostraCadCategoria()");

		$("#gridConvUniversal .k-grid-incluir").html("+ Nova Conversão");
		$("#gridConvUniversal .k-grid-incluir").attr("onclick", "mostraAddItem()");

		$("#gridConvEspecifica .k-grid-incluir").html("+ Nova Conversão");
		$("#gridConvEspecifica .k-grid-incluir").attr("onclick", "mostraAddItem()");

		$(".k-grid-limpar").html("Limpar Filtro");
		$(".k-grid-limpar").attr("onclick", "limparFiltro()");
	}

	function mostraDesativaCat(id, categoria) {
		//pegar dados e inserir nos campos inputs para atualizar... receber id do cliente selecionado
		//	mostraMascara();
		$("#mascara").fadeIn("slow");
		document.getElementById("inativaCategoria").style.display = "block";
		document.getElementById("nCatCat").innerHTML = categoria;
		document.getElementById("idCatCat").value = id;
	}

	function fechaDesativaCat() {
		$("#mascara").fadeOut("slow");
		//escondeMascara();
		document.getElementById("inativaCategoria").style.display = "none";
	}

	function mostraDesativaCentro(id, categoria) {
		//pegar dados e inserir nos campos inputs para atualizar... receber id do cliente selecionado
		//	mostraMascara();
		$("#mascara").fadeIn("slow");
		document.getElementById("inativaUnidade").style.display = "block";
		document.getElementById("nCat").innerHTML = categoria;
		document.getElementById("idCat").value = id;
	}

	function fechaDesativaCentro() {
		$("#mascara").fadeOut("slow");
		//escondeMascara();
		document.getElementById("inativaUnidade").style.display = "none";
	}

	function mostraCadCategoria() {
		$("#mascara").fadeIn("slow");
		$("#addCategoria").fadeIn("slow");
	}

	function fechaCadCategoria() {
		$("#mascara").fadeOut("slow");
		$("#addCategoria").fadeOut("slow");
	}

	function mostraCadCentro() {
		$("#mascara").fadeIn("slow");
		$("#addCentro").fadeIn("slow");
	}

	function fechaCadCentro() {
		$("#mascara").fadeOut("slow");
		$("#addCentro").fadeOut("slow");
	}
</script>