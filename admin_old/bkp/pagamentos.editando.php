<?php
/*
Alterações
 *03/02/2016{
	-ajustado para que ao informar percentual de desconto, calcule automático troco;
	-Somente mostrar opções de pagamento quando selecionado 'À Vista';
	-Mostrar em vermelho calor de troco, quando este for inferior a zero (faltou);
	-Mostrar campo "Tipo de Pagamento - Parcela 1", quando esta data for igual ao dia atual. Neste caso fazer todos os cálculos de pagamentos, com base no valor da parcela;
	-Ajustado para recalcular valores em caso de alteração de desconto depois de informado algum valor inicial;
	-
}
 *13/02/2016{
	-valta pegar dados para enviar pras tabelas;
}
 *03/04/2016{
 	-ajuste para inserir valores pagos na tb 'financeiro' quando finalizar (à vista);
		-ajustado. 
	}
 * 07/042016{
 * ajustar valor de cheque quando dividido em mais de 1x
 * }
 * 
 * 12/04/2016{
 * ajustado valores enviados por cheque.
 * ajustar valores informados em relatório financeiro;
 * }
 #21/05/2016{
	 -ajustado para calcular valores de parcelas quando aplicado desconto
	 -ajustado para poder receber pagamentos de vendas
 }
 #24/05/2016{
	 -ajsutar para não enviar vírgula em valores para financeiro
 }
 #26/05/2016{
	 -ajustar, ao informar data diferente de primeira, desabilitar todos os campos de cheque, dinheiro, etc;
 }
 #27/05/2016{
	 -criar função para chamar via ajax uma página onde deverá inserir no histórico as quantidades em compras
	 -ajustar para informar/agendar pagamento de compras
 }
*/

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if($nv_acesso > 2){
echo "<script type='text/javascript'>alert('Você não possui acesso para este módulo.');</script>";
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";
}
# DADOS PARA TABELAS #
$tp = $_REQUEST[tp];// tipço, se informar (1), ou agendar (2)
$cn = $_REQUEST[cn];//a qual consulta se refere
$vn = $_REQUEST[vn];//recebe este valor quando for venda // tipo continua sendo 1 informar e 2 agendar
$cm = $_REQUEST[cm];//adaptar métodos de pagamentos para informar pagamentos... nao esquecer de coocar como tipo de saída nestes pagamentos.

$salva = $_POST[salva];

if($salva == 1){
//abaixo funcão que vai chamar a página para atualizar hist[orico]
?>
<script>
function histPdt(){
   $.ajax({//chamar página para ajustar histórico
      url:'atvnd.php?a=8&v='+<?php echo $vn; ?>,//ação 8 - ajusta histórico
      complete: function (response) {
//alert(response.responseText);
	location.href = 'index.php?pg=v.venda';
      },
      error: function () {
         // alert('Erro');
      }
  });
}
</script>
<?php
$tipo = $_POST[tipo];//1-à vista, 2-2x, 3-3x,4-4x,5-5x;
$tipo_pg = $_POST[tipo_pg];//1-dinheiro, 2-cheque, 3-Crédito, 4-débito

if($tipo == 1){// se for pagamento a vista, dt1 recebe hoje
	$dtp1 = date('Y-m-d');
	if($tipo_pg == 1){
		$vp = $_POST[vlReal];//valor original- valor que deverá ser pago
	$vp = str_replace($vp,',','');
		if($cn!=""){//se atendimento/consulta
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vp','Pagamento à vista do atendimento $cn.','$cod_us','2',now(),'')");
	}//se consulta
	else if($vn!=""){//se venda
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vp','Pagamento à vista da Venda $vn.','$cod_us','2',now(),'')");
		echo "insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vp','Pagamento à vista da Venda $vn.','$cod_us','2',now(),'')";
		mysql_query("update vendas set st = 3 where id = $vn");
	}//se venda
	else if($cm!=""){//se compra
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'2','C$cm','$vp','Pagamento à vista da Compra $cm.','$cod_us','2',now(),'')");
		mysql_query("update compras set st = 3 where id = $vn");
	}//se compra
	}
}	
	if($tipo_pg == 2){
		$vp = $_POST[vl_cheque];//valor original- valor que deverá ser pago
		$dtck = $_POST[dt_cheque];//01 34 6789
		$dt_cheque = $dtck[6].$dtck[7].$dtck[8].$dtck[9]."-".$dtck[3].$dtck[4]."-".$dtck[0].$dtck[1];
		$nm_cheque = $_POST[nm_cheque];//nome cheque
		$num_cheque = $_POST[num_cheque];//numero cheque
		$doc_cheque = $_POST[doc_cheque];//documento
		$bc_cheque = $_POST[bc_cheque];//banco
		$ag_cheque = $_POST[ag_cheque];//agencia
		$ct_cheque = $_POST[ct_cheque];//conta
		$obs = "Cheque: ".$num_cheque."; Nome: ".$nm_cheque."; Doc.: ".$doc_cheque."; Banco: ".$bc_cheque."; Ag.: ".$ag_cheque."; Conta: ".$ct_cheque;
	if($cn!=""){//se atendimento/consulta	
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vp','Pagamento em cheque do atendimento $cn.','$cod_us','1','$dt_cheque','$obs')");
	}//se consulta
	else if($vn!=""){//se venda	
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vp','Pagamento em cheque da Venda $vn.','$cod_us','1','$dt_cheque','$obs')");
		mysql_query("update vendas set st = 3 where id = $vn");
	}//se venda
	}	
}//senão, recebe valores enviados
else if($tipo != 1){
	$stp1 = $_POST[dtp1];//01-34-6789
	$stp1 = $stp1[6].$stp1[7].$stp1[8].$stp1[9]."-".$stp1[3].$stp1[4]."-".$stp1[0].$stp1[1];
	$stp2 = $_POST[dtp2];
	$stp2 = $stp2[6].$stp2[7].$stp2[8].$stp2[9]."-".$stp2[3].$stp2[4]."-".$stp2[0].$stp2[1];
	$stp3 = $_POST[dtp3];
	$stp3 = $stp3[6].$stp3[7].$stp3[8].$stp3[9]."-".$stp3[3].$stp3[4]."-".$stp3[0].$stp3[1];
	$stp4 = $_POST[dtp4];
	$stp4 = $stp4[6].$stp4[7].$stp4[8].$stp4[9]."-".$stp4[3].$stp4[4]."-".$stp4[0].$stp4[1];
	$stp5 = $_POST[dtp5];
	$stp5 = $stp5[6].$stp5[7].$stp5[8].$stp5[9]."-".$stp5[3].$stp5[4]."-".$stp5[0].$stp5[1];
	if($tipo == 2){
		$vlp = $_POST[x2];
		$vlp1 = $vlp;
		$vlp2 = $vlp;
		$vlp3 = $vlp;
		$vlp4 = $vlp;
		$vlp5 = $vlp;
		$hoje = date("Y-m-d");
		if($cn!=""){//se atendimento/consulta
		if($stp1 != $hoje){
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 1/2 do atendimento $cn.','$cod_us','1','$stp1','')") or die("Erro.");
		}
		else if($stp1 == $hoje){
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Pagamento 1/2 do atendimento $cn.','$cod_us','2','$stp1','')") or die("Erro.");
		}
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 2/2 do atendimento $cn.','$cod_us','1','$stp2','')");
		}//se consulta
		else if($vn!=""){//se venda
		if($stp1 != $hoje){
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 1/2 da Venda $vn.','$cod_us','1','$stp1','')") or die("Erro.");
		}
		if($stp1 == $hoje){
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Pagamento 1/2 da Venda $vn.','$cod_us','2','$stp1','')") or die("Erro.");
		}
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 2/2 da Venda $vn.','$cod_us','1','$stp2','')");
		mysql_query("update vendas set st = 4 where id = $vn");
		}//se venda
	}
	if($tipo == 3){
		$vlp = $_POST[x3];
		$vlp1 = $vlp;
		$vlp2 = $vlp;
		$vlp3 = $vlp;
		$vlp4 = $vlp;
		$vlp5 = $vlp;
		$hoje = date("Y-m-d");
		if($stp1 != $hoje){
		if($cn!=""){//se atendimento/consulta
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 1/3 do atendimento $cn.','$cod_us','1','$stp1','')") or die("Erro.");
		}//se consulta
		if($vn!=""){//se venda
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 1/3 da Venda $vn.','$cod_us','1','$stp1','')") or die("Erro.");
		}//se venda
		}
		if($stp1 == $hoje){
		if($cn!=""){//se atendimento/consulta
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Pagamento 1/3 do atendimento $cn.','$cod_us','2','$stp1','')") or die("Erro.");
		}//se consulta
		if($vn!=""){//se venda
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Pagamento 1/3 da Venda $vn.','$cod_us','2','$stp1','')") or die("Erro.");
		}//se venda
		}
		if($cn!=""){//se atendimento/consulta
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 2/3 do atendimento $cn.','$cod_us','1','$stp2','')");
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 3/3 do atendimento $cn.','$cod_us','1','$stp3','')");
		}//se consulta/atendimento
		if($vn!=""){//se venda
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 2/3 da Venda $vn.','$cod_us','1','$stp2','')");
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 3/3 da Venda $vn.','$cod_us','1','$stp3','')");
		mysql_query("update vendas set st = 4 where id = $vn");
		}//se venda
	}
	if($tipo == 4){
		$vlp = $_POST[x4];
		$vlp1 = $vlp;
		$vlp2 = $vlp;
		$vlp3 = $vlp;
		$vlp4 = $vlp;
		$vlp5 = $vlp;
		if($stp1 != $hoje){//colocar regra para validar se está sendo para um agendamento ou para uma compra
		if($cn != ""){//verifica se é consulta
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 1/4 do atendimento $cn.','$cod_us','1','$stp1','')") or die("Erro.");
		}//fim se consulta
		if($vn != ""){//se venda
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 1/4 da Venda $vn.','$cod_us','1','$stp1','')") or die("Erro.");
		}//fim se venda
		}
		if($stp1 == $hoje){//colocar regra para validar se está sendo para um agendamento ou para uma compra
		if($cn != ""){//verifica se é consulta
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Pagamento 1/4 do atendimento $cn.','$cod_us','2','$stp1','')") or die("Erro.");
		}//fim se consulta
		if($vn != ""){//se venda
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Pagamento 1/4 da Venda $vn.','$cod_us','2','$stp1','')") or die("Erro.");
		}//fim se venda
		}
		if($cn != ""){//se for consulta
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 2/4 do atendimento $cn.','$cod_us','1','$stp2','')");
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 3/4 do atendimento $cn.','$cod_us','1','$stp3','')");
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 4/4 do atendimento $cn.','$cod_us','1','$stp4','')");
		}//fim se consulta
		if($vn != ""){//se for venda
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 2/4 da Venda $vn.','$cod_us','1','$stp2','')");
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 3/4 da Venda $vn.','$cod_us','1','$stp3','')");
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 4/4 da Venda $vn.','$cod_us','1','$stp4','')");
		mysql_query("update vendas set st = 4 where id = $vn");
		}//fim se venda
	}
	if($tipo == 5){
		$vlp = $_POST[x5];
		$vlp1 = $vlp;
		$vlp2 = $vlp;
		$vlp3 = $vlp;
		$vlp4 = $vlp;
		$vlp5 = $vlp;		
		if($stp1 != $hoje){
		if($cn!=""){//para atendimento
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 1/5 do atendimento $cn.','$cod_us','1','$stp1','')") or die("Erro.");
		}//fim se for consulta/atendimento
		if($vn!=""){//para venda
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 1/5 da Venda $vn.','$cod_us','1','$stp1','')") or die("Erro.");
		}//fim se for consulta/atendimento
		}
		if($stp1 == $hoje){
		if($cn!=""){//para atendimento
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Pagamento 1/5 do atendimento $cn.','$cod_us','2','$stp1','')") or die("Erro.");
		}//fim se for consulta/atendimento
		if($vn!=""){//para venda
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Pagamento 1/5 da Venda $vn.','$cod_us','2','$stp1','')") or die("Erro.");
		}//fim se for consulta/atendimento
		}
		if($cn!=""){//para consulta
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 2/5 do atendimento $cn.','$cod_us','1','$stp2','')");
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 3/5 do atendimento $cn.','$cod_us','1','$stp3','')");
		mysql_query("insert into financeiro 
		(data,tipo,'A$cn',tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','$vlp','Agendamento de pagamento 4/5 do atendimento $cn.','$cod_us','1','$stp4','')");
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','A$cn','$vlp','Agendamento de pagamento 5/5 do atendimento $cn.','$cod_us','1','$stp5','')");
		}//fim se for agendamento
		if($vn!=""){//para venda
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 2/5 da Venda $vn.','$cod_us','1','$stp2','')");
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 3/5 da Venda $vn.','$cod_us','1','$stp3','')");
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 4/5 da Venda $vn.','$cod_us','1','$stp4','')");
		mysql_query("insert into financeiro 
		(data,tipo,tipo2,valor,motivo,us,sit,dt_ag,obs) VALUES
		(now(),'1','V$vn','$vlp','Agendamento de pagamento 5/5 da Venda $vn.','$cod_us','1','$stp5','')");
		mysql_query("update vendas set st = 4 where id = $vn");
		}//fim se venda
	}
}//se parcelado
if($cn != ""){
 echo "<META http-equiv='refresh' content='0;URL=index.php'>";	
}
if($vn != ""){
	//chamar função via ajax, onde deverá apenas inserir no histórico de movimentação de itens, as quantidades vendidas por item
	 echo "
	 <script>
	 histPdt();
	 </script>
	 ";
}
}//fim salva

//mostrar dados da consulta
// colocar regra para chamar dados de venda, com valores, cliente, vendedor, e total de itens;
//usar variavel vn
if($vn != ""){//dados de venda
$dc = mysql_fetch_assoc(mysql_query("select v.id, v.st, c.nome, u.nome as 'vendedor', date_format(v.data,'%d/%m/%Y %H:%i') as 'data', c.id as 'idcli', c.end, c.num, c.compl, c.bairro, c.cidade, c.uf, sum(vnd.qt) as 'qt', sum(vnd.qt*vnd.vlu) as 'vltotal' from vendas v
inner join pacientes c on v.cliente = c.id
inner join usuarios u on v.vendedor = u.id
inner join vndpdt vnd on v.id = vnd.vnd
where v.id = $vn
"));
if($dc[st] == 3){//se for 3 (concluido), informar alerta que já foi pago... direcionar para página da venda em questão
	echo "<script>
	alert('Venda já concluída. Não pode informar mais de um pagamento.');
	location.href = 'index.php?pg=cn.venda&vn=$vn';
	</script>";
}
$vlvenda = number_format($dc[vltotal],'2','.','');
echo "<br>
<b>Venda Nº:</b> $dc[id]<br>
<b>Data:</b> $dc[data] <br>
<b>Cliente: </b>$dc[nome] <br>
<b>Atendente: </b>$dc[vendedor] <br>
<b>Qt. Produtos:</b> $dc[qt] <br>
<b>Valor:</b> R$$vlvenda<br>
<br>
";
}
else if($cm != ""){//dados de compra
$dc = mysql_fetch_assoc(mysql_query("select c.id, c.st, f.fornecedor, com.nome as 'comprador', date_format(c.data,'%d/%m/%Y %H:%i') as 'data', f.id as 'idfor', f.end, f.num, f.compl, f.bairro, f.cidade, f.uf, sum(cmp.qt) as 'qt', sum(cmp.qt*cmp.vlu) as 'vltotal' from compra c
inner join fornecedores f on c.fornecedor = f.id
inner join usuarios com on c.comprador = com.id
inner join cmppdt cmp on c.id = cmp.cmp
where c.id = $vn
"));
if($dc[st] == 3){//se for 3 (concluido), informar alerta que já foi pago... direcionar para página da venda em questão
	echo "<script>
	alert('Compra já concluída. Não pode informar mais de um pagamento.');
	location.href = 'index.php?pg=cn.compra&vn=$vn';
	</script>";
}
$vlcompra = number_format($dc[vltotal],'2','.','');
echo "<br>
<b>Compra Nº:</b> $dc[id]<br>
<b>Data:</b> $dc[data] <br>
<b>Fornecedor: </b>$dc[fornecedor] <br>
<b>Atendente: </b>$dc[comprador] <br>
<b>Qt. Produtos:</b> $dc[qt] <br>
<b>Valor:</b> R$$vlcompra<br>
<br>
";

if($tp == 1)
	informar($cm,$vlcompra);
if($tp == 2)
	agendar($cm);
}
		if($cn!=""){//se atendimento/consulta
$dc = mysql_fetch_assoc(mysql_query("select date_format(c.data,'%d/%m/%Y') as 'data', c.hr_inicio, c.hr_fim, cl.nome as 'cliente', a.nome as 'atendente', e.valor from consultas c
join atendentes a on c.atendente = a.id
join pacientes cl on c.paciente = cl.id
join especialidades e on c.especialidade = e.id
where c.id = '$cn'"));
echo "<br>
<b>Data:</b> $dc[data] ($dc[hr_inicio] - $dc[hr_fim])<br>
<b>Cliente: </b>$dc[cliente] <br>
<b>Atendente: </b>$dc[atendente] <br>
<b>Valor:</b> R$$dc[valor]<br>
<br>
";

if($tp == 1)
	informar($cn,$dc[valor]);
if($tp == 2)
	agendar($cn);
		}//se consulta
?>
<?php
function informar($cn,$vo){
echo "<span class='tt_pg'><b>Informar Pagamento</b></span><br><br>";

?>
<form name="av" id="av" action="#" method="post">
<input type="hidden" name="vo" id="vo" value="<?php echo "$vo"?>">
<input type="hidden" name="salva" value="1">
<b>Desconto (%):</b><input type="text" name="desc" id="desc" value="0" size="3" ONCHANGE="calculate()"><br><br>
<input type="radio" name="tipo" value="1" id="avs" required><label for="avs">À Vista R$<input type="text" size="6" disabled name="vp" id="vp" value="<?php echo "$vo"?>" style="background:#fff"></label> 
<input type="radio" name="tipo" value="2" id="pc2" ><label for="pc2">2x R$<input type="text" size="3" disabled id="x2" style="background:#fff" value="<?php echo number_format($vo/2,2,".",""); ?>"></label>
<input type="radio" name="tipo" value="3" id="pc3"><label for="pc3">3x R$<input type="text" size="3" disabled id="x3" style="background:#fff" value="<?php echo number_format($vo/3,2,".","");?>"></label>
<input type="radio" name="tipo" value="4" id="pc4"><label for="pc4">4x R$<input type="text" size="3" disabled id="x4" style="background:#fff" value="<?php echo number_format($vo/4,2,".","");?>"></label>
<input type="radio" name="tipo" value="5" id="pc5"><label for="pc5">5x R$<input type="text" size="3" disabled id="x5" style="background:#fff" value="<?php echo number_format($vo/5,2,".","");?>"></label>
<input type="hidden" name="x2" value="<?php echo number_format($vo/2,2,".",""); ?>">
<input type="hidden" name="x3" value="<?php echo number_format($vo/3,2,".",""); ?>">
<input type="hidden" name="x4" value="<?php echo number_format($vo/4,2,".",""); ?>">
<input type="hidden" name="x5" value="<?php echo number_format($vo/5,2,".",""); ?>">
<br> 
<span id="par2" style="display:none">
<?php
//datas 
$hoje=date("d/m/Y");
$hj_calc = date("Y-m-d");
$d = 2592000;
$p2 = date("d/m/Y",strtotime($hj_calc)+($d));
$p3 = date("d/m/Y",strtotime($hj_calc)+($d*2));
$p4 = date("d/m/Y",strtotime($hj_calc)+($d*3));
$p5 = date("d/m/Y",strtotime($hj_calc)+($d*4));

echo strtotime("Y-m-d",strtotime($hj_calc));
?>
<b>1ª Parcela</b>:<input type="text" class="date" id="dtp1" onchange="data_pg('<?php echo $hoje;?>')" required name="dtp1" value="<?php echo $hoje?>"><br>
<b>2ª Parcela</b>:<input type="text" class="date" required name="dtp2" value="<?php echo $p2?>"><br>
</span>
<span id="par3" style="display:none">
<b>3ª Parcela</b>:<input type="text" class="date" required name="dtp3" value="<?php echo $p3?>"><br>
</span>
<span id="par4" style="display:none">
<b>4ª Parcela</b>:<input type="text" class="date" required name="dtp4" value="<?php echo $p4?>"><br>
</span>
<span id="par5" style="display:none">
<b>5ª Parcela</b>:<input type="text" class="date" required name="dtp5" value="<?php echo $p5?>"><br>
</span>
<div id="tipos_pagamento" style="display:none"><br>
<b>Tipo de Pagamento <span id="ip1" style="display:none;"> - Parcela 1</span></b><br>
<input type="radio" name="tipo_pg" id="tp1" value="1" required><label for="tp1"><b>Dinheiro</b></label> 
<input type="radio" name="tipo_pg" id="tp2" value="2"><label for="tp2"><b>Cheque</b></label> 
<input type="radio" name="tipo_pg" id="tp3" value="3"><label for="tp3"><b>Crédito</b></label> 
<input type="radio" name="tipo_pg" id="tp4" value="4"><label for="tp4"><b>Débito</b></label> 
<br><br>
<span id="dt_cheque" style="display:none">
<b>Valor:</b> R$<input type="text" id="vl_ck" value="<?php echo $vo; ?>" size="5" style="background: transparent;" ><br>
<input type="hidden" name="vl_cheque" id="vl_cheque" value="<?php echo "$vo"?>">
<b>Cheque bom Para: </b><input type="text" class="date" name="dt_cheque" id="dt_cheque1" size="11" required><br>
<b>Nome:</b> <input type="text" name="nm_cheque" id="nm_cheque" required size="25" /><br>
<b>CPF/CNPJ:</b> <input type="text" name="doc_cheque" id="doc_cheque" required size="15" /><br>
<b>Nº. Cheque:</b> <input type="text" name="num_cheque" id="num_cheque" required size="9" /><br>
<b>Banco:</b> <input type="text" name="bc_cheque" id="bc_cheque" required size="3" />
<b>Agência:</b> <input type="text" name="ag_cheque" id="ag_cheque" required size="6" />
<b>Conta:</b> <input type="text" name="ct_cheque" id="ct_cheque" required size="6" /><br>
<input type="submit" value="Finalizar">
</span>
<span id="calc_troco" style="display:none">
<b>Total:</b> <input type="text" name="total_pagar" id="total_pagar" size="11" value="<?php echo "$vo"?>" disabled>
<input type="hidden" name="vlReal" id="pago" value="<?php echo "$vo"?>"><br>
<b>Recebido:</b> <input type="text" name="total_recebido" ONCHANGE="calc_troco()" id="total_rec" size="11" class="vlr" disabled><br>
<b>Troco:</b> <input type="text" name="total_troco" id="total_troco" size="11" disabled><br><br>
<input type="submit" value="Finalizar" onclick="return valida_pagamento()">
</span>
</div>
<input type="submit" value="Finalizar" id="fin2" style="display:none">
</form>
<?php
echo "<script language='javascript'>carregou();</script>";
}

function agendar($cn){
echo "<span class='tt_pg'><b>Agendar Pagamento</b></span><br><br>";
?>
<form action="#" method="post">

</form>
<?php
}
?>
<!--
colocar todos os campos dos inputs abaixo
-->
<script type='text/javascript'>//<![CDATA[
window.onload=function(){
//document.getElementById('av').onchange = function() {
  //  document.getElementById('tipos_pagamento').style.display = "block";
//};

document.getElementById('avs').onchange = function() {
	document.getElementById('par2').style.display = "none";
	document.getElementById('par3').style.display = "none";
	document.getElementById('par4').style.display = "none";
	document.getElementById('par5').style.display = "none";
	document.getElementById('tipos_pagamento').style.display = "block";
	document.getElementById("ip1").style.display = "block";
	document.getElementById('total_pagar').value = document.getElementById('vp').value;
	document.getElementById('vl_ck').value = document.getElementById('vp').value;
	document.getElementById('vl_cheque').value = document.getElementById('vp').value;
	reseta_valores();
};

document.getElementById('pc2').onchange = function() {
	document.getElementById('par2').style.display = "block";
	document.getElementById('par3').style.display = "none";
	document.getElementById('par4').style.display = "none";
	document.getElementById('par5').style.display = "none";
	document.getElementById('tipos_pagamento').style.display = "block";
	document.getElementById("ip1").style.display = "block";
	document.getElementById('total_pagar').value = document.getElementById('x2').value;
	document.getElementById('vl_ck').value = document.getElementById('x2').value;
	document.getElementById('vl_cheque').value = document.getElementById('x2').value;
	reseta_valores();
	};

document.getElementById('pc3').onchange = function() {
	document.getElementById('par2').style.display = "block";
	document.getElementById('par3').style.display = "block";
	document.getElementById('par4').style.display = "none";
	document.getElementById('par5').style.display = "none";
	document.getElementById('tipos_pagamento').style.display = "block";
	document.getElementById("ip1").style.display = "block";
	document.getElementById('total_pagar').value = document.getElementById('x3').value;
	document.getElementById('vl_ck').value = document.getElementById('x3').value;
	document.getElementById('vl_cheque').value = document.getElementById('x3').value;
	reseta_valores();
};

document.getElementById('pc4').onchange = function() {
	document.getElementById('par2').style.display = "block";
	document.getElementById('par3').style.display = "block";
	document.getElementById('par4').style.display = "block";
	document.getElementById('par5').style.display = "none";
	document.getElementById('tipos_pagamento').style.display = "block";
	document.getElementById("ip1").style.display = "block";
	document.getElementById('total_pagar').value = document.getElementById('x4').value;
	document.getElementById('vl_ck').value = document.getElementById('x4').value;
	document.getElementById('vl_cheque').value = document.getElementById('x4').value;
	reseta_valores();
};

document.getElementById('pc5').onchange = function() {
	document.getElementById('par2').style.display = "block";
	document.getElementById('par3').style.display = "block";
	document.getElementById('par4').style.display = "block";
	document.getElementById('par5').style.display = "block";
	document.getElementById('tipos_pagamento').style.display = "block";
	document.getElementById("ip1").style.display = "block";
	document.getElementById('total_pagar').value = document.getElementById('x5').value;
	document.getElementById('vl_ck').value = document.getElementById('x5').value;
	document.getElementById('vl_cheque').value = document.getElementById('x5').value;
	reseta_valores();
};

document.getElementById('tp1').onchange = function() {
    document.getElementById('dt_cheque1').disabled = true;
	document.getElementById('dt_cheque').style.display = "none";
	document.getElementById('nm_cheque').disabled = true;
	document.getElementById('num_cheque').disabled = true;
	document.getElementById('doc_cheque').disabled = true;
	document.getElementById('bc_cheque').disabled = true;
	document.getElementById('ag_cheque').disabled = true;
	document.getElementById('ct_cheque').disabled = true;
	document.getElementById('total_rec').disabled = false;
	document.getElementById('calc_troco').style.display = "block";
};

document.getElementById('tp2').onchange = function() {
    document.getElementById('dt_cheque1').disabled = false;
	document.getElementById('dt_cheque').style.display = "block";
	document.getElementById('calc_troco').style.display = "none";
	document.getElementById('nm_cheque').disabled = false;
	document.getElementById('num_cheque').disabled = false;
	document.getElementById('doc_cheque').disabled = false;
	document.getElementById('bc_cheque').disabled = false;
	document.getElementById('ag_cheque').disabled = false;
	document.getElementById('ct_cheque').disabled = false;
};

document.getElementById('tp3').onchange = function() {
    document.getElementById('dt_cheque1').disabled = true;
	document.getElementById('dt_cheque').style.display = "none";
	document.getElementById('calc_troco').style.display = "none";
	document.getElementById('nm_cheque').disabled = true;
	document.getElementById('num_cheque').disabled = true;
	document.getElementById('doc_cheque').disabled = true;
	document.getElementById('bc_cheque').disabled = true;
	document.getElementById('ag_cheque').disabled = true;
	document.getElementById('ct_cheque').disabled = true;
};

document.getElementById('tp4').onchange = function() {
    document.getElementById('dt_cheque1').disabled = true;
	document.getElementById('dt_cheque').style.display = "none";
	document.getElementById('calc_troco').style.display = "none";
	document.getElementById('nm_cheque').disabled = true;
	document.getElementById('num_cheque').disabled = true;
	document.getElementById('doc_cheque').disabled = true;
	document.getElementById('bc_cheque').disabled = true;
	document.getElementById('ag_cheque').disabled = true;
	document.getElementById('ct_cheque').disabled = true;
};

//document.getElementById('pc').onchange = function() {
  //  document.getElementById('av1').disabled = this.checked;
//};

}//]]> 
</script>
<!--script language='javascript'>
function esconde(av1){
    document.getElementById('av1').display = block;
	alert("Opa");
};
</script-->

<script LANGUAGE="JavaScript">
function data_pg(hoje){
	/*esta função pega o valor da data informada, e verifica se valor é igual a hoje.
	Se data for igual a hoje, atribui valor da parcela no campo a ser pago, e habilita para informar os pagamentos desta parcela;
	o dia atual e passado por parametro na função
	*/
	var dt_inf = document.getElementById("dtp1").value;//pega valor informado na data
	var anoat = hoje.substr(6,4);
	var anoin = dt_inf.substr(6,4);
	var mesat = hoje.substr(3,2);
	var mesin = dt_inf.substr(3,2);
	var diaat = hoje.substr(0,2);
	var diain = dt_inf.substr(0,2);
	var alt = 0;
	if(anoat > anoin){
		var alt = 1;
		document.getElementById("dtp1").focus();
	}
	if(mesat > mesin && anoat >= anoin){
var alt = 1;
		document.getElementById("dtp1").focus();
	}
	if(diaat > diain && mesat >= mesin && anoat >= anoin){
var alt = 1;
		document.getElementById("dtp1").focus();
	}
	if(alt == 1){
		alert("Data não pode ser inferior a data atual");
	}
	if(dt_inf == ""){
		alert("Data não pode ser vazia.\n Para pagar agora, informe a data atual "+hoje);
		document.getElementById("dtp1").focus();
	}
	if(hoje != dt_inf){
		document.getElementById("tipos_pagamento").style.display = "none";
		document.getElementById("ip1").style.display = "none";
		document.getElementById("fin2").style.display = "block";
		document.getElementById("total_rec").value = "0.00";
		document.getElementById("tp1").disabled = true;
		document.getElementById("tp2").disabled = true;
		document.getElementById("tp3").disabled = true;
		document.getElementById("tp4").disabled = true;
			//desabilitar cheques
		document.getElementById('dt_cheque1').disabled = true;
		document.getElementById('dt_cheque').style.display = "none";
		document.getElementById('nm_cheque').disabled = true;
		document.getElementById('num_cheque').disabled = true;
		document.getElementById('doc_cheque').disabled = true;
		document.getElementById('bc_cheque').disabled = true;
		document.getElementById('ag_cheque').disabled = true;
		document.getElementById('ct_cheque').disabled = true;
		document.getElementById('total_rec').disabled = false;
		document.getElementById('calc_troco').style.display = "block";
		}
	if(hoje == dt_inf){
		document.getElementById("tipos_pagamento").style.display = "block";
		document.getElementById("ip1").style.display = "block";
		document.getElementById("fin2").style.display = "none";
		document.getElementById("tp1").disabled = false;
		document.getElementById("tp2").disabled = false;
		document.getElementById("tp3").disabled = false;
		document.getElementById("tp4").disabled = false;
	}
	//calcular e informar datas para próximos meses
	// ver maneira de fazer os calculos e atribuir para os campos corretos
   <?php 
	/*$aux = "document.getElementById('dtp1').value";
	//echo "$aux";
//   $hj_calc = date('$aux');
$d = 2592000;
$p2 = date('d/m/Y',strtotime($hj_calc)+($d));
$p3 = date('d/m/Y',strtotime($hj_calc)+($d*2));
$p4 = date('d/m/Y',strtotime($hj_calc)+($d*3));
$p5 = date('d/m/Y',strtotime($hj_calc)+($d*4));
echo "$p5";
*/?>
}

function calculate()
{
var vlsub = (document.av.vo.value) - (((document.av.desc.value)/100) * (document.av.vo.value));
var nvalor = parseFloat(vlsub.toFixed(2));
document.av.vp.value = nvalor;
document.av.total_pagar.value = nvalor;
document.av.pago.value = nvalor;
document.getElementById('x2').value = parseFloat((nvalor/2).toFixed(2));
document.getElementById('x3').value = parseFloat((nvalor/3).toFixed(2));
document.getElementById('x4').value = parseFloat((nvalor/4).toFixed(2));
document.getElementById('x5').value = parseFloat((nvalor/5).toFixed(2));
calc_troco();
var rst = false;
if(document.getElementById("avs").checked){
	var rst = true;
}
if(document.getElementById("pc2").checked){
	var rst = true;
}
if(document.getElementById("pc3").checked){
	var rst = true;
}
if(document.getElementById("pc4").checked){
	var rst = true;
}
if(document.getElementById("pc5").checked){
	var rst = true;
}
if(rst == true){
		document.getElementById("avs").checked = false;
		document.getElementById("pc2").checked = false;
		document.getElementById("pc3").checked = false;
		document.getElementById("pc4").checked = false;
		document.getElementById("pc5").checked = false;
			document.getElementById('par2').style.display = "none";
	document.getElementById('par3').style.display = "none";
	document.getElementById('par4').style.display = "none";
	document.getElementById('par5').style.display = "none";
	document.getElementById('tipos_pagamento').style.display = "none";
	}
	}
function calc_troco()
{
	var tt_pagar = document.av.total_pagar.value;
	var tt_pago = document.av.total_rec.value;	
	var vl_troco = (tt_pago) - (tt_pagar);
	var nvalor2 = parseFloat(vl_troco.toFixed(2));
	document.av.total_troco.value = nvalor2;
	if(nvalor2 < 0){
		document.getElementById("total_troco").style.color = "#f00";
	}
	if(nvalor2 >= 0){
		document.getElementById("total_troco").style.color = "#000";
	}
}
function reseta_valores(){
	calc_troco();
	document.getElementById("dtp1").value = '<?php $hoje=date("d/m/Y"); echo $hoje; ?>';
}
function valida_pagamento(){
	var pagar = document.av.total_pagar.value;
	var rec = document.av.total_rec.value;
	if(rec < pagar){
		alert("Valor pago não pode ser menor que valor a pagar.");
		document.getElementById('total_rec').focus();
		return false;
	}
}
</script>