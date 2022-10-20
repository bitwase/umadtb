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
<div id="impresso"><?php echo "Impresso em $dt_hj por $nome";?></div>
<div class="banner">
<!--div class="logo"><a href="index.php?pg=inicio.php"><img src="arquivos/imagens/logo-login.png" border="none" />
</a></div-->
<div id="mens">
<?php
//print_r($config);
echo "$sau <b>$nome</b><br>
Você está acessando <b>".$config['sistema']."</b>."
?>
</div>
<?php
 if($cont > 0){   
?>
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
