<?php
include "seguranca.php";
#include "conexao.php";
/*
Listar os menus, com os id
*/
$f = $_REQUEST['f'];

if($f == ""){
//pegar todos ativos no primeiro nível
	$lm = $pdo->query("select * from tb_menu where st = 1 and nv = 1 order by o");

	while($m = $lm->fetch()){
		//ver se possui submenu no nível 2....
		//nv = 2 ref = id do menu atual...
		$sm = $pdo->query("select * from tb_menu where st = 1 and nv = 2 and ref = '$m[id]' and (file in (select pg from tb_acessos where tipo = 'P' and us = $cod_us) or file = '#' ) order by o");
		$vqsm = $sm->rowCount();
		if($vqsm > 0){
			$sub = "<span class='arrow'></span>";
		}
		else{
			$sub = "";
		}
		echo "<li data-bs-toggle='collapse' data-value='#m$m[id]' data-bs-target='#m$m[id]' class='active collapsed acao-menu'>
			        <a href='$m[href]' $m[add] data-value='#m$m[id]'><i class='$m[ico]' title='$m[menu]'></i> <span class='conteudoMenu' style='display:inline;'>$m[menu]</span> $sub</a>
			      </li>";
		if($vqsm > 0){
			
			//se houver submenu nv 2...
			echo "<ul class='menu-intermediario collapse' id='m$m[id]' style=''>";
			
					//ver se possui submenu no nível 3....
				//nv = 3 ref = id do menu atual...
				//fazer um while aqui e verificar nv 3
			while($sml = $sm->fetch()){
				//procura 3
				$sm2 = $pdo->query("select * from tb_menu where st = 1 and nv = 3 and ref = '$sml[id]' and (file in (select pg from tb_acessos where tipo = 'P' and us = $cod_us) or file = '#' ) order by o");
				$vqsm2 = $sm2->rowCount();
				if($vqsm2 > 0){
					$sub = "<span class='arrow'></span>";
				}
				else{
					$sub = "";
				}
				
				echo "<li data-bs-toggle='collapse' data-bs-target='#m$sml[id]' class='active collapsed acao-menu'>
						   <a href='$sml[href]' $sml[add]><i class='$sml[ico]' title='$sml[menu]'></i><span class='conteudoMenu' style='display:inline;'> $sml[menu]</span> $sub</a>
						 </li>";
				if($vqsm2 > 0){
					//se houver submenu...
					echo "<ul class='sub-menu collapse' id='m$sml[id]' style=''>";
					while($sml2 = $sm2->fetch()){
						//procura 3
						$sm3 = $pdo->query("select * from tb_menu where st = 1 and nv = 4 and ref = '$sml2[id]' and (file in (select pg from tb_acessos where tipo = 'P' and us = $cod_us) or file = '#' ) order by o");
						$vqsm3 = $sm3->rowCount();
						if($vqsm3 > 0){
							$sub = "<span class='arrow'></span>";
						}
						else{
							$sub = "";
						}
						echo "<li data-bs-toggle='collapse' data-bs-target='#$sml2[id]' class='active collapsed acao-menu'>
								   <a href='$sml2[href]' $sml2[add]><i class='$sml2[ico]' title='$sml2[menu]'></i> <span class='conteudoMenu' style='display:inline;'>$sml2[menu]</span> $sub</a>
								 </li>";
						if($vqsm3 > 0){
							//se houver submenu...
							echo "<ul class='sub-menu collapse' id='m$sml2[id]' style=''>";
							while($sml3 = $sm3->fetch()){
						
						
								echo "<li><a href='$sml3[href]' $sml3[add]><span>$sml3[menu]</span></a></li>";
							}
							echo "</ul>";
						}
		
					}
					echo "</ul>";
				}
		
				//echo "<li><a href='$sml[href]' $sml[add]><span>$sml[menu]</span></a></li>";
			}//fim while submenu nv 2
			echo "</ul>";
		}//fim se houver submenu
	}//fim while
}//se for sem filtro....

if($f != ""){
	
	//identificar todos ID relacionado
	$sid = $pdo->query("select id from tb_menu where menu like '%$f%'");
	$s = "";
	while($si = $sid->fetch()){
		$s.= "$si[id],";
	}
	$s = substr_replace($s, '', -1);
	
//pegar todos ativos no primeiro nível
//listar todos que são nível 1 e esta na listar
//para isto, listar todos que são ref em nv 3 e insere na lista..
//depois, pega nestes ref, quais são os ref deles... que serão os 1
//pega os que são 1 e complementa  alista.. fechou
$auxn2 = "0,";
$auxid = "0,";
	$lt3 = $pdo->query("select ref,id from tb_menu where nv = 3 and menu like '%$f%' and (file in (select pg from tb_acessos where tipo = 'P' and us = $cod_us) or file = '#' )");
	while($l3 = $lt3->fetch()){
		$auxn2 .= "$l3[ref],";
		$auxid .= "$l3[id],";
	}
	$auxn2 = substr_replace($auxn2, '', -1);
	
	//selecionar tudo onde for iagual a nv 2 e ou estiver nos ids ou menu like
	$lt2 = $pdo->query("select ref,id from tb_menu where nv = 2 and (id in ($auxn2) or menu like '%$f%' ) and (file in (select pg from tb_acessos where tipo = 'P' and us = $cod_us) or file = '#' )");
	$auxn1 = "0,";
	while($l2 = $lt2->fetch()){
		$auxn1 .= "$l2[ref],";
		$auxid .= "$l2[id],";
	}
	$auxn1 = substr_replace($auxn1, '', -1);	
	//listar todos os ID nv1 
	
	$auxp = "0,";
	$lt1 = $pdo->query("select * from tb_menu where nv = 1 and (id in($auxn1) or menu like '%$f%') and (file in (select pg from tb_acessos where tipo = 'P' and us = $cod_us) or file = '#' )");
	while($l1 = $lt1->fetch()){
		$auxp .= "$l1[id],";
		$auxid .= "$l1[id],";
	}
	
	$auxp = substr_replace($auxp, '', -1);	
	$auxid = substr_replace($auxid, '', -1);	

	$lm = $pdo->query("select * from tb_menu where st = 1 and nv = 1 and id in($auxid) order by o");

	while($m = $lm->fetch()){
		//ver se possui submenu no nível 2....
		//nv = 2 ref = id do menu atual...
		//ver se é o principal
		$auxCon = $pdo->query("select * from tb_menu where menu like '%$f%' and id = '$m[id]' and (file in (select pg from tb_acessos where tipo = 'P' and us = $cod_us) or file = '#' )");
		//$vprin = mysql_num_rows(mysql_query("select * from tb_menu where menu like '%$f%' and id = '$m[id]'"));
		$vprin = $auxCon->rowCount();
		if($vprin > 0){//se for no principal...
			$sm = $pdo->query("select * from tb_menu where st = 1 and nv = 2 and ref = '$m[id]' and (file in (select pg from tb_acessos where tipo = 'P' and us = $cod_us) or file = '#' ) order by o");
		}
		if($vprin == 0){//se não for no principal...
			$sm = $pdo->query("select * from tb_menu where st = 1 and nv = 2 and ref = '$m[id]' and id in ($auxid) and (file in (select pg from tb_acessos where tipo = 'P' and us = $cod_us) or file = '#' ) order by o");
		}

		$vqsm = $sm->rowCount();
		if($vqsm > 0){
			$sub = "<span class='arrow'></span>";
		}
		else{
			$sub = "";
		}
		echo "<li data-bs-toggle='collapse' data-bs-target='#m$m[id]' class='active'>
			        <a href='$m[href]' $m[add]><i class='$m[ico]' title='$m[menu]'></i> <span class='conteudoMenu' style='display:inline;'>$m[menu]</span> $sub</a>
			      </li>";
		if($vqsm > 0){
			
			//se houver submenu nv 2...
			echo "<ul class='menu-intermediario collapsed collapse in' id='m$m[id]' style=''>";
			
					//ver se possui submenu no nível 3....
				//nv = 3 ref = id do menu atual...
				//fazer um while aqui e verificar nv 3
			while($sml = $sm->fetch()){
				//procura 3
				$auxCon = $pdo->query("select * from tb_menu where menu like '%$f%' and id = '$sml[id]'");
				//$vprin = mysql_num_rows(mysql_query());
				
				if($vprin > 0){
					$sm2 = $pdo->query("select * from tb_menu where st = 1 and nv = 3 and ref = '$sml[id]' order by o");
				}
				
				if($vprin == 0){
					$sm2 = $pdo->query("select * from tb_menu where st = 1 and nv = 3 and ref = '$sml[id]' and id in($auxid) order by o");
				}
				$vqsm2 = $sm2->rowCount();//mysql_num_rows($sm2);
				if($vqsm2 > 0){
					$sub = "<span class='arrow'></span>";
				}
				else{
					$sub = "";
				}
				
				echo "<li data-bs-toggle='collapse' data-bs-target='#m$sml[id]' class='active'>
						   <a href='$sml[href]' $sml[add]><i class='$sml[ico]' title='$sml[menu]'></i> <span class='conteudoMenu' style='display:inline;'>$sml[menu] </span> $sub</a>
						 </li>";
				if($vqsm2 > 0){
					//se houver submenu...
					echo "<ul class='sub-menu collapse in' id='m$sml[id]' style=''>";
					while($sml2 = $sm2->fetch()){
						//procura 3
						$sm3 = $pdo->query("select * from tb_menu where st = 1 and nv = 4 and ref = '$sml2[id]' order by o");
						$vqsm3 = $sm3->rowCount();
						if($vqsm3 > 0){
							$sub = "<span class='arrow'></span>";
						}
						else{
							$sub = "";
						}
						echo "<li data-bs-toggle='collapse' data-bs-target='#$sml2[id]' class='active collapsed collapse in'>
								   <a href='$sml2[href]' $sml2[add]><i class='$sml2[ico]' title='$sml2[menu]'></i> <span class='conteudoMenu' style='display:inline;'>$sml2[menu]</span> $sub</a>
								 </li>";
						if($vqsm3 > 0){
							//se houver submenu...
							echo "<ul class='sub-menu collapse in' id='m$sml2[id]' >";
							while($sml3 = $sm3->fetch()){
						
						
								echo "<li><a href='$sml3[href]' $sml3[add]><span>$sml3[menu]</span></a></li>";
							}
							echo "</ul>";
						}
		
					}
					echo "</ul>";
				}
		
				//echo "<li><a href='$sml[href]' $sml[add]><span>$sml[menu]</span></a></li>";
			}//fim while submenu nv 2
			echo "</ul>";
		}//fim se houver submenu
	}//fim while
}//se for sem filtro....
//fa fa-key fa-lg
?>
