<span class="tt_pg"><b>Lista de Produtos</b></span><br><br>
<?php
/*------------------------ ALTERAÇÕES ------------------*\
#10/05/2016{
	-Desenvolvido;
}
\* -----------------------------------------------------*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
$soma = $_POST[soma];
$subtrai = $_POST[subtrai];
$idpdt = $_POST[idpdt];
$altera = $_POST[altera];

if($soma == 1){

$pdt= mysql_fetch_assoc(mysql_query("select p.*, um.um from produtos p
inner join unidademedida um on p.um = um.id
 where p.id = $idpdt"));

	$qt = $_POST[qtmv];
	$nqt = $pdt[qt]+$qt;
	$rs = mysql_query("update produtos set qt = $nqt where id = $idpdt");
	mysql_query("insert into mvprodutos (data,us,pdt,qt,qtat,tp,acao) values (now(),'$cod_us','$idpdt','$qt','$nqt','1','ENTRADA MANUAL')");
if($rs){
	echo "<script>alert('Soma realizada com sucesso.');</script>";
	echo "<META http-equiv='refresh' content='0;URL=index.php?pg=v.produto'>";
}
if(!$rs){
	echo "Se o erro persistir, informar a seguinte mensagem ao suporte:<br> <b>".mysql_error()."</b><br>";
	echo "<script>alert('Erro ao realizar operação. $erro');</script>";	
}
}//fim se soma

 
if($subtrai == 1){

$pdt= mysql_fetch_assoc(mysql_query("select p.*, um.um from produtos p
inner join unidademedida um on p.um = um.id
 where p.id = $idpdt"));

	$qt = $_POST[qtmv];
	if($pdt[qt] < $qt){
	echo "<script>alert('Saída não pode ser maior que quantidade disponível. [$pdt[qt]]');</script>";
	}
	else{
		$nqt = $pdt[qt]-$qt;
	$rs = mysql_query("update produtos set qt = $nqt where id = $idpdt");
	mysql_query("insert into mvprodutos (data,us,pdt,qt,qtat,tp,acao) values (now(),'$cod_us','$idpdt','$qt','$nqt','2','SAÍDA MANUAL')");
if($rs){
	echo "<script>alert('Saída realizada com sucesso.');</script>";
	echo "<META http-equiv='refresh' content='0;URL=index.php?pg=v.produto'>";
}
if(!$rs){
	echo "Se o erro persistir, informar a seguinte mensagem ao suporte:<br> <b>".mysql_error()."</b><br>";
	echo "<script>alert('Erro ao realizar operação. $erro');</script>";	
}
	}
}//fim se subtrai estoque

//se alteração de produto
if($altera == 1){
$aqtmin = $_POST[aqtmin];
$avlr = $_POST[avlr];
$avlrcmp = $_POST[avlrcmp];
$asit = $_POST[asit];
$aum = $_POST[aum];
$ok = mysql_query("update produtos set um = '$aum', qtmin = '$aqtmin', vlr = '$avlr', vlrcmp = '$avlrcmp', st = '$asit' where id = '$idpdt'");
if($ok){
	echo "<script>alert('Dados alterados com sucesso');</script>";
}
}

if($filtro == ""){
	$filtro = "p.id > 0 ORDER BY descricao";
}
 ?>
<div id="pdtSomaEstoque" style="display:none;">

</div>
<div id="pdtSubtraiEstoque" style="display:none;">

</div>
<div id="pdtEditaEstoque" style="display:none;">

</div>
<table id="produtos" class="display" width="100%"></table>
<script language="javascript" type="text/javascript">
var dataSet = [
<?php
$pdt1= mysql_query("select p.*, um.um from produtos p
inner join unidademedida um on p.um = um.id
 where $filtro");
 $od = 0;
while($pdt = mysql_fetch_assoc($pdt1)){
$od++;//define ordem
	if($pdt[st] == 0){
		$st = "INATIVO";
	}
	if($pdt[st] == 1){
		$st = "ATIVO";
	}
	$vnd = "R$".number_format($pdt[vlr],2,'.','');
	$cmp = "R$".number_format($pdt[vlrcmp],2,'.','');
	$lk = "";
	if($pdt[st] == 1){
	$lk .= "<a href=\'#\' onclick=\'somaEstoque($pdt[id])\' title=\'Somar Estoque\'><img src=\'arquivos/icones/117.png\' class=\'bt_p\'></a>";
	$lk .= " <a href=\'#\' onclick=\'subtraiEstoque($pdt[id])\' title=\'Subtrair Estoque\'><img src=\'arquivos/icones/118.png\' class=\'bt_p\'></a>";
	}
	$lk .= " <a href=\'index.php?pg=m.produto&id=$pdt[id]\' title=\'Consulta Movimentações\'><img src=\'arquivos/icones/36.png\' class=\'bt_p\'></a>";
	$lk .= " <a href=\'#\' onclick=\'editaEstoque($pdt[id])\' title=\'Alterar Dados do Produto\'><img src=\'arquivos/icones/26.png\' class=\'bt_p\'></a>";
	echo "
	['$od','$pdt[id]','$pdt[descricao]','$pdt[um]','$pdt[qt]','$pdt[qtmin]','$vnd','$cmp','$st','$lk',],";
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
			{ title: "Cód." },
            { title: "Descrição" },
            { title: "UM" },
            { title: "Qt. Est." },
            { title: "Qt. Min." },
            { title: "Vlr. Venda" },
            { title: "Vlr. Compra" },
            { title: "Situação" },
            { title: "....Ações...." }
        ]
    } );
} );

function somaEstoque(id){
//função para somar estoque do item selecionado
//pegar dados via json, repassar via ajax
	mostraMascara();
	
	$.getJSON('retproduto.php?tp=1&id='+id, function(pagaData){
	var descricao = [];
	var um = [];
	var qt = [];
	var qtmin = [];
	
	$(pagaData).each(function(key, value){
		descricao.push(value.descricao);
		um.push(value.um);
		qt.push(value.qt);
		qtmin.push(value.qtmin);
	});
	
	//escrever os dados
	var form = "<form action='#' method='post'><input type='hidden' name='soma' value='1'><input type='hidden' name='idpdt' value='"+id+"'><b>Quantidade a Entrar:</b><input type='text' placeholder='000' name='qtmv' size='4'><br><input type='submit' value='Gravar'></form>";

	document.getElementById("pdtSomaEstoque").innerHTML = "<img src='arquivos/icones/116.png' class='bt' style='position:absolute; top:5px; right:5px;' onclick='fechaSomaEstoque()'><span class='tt_pg'><b>Entrada Manual de Produtos</b></span><br><b>Cod.:</b> "+id+" <b>Descrição:</b> "+descricao+"<br><b>Unidade de Medida:</b> "+um+"<br><b>Quantidade em Estoque:</b> "+qt+"<br><b>Quantidade Mínima:</b> "+qtmin+"<br>"+form;
});

	document.getElementById("pdtSomaEstoque").style.display = "block";
}

function fechaSomaEstoque(){
	escondeMascara();
	document.getElementById("pdtSomaEstoque").style.display = "none";
}

function subtraiEstoque(id){
//função para somar estoque do item selecionado
//pegar dados via json, repassar via ajax
	mostraMascara();
	
	$.getJSON('retproduto.php?tp=1&id='+id, function(pagaData){
	var descricao = [];
	var um = [];
	var qt = [];
	var qtmin = [];
	
	$(pagaData).each(function(key, value){
		descricao.push(value.descricao);
		um.push(value.um);
		qt.push(value.qt);
		qtmin.push(value.qtmin);
	});
	
	//escrever os dados
	var form = "<form action='#' method='post'><input type='hidden' name='subtrai' value='1'><input type='hidden' name='idpdt' value='"+id+"'> <b>Quantidade a Sair:</b><input type='text' placeholder='000' name='qtmv' size='4'><br><input type='submit' value='Gravar'></form>";

	document.getElementById("pdtSomaEstoque").innerHTML = "<img src='arquivos/icones/116.png' class='bt' style='position:absolute; top:5px; right:5px;' onclick='fechaSubtraiEstoque()'><span class='tt_pg'><b>Saída Manual de Produtos</b></span><br><b>Cod.:</b> "+id+" <b>Descrição:</b> "+descricao+"<br><b>Unidade de Medida:</b> "+um+"<br><b>Quantidade em Estoque:</b> "+qt+"<br><b>Quantidade Mínima:</b> "+qtmin+"<br>"+form;
});

	document.getElementById("pdtSomaEstoque").style.display = "block";
}

function fechaSubtraiEstoque(){
	escondeMascara();
	document.getElementById("pdtSomaEstoque").style.display = "none";
}

function editaEstoque(id){
//função para somar estoque do item selecionado
//pegar dados via json, repassar via ajax
	mostraMascara();
	
	$.getJSON('retproduto.php?id='+id, function(pagaData){
	var descricao = [];
	var um = [];
	var qt = [];
	var qtmin = [];
	var vlr = [];
	var vlrcmp = [];
	var vlrcmp = [];
	var st = [];
	
	$(pagaData).each(function(key, value){
		descricao.push(value.descricao);
		um.push(value.um);
		qt.push(value.qt);
		qtmin.push(value.qtmin);
		vlr.push(value.vlr);
		vlrcmp.push(value.vlrcmp);
		st.push(value.st);
	});
	
//escrever os dados
	var sit = "";
	if(st == 1){
		sit = "<b>Situação:</b> <input type='radio' name='asit' value='1' id='st1' checked><label for='st1'>Ativo</label> <input type='radio' name='asit' value='0' id='st2'><label for='st2'>Inativo</label>";
	}
	if(st == 0){
		sit = "<b>Situação:</b> <input type='radio' name='asit' value='1' id='st1'><label for='st1'>Ativo</label> <input type='radio' name='asit' value='0' id='st2' checked><label for='st2'>Inativo</label>";
	}
	var vlr1 = parseFloat(vlr).toFixed(2);
	var vlrcmp1 = parseFloat(vlrcmp).toFixed(2);
	var form = "<br><form action='#' method='post'><input type='hidden' name='altera' value='1'><input type='hidden' name='idpdt' value='"+id+"'> <b>Quantidade Mínima:</b><input type='text' name='aqtmin' size='4' value='"+qtmin+"'><br><b>Valor de Venda:</b> <input type='text' class='vlr' size='5' name='avlr' value='"+vlr1+"'><br><b>Valor de Compra:</b> <input type='text' class='vlr' size='5' name='avlrcmp' value='"+vlrcmp1+"'><br>"+sit+" <div id='num'></div> <input type='submit' value='Gravar'></form>";

	document.getElementById("pdtEditaEstoque").innerHTML = "<img src='arquivos/icones/116.png' class='bt' style='position:absolute; top:5px; right:5px;' onclick='fechaEditaEstoque()'><span class='tt_pg'><b>Alteração de Produtos</b></span><br><b>Cod.:</b> "+id+" <b>Descrição:</b> "+descricao+"<br><b>Quantidade em Estoque:</b> "+qt+form;

unidades(um);

});

	document.getElementById("pdtEditaEstoque").style.display = "block";

function unidades(ua){
//fazer aqui um json para trazer todas as unidades, verifica uma a uma e compara com a que foi passado por parametro, se for igual esta deverá ser selecionada.
$.getJSON('retproduto.php?tp=2', function(atData){
	var unid = [];
	var idum = [];//id da unidade
	$(atData).each(function(key, value){
		unid.push(value.unid);
		idum.push(value.idum);
	});
	var unidades = "<select name='aum'>";
	unid.forEach(unids);
	function unids(un,i){
		if(un != ""){
		if(ua == unid[i]){
		unidades = unidades+"<option value='"+idum[i]+"' selected>"+unid[i]+"";
		}
		else if(ua != unid[i]){
		unidades = unidades+"<option value='"+idum[i]+"'>"+unid[i]+"";
		}
		}
	};
	unidades = unidades+"</select>";
	document.getElementById("num").innerHTML = "<b>Unidade de Medida:</b>"+unidades;
});
}

}

function fechaEditaEstoque(){
	escondeMascara();
	document.getElementById("pdtEditaEstoque").style.display = "none";
}
</script>
