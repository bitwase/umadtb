<?php
include 'seguranca.php';
//include 'config.php';
?>
<title><?php echo $config['sistema']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="arquivos/css/estilo.css?v=2.10.42" type="text/css" />

<link rel="stylesheet" href="arquivos/css/font-awesome.min.css?v=1.7" type="text/css" />
<!--script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script-->
<script src="arquivos/jquery/bootstrap.min.js"></script>
<link rel="stylesheet" href="arquivos/css/bootstrap.min.css?v=1.4" type="text/css" />

<link rel="stylesheet" href="arquivos/css/menu.css?v=1.10.20" type="text/css" />
<link rel="stylesheet" href="arquivos/css/print.css" type="text/css" media="print" />
<link rel="stylesheet" href="arquivos/css/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="arquivos/css/bs.css">

<script src="arquivos/jquery/jquery-3.6.0.js"></script>

<link rel="icon" type="image/png" href="logo.png" />

<link href="arquivos/css/select2.min.css?v=2.5" rel="stylesheet" />
<script src="arquivos/jquery/select2.min.js"></script>

<?php // time picker 
?>
<link rel="stylesheet" type="text/css" href="arquivos/css/jquery.timepicker.css">
<script type="text/javascript" src="arquivos/jquery/jquery.timepicker.js"></script>


<!--script language="JavaScript" src="arquivos/jquery/sc.js"></script--><?php //atalhos teclado
																		?>
<link rel="stylesheet" href="arquivos/css/jquery.dataTables.min.css" type="text/css" />
<script src="arquivos/jquery/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="arquivos/css/datatables.buttons.css" type="text/css" />
<script src="arquivos/jquery/datatables.button.js"></script>
<script src="arquivos/jquery/datatables.print.js"></script>
<script src="arquivos/jquery/datatables.html5.js"></script>
<script src="arquivos/jquery/datatables.pdf.js"></script>
<script src="arquivos/jquery/datatables.vfs.js"></script>
<script src="arquivos/jquery/datatables.jszip.js"></script>


<script type="text/javascript" src="arquivos/jquery/jquery.mask.min.js"></script>
<!--script type="text/javascript" src="arquivos/jquery/mask2.js"></script-->
<?php ## graficos
?>

<?php # GRID #
?>

<link href="knd/kendo.common.min.css" rel="stylesheet">
<link href="knd/kendo.rtl.min.css" rel="stylesheet">
<!--link href="knd/kendo.common-material.min.css" rel="stylesheet"-->
<link href="knd/kendo.default.min.css" rel="stylesheet">
<link href="knd/kendo.default.mobile.min.css" rel="stylesheet">
<!--script src="knd/jquery.min.js"></script-->
<script src="knd/kendo.all.min.js"></script>
<script src="knd/jszip.min.js"></script>
<script src="knd/console.js"></script>
<script src="knd/kendo.messages.pt-BR.js"></script>


<style>
	.k-grid {
		font-size: 14px;
	}

	.k-grid td {
		font-size: 14px;
		line-height: 1em !important;
	}
</style>

<link href="arquivos/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="arquivos/jquery/bootstrap-toggle.min.js"></script>

<?php
date_default_timezone_set("America/Sao_Paulo");
setlocale(LC_ALL, "pt_BR");
?>
<link rel="stylesheet" type="text/css" href="arquivos/css/loading.css">
<div id="loader">
	<div id="meioLoader">
		<img src="arquivos/imagens/logo.png" height="180px" width="333px" style="opacity:0.9" />
		<div id="fountainG">

			<div id="fountainG_1" class="fountainG"></div>
			<div id="fountainG_2" class="fountainG"></div>
			<div id="fountainG_3" class="fountainG"></div>
			<div id="fountainG_4" class="fountainG"></div>
			<div id="fountainG_5" class="fountainG"></div>
			<div id="fountainG_6" class="fountainG"></div>
			<div id="fountainG_7" class="fountainG"></div>
			<div id="fountainG_8" class="fountainG"></div>
		</div>
	</div>
</div>
<div id="mascara" style="display:none;"></div>
<div id="alerta" style="display:none;">Aguarde...</div>

<div id="alertaRealizado" class="alert alert-success alerta-cima-direita"></div>

<!--link rel="stylesheet" href="arquivos/css/css.php" type="text/css" /-->
<?php
try {
	$sqlConf = "select * from config";
	$r = $pdo->query($sqlConf)->fetch(); //pdo para config
} catch (PDOException $e) {
	return 'ERROR: ' . $e->getMessage();
}
?>
<link href="knd/custom.css" rel="stylesheet">
<style type="text/css">
	::-webkit-scrollbar-thumb,
	.banner,
	.nav-side-menu li:hover,
	.nav-side-menu .brand,
	#bodyLogin,
	.k-header.k-grid-toolbar,
	.k-grouping-header,
	#loader {
		background: <?php echo $r['cor1']; ?> !important;
	}
</style>
<script language="JavaScript">
	function carregou() {
		//alert("aqui");
		//document.getElementById("matricula").style.opacity = 0.4;
		document.getElementById("loader").style.display = 'none';
	}

	function carregando() {
		document.getElementById("loader").style.display = 'block';
	}

	function fechaAlerta() {
		$('#alerta').fadeOut('slow');
		$('#mascara').fadeOut('slow');
	}

	function fechaMascara() {
		document.getElementById("mascara").style.display = 'none';
		document.getElementById("mascara").className = 'escondemascara';
		if (typeof val == 'function') {
			val(); //função que não vai permitir fechar mascara enquanto estiver aberto o objeto
		}
	}

	function abreMascara() {
		document.getElementById("mascara").style.display = 'block';
		document.getElementById("mascara").className = 'mostramascara';
	}

	// DESABILITA BOTÃO VOLTAR //

	(function(window) {
		'use strict';

		var noback = {

			//globals 
			version: '0.0.1',
			history_api: typeof history.pushState !== 'undefined',

			init: function() {
				window.location.hash = '#no-back';
				noback.configure();
			},

			hasChanged: function() {
				if (window.location.hash == '#no-back') {
					window.location.hash = '#';
					//mostra mensagem que não pode usar o btn volta do browser
					if ($("#msgAviso").css('display') == 'none') {
						$("#msgAviso").slideToggle("slow");
					}
				}
			},

			checkCompat: function() {
				if (window.addEventListener) {
					window.addEventListener("hashchange", noback.hasChanged, false);
				} else if (window.attachEvent) {
					window.attachEvent("onhashchange", noback.hasChanged);
				} else {
					window.onhashchange = noback.hasChanged;
				}
			},

			configure: function() {
				if (window.location.hash == '#no-back') {
					if (this.history_api) {
						history.pushState(null, '', '#');
					} else {
						window.location.hash = '#';
						//mostra mensagem que não pode usar o btn volta do browser
						if ($("#msgAviso").css('display') == 'none') {
							$("#msgAviso").slideToggle("slow");
						}
					}
				}
				noback.checkCompat();
				noback.hasChanged();
			}

		};

		// AMD support 
		if (typeof define === 'function' && define.amd) {
			define(function() {
				return noback;
			});
		}
		// For CommonJS and CommonJS-like 
		else if (typeof module === 'object' && module.exports) {
			module.exports = noback;
		} else {
			window.noback = noback;
		}
		noback.init();
	}(window));

	// FIM DESABILITA BOTÃO VOLTAR
</script>