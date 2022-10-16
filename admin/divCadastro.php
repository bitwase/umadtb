<?php
//página para mostrar em divs os módulos de registro financeiro
//registra entrada

######## RET. ANIVERSARIANTES #########
?>
<div id="div_aniversariantes" style="display:none">
<img src="../arquivos/icones/close.png" class="bt_fecha" onclick="fechaAniversario()">
﻿<span class="tt_pg"><b>Aniversariantes</b></span>
<span id="listaAniversario"></span>
<?php

?>

</div>

<script>
function nRG(){
	if(document.getElementById("npRG").checked == true){
		document.getElementById("rg").disabled = true;
	}
	if(document.getElementById("npRG").checked == false){
		document.getElementById("rg").disabled = false;
	}
}

function nRG2(){
	if(document.getElementById("npRG2").checked == true){
		document.getElementById("rg2").disabled = true;
	}
	if(document.getElementById("npRG2").checked == false){
		document.getElementById("rg2").disabled = false;
	}
}
</script>

<?php
############ INSCRIÇÃO ##############
?>
<div id="div_cadastro" style="display:none;text-align:left;">
<img src="../arquivos/icones/close.png" class="bt_fecha" onclick="fechacadastro()">
﻿<span class="tt_pg"><b>Cadastro</b></span>
<script>
/*function completo(id){
	$.ajax({
		url:"index.php?pg=mail.inscrito&id="+id,
		complete: function(response){
		},
		error: function(){
		}
	});
}*/
</script>
<?php

$salvar = $_POST[salvar];
if($salvar == 1){
	$npRG = $_REQUEST[nrg];
	$rg = $_POST[rg];
	$rg = str_replace(".","",$rg);//remove ponto
	$rg = str_replace("-","",$rg);//remove traços
	if($npRG){
		$rg = "999999999";
	}
	$cpf = $_POST[cpf];
	if($cpf == ""){
		$cpf = "999999999999";
	}
	$nome = $_POST[nome];
	$dn = $_POST[dtNasc];//01/34/6789
	if($dn == ""){ $dn="00/00/0000";}
	$dn = $dn[6].$dn[7].$dn[8].$dn[9]."-".$dn[3].$dn[4]."-".$dn[0].$dn[1];
	$cep = $_POST[cep];
	$rua = $_POST[rua];
	$num = $_POST[num];
	if($num == ""){
		$num = '0';
	}
	$bairro = $_POST[bairro];
	$cidade = $_POST[cidade];
	$uf = $_POST[uf];
	$tel1 = $_POST[fone1];
	$tel2 = $_POST[fone2];
	$email = $_POST[email];
	$setor = $_POST[setor];
	$congregacao = $_POST[congregacao];
	
//	$cod_evento = 8;
	$inscrito = $_POST[inscrito];
	//inserir dados
	if($num != ""){
		if(!$npRG){
			$vi1 = mysql_num_rows(mysql_query("select * from tb_inscritos where rg = '$rg'"));
		}
		if($vi1 == 0){
	mysql_query("insert into tb_inscritos 	(rg,cpf,nome,nascimento,cep,rua,num,bairro,cidade,uf,tel1,tel2,email,sit,setor,congregacao, dataInscricao) VALUES
	('$rg', '$cpf', '$nome','$dn', '$cep', '$rua', '$num', '$bairro', '$cidade', '$uf', '$tel1', '$tel2', '$email', '1','$setor','$congregacao', now())") or die(mysql_error());
	
//	$vdd = mysql_fetch_assoc(mysql_query("select * from tb_inscritos where rg = '$rg'"));
//	mysql_query("insert into tb_inscricao (inscrito,evento,st) values ('$vdd[id]','$cod_evento','1')") or die(mysql_error());
		}
	}
	echo "
	<script>
	alert('Cadastro realizado com sucesso');
	</script>
	";
}
?>
<form action="#" method="POST" class="formulario">
<input type="hidden" name="salvar" value="1">
<input type="hidden" name="inscrito" id="inscrito" value="">
<label class="iden"><b>RG</b></label> <input type="text" name="rg" id="rg" class="rg" onchange="verifica()" required size="12"><input type="checkbox" name="nrg" value="1" onchange="nRG()" id="npRG"><label for="npRG">Não possuo RG</label> <div class="cel"><br></div><br>
<label class="iden"><b>CPF</b></label> <input type="text" size="15" name="cpf" id="cpf" class="cpf"><br>
<label class="iden"><b>Nome</b></label> <input type="text" size="33" id="nome" name="nome" required><br>
<label class="iden"><b>Nascimento</b></label> <input type="text" size="11" name="dtNasc" id="dtNasc" class="date" ><br>
<label class="iden"><b>Cep</b></label> <input type="text" size="8" name="cep" id="cep" class="cep" onchange="pesquisacep()" ><br>
<label class="iden"><b>Rua</b></label> <input type="text" id="rua" name="rua" size="35"><div class="cel"><br></div> 
<label class="iden"><b>Nº</b></label><input type="text" name="num" id="num" size="3"><br>
<label class="iden"><b>Bairro</b></label> <input type="text" id="bairro" name="bairro" size="15"><div class="cel"><br></div><br>
<label class="iden"><b>Cidade</b></label> <input type="text" id="cidade" name="cidade" size="15">
<b>Uf</b> <input type="text" id="uf" name="uf" size="2"><br><br>
<label class="iden"><b>Setor</b></label> <select name="setor" id="setor3" required onchange="vSetor3()">
<option value="">Selecione</option>
<?php
	$lc = mysql_query("select distinct setor from tb_congregacao order by setor");
	while($l = mysql_fetch_assoc($lc)){
		echo "<option value='$l[setor]'>$l[setor]</option>";
	}
?>
<!--option value="00">Outros</option-->
</select>
<br>
<label class="iden"><b>Congregação</b></label> <span id="cmpCongregacao3"></span>
<!--input type="text" id="congregacao" name="congregacao" size="15"--><br><br>
<label class="iden"><b>Telefone </b><b>Residencial</b></label><input type="text" name="fone1" id="tel1" class="fone" size="11"><div class="cel"><br></div><br>
<label class="iden"><b>Celular</b></label><input type="text" name="fone2" id="tel2" class="fone9" size="11"><br>
<label class="iden"><b>Email</b></label><input type="text" name="email" id="email" size="30"><br><br>
<input type="submit" value="Salvar" id="salva">
<div class="cel"><br><br></div>
</form>
</div>


<?php
############ INSCRIÇÃO EVENTO ##############
?>
<div id="alertaInscricao" style="display:none;position:fixed;top:0;bottom:0;left:0;right:0;margin:auto;background:#fff;border-radius:5px;width:300px;height:250px;z-index:100002"></div>
<div id="div_cadastroEvento" style="display:none;text-align:left;">
<img src="../arquivos/icones/close.png" class="bt_fecha" onclick="fechacadastroEvento()">
﻿<span class="tt_pg"><b>Inscrições - Eventos</b></span>
<script>
/*function completo(id){
	$.ajax({
		url:"index.php?pg=mail.inscrito&id="+id,
		complete: function(response){
		},
		error: function(){
		}
	});
}*/
</script>
<?php

$salvarInscricao = $_POST[salvarInscricao];
if($salvarInscricao){
	$ev = $_POST[evento];
	$npRG = $_REQUEST[nrg];
	$rg = $_POST[rg];
	$rg = str_replace(".","",$rg);//remove ponto
	$rg = str_replace("-","",$rg);//remove traços
	if($npRG){
		$rg = "999999999";
	}
	$cpf = $_POST[cpf];
	if($cpf == ""){
		$cpf = "999999999999";
	}
	$nome = $_POST[nome];
	$dn = $_POST[dtNasc];//01/34/6789
	if($dn == ""){ $dn="00/00/0000";}
	$dn = $dn[6].$dn[7].$dn[8].$dn[9]."-".$dn[3].$dn[4]."-".$dn[0].$dn[1];
	$cep = $_POST[cep];
	$rua = $_POST[rua];
	$num = $_POST[num];
	if($num == ""){
		$num = '0';
	}
	$bairro = $_POST[bairro];
	$cidade = $_POST[cidade];
	$uf = $_POST[uf];
	$tel1 = $_POST[fone1];
	$tel2 = $_POST[fone2];
	$email = $_POST[email];
	$setor = $_POST[setor];
	$congregacao = $_POST[congregacao];
	
//	$cod_evento = 8;
	$inscrito = $_POST[inscrito];
	//inserir dados
	if($num != ""){
		if(!$npRG){
			$vi1 = mysql_num_rows(mysql_query("select * from tb_inscritos where rg = '$rg'"));
		}
		if($vi1 == 0){
	mysql_query("insert into tb_inscritos 	(rg,cpf,nome,nascimento,cep,rua,num,bairro,cidade,uf,tel1,tel2,email,sit,setor,congregacao, dataInscricao) VALUES
	('$rg', '$cpf', '$nome','$dn', '$cep', '$rua', '$num', '$bairro', '$cidade', '$uf', '$tel1', '$tel2', '$email', '1','$setor','$congregacao', now())") or die(mysql_error());
	
	$vdd = mysql_fetch_assoc(mysql_query("select max(id) as 'id' from tb_inscritos where rg = '$rg'"));
	mysql_query("insert into tb_inscricao (inscrito,evento,st,data) values ('$vdd[id]','$ev','1',now())") or die(mysql_error());
		//pegar o ID e chamar comprovante.php
		
	$mensagem = "Inscrição realizada com sucesso.";
	$idi = $vdd[id];
	$div = "$mensagem<br><br><a href=\"http://umadpguvaranas.com.br/jovens/comprovante.php?i=$idi\" target=\"_blank\"><input type=\"button\" value=\"Gerar Comprovante\"></a><br><input type=\"button\" value=\"Fechar\" onclick=\"fechaAlertaInscricao()\">";
		
		}
		
		if($vi1 > 0){
	$vdd = mysql_fetch_assoc(mysql_query("select * from tb_inscritos where rg = '$rg'"));
//verificar se já não fez a inscrição anteriormente no evento...
	$vi = mysql_num_rows(mysql_query("select * from tb_inscricao where inscrito = '$vdd[id]' and evento = '$ev'"));
	if($vi == 0){
	mysql_query("insert into tb_inscricao (inscrito,evento,st,data) values ('$vdd[id]','$ev','1',now())") or die(mysql_error());
	$uidi = mysql_fetch_assoc(mysql_query("select id from tb_inscricao where inscrito = '$vdd[id]' and evento = '$ev' order by id desc limit 1"));
	$idi = $uidi[id];
	$mensagem = "Inscrição realizada com sucesso.";
	$div = "$mensagem<br><br><a href=\"http://umadpguvaranas.com.br/jovens/comprovante.php?i=$idi\" target=\"_blank\"><input type=\"button\" value=\"Gerar Comprovante\"></a><br><input type=\"button\" value=\"Fechar\" onclick=\"fechaAlertaInscricao()\">";
	}
	if($vi > 0){
		$mensagem = "Inscrição já realizada anteriormente.";
	}
		}
	}
	echo "
	<script>
	document.getElementById('alertaInscricao').innerHTML = '$div';
	$('#alertaInscricao').fadeIn();
	mostraMascara();
	</script>
	";
}
?>
<form action="#" method="POST" class="formulario">
<input type="hidden" name="salvarInscricao" value="1">
<input type="hidden" name="inscrito" id="inscrito" value="">

<label class="iden"><b>Evento</b></label><select name="evento" id="selEv" required onchange="selEvento()">
<option value="">Selecione</option>
<?php
$le = mysql_query("select * from tb_eventos where st = 1 order by evento");
while($l = mysql_fetch_assoc($le)){
	echo "<option value='$l[id]'>$l[evento]</option>";
}
?>
</select><br><br>
<span id="camposFormulario" style="display: none;">
<label class="iden"><b>RG</b></label> <input type="text" name="rg" id="rg2" class="rg" onchange="verifica2()" required size="12">
<input type="checkbox" name="nrg" value="1" onchange="nRG2()" id="npRG2"><label for="npRG2">Não possuo RG</label><div class="cel"><br></div><br>
<label class="iden"><b>CPF</b></label> <input type="text" size="15" name="cpf" id="cpf2" class="cpf"><br>
<label class="iden"><b>Nome</b></label> <input type="text" size="33" id="nome2" name="nome" required><br>
<label class="iden"><b>Nascimento</b></label> <input type="text" size="11" name="dtNasc" id="dtNasc2" class="date" ><br>
<label class="iden"><b>Cep</b></label> <input type="text" size="8" name="cep" id="cep2" class="cep" onchange="pesquisacep2()" ><br>
<label class="iden"><b>Rua</b></label> <input type="text" id="rua2" name="rua" size="35"><div class="cel"><br></div> 
<label class="iden"><b>Nº</b></label><input type="text" name="num" id="num2" size="3"><br>
<label class="iden"><b>Bairro</b></label> <input type="text" id="bairro2" name="bairro" size="15"><div class="cel"><br></div><br>
<label class="iden"><b>Cidade</b></label> <input type="text" id="cidade2" name="cidade" size="15">
<b>Uf</b> <input type="text" id="uf2" name="uf" size="2"><br>
<label class="iden"><b>Setor</b></label> <select name="setor" id="setor2" required onchange="vSetor2()">
<option value="">Selecione</option>
<?php
	$lc = mysql_query("select distinct setor from tb_congregacao order by setor");
	while($l = mysql_fetch_assoc($lc)){
		echo "<option value='$l[setor]'>$l[setor]</option>";
	}
?>
<!--option value="00">Outros</option-->
</select>
<br>
<label class="iden"><b>Congregação</b></label> <span id="cmpCongregacao"></span> <!--input type="text" id="congregacao2" name="congregacao" size="15"--><br>
<label class="iden"><b>Telefone </b><b>Residencial</b></label><input type="text" name="fone1" id="tel12" class="fone" size="11"><div class="cel"><br></div><br>
<label class="iden"><b>Celular</b></label><input type="text" name="fone2" id="tel22" class="fone9" size="11"><br>
<label class="iden"><b>Email</b></label><input type="text" name="email" id="email2" size="30"><br><br>
<span id="seRet" style="display:none; border:solid 1px #f00; border-radius:5px; padding:4px;">
</span>
<br><input type="submit" value="Salvar" id="salva"> <span id="jainscrito" style="display:none;color:#f00;">Inscrição já realizada anteriormente.</span>
<div class="cel"><br><br></div>
</span>
</form>
</div>
<script>

//cadastros 

function fechaAlertaInscricao(){
	$("#alertaInscricao").fadeOut();
	escondeMascara();
}

function verCongregacao(){
	var regional = "";
}

function vSetor3(){
	var s = document.getElementById("setor3").value;
	if(s == "00"){
		//fazer aqui para que seja um campo de texto...
		document.getElementById("cmpCongregacao3").innerHTML = "<input type='text' id='congregacao3' name='congregacao' size='15'>";
	}
	if(s != "00"){
	//aqui para chamar os dados...
		$.ajax({
			url:'retCongregacao.php?s='+s,//url onde será atualizado
			complete: function (response){
				var ret = response.responseText;
				document.getElementById("cmpCongregacao3").innerHTML = ret;
			},
			error: function(){
			
			}
		});
	}
}

function vSetor2(){
	var s = document.getElementById("setor2").value;
	if(s == "00"){
		//fazer aqui para que seja um campo de texto...
		document.getElementById("cmpCongregacao").innerHTML = "<input type='text' id='congregacao2' name='congregacao' size='15'>";
	}
	if(s != "00"){
	//aqui para chamar os dados...
		$.ajax({
			url:'retCongregacao.php?s='+s,//url onde será atualizado
			complete: function (response){
				var ret = response.responseText;
				document.getElementById("cmpCongregacao").innerHTML = ret;
			},
			error: function(){
			
			}
		});
	}
}

function selEvento(){
	if(document.getElementById("selEv").value != ""){
		//procurar se não atingiu qt máxima de inscritos via ajax
		var ev = document.getElementById("selEv").value;
		$.ajax({
		url:'qtInscrito.php?e='+ev,//url onde será atualizado
		complete: function (response){
			var ret = response.responseText;
			var a = ret.split(":-:");
			if(a[0] == 0){
				alert("Limite de inscrições já atingida para este evento.");
				document.getElementById("camposFormulario").style.display = "none";
			}
			if(a[0] == 1){
				document.getElementById("camposFormulario").style.display = "block";
				document.getElementById("seRet").innerHTML = a[1];
				document.getElementById("seRet").style.display = "block";
			}
		},
		error: function(){
			
		}
	});
		//document.getElementById("camposFormulario").style.display = "block";
	}
	else{
		document.getElementById("camposFormulario").style.display = "none";
	}
	//se seRet5
	if(document.getElementById("selEv").value == 8){
		document.getElementById("seRet5").style.display = "block";
	}
	
}

function cadastro(){
	mostraMascara();
	document.getElementById("div_cadastro").style.display = "block";
}
function fechacadastro(){
	escondeMascara();
	document.getElementById("div_cadastro").style.display = "none";
}

function cadastroEvento(){
	mostraMascara();
	document.getElementById("div_cadastroEvento").style.display = "block";
}
function fechacadastroEvento(){
	escondeMascara();
	document.getElementById("div_cadastroEvento").style.display = "none";
}


function mostraCadInscricao(){
	mostraMascara();
	document.getElementById("divCadInscricao").style.display = "block";
}
function fechaCadInscricao(){
	escondeMascara();
	document.getElementById("divCadInscricao").style.display = "none";
	limpa();
}

function verifica(){
	/// tutsmais.com.br/blog/ajax/json-ajax-php-query
	//função que irá pegar o valor do cpf e profucar os dados para preenchimento caso exista.
		var rg1 = document.getElementById('rg').value;
        var rg = rg1.replace(/\D/g, '');
if(rg == 0 || rg == 00 || rg == 000 || rg == 0000 || rg == 00000 || rg == 000000 || rg == 0000000 || rg == 00000000 || rg == 000000000 || rg == 0000000000){
	rgInvalido();
}

if(rg == 1 || rg == 11 || rg == 111 || rg == 1111 || rg == 11111 || rg == 111111 || rg == 1111111 || rg == 11111111 || rg == 111111111 || rg == 1111111111){
	rgInvalido();
}

if(rg == 2 || rg == 22 || rg == 222 || rg == 2222 || rg == 22222 || rg == 222222 || rg == 2222222 || rg == 22222222 || rg == 222222222 || rg == 2222222222){
	rgInvalido();
}

if(rg == 3 || rg == 33 || rg == 333 || rg == 3333 || rg == 33333 || rg == 333333 || rg == 3333333 || rg == 33333333 || rg == 333333333 || rg == 3333333333){
	rgInvalido();
}

if(rg == 4 || rg == 44 || rg == 444 || rg == 4444 || rg == 44444 || rg == 444444 || rg == 4444444 || rg == 44444444 || rg == 444444444 || rg == 4444444444){
	rgInvalido();
}

if(rg == 5 || rg == 55 || rg == 555 || rg == 5555 || rg == 55555 || rg == 555555 || rg == 5555555 || rg == 55555555 || rg == 555555555 || rg == 5555555555){
	rgInvalido();
}

if(rg == 6 || rg == 66 || rg == 666 || rg == 6666 || rg == 66666 || rg == 666666 || rg == 6666666 || rg == 66666666 || rg == 666666666 || rg == 6666666666){
	rgInvalido();
}

if(rg == 7 || rg == 77 || rg == 777 || rg == 7777 || rg == 77777 || rg == 777777 || rg == 7777777 || rg == 77777777 || rg == 777777777 || rg == 7777777777){
	rgInvalido();
}

if(rg == 8 || rg == 88 || rg == 888 || rg == 8888 || rg == 88888 || rg == 888888 || rg == 8888888 || rg == 88888888 || rg == 888888888 || rg == 8888888888){
	rgInvalido();
}

if(rg == 9 || rg == 99 || rg == 999 || rg == 9999 || rg == 99999 || rg == 999999 || rg == 9999999 || rg == 99999999 || rg == 999999999 || rg == 9999999999){
	rgInvalido();
}
	
function rgInvalido(){
	alert("Por favor, queira informar um RG válido, informação necessária para este cadastro.");
	document.getElementById("rg").value = "";
	document.getElementById("rg").focus();
}	
//VERIFICAR SE EXISTE
//se existir, preenche os dados

$.getJSON('../retdadosJovens.php?rg='+rg, function(inscritoData){
	var cpf = [];
	var nome = [];
	var nascimento = [];
	var cep = [];
	var ru = [];
	var num = [];
	var bairro = [];
	var cidade = [];
	var uf = [];
	var tel1 = [];
	var tel2 = [];
	var email = [];
	var congregacao = [];
	
	$(inscritoData).each(function(key, value){
		cpf.push(value.cpf);
		nome.push(value.nome);
		nascimento.push(value.nascimento);
		cep.push(value.cep);
		ru.push(value.rua);
		num.push(value.num);
		bairro.push(value.bairro);
		cidade.push(value.cidade);
		uf.push(value.uf);
		tel1.push(value.tel1);
		tel2.push(value.tel2);
		email.push(value.email);
		congregacao.push(value.congregacao);
	});
	
	document.getElementById("cpf").value = cpf[0];
	document.getElementById("nome").value = nome[0];
	document.getElementById("dtNasc").value = nascimento[0];
	document.getElementById("cep").value = cep[0];
	document.getElementById("rua").value = ru[0];
	document.getElementById("num").value = num[0];
	document.getElementById("bairro").value = bairro[0];
	document.getElementById("cidade").value = cidade[0];
	document.getElementById("uf").value = uf[0];
	document.getElementById("tel1").value = tel1[0];
	document.getElementById("tel2").value = tel2[0];
	document.getElementById("email").value = email[0];
	document.getElementById("congregacao").value = congregacao[0];
	//document.getElementById("inscrito").value = 1;
	
	//desabilita todos os campos
//	document.getElementById("rg").disabled = "true";
	document.getElementById("cpf").disabled = "true";
	document.getElementById("nome").disabled = "true";
	document.getElementById("dtNasc").disabled = "true";
	document.getElementById("cep").disabled = "true";
	document.getElementById("rua").disabled = "true";
	document.getElementById("num").disabled = "true";
	document.getElementById("bairro").disabled = "true";
	document.getElementById("cidade").disabled = "true";
	document.getElementById("uf").disabled = "true";
	document.getElementById("tel1").disabled = "true";
	document.getElementById("tel2").disabled = "true";
	document.getElementById("email").disabled = "true";
	document.getElementById("congregacao").disabled = "true";
	//document.getElementById("salva").style.display = "none";
	//alert("Documento já cadastrado. Se necessário alterar alguma informação, deverá realizar login com os dados enviados ao email cadastrado.");
});
			//vCidade();//permitir apenas telemaco


}

function verifica2(){
	/// tutsmais.com.br/blog/ajax/json-ajax-php-query
	//função que irá pegar o valor do cpf e profucar os dados para preenchimento caso exista.
		var rg1 = document.getElementById('rg2').value;
        var rg = rg1.replace(/\D/g, '');
if(rg == 0 || rg == 00 || rg == 000 || rg == 0000 || rg == 00000 || rg == 000000 || rg == 0000000 || rg == 00000000 || rg == 000000000 || rg == 0000000000){
	rgInvalido2();
}

if(rg == 1 || rg == 11 || rg == 111 || rg == 1111 || rg == 11111 || rg == 111111 || rg == 1111111 || rg == 11111111 || rg == 111111111 || rg == 1111111111){
	rgInvalido2();
}

if(rg == 2 || rg == 22 || rg == 222 || rg == 2222 || rg == 22222 || rg == 222222 || rg == 2222222 || rg == 22222222 || rg == 222222222 || rg == 2222222222){
	rgInvalido2();
}

if(rg == 3 || rg == 33 || rg == 333 || rg == 3333 || rg == 33333 || rg == 333333 || rg == 3333333 || rg == 33333333 || rg == 333333333 || rg == 3333333333){
	rgInvalido2();
}

if(rg == 4 || rg == 44 || rg == 444 || rg == 4444 || rg == 44444 || rg == 444444 || rg == 4444444 || rg == 44444444 || rg == 444444444 || rg == 4444444444){
	rgInvalido2();
}

if(rg == 5 || rg == 55 || rg == 555 || rg == 5555 || rg == 55555 || rg == 555555 || rg == 5555555 || rg == 55555555 || rg == 555555555 || rg == 5555555555){
	rgInvalido2();
}

if(rg == 6 || rg == 66 || rg == 666 || rg == 6666 || rg == 66666 || rg == 666666 || rg == 6666666 || rg == 66666666 || rg == 666666666 || rg == 6666666666){
	rgInvalido2();
}

if(rg == 7 || rg == 77 || rg == 777 || rg == 7777 || rg == 77777 || rg == 777777 || rg == 7777777 || rg == 77777777 || rg == 777777777 || rg == 7777777777){
	rgInvalido2();
}

if(rg == 8 || rg == 88 || rg == 888 || rg == 8888 || rg == 88888 || rg == 888888 || rg == 8888888 || rg == 88888888 || rg == 888888888 || rg == 8888888888){
	rgInvalido2();
}

if(rg == 9 || rg == 99 || rg == 999 || rg == 9999 || rg == 99999 || rg == 999999 || rg == 9999999 || rg == 99999999 || rg == 999999999 || rg == 9999999999){
	rgInvalido2();
}
	
function rgInvalido2(){
	alert("Por favor, queira informar um RG válido, informação necessária para este cadastro.");
	document.getElementById("rg2").value = "";
	document.getElementById("rg2").focus();
}	
//VERIFICAR SE EXISTE
//se existir, preenche os dados

$.getJSON('../retdadosJovens.php?rg='+rg, function(inscritoData){
	var cpf = [];
	var nome = [];
	var nascimento = [];
	var cep = [];
	var ru = [];
	var num = [];
	var bairro = [];
	var cidade = [];
	var uf = [];
	var tel1 = [];
	var tel2 = [];
	var email = [];
	var congregacao = [];
	
	$(inscritoData).each(function(key, value){
		cpf.push(value.cpf);
		nome.push(value.nome);
		nascimento.push(value.nascimento);
		cep.push(value.cep);
		ru.push(value.rua);
		num.push(value.num);
		bairro.push(value.bairro);
		cidade.push(value.cidade);
		uf.push(value.uf);
		tel1.push(value.tel1);
		tel2.push(value.tel2);
		email.push(value.email);
		congregacao.push(value.congregacao);
	});
	
	document.getElementById("cpf2").value = cpf[0];
	document.getElementById("nome2").value = nome[0];
	document.getElementById("dtNasc2").value = nascimento[0];
	document.getElementById("cep2").value = cep[0];
	document.getElementById("rua2").value = ru[0];
	document.getElementById("num2").value = num[0];
	document.getElementById("bairro2").value = bairro[0];
	document.getElementById("cidade2").value = cidade[0];
	document.getElementById("uf2").value = uf[0];
	document.getElementById("tel12").value = tel1[0];
	document.getElementById("tel22").value = tel2[0];
	document.getElementById("email2").value = email[0];
	document.getElementById("congregacao2").value = congregacao[0];
	//document.getElementById("inscrito").value = 1;
	
	//desabilita todos os campos
//	document.getElementById("rg").disabled = "true";
	document.getElementById("cpf2").disabled = "true";
	document.getElementById("nome2").disabled = "true";
	document.getElementById("dtNasc2").disabled = "true";
	document.getElementById("cep2").disabled = "true";
	document.getElementById("rua2").disabled = "true";
	document.getElementById("num2").disabled = "true";
	document.getElementById("bairro2").disabled = "true";
	document.getElementById("cidade2").disabled = "true";
	document.getElementById("uf2").disabled = "true";
	document.getElementById("tel12").disabled = "true";
	document.getElementById("tel22").disabled = "true";
	document.getElementById("email2").disabled = "true";
	document.getElementById("congregacao2").disabled = "true";
	//document.getElementById("salva").style.display = "none";
	//alert("Documento já cadastrado. Se necessário alterar alguma informação, deverá realizar login com os dados enviados ao email cadastrado.");
});

			//vCidade();//permitir apenas telemaco
var evento = document.getElementById("selEv").value;

$.getJSON('../retdadosCad.php?rg='+rg+'&e='+evento, function(inscritoData){
	var inscrito = [];
	$(inscritoData).each(function(key, value){
		inscrito.push(value.inscrito);
	});
	if(inscrito == 1){
		document.getElementById("jainscrito").style.display = "block";	
	}
	
	//document.getElementById("salva").style.display = "none";
	//alert("Documento já cadastrado. Se necessário alterar alguma informação, deverá realizar login com os dados enviados ao email cadastrado.");
});
}

/***************************************************************************
********** FUNÇÕES PARA PREENCHER O ENDEREÇO COM O CEP *********************
***************************************************************************/

function limpa_formulário_cep() {
          }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('rua').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('uf').value=(conteudo.uf);
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }

function pesquisacep() {

        //Nova variável "cep" somente com dígitos.
	var valor = document.getElementById('cep').value;
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('rua').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('uf').value="...";

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = '//viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    };

function meu_callback2(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('rua2').value=(conteudo.logradouro);
            document.getElementById('bairro2').value=(conteudo.bairro);
            document.getElementById('cidade2').value=(conteudo.localidade);
            document.getElementById('uf2').value=(conteudo.uf);
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }

function pesquisacep2() {

        //Nova variável "cep" somente com dígitos.
	var valor = document.getElementById('cep2').value;
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('rua2').value="...";
                document.getElementById('bairro2').value="...";
                document.getElementById('cidade2').value="...";
                document.getElementById('uf2').value="...";

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = '//viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback2';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    };

	function validaRg(){
	
	var doc = document.getElementById("rg").value;
	doc = doc.replace("-","");
	doc = doc.replace(".","");
	
	$.ajax({
		url:'vrg.php?r='+doc,//url onde será atualizado
		complete: function (response){
			var ret = response.responseText;
			if(ret == 0){
			}
			if(ret > 0){
				alert("Inscrição já realizada para este RG. Você será direcionado para alteração de dados.");
				location.href = "index.php?pg=atinscricao&rg="+doc;
			}
		},
		error: function(){
			
		}
	});
}
	
	
</script>
