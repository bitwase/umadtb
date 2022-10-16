<?php
$filtra = $_POST[ft];
$ev = $_POST[evento];

if($nivel == 3){
	$ev = $evento_nivel;
	$filtra = 1;
}

$cn = $_POST[congregacao];
if($ev == ""){
	$ev = $_POST[idEvento];
}

if($nivel == 2){//rega para que mostre apenas os envolvidos da 
	$cn = "$congregacao_nivel";
}

if($cn != ""){
	$congr = "and congregacao = '$cn'";
}

$st = $_POST[setor];
if($ev == ""){
	$ev = $_POST[idEvento];
}

if($nivel == 2){//rega para que mostre apenas os envolvidos da 
	$st = "$setor_nivel";
}

if($st != ""){
	$setor = "and setor = '$st'";
}

if($ev){
	$ddev = mysql_fetch_assoc(mysql_query("select * from tb_eventos where id = '$ev'"));
	$evento = "$ddev[evento]";
}
?>
<span class="tt_pg"><b>Lista de Inscritos - Evento: <?php echo "$evento";?></b></span><br><br>
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

$removeInscrito = $_POST[removeInscrito];
if($removeInscrito){
	mysql_query("delete from tb_inscricao where inscrito = '$_POST[idInscricao]' and evento = '$_POST[idEvento]'");
}

$pagaInscrito = $_POST[pagaInscrito];
if($pagaInscrito){
	mysql_query("update tb_inscricao set pg = '1' where inscrito = '$_POST[idPagar]' and evento = '$_POST[idEventoPagar]'") or die(mysql_error());
}

$altClientes = $_POST[altClientes];
if($altClientes == 1){
$dt = $_POST[nascimento];// 01 34 6789
if($dt != ""){
$dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];
}
else{
	$dt = "0000-00-00";
}
	mysql_query("update tb_inscritos set nome = '$_POST[nome]', rg='$_POST[rg]', cpf='$_POST[cpf]', nascimento = '$dt', rua = '$_POST[end3]', num = '$_POST[num]', bairro =	'$_POST[bairro3]', cidade = '$_POST[cidade3]', uf = upper('$_POST[uf3]'),cep =	'$_POST[cep3]', tel1 = '$_POST[tel1]', tel2 = '$_POST[tel2]', email= '$_POST[email]',sit = '$_POST[sit]', congregacao = '$_POST[congregacao2]' where id = '$_POST[idCli]'") or die(mysql_error());

echo "<script>alert('Dados alterados com sucesso.');</script>";
}

?>
<?php if($nivel != 3){?>
<form action="#" method="POST">
<input type="hidden" name="ft" value="1">
<label class="iden"><b>Evento</b></label><select name="evento" id="selEv" required onchange="selEvento()">
<option value="">Selecione</option>
<?php
$le = mysql_query("select * from tb_eventos where st = 1 order by evento");
while($l = mysql_fetch_assoc($le)){
	echo "<option value='$l[id]'>$l[evento]</option>";
}
?>
</select><input type="submit" value="Filtrar">
</form>

<?php }?>

<?php
if($filtra){
/*
definir filtrar como padrão...
incluir também um campo para o evento selecionado
*/
?><br><br>
<?php if($nivel == 1 || $nivel == 3){?>
<form action="#" method="POST">
<input type="hidden" name="ft" value="1">
<input type="hidden" name="evento" value="<?php echo $ev;?>">
<label class="iden"><b>Setor</b></label><select name="setor">
<option value="">Selecione</option>
<?php
$le = mysql_query("select distinct upper(setor) as 'setor' from tb_inscritos where id in (select distinct inscrito from tb_inscricao where evento = '$ev') order by setor");
while($l = mysql_fetch_assoc($le)){
	echo "<option value='$l[setor]'>$l[setor]</option>";
}
?>
</select><input type="submit" value="Filtrar Setor">
</form><br><br>

<form action="#" method="POST">
<input type="hidden" name="ft" value="1">
<input type="hidden" name="evento" value="<?php echo $ev;?>">
<label class="iden"><b>Congregação</b></label><select name="congregacao">
<option value="">Selecione</option>
<?php
$le = mysql_query("select distinct upper(congregacao) as 'congregacao' from tb_inscritos where id in (select distinct inscrito from tb_inscricao where evento = '$ev') order by congregacao");
while($l = mysql_fetch_assoc($le)){
	echo "<option value='$l[congregacao]'>$l[congregacao]</option>";
}
?>
</select><input type="submit" value="Filtrar Congregação">
</form>
<?php } ?>
<?php
}

$setores ="";
$lse = mysql_query("select distinct setor from tb_inscritos where id in (select distinct inscrito from tb_inscricao where evento = '$ev') order by setor asc");

while($ls = mysql_fetch_assoc($lse)){
	$setores .= "<option value='$ls[setor]'>$ls[setor]</option>";
}
?>
<br>
<?php if($filtra  && ($nivel == 1 || $nivel == 3)){
	echo "<b>Mostrar:</b> <input type='button' value='TODOS' title='TODOS' onclick='filtraStatus(\"\")'> <input type='button' value='PAGO' onclick='filtraStatus(\"PAGO\")'> <input type='button' value='PENDENTE' onclick='filtraStatus(\"PENDENTE\")'><br><br>
	<b>Imprimir:</b> <a href='relatorio2.php?e=$ev&cn=$cn' target='_blank'><input type='button' value='Lista de Inscritos'></a> <a href='relatorio3.php?e=$ev&cn=$cn&pg=1' target='_blank'><input type='button' value='Lista de Pagos'></a> <a href='relatorio3.php?e=$ev&cn=$cn&pg=0' target='_blank'><input type='button' value='Lista de Pendentes'></a><br><br>
	<b>Certificado:</b> <a href='certificado.php?ev=$ev&cn=$cn' target='_blank'><input type='button' value='Pagos'></a> <a href='certificado_p.php?ev=$ev&cn=$cn' target='_blank'><input type='button' value='Pendentes'></a> <a href='?pg=v.inscritos3&evento=$ev'><input type='button' value='Selecionar'></a><br><br>
	<b>Etiquetas:</b> <a href='etiquetas.php?t=1&ev=$ev&cn=$cn' target='_blank'><input type='button' value='Todas'></a> <a href='?pg=v.inscritos2&evento=$ev'><input type='button' value='Selecionar'></a> <br><br>  <b>Imprimir etiquetas por setor:</b><form action='etiquetas.php' method='POST'><input type='hidden' name='ev' value='$ev'><input type='hidden' name='t' value='3'><select name='s' required><option value=''>Selecione</option>$setores</select><input type='submit' value='Imprimir'></form><br><br>
<b>Presença:</b> <a href='?pg=presenca&evento=$ev' target='_blank'><input type='button' value='Registrar'></a> <a href='?pg=v.presenca&ev=$ev' target='_blank'><input type='button' value='Consultar'></a>	 ";
}?>
<table id="produtos" class="display" width="100%"></table>
<div id="removeInscrito" style="display:none">
<form action="#" method="POST">
<input type="hidden" name="removeInscrito" value="1">
<input type="hidden" name="idInscricao" id="idInscricao" name="idInscricao" value="">
<input type="hidden" name="idEvento" id="idEvento" value="">
<b>Deseja mesmo remover <input type="text" id="nomeRemover" disabled size="30"> da lista de inscritos?</b>
<br><br>
<input type="submit" value="SIM"> <input type="button" value="NÃO" onclick="fechaRemove()">
</form>
</div>

<div id="pagarInscrito" style="display:none">
<form action="#" method="POST">
<input type="hidden" name="ft" value="1">
<input type="hidden" name="pagaInscrito" value="1">
<input type="hidden" name="idPagar" id="idPagar" value="">
<input type="hidden" name="idEventoPagar" id="idEventoPagar" value="">
<input type="hidden" name="idEvento" id="idEventoPagar2" value="">
<b>Deseja informar pagamento para <input type="text" id="nomePagar" disabled size="30"> ?</b>
<br><br>
<input type="submit" value="SIM"> <input type="button" value="NÃO" onclick="fechaPagar()">
</form>
</div>

<div id="divEditaCliente" style="display:none;">
<span class="tt_pg"><b>Altera Dados</b></span><br><br>
<form action="#" id="formCliente2" method="POST" style="input{padding:1px;}">
<input type="hidden" name="ft" value="1">
<input type="hidden" name="altClientes" value="1">
<input type="hidden" name="evento" value="<?php echo $ev;?>">

<input type="hidden" name="idCli" id="idCli" value=""> 
<b>Nome</b> <input type="text" name="nome" id="edCliNome" size="30" required style="text-transform:'uppercase';"><br>
<b>Nascimento</b> <input type="text" id="edCliNasc" class="date" required name="nascimento" id="nascimento" size="10" maxlength="10"><br>
<b>RG</b> <input type="text" name="rg" id="edCliRg" class="rg" size="13"><br>
<b>CPF</b> <input type="text" name="cpf" class="cpf" id="edCliCpf" size="15"><br>
<br>
<b>CEP</b> <input type="text" name="cep3" size="13" id="cep3" onchange="pesquisacep3()" class="cep"><br>
<b>Rua</b> <input type="text" name="end3" id="rua3" size="30">
<b>Nº.</b> <input type="text" name="num" id="edCliNum" size="5"> <br>
<!--b>Compl. </b> <input type="text" name="compl" id="edCliCompl" size="9"><br-->
<b>Bairro</b> <input type="text" name="bairro3" id="bairro3" size="30"><br>
<b>Cidade</b> <input type="text" name="cidade3" id="cidade3" size="30">
<b>UF</b> <input type="text" class="uf" name="uf3" id="uf3" style="text-transform:uppercase"; size="2"><br>
<label class="iden"><b>Congregação</b></label> <input type="text" id="congregacao2" name="congregacao2" size="15"><br><br>
<b>Telefone Res.</b> <input type="text" name="tel1" size="13" id="edCliTel1" class="fone"><br>
<b>Celular</b> <input type="text" name="tel2" size="13" id="edCliTel2" class="fone2"><br>
<b>Email</b> <input type="text" name="email" id="edCliEmail" size="25"><br>
<br>
<div id="edSit"></div>
<br>
<input type="submit" value="Gravar"> <input type="button" value="Cancelar" onclick="fechaEditaCliente()">
</form>
</div>

<script language="javascript" type="text/javascript">
var dataSet = [
<?php
if($filtra){

	$cl1= mysql_query("select id, upper(nome) as 'nome', tel1, tel2, upper(email) as 'email', upper(congregacao) as 'congregacao', date_format(nascimento,'%d/%m/%Y') as 'nasc' from tb_inscritos where sit = 1 and id in(select inscrito from tb_inscricao where evento = '$ev') $congr $setor order by nome");
	
}
 $od = 0;
while($cli = mysql_fetch_assoc($cl1)){
$vs2 = mysql_query("select date_format(data,'%d/%m/%Y') as 'data' from tb_visitas where jovem = '$cli[id]' order by id desc limit 1") or die(mysql_error());
$vp = mysql_fetch_assoc(mysql_query("select * from tb_inscricao where inscrito = '$cli[id]' and evento = '$ev'"));

	$lk = "";
if($vp[pg] == 0){
	$pg = "PENDENTE";
	$lk .= "<a href=\'#\' onclick=\'defPago($cli[id],\"$cli[nome]\",$ev)\' title=\'Confirmar Pagamento\'> $ </a>";
}
if($vp[pg] == 1){
	$pg = "PAGO";
}
$od++;//define ordem
$vs1 = mysql_fetch_assoc($vs2);
$vs = $vs1[data];
	if($cli[situacao] == 0){
		$st = "Inativo";
	}
	if($cli[situacao] == 1){
		$st = "Ativo";
	}
	$telefone = "$cli[tel1]<br>$cli[tel2]";
	$lk .= " <a href=\'#\' onclick=\'rmInscrito($cli[id],\"$cli[nome]\",$ev)\' title=\'Remover\'><img src=\'arquivos/icones/close.png\' class=\'bt_p\'></a>";
	
	$lk .= " <a href=\'#\' onclick=\'mostraEditaCliente($cli[id])\' title=\'Altera Dados\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
//	$lk .= " <a href=\'?pg=visitas&id=$cli[id]\' title=\'Histórico de Visitas\'><img src=\'arquivos/icones/lista2.png\' class=\'bt_p\'></a>";
	
	
	echo "
	['$od','$cli[nome]','$cli[nasc]','$telefone','$cli[email]','$cli[congregacao]','$pg','$lk',],";
}
?>
];
 
$(document).ready(function() {
    $('#produtos').DataTable( {
		 "scrollX": true,
		"scrollY": "55vh",//esta media vh, representa x(60) % da altura (height)
		dom: 'Bfrtip',
	 buttons: [
            {
                extend: 'collection',
                text: 'Exportar/Imprimir',
                buttons: [
                    //'copy',
                    'excel',
                    //'csv',
				{
				  extend: 'pdfHtml5',
				  text: 'PDF',
				  orientation:'landscape'
        			},
                    'print'
                ]
            }
			//'print','copy', 'csv', 'excel', 'pdf', 
        ],
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false,
            },
			{
                "targets": [ 7 ],
                "width": "50px"
            },
        ],
	"order": [1,'asc'],
         "paging":         false,
		data: dataSet,
        columns: [
        { title: "" },
	{ title: "Nome" },
	{ title: "Nascimento" },
    { title: "Telefone" },
    { title: "Email" },
    { title: "Congregação" },
    { title: "Pagamento" },
    { title: "" }
        ]
    } );
} );

function mostraEditaCliente(id){
	//pegar dados e inserir nos campos inputs para atualizar... receber id do cliente selecionado
	mostraMascara();
	$.getJSON('../retdadosJovens.php?tp=2&id='+id, function(pagaData){

	var nome = [];
	var dt_nasc = [];
	var rg = [];
	var cpf = [];
	var end = [];
	var num = [];
	//var compl = [];
	var bairro = [];
	var cidade = [];
	var uf = [];
	var tel1 = [];
	var tel2 = [];
	var email = [];
	var cep = [];
	var situacao = [];
	var congregacao = [];
	$(pagaData).each(function(key, value){
		nome.push(value.nome);
		dt_nasc.push(value.nascimento);
		rg.push(value.rg);
		cpf.push(value.cpf);
		end.push(value.rua);
		num.push(value.num);
		//compl.push(value.compl);
		bairro.push(value.bairro);
		cidade.push(value.cidade);
		uf.push(value.uf);
		tel1.push(value.tel1);
		tel2.push(value.tel2);
		email.push(value.email);
		cep.push(value.cep);
		situacao.push(value.situacao);
		congregacao.push(value.congregacao);
	});
	document.getElementById("edCliNome").value = nome;
	document.getElementById("edCliNasc").value = dt_nasc;
	document.getElementById("edCliRg").value = rg;
	document.getElementById("edCliCpf").value = cpf;
	document.getElementById("rua3").value = end;
	document.getElementById("edCliNum").value = num;
	//document.getElementById("edCliCompl").value = compl;
	document.getElementById("bairro3").value = bairro;
	document.getElementById("cidade3").value = cidade;
	document.getElementById("uf3").value = uf;
	document.getElementById("edCliTel1").value = tel1;
	document.getElementById("edCliTel2").value = tel2;
	document.getElementById("edCliEmail").value = email;
	document.getElementById("cep3").value = cep;
	document.getElementById("idCli").value = id;
	document.getElementById("congregacao2").value = congregacao;
	
	if(situacao == 0){
		document.getElementById("edSit").innerHTML = "<b>Situação</b><select name='sit'><option value='1'>Ativo</option><option value='0'>Inativo</option></select>";
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
	
	function rmInscrito(i,n,e){
				//n nomeRemover   i = idInscricao
				document.getElementById("nomeRemover").value=n;
				document.getElementById("idInscricao").value=i;
				document.getElementById("idEvento").value=e;
				document.getElementById("removeInscrito").style.display = "block";
				mostraMascara();
	}
	
	function fechaRemove(){
				//n nomeRemover   i = idInscricao
				document.getElementById("nomeRemover").value="";
				document.getElementById("idInscricao").value="";
				document.getElementById("idEvento").value="";
				document.getElementById("removeInscrito").style.display = "none";
				escondeMascara();
	}
	
function defPago(i,n,e){
		//n nomeRemover   i = idInscricao
		document.getElementById("nomePagar").value=n;
		document.getElementById("idPagar").value=i;
		document.getElementById("idEventoPagar").value=e;
		document.getElementById("idEventoPagar2").value=e;
		document.getElementById("pagarInscrito").style.display = "block";
		mostraMascara();
}
function fechaPagar(){
		//n nomeRemover   i = idInscricao
		document.getElementById("nomePagar").value="";
		document.getElementById("idPagar").value="";
		document.getElementById("idEventoPagar").value="";
		document.getElementById("idEventoPagar2").value="";
		document.getElementById("pagarInscrito").style.display = "none";
		escondeMascara();
}


var stant;
function filtraStatus(st){
var table = $('#produtos').DataTable();
if(stant != st){
	stant = st;
		table.columns(6).search(st).draw();
}
else{
	stant = "";
	table.columns(6).search("").draw();
}
}
</script>
