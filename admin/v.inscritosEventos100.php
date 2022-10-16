<?php
$filtra = $_POST[ft];
$ev = $_POST[evento];
$cn = $_POST[congregacao];
if($ev == ""){
	$ev = $_POST[idEvento];
}

if($cn != ""){
	$congr = "and congregacao = '$cn'";
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

?>
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

<?php
if($filtra){
/*
definir filtrar como padrão...
incluir também um campo para o evento selecionado
*/
?><br><br>
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

<?php
}
?>
<br>
<?php if($filtra){
	echo "<a href='relatorio2.php?e=$ev&cn=$cn' target='_blank'><input type='button' value='Imprimir Lista'></a>";
}?>
<table id="produtos" class="display" width="100%"></table>
<div id="removeInscrito" style="display:none">
<form action="#" method="POST">
<input type="hidden" name="removeInscrito" value="1">
<input type="hidden" name="idInscricao" id="idInscricao" name="idInscricao" value="">
<input type="hidden" name="idEvento" id="idEvento" name="idInscricao" value="">
<b>Deseja mesmo remover <input type="text" id="nomeRemover" disabled size="30"> da lista de inscritos?</b>
<br><br>
<input type="submit" value="SIM"> <input type="button" value="NÃO" onclick="fechaRemove()">
</form>
</div>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
if($filtra){
$cl1= mysql_query("select id, nome, tel1, tel2, email, congregacao, date_format(nascimento,'%d/%m/%Y') as 'nasc' from tb_inscritos where sit = 1 and id in(select inscrito from tb_inscricao where evento = '$ev') $congr order by nome");
}
 $od = 0;
while($cli = mysql_fetch_assoc($cl1)){
$od++;//define ordem
$vs2 = mysql_query("select date_format(data,'%d/%m/%Y') as 'data' from tb_visitas where jovem = '$cli[id]' order by id desc limit 1") or die(mysql_error());
$vs1 = mysql_fetch_assoc($vs2);
$vs = $vs1[data];
	if($cli[situacao] == 0){
		$st = "Inativo";
	}
	if($cli[situacao] == 1){
		$st = "Ativo";
	}
	$telefone = "$cli[tel1]<br>$cli[tel2]";
	$lk = "";
	$lk .= " <a href=\'#\' onclick=\'rmInscrito($cli[id],\"$cli[nome]\",$ev)\' title=\'Remover\'><img src=\'arquivos/icones/close.png\' class=\'bt_p\'></a>";
//	$lk .= " <a href=\'?pg=visitas&id=$cli[id]\' title=\'Histórico de Visitas\'><img src=\'arquivos/icones/lista2.png\' class=\'bt_p\'></a>";
	echo "
	['$od','$cli[nome]','$cli[nasc]','$telefone','$cli[email]','$cli[congregacao]','$lk',],";
}
?>
];
 
$(document).ready(function() {
    $('#produtos').DataTable( {
		 "scrollX": true,
		"scrollY": "55vh",//esta media vh, representa x(60) % da altura (height)
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false,
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
    { title: "Remover" }
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
</script>
