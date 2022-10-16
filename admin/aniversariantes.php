<span class="tt_pg"><b>Aniversariantes</b></span><br><br>
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

if($filtrar){
//colocar regras aqui pra filtrar
$meses = "";
$j = $_POST[j];
$f = $_POST[f];
$m = $_POST[m];
$a = $_POST[a];
$ma = $_POST[ma];
$jun = $_POST[jun];
$jul = $_POST[jul];
$ag = $_POST[ag];
$s = $_POST[s];
$o = $_POST[o];
$n = $_POST[n];
$d = $_POST[d];

if($j){
	$meses .= "1,";
}
if($f){
	$meses .= "2,";
}
if($m){
	$meses .= "3,";
}
if($a){
	$meses .= "4,";
}
if($ma){
	$meses .= "5,";
}
if($jun){
	$meses .= "6,";
}
if($jul){
	$meses .= "7,";
}
if($ag){
	$meses .= "8,";
}
if($s){
	$meses .= "9,";
}
if($o){
	$meses .= "10,";
}
if($n){
	$meses .= "11,";
}
if($d){
	$meses .= "12,";
}
$meses = substr_replace($meses, '', -1);
}
if($nivel == 1){
	$cl1= mysql_query("select id, nome, tel1, tel2, email, date_format(nascimento,'%d/%m') as 'nasc', date_format(nascimento,'%m-%d') as 'na' from tb_inscritos where date_format(nascimento,'%m') in ($meses) and sit = 1 order by na asc");
}

if($nivel == 2){
	$cl1= mysql_query("select id, nome, tel1, tel2, email, date_format(nascimento,'%d/%m') as 'nasc', date_format(nascimento,'%m-%d') as 'na' from tb_inscritos where congregacao = '$congregacao_nivel' and date_format(nascimento,'%m') in ($meses) and sit = 1 order by na asc");
}
?>
<form action="#" method="POST">
<input type="hidden" name="filtrar" value="1">
<input type="checkbox" <?php if($j){echo "checked";}?> name="j" id="j" value="1"><label for="j">Janeiro</label>
<input type="checkbox" <?php if($f){echo "checked";}?> name="f" id="f" value="1"><label for="f">Fevereiro</label>
<input type="checkbox" <?php if($m){echo "checked";}?> name="m" id="m" value="1"><label for="m">Março</label>
<input type="checkbox" <?php if($a){echo "checked";}?> name="a" id="a" value="1"><label for="a">Abril</label>
<input type="checkbox" <?php if($ma){echo "checked";}?> name="ma" id="ma" value="1"><label for="ma">Maio</label>
<input type="checkbox" <?php if($jun){echo "checked";}?> name="jun" id="jun" value="1"><label for="jun">Junho</label>
<input type="checkbox" <?php if($jul){echo "checked";}?> name="jul" id="jul" value="1"><label for="jul">Julho</label>
<input type="checkbox" <?php if($ag){echo "checked";}?> name="ag" id="ag" value="1"><label for="ag">Agosto</label>
<input type="checkbox" <?php if($s){echo "checked";}?> name="s" id="S" value="1"><label for="s">Setembro</label>
<input type="checkbox" <?php if($o){echo "checked";}?> name="o" id="o" value="1"><label for="o">Outubro</label>
<input type="checkbox" <?php if($n){echo "checked";}?> name="n" id="n" value="1"><label for="n">Novembro</label>
<input type="checkbox" <?php if($d){echo "checked";}?> name="d" id="d" value="1"><label for="d">Dezembro</label><br><input type="submit" value="Filtrar"></form>
<table id="produtos" class="display" width="100%"></table>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
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
	$lk .= " <a href=\'#\' onclick=\'mostraEditaCliente($cli[id])\' title=\'Altera Dados\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	$lk .= " <a href=\'?pg=visitas&id=$cli[id]\' title=\'Histórico de Visitas\'><img src=\'arquivos/icones/lista2.png\' class=\'bt_p\'></a>";
	echo "
	['$od','$cli[nome]','$cli[nasc]','$telefone','$cli[email]','$vs','$lk',],";
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
       { "width": "30px", "targets": [6] },
        ],
	"order": [0,'asc'],
         "paging":         false,
		data: dataSet,
        columns: [
        { title: "" },
	{ title: "Nome" },
	{ title: "Aniversário" },
    { title: "Telefone" },
    { title: "Email" },
    { title: "Últ. Visita" },
    { title: "" }
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
</script>
