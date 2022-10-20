<span class="tt_pg"><b>Grupos - Estoque</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#22/06/2016{
	-Desenvolvido;
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
############# CARREGAMENTO DE GRID ####################

$idsGrid = "19, 20"; //se houver mais de uma, separar por vírgula.

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
	//classe para cadastrar grupo
	$sql = "insert into est_grupo (grupo, st, us, inclusao) values(upper('$_POST[grupo]'),'1','$cod_us',now())";
	//	echo $sql;
	//verificar existencias
	$ve = $pdo->query("select * from est_grupo where grupo = upper('$_POST[grupo]')")->rowCount();
	if (!$ve) {
		$rc = new regDados($pdo, $sql);
		$r = $rc->registra();
		echo $r;
	}
	//	mysql_query("insert into tb_centrocusto (centro, st, us, inclusao) values(upper('$_POST[centro]'),'1','$cod_us',now())")or die(mysql_error());
}

$adicionar = $_POST['adicionar'];
//echo "$cod_us";
if ($adicionar) {
	//classe para cadastrar centro de custo
	$sql = "insert into est_subgrupo (grupo, subgrupo, st, us, inclusao) values('$_POST[grupo]',upper('$_POST[centro]'),'1','$cod_us',now())";
	$pdo->query($sql);
}

$desativa = $_POST['desativa'];

if ($desativa) {
	//se desativar...

	// inativar centro de custo...
	$sql = "update tb_centrocusto set st = 0 where id = '$_POST[idCat]'";
	//	echo $sql;
	$rc = new regDados($pdo, $sql);
	$r = $rc->registra();
	echo $r;

	//mysql_query("update tb_centrocusto set st = 0 where id = '$_POST[idCat]'") or die(mysql_error());
	//inativar as subgrupos...
	//mysql_query("update tb_subCatFin set st = 0 where grupo = '$_POST[idCat]'") or die(mysql_error());

	echo "<script>
document.getElementById('alertaTxt').innerHTML = 'Centro de Custo inativado.';//limpa alerta
mostraAlerta();
</script>";
}

$desativaCat = $_POST['desativaCat'];

if ($desativaCat) {
	//se desativar...

	// inativar centro de custo...
	$sql = "update est_grupo set st = 0 where id = '$_POST[idCatCat]'";
	//	echo $sql;
	$rc = new regDados($pdo, $sql);
	$r = $rc->registra();
	echo $r;

	//listar todos centros de custo vinculados e desativar
	$lc = $pdo->query("select * from est_subgrupo where grupo = '$_POST[idCatCat]'");
	while ($l = $lc->fetch()) {
		$sql = "update est_subgrupo set st = 0 where id = '$l[id]'";
		//	echo $sql;
		$rc = new regDados($pdo, $sql);
		$r = $rc->registra();
	}

	//mysql_query("update tb_centrocusto set st = 0 where id = '$_POST[idCat]'") or die(mysql_error());
	//inativar as subgrupos...
	//mysql_query("update tb_subCatFin set st = 0 where grupo = '$_POST[idCat]'") or die(mysql_error());

	echo "<script>
document.getElementById('alertaTxt').innerHTML = 'Centro de Custo inativado.';//limpa alerta
mostraAlerta();
</script>";
}
?>

<div id="addgrupo" style="display:none;">
	<span class="tt_pg">Cadastra grupo</span><br><br>
	<form action="#" method="POST">
		<input type="hidden" name="adicionarCat" value="1">
		<input type="text" name="grupo" class="form-control" required><br>
		<input type="submit" value="Gravar" class="btn btn-success"> <input type="button" value="Cancelar" class="btn btn-danger" onclick="fechaCadgrupo()">
	</form>
</div>

<div id="addCentro" style="display:none;">
	<span class="tt_pg">Cadastra Subgrupo</span><br><br>
	<form action="#" method="POST">
		<input type="hidden" name="adicionar" value="1">
		<b>grupo:</b><select name="grupo" class="form-control" required>
			<option value="">Selecione</option>
			<?php
			$lc = $pdo->query("select * from est_grupo where st = 1 order by grupo");
			while ($l = $lc->fetch()) {
				echo "<option value='$l[id]'>$l[grupo]</option>";
			}
			?>
		</select><br>
		<b>Subgrupo:</b><input type="text" name="centro" class="form-control" style="width:100%;" required><br>
		<input type="submit" value="Gravar" class="btn btn-success"> <input type="button" value="Cancelar" class="btn btn-danger" onclick="fechaCadCentro()">
	</form>
</div>
<input type="button" value="+Novo grupo" class="btn btn-primary" onclick="mostraCadgrupo()">
<div id="gridCat"></div>
<hr>
<input type="button" value="+Novo Subgrupo" class="btn btn-primary" onclick="mostraCadCentro()">

<div id="grid"></div>
<br><br><br>

<div id="inativaConta" style="display:none">
	<span class="tt_pg">Desativar Subgrupo</span><br>
	<b>Centro de Custo:</b><span id='nCat'></span><br>
	<span class="red">Atenção!<br> Desativando uma grupo, torna impossível o uso. Informamos também que após desativar não será possível reativar.<br> Apenas continue se tiver certeza desta decisão.</span><br><br>
	<form action="#" method="post">
		<input type="hidden" name="desativa" value="1">
		<input type="hidden" name="idCat" id="idCat" value="">
		<br>
		<input type="checkbox" name="confirma" required value="1" id="ckconfirma"><label for="ckconfirma">Estou ciente que não poderei mais utilizar este centro de custo, e também não poderei reativá-lo futuramente.</label><br>
		<input type="submit" value="Inativar" class="btn btn-success"> <input type="button" value="Cancelar" class="btn btn-warning" onclick="fechaDesativaCentro()">
	</form>
</div>

<div id="inativagrupo" style="display:none">
	<span class="tt_pg">Desativar grupo</span><br>
	<b>grupo:</b><span id='nCatCat'></span><br>
	<span class="red">Atenção!<br> Desativando uma grupo, torna impossível o uso. Informamos também que após desativar não será possível reativar e todos os centros de custo vinculados também serão desativados.<br> Apenas continue se tiver certeza desta decisão.</span><br><br>
	<form action="#" method="post">
		<input type="hidden" name="desativaCat" value="1">
		<input type="hidden" name="idCatCat" id="idCatCat" value="">
		<br>
		<input type="checkbox" name="confirma" required value="1" id="ckconfirmaCat"><label for="ckconfirmaCat">Estou ciente que não poderei mais utilizar esta grupo, e também não poderei reativá-la futuramente.</label><br>
		<input type="submit" value="Inativar" class="btn btn-success"> <input type="button" value="Cancelar" class="btn btn-warning" onclick="fechaDesativaCat()">
	</form>
</div>
<script language="javascript" type="text/javascript">
	/* GRID CENTRO DE CUSTO */
	$(document).ready(function() {
		var dataSource = new kendo.data.DataSource({
			data: [
				<?php
				$cl1 = $pdo->query("select grupo.grupo, c.id, c.subgrupo, c.st, date_format(c.inclusao,'%d/%m/%Y %H:%i') as 'inclusao' from est_subgrupo c
				inner join est_grupo grupo on c.grupo = grupo.id 
				order by grupo.grupo, c.subgrupo");
				$od = 0;
				while ($cli = $cl1->fetch()) {
					$od++; //define ordem
					$grupo = strtoupper($cli['grupo']);
					$centro = strtoupper($cli['subgrupo']);
					//ver a última data...

					/* 
REGRA ABAIXO APENAS SERÁ ÚTIL COM A UTILIZAÇÃO DO FINANCEIRO 
Manter comentado a query enquanto não for utilizado.
*/
					//$uMv = $pdo->query("select date_format(data,'%d/%m/%Y %H:%i') as 'mov' from financeiro where centro = '$cli[id]' order by id desc limit 1")->fetch();
					//calcular saldo... (considera somente concluídos) - 2
					//todas entradas, subtratindo saídas...

					$saldo = number_format($saldo['saldo'] ?? 0, '2', '.');

					$lk = "";
					if ($cli['st'] == 0) {
						$st = "Inativo";
						$lk = "";
					}
					if ($cli['st'] == 1) {
						$st = "Ativo";
						$lk = "<i class=\'fa fa-circle-xmark fa-lg\' title=\'Desativar Centro de Custo\' onclick=\'mostraDesativaCentro($cli[id],\"$centro\")\'></i>";
					}

					$id = $cli['id'];

					echo "{
		ID: '$id',
		Grupo: '$grupo',
		Subgrupo: '$centro',
		DtInclusao:'$cli[inclusao]',
		Status: '$st',
		Lk: '$lk'
		},";
					$od++;
				}

				?>

			]
		}); //fim dos dados dataSource

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
				fileName: "grupoestoque.pdf",
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
				fileName: "grupoestoque.xlsx",
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
					field: "Grupo",
					title: "Grupo",
					width: 200
				},
				{
					field: "Subgrupo",
					title: "Subgrupo",
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
			localStorage["kendo-grid-options-listaGrp"] = xpt;
			//  alert(xpt);

			//abaixo, g: é o id da grid na base de dados, u é id do usuário e s: o stete novo
			var u = "<?php echo $cod_us; ?>";
			$.ajax({
				method: "POST",
				url: "atualizaGrid.php",
				data: {
					g: "19",
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
			var options = localStorage["kendo-grid-options-listaGrp"];
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

	/* GRID grupoS */
	$(document).ready(function() {
		var dataSource = new kendo.data.DataSource({
			data: [
				<?php
				$cl1 = $pdo->query("select c.id, c.grupo, c.st, date_format(c.inclusao,'%d/%m/%Y %H:%i') as 'inclusao' from est_grupo c order by c.grupo");
				$od = 0;
				while ($cli = $cl1->fetch()) {
					$od++; //define ordem
					$centro = strtoupper($cli['grupo']);
					//ver a última data...

					/* 
REGRA ABAIXO APENAS SERÁ ÚTIL COM A UTILIZAÇÃO DO FINANCEIRO 
Manter comentado a query enquanto não for utilizado.
*/
					//$uMv = $pdo->query("select date_format(data,'%d/%m/%Y %H:%i') as 'mov' from financeiro where centro = '$cli[id]' order by id desc limit 1")->fetch();
					//calcular saldo... (considera somente concluídos) - 2
					//todas entradas, subtratindo saídas...

					$saldo = number_format($saldo['saldo'] ?? 0, '2', '.', '');

					$lk = "";
					if ($cli['st'] == 0) {
						$st = "Inativo";
						$lk = "";
					}
					if ($cli['st'] == 1) {
						$st = "Ativo";
						$lk = "<i class=\'fa fa-circle-xmark fa-lg\' title=\'Desativar grupo de Centro de Custo\' onclick=\'mostraDesativaCat($cli[id],\"$centro\")\'></i>";
					}

					$id = $cli['id'];

					echo "{
		ID: '$id',
		Grupo: '$centro',
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
				fileName: "subgruposestoque.pdf",
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
				fileName: "subgruposestoque.xlsx",
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
					field: "Grupo",
					title: "Grupo",
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
			localStorage["kendo-grid-options-listaSubGrp"] = xpt;
			//  alert(xpt);

			//abaixo, g: é o id da grid na base de dados, u é id do usuário e s: o stete novo
			var u = "<?php echo $cod_us; ?>";
			$.ajax({
				method: "POST",
				url: "atualizaGrid.php",
				data: {
					g: "20",
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
			var options = localStorage["kendo-grid-options-listaSubGrp"];
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


	function mostraDesativaCat(id, grupo) {
		//pegar dados e inserir nos campos inputs para atualizar... receber id do cliente selecionado
		//	mostraMascara();
		$("#mascara").fadeIn("slow");
		document.getElementById("inativagrupo").style.display = "block";
		document.getElementById("nCatCat").innerHTML = grupo;
		document.getElementById("idCatCat").value = id;
	}

	function fechaDesativaCat() {
		$("#mascara").fadeOut("slow");
		//escondeMascara();
		document.getElementById("inativagrupo").style.display = "none";
	}

	function mostraDesativaCentro(id, grupo) {
		//pegar dados e inserir nos campos inputs para atualizar... receber id do cliente selecionado
		//	mostraMascara();
		$("#mascara").fadeIn("slow");
		document.getElementById("inativaConta").style.display = "block";
		document.getElementById("nCat").innerHTML = grupo;
		document.getElementById("idCat").value = id;
	}

	function fechaDesativaCentro() {
		$("#mascara").fadeOut("slow");
		//escondeMascara();
		document.getElementById("inativaConta").style.display = "none";
	}

	function mostraCadgrupo() {
		$("#mascara").fadeIn("slow");
		$("#addgrupo").fadeIn("slow");
	}

	function fechaCadgrupo() {
		$("#mascara").fadeOut("slow");
		$("#addgrupo").fadeOut("slow");
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