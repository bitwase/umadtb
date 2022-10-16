<span class="tt_pg"><b>Lista de Inscritos</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#22/06/2016{
	-Desenvolvido;
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$salva = $_POST[salva];
$altClientes = $_POST[altClientes];

if($altClientes == 1){
$dt = $_POST[nascimento];// 01 34 6789
$dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
	mysql_query("update clientes set nome = '$_POST[nome]', rg='$_POST[rg]', cpf='$_POST[cpf]', dt_nasc = '$dt', end = '$_POST[end3]', num = '$_POST[num]', compl = '$_POST[compl]', bairro =	'$_POST[bairro3]', cidade = '$_POST[cidade3]', uf = upper('$_POST[uf3]'),cep =	'$_POST[cep3]', tel1 = '$_POST[tel1]', tel2 = '$_POST[tel2]', email= '$_POST[email]',situacao = '$_POST[sit]' where id = '$_POST[idCli]'") or die(mysql_error());
echo "<script>alert('Dados alterados com sucesso.');</script>";
}
?>
<input type="button" value="Realizar Cadastro" onclick="mostraCadInscricao()">
<table id="produtos" class="display" width="100%"></table>
<div id="divEditaCliente" style="display:none;">
<span class="tt_pg"><b>Altera Dados de Cliente</b></span><br><br>
<form action="#" id="formCliente2" method="POST" style="input{padding:1px;}">
<input type="hidden" name="altClientes" value="1">
<input type="hidden" name="idCli" id="idCli" value=""> 
<b>Nome</b> <input type="text" name="nome" id="edCliNome" size="30" required style="text-transform:'uppercase';"><br>
<b>Nascimento</b> <input type="text" id="edCliNasc" class="date" required name="nascimento" id="nascimento" size="10" maxlength="10"><br>
<b>RG</b> <input type="text" name="rg" id="edCliRg" class="rg" size="13"><br>
<b>CPF</b> <input type="text" name="cpf" class="cpf" id="edCliCpf" size="15"><br>
<br>
<b>CEP</b> <input type="text" name="cep3" size="13" id="cep3" onchange="pesquisacep3()" class="cep"><br>
<b>Rua</b> <input type="text" name="end3" id="rua3" size="30"><br>
<b>Nº.</b> <input type="text" name="num" id="edCliNum" size="5"> 
<b>Compl. </b> <input type="text" name="compl" id="edCliCompl" size="9"><br>
<b>Bairro</b> <input type="text" name="bairro3" id="bairro3" size="30"><br>
<b>Cidade</b> <input type="text" name="cidade3" id="cidade3" size="30"><br>
<b>UF</b> <input type="text" class="uf" name="uf3" id="uf3" style="text-transform:uppercase"; size="2"> 
<br>
<b>Telefone</b> <input type="text" name="tel1" size="13" id="edCliTel1" class="fone"><br>
<b>Telefone</b> <input type="text" name="tel2" size="13" id="edCliTel2" class="fone"><br>
<b>Email</b> <input type="text" name="email" id="edCliEmail" size="25"><br>
<br>
<div id="edSit"></div>
<br>
<input type="submit" value="Gravar"> <input type="button" value="Cancelar" onclick="fechaEditaCliente()">
</form>
</div>
<div id="alinsc" style="display:none">
<span class='tt_pg'>Atenção!!!</span><br>
Siga estes passos:<br><br>
-No campo <img src='arquivos/imagens/filtrar.png'> digite seu nome;<br>
-Caso apareça seus dados: nome completo, Regional e Congregação, não precisa mais fazer nada, seu cadastro já foi realizado anteriormente.<br><br>
-Não aparecendo seus dados, você deve clicar em <img src='arquivos/imagens/cadastro.png'> e preencher os dados solicitados, após isto, seu cadastro será realizado.<br><br>
<input type="button" value="Entendi" onclick="fOrienta()">
</div>

<script>
orienta();
function orienta(){
	mostraMascara();
	document.getElementById("alinsc").style.display = "block";
}

function fOrienta(){
	escondeMascara();
	document.getElementById("alinsc").style.display = "none";
	document.getElementById("ifilt").focus();
}
</script>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$cl1= mysql_query("select * from tb_inscritos");
 $od = 0;
while($cli = mysql_fetch_assoc($cl1)){
$od++;//define ordem
	if($cli[situacao] == 0){
		$st = "Inativo";
	}
	if($cli[situacao] == 1){
		$st = "Ativo";
	}
	$lk = "";
	$lk .= " <a href=\'etiquetas.php?t=2&id=$cli[id]\' target=\'_blank\' title=\'Reimprimir Etiqueta\'><img src=\'arquivos/icones/print.png\' class=\'bt_p\'></a>";
	echo "
	['$od','$cli[nome]','$cli[regional]','$cli[congregacao]'],";
}
?>
];
 
$(document).ready(function() {
    $('#produtos').DataTable( {
		 "scrollX": true,
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
        ],
	"order": [2,'asc'],
         "paging":         false,
		data: dataSet,
        columns: [
        { title: "" },
	{ title: "Nome" },
        { title: "Regional" },
	{ title: "Congregação" }
        ]
    } );
} );

function mostraEditaCliente(id){
	//pegar dados e inserir nos campos inputs para atualizar... receber id do cliente selecionado
	mostraMascara();
	$.getJSON('retcliente.php?tp=1&id='+id, function(pagaData){

	var nome = [];
	var dt_nasc = [];
	var rg = [];
	var cpf = [];
	var end = [];
	var num = [];
	var compl = [];
	var bairro = [];
	var cidade = [];
	var uf = [];
	var tel1 = [];
	var tel2 = [];
	var email = [];
	var cep = [];
	var situacao = [];
	$(pagaData).each(function(key, value){
		nome.push(value.nome);
		dt_nasc.push(value.nasc);
		rg.push(value.rg);
		cpf.push(value.cpf);
		end.push(value.end);
		num.push(value.num);
		compl.push(value.compl);
		bairro.push(value.bairro);
		cidade.push(value.cidade);
		uf.push(value.uf);
		tel1.push(value.tel1);
		tel2.push(value.tel2);
		email.push(value.email);
		cep.push(value.cep);
		situacao.push(value.situacao);
	});
	document.getElementById("edCliNome").value = nome;
	document.getElementById("edCliNasc").value = dt_nasc;
	document.getElementById("edCliRg").value = rg;
	document.getElementById("edCliCpf").value = cpf;
	document.getElementById("rua3").value = end;
	document.getElementById("edCliNum").value = num;
	document.getElementById("edCliCompl").value = compl;
	document.getElementById("bairro3").value = bairro;
	document.getElementById("cidade3").value = cidade;
	document.getElementById("uf3").value = uf;
	document.getElementById("edCliTel1").value = tel1;
	document.getElementById("edCliTel2").value = tel2;
	document.getElementById("edCliEmail").value = email;
	document.getElementById("cep3").value = cep;
	document.getElementById("idCli").value = id;
	
	if(situacao == 0){
		document.getElementById("edSit").innerHTML = "<b>Situação</b><select name='sit'><option value='0'>Inativo</option><option value='1'>Ativo</option></select>";
	}
	if(situacao == 1){
		document.getElementById("edSit").innerHTML = "<b>Situação</b><select name='sit'><option value='1'>Ativo</option><option value='0'>Inativo</option></select>";
	}
});
	
	document.getElementById("divEditaCliente").style.display = "block";
}
function fechaEditaCliente(){
	escondeMascara();
	document.getElementById("divEditaCliente").style.display = "none";
}

function meu_callback3(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('rua3').value=(conteudo.logradouro);
            document.getElementById('bairro3').value=(conteudo.bairro);
            document.getElementById('cidade3').value=(conteudo.localidade);
            document.getElementById('uf3').value=(conteudo.uf);
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }

function pesquisacep3() {

        //Nova variável "cep" somente com dígitos.
	var valor = document.getElementById('cep3').value;
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('rua3').value="...";
                document.getElementById('bairro3').value="...";
                document.getElementById('cidade3').value="...";
                document.getElementById('uf3').value="...";

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = '//viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback3';

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
