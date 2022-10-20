<?php

/**
 * Obrigatoriamente zip deve ser xml.zip
 */

include "../seguranca.php";
//include "../classes.php";

$acao = $_POST['a'];

$dir = "xml";

if ($acao == "verificaAnterior") {
	verificaAnterior($dir);
}
if ($acao == "limpa") {
	limpaDados($dir);
}

if ($acao == "analisa") {
	analisa($dir);
}

if ($acao == "descompacta") {
	descompacta($dir);
}

if ($acao == "processa") {
	lerArquivos($pdo, $dir);
}

if ($acao == "lerArquivos") {
	lerArquivos($pdo, $dir);
}

exit();

function verificaAnterior($dir)
{
	if (file_exists($dir)) {
		echo "0 .==. <i class='fa fa-forklift'></i> Limpando os dados...<br>";
	} else {
		echo "1 .==. <i class='fa fa-thumbs-up'></i> Preparado para importação...<br>";
	}
}
function limpaDados($dir)
{
	if ($dd = opendir($dir)) {

		// ENQUANTO HOUVER ARQUIVO DENTRO DO DIRETÓRIO REMOVE 
		while (false !== ($Arq = readdir($dd))) {

			//EVITA LEITURA DE "." E ".."
			if ($Arq != "." && $Arq != "..") {
				unlink($dir . "/" . $Arq);
				echo "<i class='fa fa-trash'></i> Arquivo $Arq deletado...<br>";
			}
		}
		closedir($dd);
	}
	rmdir($dir);
	echo "<i class='fa fa-trash'></i> Diretório $dir deletado...<br>";
	echo "<i class='fa fa-thumbs-up'></i> Preparado para importação...<br>";
}

function analisa($dir)
{
	//validar se nome do arquivo é xml.rar
	//echo getcwd();
	if ($dd = opendir(getcwd())) { //identificar online

		// ENQUANTO HOUVER ARQUIVO DENTRO DO DIRETÓRIO REMOVE 
		$existe = "false";
		while (false !== ($Arq = readdir($dd))) {
			//EVITA LEITURA DE "." E ".."
			//echo $Arq;
			if ($Arq != "." && $Arq != "..") {
				if ($Arq == "xml.zip") {
					$existe = "true";
				}
				//				echo "<i class='fa fa-trash'></i> Arquivo $Arq deletado...<br>";
			}
		}
		closedir($dd);
	}
	if ($existe == "true") {
		echo "1 .==. <i class='fa fa-thumbs-up'></i> Preparado para importação...<br>";
	} else {
		echo "0 .==. <i class='fa-light fa-circle-exclamation'></i> Arquivo não encontrado...<br>";
	}
}

function descompacta($dir)
{
	$arquivo = getcwd() . '/' . $dir . '.zip';
	$destino = getcwd() . "/";

	$zip = new ZipArchive;
	$zip->open($arquivo);
	if ($zip->extractTo($destino) == TRUE) {
		echo "1 .==. <i class='fa fa-face-smile-wink'></i> Arquivo descompactado...<br>";
	} else {
		echo "0 .==. <i class='fa fa-face-sad-tear'></i> O Arquivo não pode ser descompactado...<br>";
	}
	$zip->close();
}

function lerArquivos($pdo, $dir)
{
	if ($dd = opendir($dir)) {

		// ENQUANTO HOUVER ARQUIVO DENTRO DO DIRETÓRIO CONTINUA LENDO 
		while (false !== ($Arq = readdir($dd))) {
			//EVITA LEITURA DE "." E ".."
			if ($Arq != "." && $Arq != "..") {
				echo "<i class='fa-solid fa-file-lines'></i> $Arq <br>";
				registra($pdo, "xml/" . $Arq);
			}
		}
		closedir($dd);
		echo "<i class='fa-solid fa-check-double'></i> Concluído. <br>";
	}
}

function registra($pdo, $arquivo)
{

	$docXml = "$arquivo";
	$xml = simplexml_load_file($arquivo); /* Lê o arquivo XML e recebe um objeto com as informações */
	//ler como string
	$xmlStr = file_get_contents($arquivo);
	#echo "<pre>";
	#print_r($xml);
	$tipo = "";
	if ($xml->NFe->infNFe) {
		$tipo = "PDT";
	} else {
		$tipo = "SRV";
	}

	if($tipo == "SRV"){
		echo "<span class='txt-vermelho'><i class='fa fa-file-exclamation'></i> XML DE SERVIÇO ...</span><br>";
	}

	if ($tipo == "PDT") {
		$chave = substr($xml->NFe->infNFe->attributes()->Id, 3);

		//verificar se existe na base de dados.. se existir, já foi incluso uma vez
		echo "<i class='fa fa-face-thinking'></i> Verificando se foi registrado anteriormente ...<br>";
		$existe = $pdo->query("select * from nfe_nfe where chave = '$chave'")->rowCount();

		if ($existe == 0) {
			echo "<i class='fa fa-thumbs-up'></i> Primeira importação...<br>";
		}
		if ($existe == 1) {
			echo "<span class='txt-vermelho'><i class='fa fa-thumbs-down'></i> Duplicado...</span><br>";
		}

		if ($existe == 0) { //apenas processar se não foi anteriormente
			$serie = $xml->NFe->infNFe->ide->serie;
			$nf = $xml->NFe->infNFe->ide->nNF;
			$emissao = convertDate($xml->NFe->infNFe->ide->dhEmi) . " " . convertTime($xml->NFe->infNFe->ide->dhEmi);
			$emissaoReg = convertDate2($xml->NFe->infNFe->ide->dhEmi) . " " . convertTime($xml->NFe->infNFe->ide->dhEmi);
			$natOP = $xml->NFe->infNFe->ide->natOp;
			$autorizacao = $xml->protNFe->infProt->nProt;
			$dt_autorizacao = convertDate($xml->protNFe->infProt->dhRecbto) . " " . convertTime($xml->protNFe->infProt->dhRecbto);
			$dt_autorizacaoReg = convertDate2($xml->protNFe->infProt->dhRecbto) . " " . convertTime($xml->protNFe->infProt->dhRecbto);
			$fornecedor = $xml->NFe->infNFe->emit->CNPJ;

			/* VALORES */
			$vBC = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vBC ?? 0, 2, ".", "");
			$vICMS = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vICMS ?? 0, 2, ".", "");
			$vICMSDeson = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vICMSDeson ?? 0, 2, ".", "");
			$vFCPUFDest = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vFCPUFDest ?? 0, 2, ".", "");
			$vICMSUFDest = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vICMSUFDest ?? 0, 2, ".", "");
			$vICMSUFRemet = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vICMSUFRemet ?? 0, 2, ".", "");
			$vFCP = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vFCP ?? 0, 2, ".", "");
			$vBCST = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vBCST ?? 0, 2, ".", "");
			$vST = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vST ?? 0, 2, ".", "");
			$vFCPST = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vFCPST ?? 0, 2, ".", "");
			$vFCPSTRet = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vFCPSTRet ?? 0, 2, ".", "");
			$vProd = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vProd ?? 0, 2, ".", "");
			$vFrete = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vFrete ?? 0, 2, ".", "");
			$vSeg = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vSeg ?? 0, 2, ".", "");
			$vDesc = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vDesc ?? 0, 2, ".", "");
			$vII = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vII ?? 0, 2, ".", "");
			$vIPI = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vIPI ?? 0, 2, ".", "");
			$vIPIDevol = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vIPIDevol ?? 0, 2, ".", "");
			$vPIS = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vPIS ?? 0, 2, ".", "");
			$vCOFINS = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vCOFINS ?? 0, 2, ".", "");
			$vOutro = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vOutro ?? 0, 2, ".", "");
			$vNF = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vNF ?? 0, 2, ".", "");
			$vTotTrib = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vTotTrib ?? 0, 2, ".", "");


			//grava dados da NF
			$reg = new regNf($pdo, $chave, $serie, $nf, $emissaoReg, $fornecedor, $autorizacao, $dt_autorizacaoReg,  $vBC, $vICMS, $vICMSDeson, $vFCPUFDest, $vICMSUFDest, $vICMSUFRemet, $vFCP, $vBCST, $vST, $vFCPST, $vFCPSTRet, $vProd, $vFrete, $vSeg, $vDesc, $vII, $vIPI, $vIPIDevol, $vPIS, $vCOFINS, $vOutro, $vNF, $vTotTrib, $xmlStr);
			$r = $reg->registraNota();
			echo $r;
			echo "<i class='fa fa-database'></i> Gravando dados do xml<br>";
			## ATUALIZAR STATUS DA NF PARA O PADRÃO ATENDIDO ##
			$idReg = $pdo->lastInsertId();
			$pdo->query("update nfe_nfe set pendencia = '0' where id = '$idReg'");
			echo "<i class='fa fa-database'></i> Atualizando status<br>";

			/* fonecedor */
			$emit_xNome = $xml->NFe->infNFe->emit->xNome;
			$emit_xFant = $xml->NFe->infNFe->emit->xFant;
			$emit_CNPJ = $xml->NFe->infNFe->emit->CNPJ;
			$emit_xLgr = $xml->NFe->infNFe->emit->enderEmit->xLgr;
			$emit_nro = $xml->NFe->infNFe->emit->enderEmit->nro;
			$emit_xCpl = $xml->NFe->infNFe->emit->enderEmit->xCpl;
			$emit_xBairro = $xml->NFe->infNFe->emit->enderEmit->xBairro;
			$emit_xMun = $xml->NFe->infNFe->emit->enderEmit->xMun;
			$emit_cMun = $xml->NFe->infNFe->emit->enderEmit->cMun;
			$emit_UF = $xml->NFe->infNFe->emit->enderEmit->UF;
			$emit_CEP = $xml->NFe->infNFe->emit->enderEmit->CEP;
			$emit_xPais = $xml->NFe->infNFe->emit->enderEmit->xPais;
			$emit_cPais = $xml->NFe->infNFe->emit->enderEmit->cPais;
			$emit_fone = $xml->NFe->infNFe->emit->enderEmit->fone;
			$emit_IE = $xml->NFe->infNFe->emit->IE;
			$emit_CRT = $xml->NFe->infNFe->emit->CRT;

			/*Grava dados do fornecedor */

			$regForn = new regFornecedor($pdo, $emit_CNPJ, $emit_xFant, $emit_xNome, $emit_xLgr, $emit_nro, $emit_xCpl, $emit_xBairro, $emit_cMun, $emit_xMun, $emit_UF, $emit_CEP, $emit_xPais, $emit_cPais, $emit_fone, $emit_IE, $emit_CRT);
			$rf = $regForn->registraFornecedor();
			echo $rf;
			echo "<i class='fa fa-database'></i> Gravando fornecedor<br>";

			/* Dados dos itens para base de dados */

			$seq = 0;
			echo "<i class='fa fa-database'></i> Gravando itens<br>";
			foreach ($xml->NFe->infNFe->det as $item) {
				$seq++;

				$linhaNf = $item->attributes()->nItem;
				//   echo $linhaNf;
				$cProd = $item->prod->cProd;
				$cEAN = $item->prod->cEAN;
				$cBarra = $item->prod->cBarra;
				$xProd = $item->prod->xProd;
				$NCM = $item->prod->NCM;
				$CST = $item->imposto->ICMS->ICMS00->CST;
				$cBenef = $item->prod->cBenef;
				$CFOP = $item->prod->CFOP;
				$uCom = $item->prod->uCom;
				$qCom = $item->prod->qCom;
				$vUnCom = number_format((float) $item->prod->vUnCom ?? 0, 4, '.', '');
				$vProd = number_format((float) $item->prod->vProd ?? 0, 4, '.', '');
				$cEANTrib = $item->prod->cEANTrib;
				$cBarraTrib = $item->prod->cBarraTrib;
				$uTrib = $item->prod->uTrib;
				$qTrib = $item->prod->qTrib;
				$vUnTrib = $item->prod->vUnTrib;
				$vFreteItem = number_format((float) $item->prod->vFrete ?? 0, 2, ".", "");
				$vSeg = number_format((float) $item->prod->vSeg ?? 0, 2, ".", "");
				$vDesc = number_format((float) $item->prod->vDesc ?? 0, 2, ".", "");
				$vOutro = number_format((float) $item->prod->vOutro ?? 0, 2, ".", "");
				$indTot = $item->prod->indTot;
				$xPed = $item->prod->xPed;
				$nItemPed = $item->prod->nItemPed;

				$vBC_item = $item->imposto->ICMS->ICMS00->vBC;
				//    echo "BV $vBC_item";
				$icms00 = $item->imposto->ICMS->ICMS00;
				$ICMSST = $item->imposto->ICMS->ICMSST;
				$icms10 = $item->imposto->ICMS->ICMS10;
				$icms20 = $item->imposto->ICMS->ICMS20;
				$icms30 = $item->imposto->ICMS->ICMS30;
				$icms40 = $item->imposto->ICMS->ICMS40;
				$icms50 = $item->imposto->ICMS->ICMS50;
				$icms51 = $item->imposto->ICMS->ICMS51;
				$icms60 = $item->imposto->ICMS->ICMS60;
				$ICMSSN101 = $item->imposto->ICMS->ICMSSN101;
				$ICMSSN102 = $item->imposto->ICMS->ICMSSN102;

				if (!empty($ICMSST)) {
					$bc_icms = "0.00";
					$pICMS = "0";
					$vlr_icms = "0.00";
				}
				if (!empty($ICMSSN102)) {
					$bc_icms = "0.00";
					$pICMS = "0";
					$vlr_icms = "0.00";
				}

				if (!empty($ICMSSN101)) {
					$bc_icms = "0.00";
					$pICMS = "0";
					$vlr_icms = "0.00";
				}

				if (!empty($icms00)) {
					$bc_icms = $item->imposto->ICMS->ICMS00->vBC;
					$bc_icms = number_format((float) $bc_icms ?? 0, 2, ".", "");
					$pICMS = $item->imposto->ICMS->ICMS00->pICMS;
					$pICMS = round((float) $pICMS ?? 0, 0);
					$vlr_icms = $item->imposto->ICMS->ICMS00->vICMS;
					$vlr_icms = number_format((float) $vlr_icms ?? 0, 2, ".", "");
				}
				if (!empty($icms20)) {
					$bc_icms = $item->imposto->ICMS->ICMS20->vBC;
					$bc_icms = number_format((float) $bc_icms ?? 0, 2, ".", "");
					$pICMS = $item->imposto->ICMS->ICMS20->pICMS;
					$pICMS = round((float) $pICMS ?? 0, 0);
					$vlr_icms = $item->imposto->ICMS->ICMS20->vICMS;
					$vlr_icms = number_format((float) $vlr_icms ?? 0, 2, ".", "");
				}
				if (!empty($icms30)) {
					$bc_icms = "0.00";
					$pICMS = "0";
					$vlr_icms = "0.00";
				}
				if (!empty($icms40)) {
					$bc_icms = "0.00";
					$pICMS = "0";
					$vlr_icms = "0.00";
				}
				if (!empty($icms50)) {
					$bc_icms = "0.00";
					$pICMS = "0";
					$vlr_icms = "0.00";
				}
				if (!empty($icms51)) {
					$bc_icms = $item->imposto->ICMS->ICMS51->vBC;
					$bc_icms = number_format((float) $bc_icms ?? 0, 2, ".", "");
					$pICMS = $item->imposto->ICMS->ICMS51->pICMS;
					$pICMS = round((float) $pICMS ?? 0, 0);
					$vlr_icms = $item->imposto->ICMS->ICMS51->vICMS;
					$vlr_icms = number_format((float) $vlr_icms ?? 0, 2, ".", "");
				}
				if (!empty($icms60)) {
					$bc_icms = "0.00";
					$pICMS = "0";
					$vlr_icms = "0.00";
				}
				$IPITrib = $item->imposto->IPI->IPITrib;
				if (!empty($IPITrib)) {
					$bc_ipi = $item->imposto->IPI->IPITrib->vBC;
					$bc_ipi = number_format((float) $bc_ipi ?? 0, 2, ".", "");
					$perc_ipi =  $item->imposto->IPI->IPITrib->pIPI;
					$perc_ipi = round((float) $perc_ipi ?? 0, 0);
					$vlr_ipi = $item->imposto->IPI->IPITrib->vIPI;
					$vlr_ipi = number_format((float) $vlr_ipi ?? 0, 2, ".", "");
				}
				$IPINT = $item->imposto->IPI->IPINT; {
					$bc_ipi = "0.00";
					$perc_ipi =  "0";
					$vlr_ipi = "0.00";
				}

				/*
			Regra custo unitário:
			((valor total do produto + valor frete + valor seguro  + valor outro + ipi + icms st - desconto) / quantidade)
			*/

				$custoUnitario = (($vProd + $vFreteItem + $vSeg + $vOutro + $vlr_ipi - $vDesc) / $qCom);
				//cadastrar o item
				$cp = new regProduto($pdo, $chave, $linhaNf, $cProd, $cEAN, $cBarra, $xProd, $NCM, $CST, $cBenef, $CFOP, $uCom, $qCom, $vUnCom, $vProd, $cEANTrib, $cBarraTrib, $uTrib, $qTrib, $vUnTrib, $vFreteItem, $vSeg, $vDesc, $vOutro, $indTot, $xPed, $nItemPed, $bc_icms, $pICMS, $vlr_icms, $bc_ipi, $perc_ipi, $vlr_ipi, $custoUnitario);
				$rcp = $cp->registraProduto();
				echo $rcp;

				//cadastrar em pdt_fornecedor
				$pf = new pdtFornecedor($pdo, $fornecedor, $cProd, '', $xProd, $NCM, $uCom);
				$rpf = $pf->registraPdtFornecedor();
				echo $rpf;
			}
		}
	}
}

function convertDate($d)
{
	if ($d) {
		preg_match("/(.*)\T/", $d);
		$array = explode(",", $d);
		return date('d/m/Y', str_replace('-', '/', strtotime($array[0] ?? 0)));
	} else {
		return ' - ';
	}
}

function convertDate2($d)
{
	if ($d) {
		preg_match("/(.*)\T/", $d);
		$array = explode(",", $d);
		return date('Y-m-d', str_replace('/', '-', strtotime($array[0])));
	} else {
		return ' - ';
	}
}

function convertTime($t)
{
	if ($t) {
		preg_match("/\T(.*)/", $t);
		$array = explode(",", $t);
		return date('H:i:s', strtotime($array[0]));
	} else {
		return ' - ';
	}
}
