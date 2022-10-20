<span class="tt_pg"><b>Lista de Especialidades</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#24/07/2016{
	-Desenvolvido;
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$atualiza = $_POST[atualiza];
$filtro = "";
if($atualiza == 1){
	$idEsp = $_POST[idEsp];
	$valor = $_POST[valor];
	$st = $_POST[st];
mysql_query("update especialidades set valor = '$valor', situacao = '$st' where id = '$idEsp'");
}
if($filtro == ""){
	$filtro = "p.id > 0 ORDER BY nome";
}
 ?>
<div id="editaEspecialidade" style="display:none;">
<img src="arquivos/icones/116.png" class="bt" style="position:absolute; top:5px; right:5px;" onclick="canEdita()">
<span class="tt_pg"><b>Edita Especialidade</b></span><br>
<div id="edEsp"> </div>
</div>
<table id="produtos" class="display" width="100%"></table>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$cl1= mysql_query("select * from especialidades order by especialidade");
 $od = 0;
while($cli = mysql_fetch_assoc($cl1)){
$od++;//define ordem
	if($cli[situacao] == 0){
		$st = "Inativo";
	}
	if($cli[situacao] == 1){
		$st = "Ativo";
	}
	$lk = " <img src=\'arquivos/icones/26.png\' class=\'bt_p\' onclick=\'editaEspecialidade($cli[id])\' title=\'Editar\'>";
	$valor = number_format($cli[valor],2,".","");
	echo "
	['$od','$cli[especialidade]','R$$valor','$st','$lk'],";
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
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "" },
			{ title: "Especialidade" },
            { title: "Preço" },
            { title: "Situação" },
            { title: "" }
        ]
    } );
} );

//funções 
function editaEspecialidade(id){
	mostraMascara();
//listar quem pode atender
function lAtender(){
$.getJSON('retespecialidade.php?tp=2&id='+id, function(atData){
	var nome2 = [];
	var idat2 = [];//id do atendente
	$(atData).each(function(key, value){
		nome2.push(value.nome2);
		idat2.push(value.idat2);
	});
	var atender = "<br>";
	nome2.forEach(atenderao);
	function atenderao(at2,i2){
		if(at2 != ""){
		atender = atender+at2+"<img src='arquivos/icones/117.png' class='bt_p' title='Adicionar' onclick='incAtendente("+idat2[i2]+","+id+")'><br>";
		}
	};
	document.getElementById("listaAtender").innerHTML = atender;
});
}
//lista dados 
$.getJSON('retespecialidade.php?tp=1&id='+id, function(pagaData){

	var especialidade = [];
	var valor = [];
	var situacao = [];
	var nome = [];
	var idat = [];//id do atendente
	$(pagaData).each(function(key, value){
		especialidade.push(value.especialidade);
		valor.push(value.valor);
		situacao.push(value.situacao);
		nome.push(value.nome);
		idat.push(value.idat);
	});
	var atende = "<br>";
	nome.forEach(atendem);
	function atendem(at,i){
		if(at != ""){
		atende = atende+at+"<img src='arquivos/icones/118.png' class='bt_p' title='Remover' onclick='remAtendente("+idat[i]+","+id+")'><br>";
		}
	};
	//escrever os dados
	var sit = "";
	if(situacao[0] == 0){
		sit = "<option value='0'>Inativo</option><option value='1'>Ativo</option>";
	}
	if(situacao[0] == 1){
		sit = "<option value='1'>Ativo</option><option value='0'>Inativo</option>";
	}
	var val = parseFloat(valor[0]);
	val = val.toFixed(2);
	var form = "<form action='#' method='POST'><input type='hidden' name='atualiza' value='1'><input type='hidden' name='idEsp' value='"+id+"'><b>Valor</b> R$<input type='text' class='vlr' name='valor' size='5' value='"+val+"'><br><b>Situação</b> <select name='st'>"+sit+"</select><br><input type='submit' value='Salvar'><br><br></form><b>Atendem</b>"+atende+"<br><br><b>Disponíveis Para Atender</b><div id='listaAtender'></div>";
	document.getElementById("edEsp").innerHTML = "<b>"+especialidade[0]+"</b>"+form;
lAtender();
});


//mostra a div para editar	
	document.getElementById("editaEspecialidade").style.display = "block";
}
function remAtendente(idat,id){
carregando();
$.ajax({
      url:'retespecialidade.php?tp=3&at='+idat+'&id='+id,
      complete: function (response) {
      },
      error: function () {
         // alert('Erro');
      }
  });
editaEspecialidade(id);
editaEspecialidade(id);
editaEspecialidade(id);
setTimeout(atEdita, 1000);
function atEdita(id){
editaEspecialidade(id);
editaEspecialidade(id);
carregou();
}
}

function incAtendente(idat,id){
carregando();
$.ajax({
      url:'retespecialidade.php?tp=4&at='+idat+'&id='+id,
      complete: function (response) {
//alert(response.responseText);
      },
      error: function () {
         // alert('Erro');
      }
  });
editaEspecialidade(id);
editaEspecialidade(id);
editaEspecialidade(id);
setTimeout(atEdita, 1000);
function atEdita(id){
editaEspecialidade(id);
editaEspecialidade(id);
carregou();
}
}

function canEdita(){
	escondeMascara();
	document.getElementById("editaEspecialidade").style.display = "none";
}
</script>
