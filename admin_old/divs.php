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

<?php
############ INSCRIÇÃO ##############
?>
<div id="div_cadastro" style="display:none">
<img src="../arquivos/icones/close.png" class="bt_fecha" onclick="fechacadastro()">
﻿<span class="tt_pg"><b>Cadastro</b></span>
<script>
function completo(id){
	$.ajax({
		url:"index.php?pg=mail.inscrito&id="+id,
		complete: function(response){
		},
		error: function(){
		}
	});
}
</script>
<?php

$salvar = $_POST[salvar];
if($salvar == 1){
	$rg = $_POST[rg];
	$rg = str_replace(".","",$rg);//remove ponto
	$rg = str_replace("-","",$rg);//remove traços
	$cpf = $_POST[cpf];
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
	$congregacao = $_POST[congregacao];
	
//	$cod_evento = 8;
	$inscrito = $_POST[inscrito];
	//inserir dados
	if($num != ""){
		$vi1 = mysql_num_rows(mysql_query("select * from tb_inscritos where rg = '$rg'"));
		if($vi1 == 0){
	mysql_query("insert into tb_inscritos 	(rg,cpf,nome,nascimento,cep,rua,num,bairro,cidade,uf,tel1,tel2,email,sit,congregacao, dataInscricao) VALUES
	('$rg', '$cpf', '$nome','$dn', '$cep', '$rua', '$num', '$bairro', '$cidade', '$uf', '$tel1', '$tel2', '$email', '1','$congregacao', now())") or die(mysql_error());
	
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
<label class="iden"><b>RG</b></label> <input type="text" name="rg" id="rg" class="rg" onchange="verifica()" required size="12"> <div class="cel"><br></div><br>
<label class="iden"><b>CPF</b></label> <input type="text" size="15" name="cpf" id="cpf" class="cpf"><br>
<label class="iden"><b>Nome</b></label> <input type="text" size="33" id="nome" name="nome" required><br>
<label class="iden"><b>Nascimento</b></label> <input type="text" size="11" name="dtNasc" id="dtNasc" class="date" ><br>
<label class="iden"><b>Cep</b></label> <input type="text" size="8" name="cep" id="cep" class="cep" onchange="pesquisacep()" ><br>
<label class="iden"><b>Rua</b></label> <input type="text" id="rua" name="rua" size="35"><div class="cel"><br></div> 
<label class="iden"><b>Nº</b></label><input type="text" name="num" id="num" size="3"><br>
<label class="iden"><b>Bairro</b></label> <input type="text" id="bairro" name="bairro" size="15"><div class="cel"><br></div><br>
<label class="iden"><b>Cidade</b></label> <input type="text" id="cidade" name="cidade" size="15">
<b>Uf</b> <input type="text" id="uf" name="uf" size="2"><br><br>
<label class="iden"><b>Congregação</b></label> 
<select name="congregacao" required>
<option value="">Selecione</option>
<?php
	$lc = mysql_query("select * from tb_congregacoes order by congregacao");
	while($l = mysql_fetch_assoc($lc)){
		echo "<option value='$l[congregacao]'>$l[congregacao]</option>";
	}
?>
</select><br><br>
<!--label class="iden"><b>Congregação</b></label> <input type="text" id="congregacao" name="congregacao" size="15"><br-->
<label class="iden"><b>Telefone </b><b>Residencial</b></label><input type="text" name="fone1" id="tel1" class="fone" size="11"><div class="cel"><br></div><br>
<label class="iden"><b>Celular</b></label><input type="text" name="fone2" id="tel2" class="fone9" size="11"><br>
<label class="iden"><b>Email</b></label><input type="text" name="email" id="email" size="30"><br><br>
<input type="submit" value="Salvar" id="salva">
<div class="cel"><br><br></div>
</form>
</div>

<?php
############ CADASTRO DE VISITANTES ##############
?>
<div id="div_visitantes" style="display:none">
<img src="../arquivos/icones/close.png" class="bt_fecha" onclick="fechaVisitantes()">
﻿<span class="tt_pg"><b>Cadastro de Visitantes</b></span>

<?php

$visitante = $_POST[visitante];
if($visitante){
	$nome = $_POST[nome];
	$evangelico = $_POST[ev];// 0 ou 1
	$local = $_POST[local];
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
	
	$inscrito = $_POST[inscrito];
	//inserir dados
	if($num != ""){
	mysql_query("insert into tb_visitantes 	(nome,evangelico,onde,cep,rua,num,bairro,cidade,uf,tel1,tel2,email,sit, congregacao) VALUES
	('$nome','$evangelico','$local', '$cep', '$rua', '$num', '$bairro', '$cidade', '$uf', '$tel1', '$tel2', '$email', '1','$congregacao')") or die(mysql_error());
	}
	echo "
	<script>
	alert('Visitante registrado com sucesso');
	</script>
	";
}//fim registro
?>
<form action="#" method="POST" class="formulario">
<input type="hidden" name="visitante" value="1">
<label class="iden"><b>Nome</b></label> <input type="text" size="33" id="nome" name="nome" required><br>
<label class="iden"><b>Evangélico?</b></label><input type="radio" name="ev" id="o1" value="1"><label for="o1">Sim</label><input type="radio" name="ev" id="o2" value="0"><label for="o2">Não</label><br>
<label class="iden"><b>Onde Congr.</b></label><input type="text" name="local" size="20"><br>
<label class="iden"><b>Cep</b></label> <input type="text" size="8" name="cep" id="cep" class="cep" onchange="pesquisacep()" ><br>
<label class="iden"><b>Rua</b></label> <input type="text" id="rua" name="rua" size="35"><div class="cel"><br></div> <b>Nº</b><input type="text" name="num" id="num" size="3"><br>
<label class="iden"><b>Bairro</b></label> <input type="text" id="bairro" name="bairro" size="15"><div class="cel"><br></div><br>
<label class="iden"><b>Cidade</b></label> <input type="text" id="cidade" name="cidade" size="15">
<b>Uf</b> <input type="text" id="uf" name="uf" size="2"><br>
<label class="iden"><b>Telefone </b><b>Residencial</b></label><input type="text" name="fone1" id="tel1" class="fone" size="11"><div class="cel"><br></div><br>
<label class="iden"><b>Celular</b></label><input type="text" name="fone2" id="tel2" class="fone9" size="11"><br>
<label class="iden"><b>Email</b></label><input type="text" name="email" id="email" size="30"><br><br>
<input type="submit" value="Salvar" id="salva">
<div class="cel"><br><br></div>
</form>
</div>

<?php ########### 
############ VISITA ##############
?>
<div id="div_visita" style="display:none">
<img src="../arquivos/icones/close.png" class="bt_fecha" onclick="fechavisita()">
﻿<span class="tt_pg"><b>Registra Visita</b></span>
<?php

$visita = $_POST[visita];
if($visita){
$jovem = $_POST[jId];//id do jovem
$dt = $_POST[dVisita];//data formato padrão BR 01/34/6789
$dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];

$des = addslashes($_POST[vDescricao]);
$des = nl2br($des);
mysql_query("insert into tb_visitas (jovem,data,obs) value('$jovem','$dt','$des')") or die(mysql_error());
}
?>
<form action="#" method="POST" class="formulario">
<input type="hidden" name="visita" value="1">
<label class="iden"><b>Nome</b></label> <select name="jId" required>
<option value="">Selecione</option>
<?php
$lI1=mysql_query("select id,nome from tb_inscritos where congregacao = '$congregacao' order by nome asc");
while($lI = mysql_fetch_assoc($lI1)){
echo "<option value='$lI[id]'>$lI[nome]</option>";
} 
?>
</select><br>
<label class="iden"><b>Data Visita</b></label> <input type="text" class="date" name="dVisita" id="dVisita" size="11" required><br>
<label class="iden"><b>Descrição</b></label><br>
<textarea rows="10" cols="45" name="vDescricao"></textarea>
<br>
<input type="submit" value="Salvar" id="salva">
<div class="cel"><br><br></div>
</form>
</div>
<script>
$( function() {
    $( "#dVisita" ).datepicker();
  } );
</script>
<?php
##############
?>
<div id="regEntrada" style="display:none;">
<span class="tt_pg"><b>Registro de Entrada Financeira</b></span>
<br>
<br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$agora = date('dmYHis');
$agr = date("d/m/Y H:i");
$regentrada = $_POST[regentrada];
$hj = date("d/m/Y");
$hj2 = date("Y-m-d");

if ($regentrada == 1) {
    $vlr = $_POST[vlr];
    $obs = $_POST[just];
    $dt = $_POST[data];//01/34/6789
    $dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
    if($dt != $hj2){
        $sit = 1;
    }
    else if($dt == $hj2){
        $sit = 2;
    }
   mysql_query("
        insert into financeiro (data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
        (now(),'1','E','$vlr','Entrada Manual','$cod_us','$sit','$dt','$obs')
        ");
	echo "<script type='text/javascript'>alert('Entrada Realizada com Sucesso.');</script>";
	echo "<meta http-equiv='refresh' content='0'>";
}
?>

<form action="#" id="formEntrada" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="regentrada" value="1">
    <b>Data/Hora: </b><?php echo "$agr"; ?><br>
    <b>Valor: </b><input type="text" name="vlr" id="enVlr" class="vlr" size="6"><br>
    <b>Data Programada: </b><input type="text" class="date" size="9" name="data" id="enData" value="<?php echo $hj;?>"><br>
    <b>Justificativa: </b><br><textarea name="just" id="enJust" rows="3" cols="40" maxlenght="200" required></textarea><br>
	<input type="submit" value="Salvar">
	<input type="button" value="Cancelar" onclick="fechaRegEntrada()">
</form>
</div>
<?php //registro de saídas?>

<div id="regSaida" style="display:none;">
<span class="tt_pg"><b>Registro de Saída Financeira</b></span>
<br>
<br>
<?php
$regsaida = $_POST[regsaida];

if ($regsaida == 1) {
    $vlr = $_POST[vlr];
    $obs = $_POST[just];
    $dt = $_POST[data];//01/34/6789
    $dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
    if($dt != $hj2){
        $sit = 1;
    }
    else if($dt == $hj2){
        $sit = 2;
    }
    mysql_query("
        insert into financeiro (data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
        (now(),'2','S','$vlr','Saída Manual','$cod_us','$sit','$dt','$obs')
        ");
	echo "<script type='text/javascript'>alert('Saída Realizada com Sucesso.');</script>";
	echo "<meta http-equiv='refresh' content='0'>";
}
?>

<form action="#" id="formSaida" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="regsaida" value="1">
    <b>Data/Hora: </b><?php echo "$agr"; ?><br>
    <b>Valor: </b><input type="text" name="vlr" id="saiVlr" class="vlr" size="6"><br>
    <b>Data Programada: </b><input type="text" class="date" size="9" name="data" id="saiData" value="<?php echo $hj;?>"><br>
    <b>Justificativa: </b><br><textarea name="just" id="saiJust" rows="3" cols="40" maxlenght="200" required></textarea><br>
	<input type="submit" value="Salvar"><input type="button" value="Cancelar" onclick="fechaRegSaida()">
</form>
</div>

<?php //cadasro de atendentes USUÁRIOS
/*
-Verificar se nº de usuários tipo 1 ativos é menor que quantidade contratada;
-Verificar se nº de usuários tipo 2 ativos é menor que quantidade contratada;
-Colocar radio para selecionar, verificar se poderá incluir ou não, de acordo com o resultado anterior..., não permitindo, emitir alerta informando que quantidade cadastrada já é igual a quantidade contratada...
-Permitindo, continua com o cadastro
*/
?>
<div id="cadAtendente" style="display:none;">
<span class="tt_pg"><b>Cadastra Usuário</b></span><br><br>
<?php
$cadatendente = $_POST[cadAtendente];

if($cadatendente){
	$nome = $_POST[nome];
	$senha = $_POST[senha];
	$senha = hash('whirlpool',$senha);
	$usuario = $_POST[usuario];
	$tpSel = 1;
	if($tpSel == 1){
		mysql_query("insert into usuarios (nome,usuario,senha,tipo,situacao) values('$nome','$usuario','$senha','1','1')") or die(mysql_error());
	}

echo "<script>
	alert('Registro realizado com sucesso.');
	</script>";
echo "<meta http-equiv='refresh' content='0'>";
}

$qt_us = mysql_num_rows(mysql_query("select * from usuarios where tipo = 1 and situacao = 1"));
$qt_ag = mysql_num_rows(mysql_query("select * from usuarios where tipo = 2 and situacao = 1"));

$tpSel = "";//tipo de cadastro para Selecionar
$nCad = 0;

if($qt_us >= 999){
 $tpSel .= "<input type='radio' name='tpSel' value='1' id='tpSel1' disabled title='Quantidade de Usuários Ativos Contratado Atingida.'><label for='tpSel1' title='Quantidade de Usuários Ativos Contratado Atingida.'>Secretária(o)</label>";
}
	?>
<form action="#" id="formAtendente" method="POST">
<input type="hidden" name="cadAtendente" value="1">
<?php 
if($nCad){
	echo "<b>Cadastro não permitido. Quantidade contratada atingida. Se deseja aumentar o número de cadastros, entre em contato.</b>
<br><a href='http://bitwase.com' target='_blank'>www.bitwase.com</a>
<br><br>

<input type='button' value='Sair' onclick='fechaCadAtendente()'>";
}
else{
 ?><br>
<label class="iden"><b>Nome</b></label> <input type="text" name="nome" id="atNome" size="30" required><br><br>
<label class="iden"><b>Usuário de Acesso</b></label> <input type="text" name="usuario" id="usuario" size="30" required><br><br>
<label class="iden"><b>Senha</b></label> <input type="text" name="senha" id="senha" size="30" required><br><br>

<br><input type="submit" value="Gravar"><input type="button" value="Cancelar" onclick="fechaCadAtendente()">
<?php }?>
</form>
</div>

<?php 
#################################
##### CADASTRA CLIENTES #########
#################################
?>
<div id="divCadCliente" style="display:none;">
<span class="tt_pg"><b>Cadastra Clientes</b></span><br><br>
<?php
$cadClientes = $_POST[cadClientes];

if($cadClientes == 1){
$dt = $_POST[nascimento];// 01 34 6789
$dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
	mysql_query("INSERT INTO clientes  	(nome,rg,cpf,dt_nasc,end,num,compl,bairro,cidade,uf,cep,tel1,tel2,email,situacao)
	VALUES(
	'$_POST[nome]',
	'$_POST[rg]',
	'$_POST[cpf]',
	'$dt',
	'$_POST[end]',
	'$_POST[num]',
	'$_POST[compl]',
	'$_POST[bairro]',
	'$_POST[cidade]',
	upper('$_POST[uf]'),
	'$_POST[cep]',
	'$_POST[tel1]',
	'$_POST[tel2]',
	'$_POST[email]',
	'1')") or die(mysql_error());
echo "<script>alert('Cliente cadastrado com sucesso.');</script>";
echo "<meta http-equiv='refresh' content='0'>";
}
?>
<form action="#" id="formCliente" method="POST" style="input{padding:1px;}">
<input type="hidden" name="cadClientes" value="1">
<b>Nome</b> <input type="text" name="nome" size="30" required style="text-transform:'uppercase';"><br>
<b>Nascimento</b> <input type="text" class="date" required name="nascimento" id="nascimento" size="10" maxlength="10"><br>
<b>RG</b> <input type="text" name="rg" class="rg" size="13"><br>
<b>CPF</b> <input type="text" name="cpf" class="cpf" size="15"><br>
<br>
<b>CEP</b> <input type="text" name="cep" size="13" id="cep" onchange="pesquisacep()" class="cep"><br>
<b>Rua</b> <input type="text" name="end" id="rua" size="30"><br>
<b>Nº.</b> <input type="text" name="num" size="5"> 
<b>Compl. </b> <input type="text" name="compl" size="9"><br>
<b>Bairro</b> <input type="text" name="bairro" id="bairro" size="30"><br>
<b>Cidade</b> <input type="text" name="cidade" id="cidade" size="30"><br>
<b>UF</b> <input type="text" class="uf" name="uf" id="uf" style="text-transform:uppercase"; size="2"> 
<br>
<b>Tel. Fixo</b> <input type="text" name="tel1" size="13" class="fone"><br>
<b>Tel. Celular</b> <input type="text" name="tel2" size="13" class="fone"><br>
<b>Email</b> <input type="text" name="email" size="25"><br>
<br><br>
<input type="submit" value="Gravar"> <input type="button" value="Cancelar" onclick="fechaCadCliente()">
</form>
</div>

<?php 
#################################
##### CADASTRA CLIENTES #########
#################################
?>
<div id="divCadInscricao" style="display:none;">
<span class="tt_pg"><b>Novo Cadastro</b></span><br><br>
<?php
$cadInscricao = $_POST[cadInscricao];

if($cadInscricao == 1){
$dt = $_POST[nascimento];// 01 34 6789
$dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
mysql_query("insert into tb_inscritos (nome,regional,congregacao,nascimento,voz,telefone,email) values('$_POST[nome]','$_POST[regional]','$_POST[congregacao]','$_POST[nascimento]','$_POST[voz]','$_POST[telefone]','$_POST[email]')") or die(mysql_error());

echo "<script>alert('Inscrição realizada com sucesso.');</script>";
echo "<meta http-equiv='refresh' content='0'>";
}
?>
<form action="#" id="formCliente" method="POST" style="input{padding:1px;}">
<input type="hidden" name="cadInscricao" value="1">
<label class="iden"><b>Nome</b></label> <input type="text" name="nome" size="30" required style="text-transform:'uppercase';"><br>
<label class="iden"><b>Nascimento</b></label> <input type="text" class="date" required name="nascimento" id="nascimento" size="10" maxlength="10"><br>
<br>
<label class="iden"><b>Regional</b></label> <input type="text" name="regional" size="20"><br>
<label class="iden"><b>Congregação</b></label> <input type="text" name="congregacao" size="20"><br>
<br>
<label class="iden"><b>Telefone</b></label> <input type="text" name="telefone" id="ntel" size="14" required class="fone"><br>
<label class="iden"><b>Email</b></label> <input type="text" name="email" size="25"><br>
<br>
<label class="iden"><b>Voz que canta</b></label> <input type="text" required name="voz" size="15"><br>
<br><br>
<input type="submit" value="Gravar"> <input type="button" value="Cancelar" onclick="fechaCadInscricao()">
</form>
</div>

<script>
    $("#ntel").keyup(function(event){
        if($(this).val().length==14){
            document.getElementById("ntel").className="fone9";
        }
	if($(this).val().length<14){
            document.getElementById("ntel").className="fone";
        }
    });   
</script>

<?php 
#################################
##### CADASTRA FORNECEDOR #######
#################################
?>
<div id="divCadFornecedor" style="display:none;">
<span class="tt_pg"><b>Cadastra Fornecedores</b></span><br><br>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$cadFornecedor = $_POST[cadFornecedor];

if($cadFornecedor == 1){

	mysql_query("INSERT INTO fornecedores  
	(fornecedor,doc,end,num,compl,bairro,cidade,uf,cep,situacao)
	VALUES(
	'$_POST[fornecedor]',
	'$_POST[docFor]',
	'$_POST[end]',
	'$_POST[num]',
	'$_POST[compl]',
	'$_POST[bairro]',
	'$_POST[cidade]',
	upper('$_POST[uf]'),
	'$_POST[cep]',
	'1')") or die(mysql_error());

}
?>
<form action="#" id="formFornecedor" method="POST" style="input{padding:1px;}">
<input type="hidden" name="cadFornecedor" value="1">
<b>Fornecedor</b> <input type="text" name="fornecedor" size="30" required style="text-transform:'uppercase';"><br>
<b>Documento</b> <input type="radio" name="tdoc" id="tdoc1" value="1" onchange="tpDocFor()"><label for="tdoc1">CNPJ</label>
<input type="radio" name="tdoc" id="tdoc2" value="2" onchange="tpDocFor()"><label for="tdoc2">CPF</label> <input type="text" size="18" class="cnpj" name="docFor" id="docFor" disabled><br>
<br>
<b>CEP</b> <input type="text" name="cep" size="13" id="cep2" onchange="pesquisacep2()" class="cep"><br>
<b>Rua</b> <input type="text" name="end" id="rua2" size="30"><br>
<b>Nº.</b> <input type="text" name="num" size="5"> 
<b>Compl. </b> <input type="text" name="compl" size="9"><br>
<b>Bairro</b> <input type="text" name="bairro" id="bairro2" size="30"><br>
<b>Cidade</b> <input type="text" name="cidade" id="cidade2" size="30"><br>
<b>UF</b> <input type="text" class="uf" name="uf" id="uf2" style="text-transform:uppercase"; size="2"> 
<br>
<br><input type="submit" value="Gravar"> <input type="button" value="Cancelar" onclick="fechaCadFornecedor()">
</form>
</div>

<?php 
####################################
##### CADASTRA ESPECIALIDADE #######
####################################
?>
<div id="divCadEspecialidade" style="display:none;">
<span class="tt_pg"><b>Cadastra Especialidade</b></span><br><br>
<?php
$cadEspecialidade = $_POST[cadEspecialidade];

if($cadEspecialidade == 1){
	mysql_query("insert into especialidades (especialidade,valor,situacao) values(upper('$_POST[esp]'),'$_POST[vlr]','1')") or die(mysql_error());
echo "<meta http-equiv='refresh' content='0;url=index.php?pg=v.especialidades'>";
}
?>
<form action="#" id="formEspecialidade" method="POST" style="input{padding:1px;}">
<input type="hidden" name="cadEspecialidade" value="1">
<b>Especialidade</b> <input type="text" name="esp" size="20" required style="text-transform:uppercase;"><br>
<b>Valor</b> <input type="text" class="vlr" name="vlr" id="vlr" required size="10" maxlength="10"><br>
<br><input type="submit" value="Gravar"> <input type="button" value="Cancelar" onclick="fechaCadEspecialidade()">
</form>
</div>

<?php 
####################################
##### CADASTRA PRODUTO #############
####################################

/*
Neste primeiro momento, quando atingir estoque mínimo não irá gerar solicitação de compras, apenas alerta.
*/
?>
<div id="divCadEstoque" style="display:none;">
<span class="tt_pg"><b>Cadastra Produtos</b></span><br><br>
<?php
$cadEstoque = $_POST[cadEstoque];

if($cadEstoque == 1){
	$cadEstdesc = strtoupper($_POST[cadEstdesc]);
	$cadEstum = $_POST[cadEstum];
	$cadEstqtmin = $_POST[cadEstqtmin];
		if($cadEstqtmin == ""){
			$cadEstqtmin = 0;
		}
	$cadEstvlr = $_POST[cadEstvlr];
		if($cadEstvlr == ""){
			$cadEstvlr = 0;
		}
	$cadEstvlrcmp = $_POST[cadEstvlrcmp];
		if($cadEstvlrcmp == ""){
			$cadEstvlrcmp = 0;
		}
	$rs = mysql_query("insert into produtos (descricao,um,qtmin,qt,vlr,vlrcmp,st) VALUES ('$cadEstdesc','$cadEstum','$cadEstqtmin','0','$cadEstvlr','$cadEstvlrcmp','1')");
if($rs){
	echo "<script>alert('Produto cadastrado com sucesso.');</script>";
	echo "<META http-equiv='refresh' content='0;URL=index.php?pg=v.produto'>";
}
if(!$rs){
	echo "Se o erro persistir, informar a seguinte mensagem ao suporte:<br> <b>".mysql_error()."</b><br>";
	echo "<script>alert('Erro ao realizar cadastrado. $erro');</script>";	
}
}
?>
<form action="#" id="formEstoque" method="POST" style="input{padding:1px;}">
<input type="hidden" name="cadEstoque" value="1">
<b>Descrição</b><img src="arquivos/icones/45.png" class="bt_p" title="Descrição clara e objetiva do produto. Este campo não pode ter mais que 150 caracteres."> 
<input type="text" name="cadEstdesc" size="50" maxlength="150" title="Descrição clara e objetiva do produto. Este campo não pode ter mais que 150 caracteres." required style="text-transform:'uppercase';"><br>
<b>Unidade de Medida</b><img src="arquivos/icones/45.png" class="bt_p" title="Unidade de Medida do produto. Deve ser selecionado uma opção.">
 <select name="cadEstum" required title="Unidade de Medida do produto. Deve ser selecionado uma opção.">
<option value="">Selecione</option>
<?php
$um1 = mysql_query("select * from unidademedida where st = 1 order by um asc");
while($um = mysql_fetch_assoc($um1)){
	echo "<option value='$um[id]'>$um[um]</option>";
}
?>
</select><br>
<b>Quantidade Mínima p/ Estoque</b><img src="arquivos/icones/45.png" class="bt_p" title="A quantidade mínima para estoque define quando deve ser gerado um alerta para o produto. Caso não seja informado esta quantidade, não será informado alerta para o item cadastrado."> <input type="text" name="cadEstqtmin" size="5" title="A quantidade mínima para estoque define quando deve ser gerado um alerta para o produto. Caso não seja informado esta quantidade, não será informado alerta para o item cadastrado."><br>
<b>Preço de Venda</b><img src="arquivos/icones/45.png" class="bt_p" title="Se o produto for de venda, deverá ser informado o preço, para que o sistema possa calcular automaticamente os valores das vendas."> <input type="text" name="cadEstvlr" size="7" class="vlr" title="Se o produto for de venda, deverá ser informado o preço, para que o sistema possa calcular automaticamente os valores das vendas.">
<br>
<b>Preço de Compra</b><img src="arquivos/icones/45.png" class="bt_p" title="Este valor será utilizado ao realizar uma compra."> <input type="text" name="cadEstvlrcmp" size="7" class="vlr" title="Este valor será utilizado ao realizar uma compra.">
<br><input type="submit" value="Gravar"> <input type="button" value="Cancelar" onclick="fechaCadEstoque()">
</form>
</div>

<script>

//cadastros 

function cadastro(){
	mostraMascara();
	document.getElementById("div_cadastro").style.display = "block";
}
function fechacadastro(){
	escondeMascara();
	document.getElementById("div_cadastro").style.display = "none";
}

//visitantes 
function regVisitantes(){
	mostraMascara();
	document.getElementById("div_visitantes").style.display = "block";
}
function fechaVisitantes(){
	escondeMascara();
	document.getElementById("div_visitantes").style.display = "none";
}

//visita 

function regVisita(){
	mostraMascara();
	document.getElementById("div_visita").style.display = "block";
}
function fechavisita(){
	escondeMascara();
	document.getElementById("div_visita").style.display = "none";
}

//aniversariantes

function mostraAniversario(tp){
//pegar 1 para mês , e 2 para dia
//procurar com este tipo quais são os aniversariantes, por ordem de data...
//mostrar todos
$.getJSON('aniversario.php?tp='+tp, function(atData){

	var nome = [];
	var aniversario = [];
	$(atData).each(function(key, value){
		nome.push(value.nome);
		aniversario.push(value.data);
	});
	var lista = "<br>";
	nome.forEach(atnd);
	function atnd(nom,i){
		if(nom != ""){
		lista = lista+aniversario[i]+" - "+nome[i]+"<br>";
		}
	};
	document.getElementById("listaAniversario").innerHTML = lista;
});

	mostraMascara();
	document.getElementById("div_aniversariantes").style.display = "block";
}
function fechaAniversario(){
	escondeMascara();
	document.getElementById("div_aniversariantes").style.display = "none";
}

///////////

function tpDocFor(){
	if(document.getElementById("tdoc1").checked==true){
		document.getElementById("docFor").className = "cnpj";
		document.getElementById("docFor").disabled = false;
	} 
	if(document.getElementById("tdoc2").checked==true){
		document.getElementById("docFor").className = "cpf";
		document.getElementById("docFor").disabled = false;
	} 
}

function limpa(){
	document.getElementById("formEntrada").reset();
	document.getElementById("formSaida").reset();
	document.getElementById("formAtendente").reset();
	document.getElementById("formCliente").reset();
	document.getElementById("formFornecedor").reset();
	document.getElementById("formEspecialidade").reset();
	document.getElementById("formEstoque").reset();
}

function mostraRegEntrada(){
	mostraMascara();
	document.getElementById("regEntrada").style.display = "block";
}
function fechaRegEntrada(){
	escondeMascara();
	document.getElementById("regEntrada").style.display = "none";
	limpa();
}

function mostraRegSaida(){
	mostraMascara();
	document.getElementById("regSaida").style.display = "block";
}
function fechaRegSaida(){
	escondeMascara();
	document.getElementById("regSaida").style.display = "none";
	limpa();
}

function mostraCadAtendente(){
	mostraMascara();
	document.getElementById("cadAtendente").style.display = "block";
}
function fechaCadAtendente(){
	escondeMascara();
	document.getElementById("cadAtendente").style.display = "none";
	limpa();
}

function mostraCadCliente(){
	mostraMascara();
	document.getElementById("divCadCliente").style.display = "block";
}
function fechaCadCliente(){
	escondeMascara();
	document.getElementById("divCadCliente").style.display = "none";
	limpa();
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

function mostraCadFornecedor(){
	mostraMascara();
	document.getElementById("divCadFornecedor").style.display = "block";
}
function fechaCadFornecedor(){
	escondeMascara();
	document.getElementById("divCadFornecedor").style.display = "none";
	limpa();
}

function mostraCadEspecialidade(){
	mostraMascara();
	document.getElementById("divCadEspecialidade").style.display = "block";
}
function fechaCadEspecialidade(){
	escondeMascara();
	document.getElementById("divCadEspecialidade").style.display = "none";
	limpa();
}

function mostraCadEstoque(){
	mostraMascara();
	document.getElementById("divCadEstoque").style.display = "block";
}
function fechaCadEstoque(){
	escondeMascara();
	document.getElementById("divCadEstoque").style.display = "none";
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

</script>
