<span class="tt_pg"><b>Lista de Usuários</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#18/08/2016{
	-Desenvolvimento.
	criar página para alterações de usuários e usar mesma lógica para verificação de reativar utilizado neste... verificar tbm em agendamentos, para não permitir novo agendamento com atendentes inativos
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
$idAt = $_POST[idAt];
$st = $_POST[st];
$atipo = $_POST[atipo];
$cor = $_POST[cor];

if($atipo == 2){// tipo 2 = atipo
	//verificar se existe em atendentes
	if($st == 1){
	$vat = mysql_num_rows(mysql_query("select * from atendentes where us = '$idAt'"));
	if($vat > 0){
		mysql_query("update atendentes set situacao = '$st' where us = '$idAt'");
	}
	else if($vat == 0){
		$dddd = mysql_fetch_assoc(mysql_query("select * from usuarios where id = '$idAt'"));
		mysql_query("insert into atendentes (nome,cor,us,situacao) values('$dddd[nome]','#000000','$idAt','1')");
	}
	}
	else if($st == 0){
		mysql_query("update atendentes set situacao = '0' where us = '$idAt'");
	}
	}//fim tipo 2

if($atipo == 1){
	//verificar se existe em atendentes
	$vat = mysql_num_rows(mysql_query("select * from atendentes where us = '$idAt"));
	if($vat > 0){
		mysql_query("update atendentes set situacao = '0' where us = '$idAt'");
	}
	else if($vat == 0){
	}
}
//atualizar tabela de atendentes
//$pId = mysql_fetch_assoc(mysql_query("select us from atendentes where id = '$idAt'"));
mysql_query("update usuarios set situacao = '$st', tipo = '$atipo' where id = '$idAt'") or die(mysql_error());

echo "<script>
alert('Dados atualizados com sucesso.');
</script>
<meta http-equiv='refresh' content='0'>
";
}
if($filtro == ""){
	$filtro = "p.id > 0 ORDER BY nome";
}
 ?>
<div id="editaEspecialidade" style="display:none;">
<img src="arquivos/icones/116.png" class="bt" style="position:absolute; top:5px; right:5px;" onclick="canEdita()">
<span class="tt_pg"><b>Edita Usuário</b></span><br>
<div id="edEsp"> </div>
</div>
<table id="produtos" class="display" width="100%"></table>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$cl1= mysql_query("select * from usuarios order by nome");
 $od = 0;
while($cli = mysql_fetch_assoc($cl1)){
$od++;//define ordem
	if($cli[situacao] == 0){
		$st = "Inativo";
	}
	if($cli[situacao] == 1){
		$st = "Ativo";
	}
	if($cli[tipo] == 1){
		$tipo = "Secretária(o)";
	}
	if($cli[tipo] == 2){
		$tipo = "Atendente";
	}
	$lk = " <img src=\'arquivos/icones/26.png\' class=\'bt_p\' onclick=\'editaEspecialidade($cli[id])\' title=\'Editar\'>";
	$valor = number_format($cli[valor],2,".","");
	echo "
	['$od','$cli[nome]','$cli[rg]','$cli[cpf]','$tipo','$st','$lk'],";
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
            { title: "RG" },
	    { title: "CPF" },
	    { title: "Tipo" },
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
//ajustar e criar um retusuario com os dados a ser alterado...
//verificar regras para quando inativar/ativar, verificar quantidade de usuários ativos e comparar com quantidades contratada.
$.getJSON('retusuario.php?id='+id, function(pagaData){

	var situacao = [];
	var idAt = [];
	var nome = [];
	var rg = [];
	var cpf = [];
	var tipo = [];
	$(pagaData).each(function(key, value){
		situacao.push(value.situacao);
		idAt.push(value.id);
		nome.push(value.nome);
		rg.push(value.rg);
		cpf.push(value.cpf);
		tipo.push(value.tipo);
	});
	//escrever os dados
	var sit = "";
	var des = "";
	var atipo = "";
	if(situacao[0] == 0){
if(tipo == 1){
	<?php $qt_us = mysql_num_rows(mysql_query("select * from usuarios where tipo = 1 and situacao = 1"));
if($qt_us >= $cnf_usuarios){
?>	
var des2 = "disabled";
var qtat = "Não é possível reativar, Quantidade de usuários contratados ativos atingida.";
<?php }

else if($qt_us < $cnf_usuarios){ ?>
var des2 = "";
var qtat = "";
<?php }
?>
}
else if(tipo == 2){
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
}
		sit = "<option value='0'>Inativo</option><option value='1' "+des2+">Ativo</option>";
		des = "disabled";		
	}
	if(situacao[0] == 1){
		sit = "<option value='1'>Ativo</option><option value='0'>Inativo</option>";
		var qtat = "";
	}
	
	if(tipo[0] == 1){//se tipo for igual a secretario/a
	<?php $qt_ag = mysql_num_rows(mysql_query("select * from usuarios where tipo = 2 and situacao = 1"));
if($qt_ag >= $cnf_agenda){
?>	
var des3 = "disabled";
var qtat1 = "Não é possível alterar para 'Atendente'. Quantidade de agendas contratadas ativas atingida.";
<?php }

else if($qt_ag < $cnf_agenda){ ?>
var des3 = "";
var qtat1 = "";
<?php }
?>
		atipo = "<option value='1'>Secretária(o)</option><option value='2' "+des3+">Atendente</option>";		
	}
	
	else if(tipo[0] == 2){//se tipo for igual a atendente
	<?php $qt_ag = mysql_num_rows(mysql_query("select * from usuarios where tipo = 1 and situacao = 1"));
if($qt_ag >= $cnf_usuarios){
?>	
var des3 = "disabled";
var qtat1 = "Não é possível alterar para 'Secretária(o)'. Quantidade de usuários contratados ativos atingida.";
<?php }

else if($qt_ag < $cnf_usuarios){ ?>
var des3 = "";
var qtat1 = "";
<?php }
?>
		atipo = "<option value='2' >Atendente</option><option value='1' "+des3+">Secretária(o)</option>";		
	}
	
	var form = "<form action='#' method='POST'><input type='hidden' name='atualiza' value='1'><input type='hidden' name='idAt' value='"+idAt+"'><b>Nome:</b> "+nome+"<br><b>RG:</b> "+rg+"<br><b>CPF:</b> "+cpf+"<br><b>Situação</b> <select name='st'>"+sit+"</select> <br><b>Tipo de Acesso:</b> <select name='atipo'>"+atipo+"</select> <br><b>"+qtat+qtat1+"</b><br><input type='submit' value='Salvar'><br><br></form>";
	document.getElementById("edEsp").innerHTML = form;

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
