<span class="tt_pg"><b>Lista de Atendentes</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#02/08/2016{
	-Desenvolvido;
}
#18/08/2016{
	-Regra para ao inativar uma tendente, inativar também acesso ao sistema.
}

criar página para alterações de usuários e usar mesma lógica para verificação de reativar utilizado neste... verificar tbm em agendamentos, para não permitir novo agendamento com atendentes inativos
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$atualiza = $_POST[atualiza];
$filtro = "";
if($atualiza == 1){
$idAt = $_POST[idAt];
$st = $_POST[st];
$cor = $_POST[cor];

mysql_query("update atendentes set situacao = '$st', cor = '$cor' where id = '$idAt'");

//atualizar tabela de usuarios
$pId = mysql_fetch_assoc(mysql_query("select us from atendentes where id = '$idAt'"));
mysql_query("update usuarios set situacao = '$st' where id = '$pId[us]'") or die(mysql_error());

echo "<script>
alert('Dados atualizados com sucesso.');
</script>
";
}
if($filtro == ""){
	$filtro = "p.id > 0 ORDER BY nome";
}
 ?>
<div id="editaEspecialidade" style="display:none;">
<img src="arquivos/icones/116.png" class="bt" style="position:absolute; top:5px; right:5px;" onclick="canEdita()">
<span class="tt_pg"><b>Edita Atendente</b></span><br>
<div id="edEsp"> </div>
</div>
<table id="produtos" class="display" width="100%"></table>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$cl1= mysql_query("select * from atendentes order by nome");
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
	['$od','$cli[nome]','<div style=\'border-radius:2px;width:20px;height:20px;background:$cli[cor]\'></div>','$st','$lk'],";
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
			{ title: "Nome" },
            { title: "Cor" },
            { title: "Situação" },
            { title: "" }
        ]
    } );
} );

//funções 
function editaEspecialidade(id){
	mostraMascara();
//listar quem pode atender
function lAtende(){
$.getJSON('retespecialidade.php?tp=6&id='+id, function(atData){
	var especialidade = [];
	var idespecialidade = [];
	$(atData).each(function(key, value){
		especialidade.push(value.especialidade);
		idespecialidade.push(value.id);
	});
	var atende = "<br>";
	especialidade.forEach(atnd);
	function atnd(esp,i){
		if(esp != ""){
		atende = atende+esp+"<img src='arquivos/icones/118.png' class='bt_p' title='Remover' onclick='remAtendente("+idespecialidade[i]+","+id+")'><br>";
		}
	};
	document.getElementById("listaAtende").innerHTML = atende;
});
}

function lAtender(){
$.getJSON('retespecialidade.php?tp=7&id='+id, function(atData){
	var especialidade = [];
	var idespecialidade = [];
	$(atData).each(function(key, value){
		especialidade.push(value.especialidade);
		idespecialidade.push(value.id);
	});
	var atende = "<br>";
	especialidade.forEach(atnd);
	function atnd(esp,i){
		if(esp != ""){
		atende = atende+esp+"<img src='arquivos/icones/117.png' class='bt_p' title='Remover' onclick='incAtendente("+idespecialidade[i]+","+id+")'><br>";

		}
	};
	document.getElementById("listaAtender").innerHTML = atende;
});
}

//lista dados 
$.getJSON('retatendente.php?id='+id, function(pagaData){

	var situacao = [];
	var nome = [];
	var cor = [];
	$(pagaData).each(function(key, value){
		situacao.push(value.situacao);
		nome.push(value.nome);
		cor.push(value.cor);
	});
	//escrever os dados
	var sit = "";
	var des = "";
	if(situacao[0] == 0){
	<?php $qt_ag = mysql_num_rows(mysql_query("select * from usuarios where tipo = 2 and situacao = 1"));
if($qt_ag >= $cnf_agenda){
?>	
var des2 = "disabled";
var qtat = "Não é possível reativar, Quantidade de agendas contratadas ativas atingida.";
<?php }

else if($qt_ag < $cnf_agenda){ ?>
var des2 = "";
var qtat = "";
<?php }
?>
		sit = "<option value='0'>Inativo</option><option value='1' "+des2+">Ativo</option>";
		des = "disabled";		
	}
	if(situacao[0] == 1){
		sit = "<option value='1'>Ativo</option><option value='0'>Inativo</option>";
		var qtat = "";
	}
	var form = "<form action='#' method='POST'><input type='hidden' name='atualiza' value='1'><input type='hidden' name='idAt' value='"+id+"'><b>Nome</b> "+nome+"<br><b>Cor</b> <input type='color' name='cor' value="+cor+"><br>Situação</b> <select name='st'>"+sit+"</select> <br><b>"+qtat+"</b><br><input type='submit' value='Salvar'><br><br></form><b>Especialidades</b><br><div id='listaAtende'></div><br><b>Disponíveis Para Atender</b><div id='listaAtender'></div>";
	document.getElementById("edEsp").innerHTML = form;

if(situacao[0] == 1){ //só chama função pra permitir incluir / excluir se ativo
lAtende();
lAtender();
}
});


//mostra a div para editar	
	document.getElementById("editaEspecialidade").style.display = "block";
}
function remAtendente(ides,id){
carregando();
$.ajax({
      url:'retespecialidade.php?tp=8&at='+id+'&id='+ides,
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

function incAtendente(idesp,id){
carregando();
$.ajax({
      url:'retespecialidade.php?tp=9&at='+id+'&id='+idesp,
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
