<?php
include 'seguranca.php';
$c = $_REQUEST['c']; //chave de acesso
$l = $_REQUEST['l']; //linha do item na NF

/* 
	REGRAS DE AÇÃO
	vazio => retornar os dados do item
	1 => retornar os dados do centro de custo do item informado
	2 => retornar os centros de custos - receber parâmetro cat
	3 => gravar dados do centro de custo
*/
$a = $_REQUEST['a'];
$cat = $_REQUEST['cat'];


//listar dados do item na NF...
if (!$a) {
	if ($l != "T") {
		$sql = "select i.cProd, i.xProd, i.qCom, i.custoUnitario, n.nf, f.nome, coalesce(round(sum(cc.qt),2),0) as 'qtA' from nfe_produtos i
		inner join nfe_nfe n on i.chave = n.chave
		inner join est_fornecedores f on n.fornecedor = f.doc
		left join tb_custo_centrocusto cc on cc.chave = i.chave and cc.linha = i.linhaNf
		where i.chave = '$c' and i.linhaNf = '$l'";
	}
	if ($l == "T") {
		$sql = "select n.nf, f.nome from nfe_nfe n
			inner join est_fornecedores f on n.fornecedor = f.doc
			where n.chave = '$c'";
	}
	$di = $pdo->query($sql);
	$dd = $di->fetch();

	$inc_quantidade = $dd['qCom'] - $dd['qtA']; //quantidade de entrada - quantidade atribuída.

	//comparar retorno com a quantidade do item na NF
	if ($dd['qtA'] == 0) { //nada atribuído
		$bg = "rgba(255,0,0,0.5)";
	} else if ($dd['qtA'] > 0 && $dd['qtA'] < $dd['qCom']) { //parcialmente atribuído
		$bg = "rgba(255,255,0,0.5)";
	} else if ($inc_quantidade == 0) { //totalmente atribuído
		$bg = "rgba(50,205,50,0.5)";
	} else {
		$bg = "rgba(220,220,220,0.5)";
	}

	$pa = array(
		"nf" => $dd['nf'],
		"fornecedor" => $dd['nome'],
		"item" => $dd['cProd'] . " - " . $dd['xProd'],
		"quantidade" => $dd['qCom'],
		"valorUnitario" => "R$ " . number_format((float) $dd['custoUnitario'], 2, ",", "."),
		"inc_quantidade" => $inc_quantidade,
		"corLinha" => "$bg"
	);

	if ($pa) {
		echo json_encode($pa, JSON_PRETTY_PRINT);
	}
}

if ($a == 1) {
	$sql2 = "select v.id, cat.categoria, v.categoria as 'cat_id', c.centro, v.centro as 'centro_id', v.qt, round((v.valor * v.qt),2) as 'valor', v.catFin, v.subCatFin, cf.categoria as 't_catFin', scf.sub from tb_custo_centrocusto v
	inner join tb_catcentrocusto cat on v.categoria = cat.id
	left join tb_centrocusto c on v.centro = c.id
	left join fin_catfin cf on v.catFin = cf.id
	left join fin_subcatfin scf on v.subCatFin = scf.id
	where referencia = 'NF' and chave = '$c' and linha = '$l'";
	$rd = $pdo->query($sql2);
	$dataSet = array();
	$aux = 0;
	while ($r = $rd->fetch()) {

		//se for categoria padrão, pegar catFin e sub
		if ($r['cat_id'] == $config['centroEngerede']) {
			$r['centro'] = $r['t_catFin'] . "/" . $r['sub'];
		}

		$lk = "<i class='fa fa-pencil' onclick='editaCentro($r[id], $r[qt], $r[cat_id], \"$r[centro_id]\", \"$r[catFin]\", \"$r[subCatFin]\")' title='Editar Dados'></i>";

		$dataSet[$aux] = array(
			"DT_RowId" => "row_$aux",
			"Ref" => $r['id'],
			"Categoria" => $r['categoria'],
			"Centro" => $r['centro'],
			"Quantidade" => $r['qt'],
			"Custo" => "R$" . number_format($r['valor'], 2, ",", "."),
			"" => $lk
		);
		$aux++;
	}
	$dataSet = array("data" => $dataSet);
	//echo "<pre>";
	//print_r($dataSet);
	echo json_encode($dataSet, JSON_PRETTY_PRINT);
}

//retornar dados do centro de custo
if ($a == 2) {
	$sql2 = "select * from tb_centrocusto where categoria = '$cat' and st = '1'";
	$rd = $pdo->query($sql2);
	$cc = "";
	$aux = 0;
	while ($r = $rd->fetch()) {
		$cc .= "<option value='$r[id]'>$r[centro]</option>";
	}
	$retCentro = array("centro" => $cc);
	echo json_encode($retCentro, JSON_PRETTY_PRINT);
}

if ($a == 3) { //insere dados...

	$centro = $_POST['centro'];
	$categoria = $_POST['categoria'];
	$chave = $_POST['chave'];
	$linha = $_POST['linha'];

	$catFin = $_POST['catFin'];
	$subCatFin = $_POST['subCatFin'];

	$referencia = $_POST['referencia'];
	$inicio = $_POST['inicio'];
	$fim = $_POST['fim'];

	if ($referencia != "MO") {
		$quantidade = $_POST['quantidade'];
	}

	$descricao = $_POST['descricao'];
	$cod = $_POST['cod'];
	$manual = $_POST['manual'];
	$data = $_POST['data'];
	$valor = $_POST['valor'];

	$valor = str_replace(".", "", $valor ?? 0);
	$valor = (float) str_replace(",", ".", $valor ?? 0);

	$un = $_POST['un'];
	$nf = $_POST['nf'];
	$projeto = $_POST['projeto'];
	$garantia = $_POST['garantia'];
	if ($garantia == "GA") {
		$garantia = 1;
	}
	if ($garantia == "") {
		$garantia = 0;
	}


	if ($referencia == "MO" && $manual == "true") {
		$tipoHora = "1";
		$quantidade = $_POST['quantidade'];
	}

	if ($referencia == "MO" && $manual == "false") {
		$horarios = $_POST['horarios'];

		//echo "<pre>";
		//		foreach ($cod as $c) { //desabilitado quando removido mais de um colaboador por registro
		echo "AQ";
		foreach ($horarios as $h) {
			//$tipoHora = $_POST['tipoHora'];//pegar do array
			$tipoHora = $h['tipo']; //pegar do array
			$data = $h['data'];
			$inicio = $h['inicio'];
			$fim = $h['fim'];
			$valor = $h['valor'];
			$valor = str_replace(".", "", $valor ?? 0);
			$valor = (float) str_replace(",", ".", $valor ?? 0);
			//$quantidade = qtHr($inicio, $fim);//pegar do array
			$quantidade = qtHr($inicio, $fim); //pegar do array

			$rc = new custoCentro($pdo, $cod_us, $centro, $categoria, $descricao, $cod, $quantidade, $un, $valor, $referencia, $nf, $chave, $linha, $projeto, $manual, $data, $tipoHora, $inicio, $fim, $garantia, $catFin, $subCatFin);
			$r = $rc->registraCustoCento();
		}
	}
	//	}//desabilitado quando removido mais de um colaborador por registro
	if ($referencia != "MO" || $manual == "true") {
		$rc = new custoCentro($pdo, $cod_us, $centro, $categoria, $descricao, $cod, $quantidade, $un, $valor, $referencia, $nf, $chave, $linha, $projeto, $manual, $data, $tipoHora, $inicio, $fim, $garantia, $catFin, $subCatFin);
		$r = $rc->registraCustoCento();
	}
	//echo $r;
	//consulta para atualizar linha e NF;
	$al = new atNF($pdo, $chave);
	$r2 = $al->atualiza();
	echo $r2;

	$sl = "select descricao, valor, qt, round(qt*valor,2) as 'valorTot' from tb_custo_centrocusto where id = '$r'";
	$dl = $pdo->query($sl)->fetch();
	$log = "Linha #$r - <b>$dl[descricao]</b> - Valor Unit.: <b>R$$dl[valor]</b> - Qt.: <b>$dl[qt]</b> - Total: <b>R$$dl[valorTot]</b><br><br>Incluso custo";
	if ($projeto) {
		$gl = new logProjeto($pdo, $projeto, $cod_us, $log);
		echo $gl->registraLog();
		//echo "$log";
	}
}

if ($a == 4) { //atualizar
	/*
	id
	centro
	categoria
	qt
	*/

	$id = $_POST['id'];
	$centro = $_POST['centro'];
	$categoria = $_POST['categoria'];
	$quantidade = $_POST['quantidade'];
	$catFin = $_POST['catFin'];
	$subCatFin = $_POST['subCatFin'];
	//pegar chave se existir para atualizar
	$vc = $pdo->query("select chave from tb_custo_centrocusto where id = '$id'")->fetch();

	if ($quantidade) {
		$sql2 = "update tb_custo_centrocusto set
		centro = '$centro',
		categoria = '$categoria',
		qt = '$quantidade',
		catFin = '$catFin',
		subCatFin = '$subCatFin'
		where id = '$id'";
	}
	if (!$quantidade) { //vazia ou zero
		$sql2 = "delete from tb_custo_centrocusto where id = '$id'";
	}
	$rd = $pdo->query($sql2);

	//verificar se foi informado chave...
	if ($vc['chave']) { //se existir chave
		$al = new atNF($pdo, $vc['chave']);
		$r = $al->atualiza();
	}
}

if ($a == 5) { //atualizar via projetos...

	$i = $_REQUEST['i']; //id a ser alterado
	$o = $_REQUEST['projeto'];
	$remover = $_REQUEST['remover'];
	$manual = $_REQUEST['manual'];

	//pegar dados atuais
	$sql = "select *, date_format(data,'%Y-%m-%d') as 'data' from tb_custo_centrocusto where id = '$i'";

	$di = $pdo->query($sql);
	$dd = $di->fetch();

	$pa = array(
		"data" => $dd['data'],
		"descricao" => $dd['descricao'],
		"cod" => $dd['cod'],
		"qt" => number_format($dd['qt'], 2, ",", "."),
		"un" => $dd['un'],
		"valor" => number_format($dd['valor'], 2, ",", "."),
		"valorTot" => number_format($dd['qt'] * $dd['valor'], 2, ",", "."),
		"referencia" => $dd['referencia'],
		"tipoHora" => $dd['tipoHora'],
		"inicio" => $dd['inicio'],
		"fim" => $dd['fim'],
		"nf" => $dd['nf'],
		"chave" => $dd['chave'],
		"linha" => $dd['linha']
	);
	//	echo "REMOVER::: $remover";
	if ($remover == "true") {
		$log = "Removido custo: <b>$pa[descricao]</b> - Valor Unit.: <b>R$$pa[valor]</b> - Qt.: <b>$pa[qt]</b> - Total: <b>R$$pa[valorTot]</b>";
		//deletar
		$sql = "delete from tb_custo_centrocusto where id = '$i'";
		$rm = new regDados($pdo, $sql);
		echo $rm->registra();

		//gravar log
		$gl = new logProjeto($pdo, $o, $cod_us, $log);
		echo $gl->registraLog();
	}
	if ($remover == "false") {
		//se não for regra de remover, é apenas alterar... deve verificar quais são os campos que estão diferentes e atualizar apenas estes...
		$log = "Linha #$i<br><br>";
		//novos dados
		$referencia = $pa['referencia'];
		//		echo "$referencia";
		if ($referencia != "MO") {
		}
		$cod = $_POST['cod'];
		if ($referencia == "MO" && $manual == "false") {
			$quantidade = qtHr($_POST['inicio'], $_POST['fim']);
			$cod = $cod[0];
		}
		if ($referencia == "MO" && $manual == "true") {
			$quantidade = $_POST['quantidade'];
		}
		if ($referencia != "MO") {
			$quantidade = $_POST['quantidade'];
		}

		//$quantidade = number_format($quantidade ?? 0, 2, ".", "");

		$manual = $_POST['manual'];

		$valor = $_POST['valor'];
		$valor = str_replace(".", "", $valor);
		$valor = str_replace(",", ".", $valor);

		$valorTotal = ((float)$quantidade * (float) $valor);

		$nd = array(
			"data" => $_POST['data'],
			"descricao" => $_POST['descricao'],
			"cod" => $cod,
			"qt" => number_format((float)$quantidade, 2, ",", "."),
			"un" => $_POST['un'],
			"valor" => number_format((float) $valor ?? 0, 2, ",", "."),
			"valorTot" => number_format($valorTotal, 2, ",", "."),
			"referencia" => $_POST['referencia'],
			"tipoHora" => $_POST['tipoHora'],
			"inicio" => $_POST['inicio'],
			"fim" => $_POST['fim'],
			"nf" => $_POST['nf'],
			"chave" => $_POST['chave'],
			"linha" => $_POST['linha']
		);

		//			echo "<pre>";
		//pegar as diferenças
		$result = array_diff($nd, $pa);

		//echo "<pre>";
		//print_r($pa);
		//print_r($nd);
		//print_r($result);

		if ($nd['tipoHora'] != $pa['tipoHora']) {
			switch ($nd['tipoHora']) {
				case "1":
					$nvT = "Normal";
					break;
				case "1.":
					$nvT = "Extra 50%";
					break;
				case "2":
					$nvT = "Extra 100%";
					break;
			}
			switch ($pa['tipoHora']) {
				case "1":
					$anT = "Normal";
					break;
				case "1.":
					$anT = "Extra 50%";
					break;
				case "2":
					$anT = "Extra 100%";
					break;
			}
			$sql = "update tb_custo_centrocusto set tipoHora = '$nd[tipoHora]' where id = '$i'";
			$log .= "Alterado <b>tipoHora</b>  de <b>$anT</b> para <b>$nvT</b><br>";
			$pdo->query($sql);
		}
		foreach ($result as $k => $v) {
			if ($k != "valorTot") { //campo que não existe
				//echo $k . $v . "<br>";
				//criar o log e chamar a função para atualizar o campo correspondente

				if ($k == "chave") {
					$al = new atNF($pdo, $pa['chave']); //faz as contas pra chave anterior
					$r = $al->atualiza();
					echo $r;
				}
				if ($referencia != "MO") {
					if ($k == "qt" || $k == "valor") {
						echo "Qt 324: $v";
						$v = str_replace(".", "", $v);
						echo "Qt 326: $v";
						$v = str_replace(",", ".", $v);
						echo "Qt 328: $v";
						$v = number_format((float) $v ?? 0, 2, ".", "");
						echo "Qt 330: $v";
					}

					$sql = "update tb_custo_centrocusto set $k = '$v' where id = '$i'";
					$pdo->query($sql);
					$log .= "Alterado <b>$k</b>  de <b>$pa[$k]</b> para <b>$nd[$k]</b><br>";
				}
				if ($referencia == "MO") {
					if ($manual == "true") {
						$cod = "";
						if ($nd['cod'] != $pa['cod']) {
							$sql = "update tb_custo_centrocusto set cod = '' where id = '$i'";
							$log .= "Alterado <b>COD</b>  de <b>$pa[cod]</b> para <b></b><br>";
							$pdo->query($sql);
						}

						//alterar valor, quantidade e valorTot para padrão americano
						if ($k == "qt" || $k == "valor" || $k == "valorTot") {
							$v = str_replace(".", "", $v);
							$v = str_replace(",", ".", $v);
							$v = number_format((float) $v ?? 0, 2, ".", "");
						}

						$sql = "update tb_custo_centrocusto set $k = '$v' where id = '$i'";
						$log .= "Alterado <b>$k</b>  de <b>$pa[$k]</b> para <b>$nd[$k]</b><br>";
						$pdo->query($sql);
					}

					if ($manual == "false") {
						//	echo "NÃO MANUAL";
						if ($nd['cod'] != $pa['cod']) {
							$s = "select c.nome, cs.valorHora from tb_custoscolaboradores cs inner join tb_colaboradores c on cs.colaborador = c.id where c.id = '$nd[cod]'";
							$nvNome = $pdo->query($s)->fetch();
							$sql = "update tb_custo_centrocusto set descricao = '$nvNome[nome]', cod = '$nd[cod]' where id = '$i'";
							$log .= "Alterado <b>Descrição</b>  de <b>$pa[descricao]</b> para <b>$nvNome[nome]</b><br>";
							$pdo->query($sql);

							$valor = "$nvNome[valorHora]";
							if ($valor != $pa['valor']) {
								$sql = "update tb_custo_centrocusto set valor = '$valor' where id = '$i'";
								$log .= "Alterado <b>Valor</b>  de <b>$pa[valor]</b> para <b>$valor</b><br>";
								$pdo->query($sql);
							}
						}
						if ($nd['cod'] == $pa['cod']) {
							$s = "select c.nome, cs.valorHora from tb_custoscolaboradores cs inner join tb_colaboradores c on cs.colaborador = c.id where c.id = '$nd[cod]'";
							$nvNome = $pdo->query($s)->fetch();
							$nd['valor'] = "$nvNome[valorHora]";
						}
						if ($k == "qt" || $k == "valor") { //validado
							$v = str_replace(".", "", $v);
							$v = str_replace(",", ".", $v);
							$v = number_format((float) $v ?? 0, 2, ".", "");
						}
						$sql = "update tb_custo_centrocusto set $k = '$v' where id = '$i'";
						$log .= "Alterado <b>$k</b>  de <b>$pa[$k]</b> para <b>$nd[$k]</b><br>";
						//echo $sql;
						$pdo->query($sql);
					} //fim manual false
				}
				//				echo "$sql <br>";
			}
		}
		//atualizar log
		$gl = new logProjeto($pdo, $o, $cod_us, $log);
		echo $gl->registraLog();

		//atualizar status da linha e da NF

		$al = new atNF($pdo, $nd['chave']);
		$r = $al->atualiza();
		echo $r;
	}
}
//função para calcular quantidade para MO

if ($a == 6) { //insere dados da nota completa...

	$centro = $_POST['centro'];
	$categoria = $_POST['categoria'];
	$chave = $_POST['chave'];
	$linha = $_POST['linha'];

	$catFin = $_POST['catFin'];
	$subCatFin = $_POST['subCatFin'];

	$referencia = $_POST['referencia'];

	//deletar os vínculos que exisir da mesma NF?
	$pdo->query("delete from tb_custo_centrocusto where chave = '$chave'");
	$sql = "select * from nfe_produtos where chave = '$chave'";
	$lp = $pdo->query($sql);

	while ($l = $lp->fetch()) { //fazer uma busca para cada linha e pegar os dados necessários da NF

		$rc = new custoCentro($pdo, $cod_us, $centro, $categoria, $descricao, $cod, $l['qCom'], $un, $valor, $referencia, $nf, $chave, $l['linhaNf'], $projeto, $manual, $data, $tipoHora, $inicio, $fim, '0', $catFin, $subCatFin);
		$r = $rc->registraCustoCento();

		//echo $r;
		//consulta para atualizar linha e NF;
		$al = new atNF($pdo, $chave);
		$r2 = $al->atualiza();
		echo $r2;

		$sl = "select descricao, valor, qt, round(qt*valor,2) as 'valorTot' from tb_custo_centrocusto where id = '$r'";
		$dl = $pdo->query($sl)->fetch();
		$log = "Linha #$r - <b>$dl[descricao]</b> - Valor Unit.: <b>R$$dl[valor]</b> - Qt.: <b>$dl[qt]</b> - Total: <b>R$$dl[valorTot]</b><br><br>Incluso custo";
		if ($projeto) {
			$gl = new logProjeto($pdo, $projeto, $cod_us, $log);
			echo $gl->registraLog();
			//echo "$log";
		}
	}
}

function qtHr($h1, $h2)
{
	//pegar as horas e ver diferença em horas cheias

	$h1_v = explode(":", $h1);
	$h1_h = $h1_v[0];
	$h1_m = $h1_v[1];

	$h2_v = explode(":", $h2);
	$h2_h = $h2_v[0];
	$h2_m = $h2_v[1];

	//ver quantos minutos tem de diferença e converter depois
	$hi_1 = ($h1_h * 60) + $h1_m; //transforma em minutos
	$hi_2 = ($h2_h * 60) + $h2_m; //transforma em minutos

	//dif em minutos
	$dm = number_format(($hi_2 - $hi_1) / 60, 2, ".", "");
	return $dm;
}
