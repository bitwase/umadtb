<span class="tt_pg"><b>Lista de Ausências</b></span><br><br>
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
$filtrar = $_POST[filtrar];
$ft = " where congregacao = '$congregacao'";
if($filtrar){
$ft .= " and a.id > 0 ";
$di = $_POST[di];//01-34-6789
if($di != ""){
$di = $di[6].$di[7].$di[8].$di[9]."-".$di[3].$di[4]."-".$di[0].$di[1]; 
$ft .= "and a.data >= '$di'";
}
$df = $_POST[df];
if($df != ""){
$df = $df[6].$df[7].$df[8].$df[9]."-".$df[3].$df[4]."-".$df[0].$df[1]; 
$ft .= "and a.data <= '$df'";
}
}

?>
<form action="#" method="POST">
<input type="hidden" name="filtrar" value="1">
<b>Data Inicial</b> <input type="text" class="date" id="diFiltro" name="di" size="11"> 
<b>Data Final</b> <input type="text" class="date" id="dfFiltro" name="df" size="11">  
<input type="submit" value="Filtrar">
</form>
<table id="produtos" class="display" width="100%"></table>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
if($ft != ""){
$cl1= mysql_query("select i.nome, a.jovem, a.envio, date_format(a.data,'%d/%m/%Y') as 'ausencia', date_format(a.dataEnvio,'%d/%m/%Y %H:%i:%s') as 'dtEnvio' from tb_ausente a
inner join tb_inscritos i on a.jovem = i.id
$ft
order by a.data");
}
$od = 0;
while($cli = mysql_fetch_assoc($cl1)){
$od++;//define ordem
$au = mysql_num_rows(mysql_query("select * from tb_ausente where jovem = $cli[jovem]"));
	if($cli[envio] == 0){
		$envio = "Não";
	}
	if($cli[envio] == 1){
		$envio = "Sim";
	}

	echo "
	['$od','$cli[ausencia]','$cli[nome]','$envio','$cli[dtEnvio]','$au'],";
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
       {},
        ],
	"order": [1,'asc'],
         "paging":         false,
		data: dataSet,
        columns: [
        { title: "" },
	{ title: "Data" },
	{ title: "Nome" },
    { title: "Envio" },
    { title: "Data Envio" },
    { title: "Ausências" },
        ]
    } );
} );

function mostraEditaCliente(id){
	//pegar dados e inserir nos campos inputs para atualizar... receber id do cliente selecionado
	mostraMascara();
	$.getJSON('../retdados.php?tp=2&id='+id, function(pagaData){

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
$( function() {
    $( "#diFiltro" ).datepicker();
    $( "#dfFiltro" ).datepicker();
  } );

</script>
