<div id="topo">
<?php
date_default_timezone_set("America/Sao_Paulo");
$dt_hj = date("d/m/Y H:i:s");
### SAUDAÇÃO ###

$hs = date('H');

if($hs > 0 && $hs <= 12){
	$sau = "Bom dia";
}
else if($hs > 12 && $hs <= 18){
	$sau = "Boa tarde";
}
else{
	$sau = "Boa noite";
}

?>
<div id="impresso"><?php echo "Impresso em $dt_hj por $us_nome";?></div>
<div class="banner">
<div class="logo"><a href="index.php?pg=inicio.php"><img src="arquivos/imagens/logo-login.png" border="none" />
</a></div>
<div id="mens">
<?php
echo "$sau <b>$nome_</b>";
?>
</div>
<?php
 if($cont > 0){   
?><div id="menu" class="menu"><?php include 'menu.php'?></div>
<div id="menuico" onclick="mostraMenu()"><img src="arquivos/icones/menu.png" height="25px" width="25px"></div>
<div id="menu320" class="menu320" style="display:none;"><?php include 'menu.php'?></div>
<?php }?>
</div>
</div>
<script>
function mostraMenu(){
	if(document.getElementById("menu320").style.display == "none"){
		document.getElementById("menu320").style.display = "block";
	}
	else if(document.getElementById("menu320").style.display == "block"){
		document.getElementById("menu320").style.display = "none";
	}
}
</script>
