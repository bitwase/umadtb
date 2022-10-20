<span class="tt_pg"><b>Lista de Usuários</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#22/06/2016{
	-Desenvolvido;
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 1){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}

$t = $_REQUEST[t];//t== 1 altera

if($t){
$i = $_REQUEST[i];//id que será alterado..
$a = $_REQUEST[a];//status que ficará
mysql_query("update usuarios set situacao = $a where id = $i");

}

$atualizaDados = $_POST[atualizaDados];

if($atualizaDados){
	$idMuda = $_POST[idMuda];
	$senhanv = $_POST[novaSenha];
	$eventoMuda = $_POST[eventoMuda];
	if($eventoMuda == ""){
		$eventoMuda = 0;
	}
	if($senhanv != ""){
		$senhanv = hash('whirlpool',$senhanv);
		mysql_query("update usuarios set senha = '$senhanv' where id = '$idMuda'");
	}
	
	mysql_query("update usuarios set tipo = '$_POST[nivelMuda]', congregacao = '$_POST[congregacaoMuda]', evento = '$eventoMuda', situacao = '$_POST[statusMuda]' where id = '$idMuda'") or die(mysql_error());
	
}

$salva = $_POST[salva];
$altClientes = $_POST[altClientes];
$cnfPg = $_POST[cnfPg];

if($cnfPg){
//se confirmar pagamento....
$id = $_POST[idInsc];//id do inscrito
mysql_query("update tb_inscritos set st = 2 where id = $id");//atualiza para confirmardo
mysql_query("insert into tb_historico (inscrito,resp,data,hist) values('$id','$cod_us',now(),'Pagamento Confirmado.')");
//inserir na tb_pagamento
mysql_query("insert into tb_pagamento (inscrito,resp,data) values('$id','$cod_us',now())");
}

if($altClientes){
$dt = $_POST[nascimento];// 01 34 6789
	mysql_query("update tb_inscritos set nome = '$_POST[nome]', cpf='$_POST[cpf]', nascimento = '$_POST[nascimento]', celular = '$_POST[tel2]', email= '$_POST[email]', congregacao = '$_POST[congregacao]', regional = '$_POST[regional]', titulo = '$_POST[titulo]', cargo = '$_POST[cargo]' where id = '$_POST[idCli]'") or die(mysql_error());
echo "<script>alert('Dados alterados com sucesso.');</script>";
}
?>

<div id="alterarCoisas" style="display:none; position:fixed;width:400px;height:200px;left:0;right:0;top:0;bottom:0;margin:auto;z-index:99999999999;background:#fff;border-radius:5px;padding:10px;">
<form action="#" method="POST">
<input type="hidden" name="atualizaDados" value="1">
<input type="hidden" name="idMuda" id="idMuda" value="1">
<label class="iden"><b>Nome:</b></label> <input type="text" disabled id="nomeMuda" name="nomeMuda"><br>
<label class="iden"><b>Usuário:</b></label> <input type="text" disabled id="usuarioMuda" name="usuarioMuda"><br>
<label class="iden"><b>Nova Senha:</b></label> <input type="text" id="novaSenha" name="novaSenha"><br>
<label class="iden"><b>Nível:</b></label> <select name="nivelMuda" required>
<option value="">Selecione</option>
<option value="1">Administrador</option>
<option value="2">Líder Congregação</option>
<option value="3">Eventos</option>
</select><br>
<label class="iden"><b>Congregação:</b></label> <input type="text" id="congregacaoMuda" name="congregacaoMuda"><br>
<label class="iden"><b>Evento:</b></label> <select name="eventoMuda">
<option value="">Selecione</option>
<?php
$le = mysql_query("select * from tb_eventos where st = 1 order by evento");
while($l = mysql_fetch_assoc($le)){
	echo "<option value='$l[id]'>$l[evento]</option>";
}

?>
</select><br>
<label class="iden"><b>Status:</b></label> <select name="statusMuda">
<option value="1">Ativo</option>
<option value="0">Inativo</option>
</select><br><br>

<input type="submit" value="Atualizar"><input type="button" value="Cancelar" onclick="fechaEdita()">
</form>
</div>

<table id="produtos" class="display" width="100%"></table>

<script language="javascript" type="text/javascript">
var dataSet = [
<?php

$cl1= mysql_query("select * from usuarios where id > 1");

 $od = 0;
while($cli = mysql_fetch_assoc($cl1)){
$od++;//define ordem
	$lk = "";
	if($cli[st] == 0){
		$st = "Inativo";
	}
	if($cli[st] == 1){
		$st = "Ag. Pagamento";
			if($nv_acesso == 1 || $nv_acesso == 3){
		$lk .= " <a href=\'#\' target=\'\' onclick=\'mostraCnfPg(\"$cli[nome]\",$cli[id])\' title=\'Confirmar Pagamento\'><img src=\'arquivos/icones/ok.png\' class=\'bt_p\'></a>";
			}
	}
	if($cli[st] == 2){
		$st = "Realizado";
	}
	if($nv_acesso == 1 || $nv_acesso == 3){
	$lk .= " <a href=\'etiquetas.php?t=2&id=$cli[id]\' target=\'_blank\' title=\'Reimprimir Etiqueta\'><img src=\'arquivos/icones/print.png\' class=\'bt_p\'></a>";
	}
	$lk .= " <a href=\'index.php?pg=historico&id=$cli[id]\' title=\'Histórico\'><img src=\'arquivos/icones/list.png\' class=\'bt_p\'></a>";
		if($nv_acesso == 1 || $nv_acesso == 3){
	$lk .= " <a href=\'#\' title=\'Editar\' onclick=\'mostraEditaCliente($cli[id])\'><img src=\'arquivos/icones/pen.png\' class=\'bt_p\'></a>";
		}
	if($cli[tp] == 1){
		$tpag = "Dinheiro";
	}
	if($cli[tp] == 2){
		$tpag = "Cartão";
	}
	if($cli[tp] == 3){
		$tpag = "PagSeguro";
	}
	$cod = $cli[id];
	if($cli[tipo] == 1){
		$tp = "Administrador";
		$local = "";
	}
	if($cli[tipo] == 2){
		$tp = "Líder Congregação";
		$local = "$cli[congregacao]";
	}
	if($cli[tipo] == 3){
	$ddev = mysql_fetch_assoc(mysql_query("select * from tb_eventos where id = '$cli[evento]'"));
	$local = "$ddev[evento]";
		$tp = "Eventos";
		//$evento = "";
		//$local = "$cli[evento]";
	}
	if($cli[situacao] == 1){
			$status = "Ativo";
			$lk = "<a href=\'index.php?pg=v.lider&t=1&i=$cli[id]&a=0\' title=\'Inativar $cli[nome]\'>Inativar</a>";
	}
	if($cli[situacao] == 0){
			$status = "Inativo";
			$lk = "<a href=\'index.php?pg=v.lider&t=1&i=$cli[id]&a=1\' title=\'Reativar $cli[nome]\'>Reativar</a>";
	}
	echo "
	['$od','$cli[id]','$cli[nome]','$cli[usuario]','$tp','$local','$status',],";
}
?>
];
 
$(document).ready(function() {
    $('#produtos').DataTable( {
		 "scrollX": true,
		 "scrollY": "55vh",
		"columnDefs": [
            {
                "targets": [ 0,1 ],
                "visible": false,
                "searchable": false
            },
        ],
	"order": [1,'asc'],
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "" },
            { title: "ID" },
			{ title: "Nome" },
            { title: "Usuário" },
			{ title: "Tipo" },
            { title: "Congr./Evento" },
            { title: "Status" }
        ]
    } );
    
    
    var table = $('#produtos').DataTable();
     
    $('#produtos tbody').on('click', 'td', function () {
        var data = table.row( $(this).parents('tr') ).data();
			document.getElementById("idMuda").value = data[1];
			document.getElementById("nomeMuda").value = data[2];
			document.getElementById("usuarioMuda").value = data[3];
			
			$("#alterarCoisas").fadeIn("slow");
			$("#mascara").fadeIn("slow");
			
		
		  });
    
} );

function fechaEdita(){
	$("#alterarCoisas").fadeOut("slow");
	$("#mascara").fadeOut("slow");
}
</script>
