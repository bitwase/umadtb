<span class="tt_pg"><b>Registro de Ausências</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#17/04/2017{
	-Desenvolvido;
	-selecionar os ausentes...
	-informar a data (obrigatório)
	
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$regAusente = $_POST[regAusente];
if($regAusente){
$data = $_POST[data_ausencia];
$dt = $data;//01-34-6789
$dt = $dt[6].$dt[7].$dt[8].$dt[9]."-".$dt[3].$dt[4]."-".$dt[0].$dt[1];//formato para bd
$jv = $_POST[idUs];
$jv = explode(",",$jv);
foreach($jv as $j){
mysql_query("insert into tb_ausente (jovem,data) values('$j','$dt')");
}
include "consecutivo.php";
echo "<script> alert('Ausências registradas.');</script>";

}

?>

<form action="#" name="ausente" id="ausente" method="POST">
<input type="hidden" name="regAusente" value="1">
<input type="hidden" name="idUs" id="idUs">
<b>Data</b> <input type="text" name="data_ausencia" id="data_ausencia" required size="11" class="date">
</form>

<table id="produtos" class="display" width="100%"></table>
<input type="button" value="Informar Ausência" onclick="selPrint()">
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$cl1= mysql_query("select id, nome, tel1, tel2, email, date_format(nascimento,'%d/%m/%Y') as 'nasc' from tb_inscritos where congregacao = '$congregacao' and sit = 1 order by nome");
 $od = 0;
while($cli = mysql_fetch_assoc($cl1)){
$od++;//define ordem
	if($cli[situacao] == 0){
		$st = "Inativo";
	}
	if($cli[situacao] == 1){
		$st = "Ativo";
	}
	$telefone = "$cli[tel1]<br>$cli[tel2]";
	$lk = "<input type=\'checkbox\' value=\'$cli[id]\' id=\'ckbx\'>";
	$lk1 = " <a href=\'etiquetas.php?t=2&id=$cli[id]\' target=\'_blank\' title=\'Reimprimir Etiqueta\'>";
	$lk2 = "</a>";
	echo "
	['$od','$lk',' $cli[nome]','$cli[nasc] ',' $telefone',' $cli[email]'],";
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
                "searchable": false
            },
        ],
	"order": [1,'asc'],
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "" },
	{ title: "Selec." },
			{ title: "Nome" },
            { title: "Nascimento" },
	{ title: "Telefone" },
	{ title: "Email" }
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


function selPrint(){
//alert("teste");
  var inputs, x, selecionados=0;
  inputs = document.getElementsByTagName('input');//pega todos os elementos desse tipo
  var txt = "";
  for(x=0;x<inputs.length;x++){
    if(inputs[x].type=='checkbox'){
      if(inputs[x].checked==true && inputs[x].id == 'ckbx'){
	if(txt == ""){
		txt = inputs[x].value;
	}
	else if(txt != ""){
	txt = inputs[x].value+", "+txt;//lista todos os selecionados
	}
      }
    }
  }
//	alert(txt);
var dataAusente = document.getElementById("data_ausencia").value;
if(dataAusente == ""){
	alert("Deve informar a data da ausência.");
	document.getElementById("data_ausencia").focus();
}
if(txt != "" && dataAusente != ""){
document.getElementById("idUs").value = txt;
//alert(txt);
//var URL = "etiquetas.php?t=2&id="+txt;
//window.open(URL,"_blank");
document.getElementById("ausente").submit();
}
if(txt == ""){
	alert("Não foi selecionado nenhum registro.");
}
}

$( function() {
    $( "#data_ausencia" ).datepicker();
  } );
</script>
