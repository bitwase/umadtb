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

<form action="#" name="entrada" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="regentrada" value="1">
    <b>Data/Hora: </b><?php echo "$agr"; ?><br>
    <b>Valor: </b><input type="text" name="vlr" class="vlr" size="6"><br>
    <b>Data Programada: </b><input type="text" class="date" size="9" name="data" value="<?php echo $hj;?>"><br>
    <b>Justificativa: </b><br><textarea name="just" rows="3" cols="40" maxlenght="200" required></textarea><br>
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

<form action="#" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="regsaida" value="1">
    <b>Data/Hora: </b><?php echo "$agr"; ?><br>
    <b>Valor: </b><input type="text" name="vlr" class="vlr" size="6"><br>
    <b>Data Programada: </b><input type="text" class="date" size="9" name="data" value="<?php echo $hj;?>"><br>
    <b>Justificativa: </b><br><textarea name="just" rows="3" cols="40" maxlenght="200" required></textarea><br>
	<input type="submit" value="Salvar"><input type="button" value="Cancelar" onclick="fechaRegSaida()">
</form>
</div>

<?php //cadasro de atendentes?>
<div id="cadAtendente" style="display:none;">
<span class="tt_pg"><b>Cadastra Atendentes</b></span><br><br>
<?php
$cadatendente = $_POST[cadAtendente];

if($cadatendente == 1){
	$nome = $_POST[nome];
$cor = $_POST[cor];
	mysql_query("INSERT INTO atendentes (nome,cor,situacao) VALUES('$_POST[nome]','$_POST[cor]','1')");
echo 	"<script>
	alert('Registro realizado com sucesso.');
	</script>";
echo "<meta http-equiv='refresh' content='0'>";
}
	?>
<form action="#" method="POST">
<input type="hidden" name="cadAtendente" value="1">
<b>Nome</b><br>
<input type="text" name="nome" size="30" style="text-transform:uppercase;"><br>
<b>Selecione uma cor para agendamentos:</b> <input type="color" name="cor">(cor mostrada na agenda)<br>
<br><input type="submit" value="Gravar"><input type="button" value="Cancelar" onclick="fechaCadAtendente()">
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

	mysql_query("INSERT INTO clientes  
	(nome,dt_nasc,end,num,compl,bairro,cidade,uf,cep,tel1,tel2,email,situacao)
	VALUES(
	'$_POST[nome]',
	'$_POST[nascimento]',
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
}
?>
<form action="#" method="POST" style="input{padding:1px;}">
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
<b>Telefone</b> <input type="text" name="tel1" size="13" class="fone"><br>
<b>Telefone</b> <input type="text" name="tel2" size="13" class="fone"><br>
<b>Email</b> <input type="text" name="email" size="25"><br>
<br><br>
<input type="submit" value="Gravar"> <input type="button" value="Cancelar" onclick="fechaCadCliente()">
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
	(fornecedor,end,num,compl,bairro,cidade,uf,cep,situacao)
	VALUES(
	'$_POST[fornecedor]',
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
<form action="#" method="POST" style="input{padding:1px;}">
<input type="hidden" name="cadFornecedor" value="1">
<b>Fornecedor</b> <input type="text" name="fornecedor" size="30" required style="text-transform:'uppercase';"><br>
<b>Documento</b> <input type="radio" name="tdoc" id="tdoc1" value="1" onchange="tpDocFor()" disabled><label for="tdoc1">CNPJ</label>
<input type="radio" name="tdoc" id="tdoc2" value="2" onchange="tpDocFor()"><label for="tdoc2">CPF</label> <input type="text" size="15" class="cnpj" name="docFor" id="docFor"><br>
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
function mostraRegEntrada(){
	mostraMascara();
	document.getElementById("regEntrada").style.display = "block";
}
function fechaRegEntrada(){
	escondeMascara();
	document.getElementById("regEntrada").style.display = "none";
}

function mostraRegSaida(){
	mostraMascara();
	document.getElementById("regSaida").style.display = "block";
}
function fechaRegSaida(){
	escondeMascara();
	document.getElementById("regSaida").style.display = "none";
}

function mostraCadAtendente(){
	mostraMascara();
	document.getElementById("cadAtendente").style.display = "block";
}
function fechaCadAtendente(){
	escondeMascara();
	document.getElementById("cadAtendente").style.display = "none";
}

function mostraCadCliente(){
	mostraMascara();
	document.getElementById("divCadCliente").style.display = "block";
}
function fechaCadCliente(){
	escondeMascara();
	document.getElementById("divCadCliente").style.display = "none";
}

function mostraCadFornecedor(){
	mostraMascara();
	document.getElementById("divCadFornecedor").style.display = "block";
}
function fechaCadFornecedor(){
	escondeMascara();
	document.getElementById("divCadFornecedor").style.display = "none";
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
