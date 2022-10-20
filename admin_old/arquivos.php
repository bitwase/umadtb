<?php
include 'seguranca.php';
include 'config.php';
error_reporting(~E_ALL);
?>
<title>Bitwase Sistemas</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="arquivos/css/estilo_pc.css?v=5.5"> <!--media="screen and (min-width: 640px)">
<link rel="stylesheet" href="arquivos/css/estilo_320.css" media="screen and (max-width: 639px)"-->

<link rel="stylesheet" href="arquivos/css/loader.css" type="text/css" />
<!--link rel="stylesheet" href="arquivos/css/menu.css" type="text/css" /-->
<link rel="stylesheet" href="arquivos/css/loader.css" type="text/css" />
<link rel="stylesheet" href="arquivos/css/print.css" type="text/css" media="print" />
<link rel="stylesheet" href="arquivos/jquery/jquery-ui.css" type="text/css" />
<script src="arquivos/jquery/jquery.min.js"></script>

<script src="arquivos/jquery/jquery-ui.js"></script>
<script src="arquivos/jquery/mask.js"></script>
<link rel="icon" type="image/png" href="arquivos/imagens/lg2.png" />
<?php ## SHADOWBOX ## ?>
<link rel="stylesheet" type="text/css" href="arquivos/shadowbox/shadowbox.css">
<script type="text/javascript" src="arquivos/shadowbox/shadowbox.js"></script>
 <!--script src="arquivos/jquery/jquery-1.10.2.js"></script-->

<script src="arquivos/jquery/jquery-ui.11.4.js"></script>
<link rel="stylesheet" href="arquivos/css/jquery.dataTables.min.css" type="text/css" />
<link rel="stylesheet" href="arquivos/css/datatables.buttons.css" type="text/css" />


<script src="arquivos/jquery/jquery.dataTables.min.js"></script>
<script src="arquivos/jquery/datatables.button.js"></script>
<script src="arquivos/jquery/datatables.print.js"></script>
<script src="arquivos/jquery/datatables.html5.js"></script>
<script src="arquivos/jquery/datatables.pdf.js"></script>
<script src="arquivos/jquery/datatables.vfs.js"></script>
<script src="arquivos/jquery/datatables.jszip.js"></script>
<!--script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script-->


<script type="text/javascript" src="arquivos/jquery/bootstrap.min.js"></script>
<script type="text/javascript" src="arquivos/jquery/jquery.mask.min.js"></script>

<script type="text/javascript" src="arquivos/jquery/mask2.js"></script>

<script type="text/javascript" src="arquivos/jquery/sc.js"></script>
<?php
date_default_timezone_set("America/Sao_Paulo");
setlocale(LC_ALL, "pt_BR");
?>
<div id="loader" style="display:none;">
	<div>
	<img src="arquivos/imagens/logo_loader.png" id="img_loader" />
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
<div id="mascara" class="esconde" onclick=""></div>

<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js?v=2.1" async=""></script>
<script>
  var OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "f6eb233f-6433-4b68-9dec-987d419be263",
      notifyButton: {
        enable: false,
      },
    });
  });
</script>
<script language="javascript">
function carregou(){
	document.getElementById("loader").style.display = 'none';
}
function carregando(){
	document.getElementById("loader").style.display = 'block';
}
function escondeMascara(){
		//document.getElementById("mascara").style.display = 'none';
		document.getElementById("mascara").className = 'esconde';
}
function mostraMascara(){
		document.getElementById("mascara").className = 'mostra';
//		document.getElementById("mascara").style.display = 'block';
}

window.onscroll = function() {myFunction()};

function myFunction() {
    if (document.body.scrollTop > 110 || document.documentElement.scrollTop > 110) {
        document.getElementById("menu").className = "menu2";
        document.getElementById("minilogo").className = "lg2";
    } 
else {
        document.getElementById("menu").className = "menu";
        document.getElementById("minilogo").className = "lg1";
    }
}
</script>
