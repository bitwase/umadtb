<?php
//página para mostrar em divs os módulos de registro financeiro
//registra entrada
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
<span class="tt_pg"><b>Cadastra Usuário/Atendentes</b></span><br><br>
<?php
$cadatendente = $_POST[cadAtendente];

if($cadatendente == 1){
	$nome = $_POST[nome];
	$senha = $cnf_senha;
	$senha = hash('whirlpool',$senha);
	$cor = $_POST[cor];
	$rg = $_POST[usrg];
	$cpf = $_POST[uscpf];
	$Nus = str_replace(".","",$cpf);
	$Nus = str_replace("-","",$Nus);
	$tpSel = $_POST[tpSel];//tipo selecionado 1 usuário, 2-atendente
	if($tpSel == 1){
		mysql_query("insert into usuarios (nome,usuario,rg,cpf,senha,tipo,situacao) values('$nome','$Nus','$rg','$cpf','$senha','$tpSel','1')") or die(mysql_error());
	}
	else if($tpSel == 2){
		mysql_query("insert into usuarios (nome,usuario,rg,cpf,senha,tipo,situacao) values('$nome','$Nus','$rg','$cpf','$senha','$tpSel','1')")or die(mysql_error());
	$nIdUs = mysql_fetch_assoc(mysql_query("select max(id) as 'id' from usuarios"));	
	
	mysql_query("INSERT INTO atendentes (nome,cor,us,situacao) VALUES('$_POST[nome]','$_POST[cor]','$nIdUs[id]','1')")or die(mysql_error());
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
if($qt_us < $cnf_usuarios){
 $tpSel .= "<input type='radio' name='tpSel' value='1' id='tpSel1' required><label for='tpSel1'>Secretária(o)</label>";
}
else if($qt_us >= $cnf_usuarios){
 $tpSel .= "<input type='radio' name='tpSel' value='1' id='tpSel1' disabled title='Quantidade de Usuários Ativos Contratado Atingida.'><label for='tpSel1' title='Quantidade de Usuários Ativos Contratado Atingida.'>Secretária(o)</label>";
}

if($qt_ag < $cnf_agenda){
 $tpSel .= "<input type='radio' name='tpSel' value='2' id='tpSel2' required><label for='tpSel2'>Atendente</label>";
}
else if($qt_ag >= $cnf_agenda){
 $tpSel .= "<input type='radio' name='tpSel' value='2' id='tpSel2' disabled title='Quantidade de Atendentes Ativos Contratado Atingida.'><label for='tpSel2' title='Quantidade de Atendentes Ativos Contratado Atingida.'>Atendente</label>";
}
if($qt_ag >= $cnf_agenda && $qt_us >= $cnf_usuarios){
	$nCad = 1;
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
echo $tpSel; ?><br>
<b>Nome</b><input type="text" name="nome" id="atNome" size="30" required><br>
<b>RG</b><input type="text" name="usrg" size="13" class="rg" required><br>
<b>CPF</b><input type="text" name="uscpf" size="15" class="cpf" required><br>
<b>Selecione uma cor para agendamentos:</b> <input type="color" name="cor">(cor mostrada na agenda)<br>
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
<span class="tt_pg"><b>Registra Inscrições</b></span><br><br>
<?php
$cadInscricao = $_POST[cadInscricao];

if($cadInscricao == 1){
$dt = $_POST[nascimento];// 01 34 6789
$dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
mysql_query("insert into tb_inscritos (nome,cidade,email,nascimento,cpf,telefone,titulo) values('$_POST[nome]','$_POST[cidade]','$_POST[email]','$_POST[nascimento]','$_POST[cpf]','$_POST[telefone]','$_POST[titulo]')") or die(mysql_error());

/*	mysql_query("INSERT INTO clientes  	(nome,rg,cpf,dt_nasc,end,num,compl,bairro,cidade,uf,cep,tel1,tel2,email,situacao)
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
	'1')") or die(mysql_error());*/
echo "<script>alert('Inscrição realizada com sucesso.');</script>";
echo "<meta http-equiv='refresh' content='0'>";
}
?>
<form action="#" id="formCliente" method="POST" style="input{padding:1px;}">
<input type="hidden" name="cadInscricao" value="1">
<b>Nome</b> <input type="text" name="nome" size="30" required style="text-transform:'uppercase';"><br>
<b>Nascimento</b> <input type="text" class="date" required name="nascimento" id="nascimento" size="10" maxlength="10"><br>
<!--b>RG</b> <input type="text" name="rg" class="rg" size="13"><br-->
<b>CPF</b> <input type="text" name="cpf" class="cpf" size="15"><br>
<br>
<!--b>CEP</b> <input type="text" name="cep" size="13" id="cep" onchange="pesquisacep()" class="cep"><br>
<b>Rua</b> <input type="text" name="end" id="rua" size="30"><br>
<b>Nº.</b> <input type="text" name="num" size="5"> 
<b>Compl. </b> <input type="text" name="compl" size="9"><br>
<b>Bairro</b> <input type="text" name="bairro" id="bairro" size="30"><br-->
<b>Cidade</b> <input type="text" name="cidade" id="cidade" size="30"><br>
<!--b>UF</b> <input type="text" class="uf" name="uf" id="uf" style="text-transform:uppercase"; size="2"> 
<br>
<b>Tel. Fixo</b> <input type="text" name="tel1" size="13" class="fone"><br-->
<b>Telefone</b> <input type="text" name="tel2" size="13" class="fone"><br>
<b>Email</b> <input type="text" name="email" size="25"><br>
<b>Título</b> <select name="titulo" required>
<option value="">Selecione
<option>Membro
<option>Missionário
<option>Cooperador
<option>Diácono
<option>Presbítero
<option>Evangelista
<option>Pastor
</select>
<br><br>
<input type="submit" value="Gravar"> <input type="button" value="Cancelar" onclick="fechaCadInscricao()">
</form>
</div>


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
