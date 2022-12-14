    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <style>
        .border {
            border: 1px solid #cccccc;
            margin: 5px;
        }

        .border div {
            border: 0.5px solid #cccccc;
            padding: 3px;
        }

        .title {
            margin: 15px 0px 0px 15px
        }

        .file {
            visibility: hidden;
            position: absolute;
        }

        .container {
            width: 100%;
            background-color: #fff;
            padding: 20px 40px;
            border-radius: 8px;
        }

        h1 {
            color: #fff;
            font-size: 3rem;
            font-weight: 600;
            margin: 0 0 5px 0;
            background: -webkit-linear-gradient(#16a085, #34495e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
        }

        h4 {
            color: lighten(#5c3d86, 30%);
            font-size: 20px;
            font-weight: 400;
            text-align: center;
            margin: 0 0 5px 0;
        }

        p {
            margin: 0 0 2px 0;
        }

        .center {
            text-align: center;
        }
    </style>
<div class="container">

    <h2>Importar NFE</h2>
    <h4>Use o botão abaixo para importar a NFe</h4>
    <div class="container">
        <div class="col-md-12">
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="upl" class="file">
                    <div class="input-group col-xs-12">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-file"></i></span>
                        <input type="text" class="form-control input-lg" disabled placeholder="NF eletronica">
                        <span class="input-group-btn">
        <button class="browse btn btn-primary input-lg" type="button"><i class="glyphicon glyphicon-search"></i> Arquivo</button>        
      </span>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary input-lg" name="enviar_xml"><i
                                        class="glyphicon glyphicon-save"></i> Carregar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php

    function convertDate($d){
        if ($d){
            preg_match("/(.*)\T/", $d, $capturado);
            $array = explode(",", $capturado[1]);
            return date('d/m/Y', str_replace('-','/', strtotime($array[0])));
        }else{
            return ' - ';
        }

    }

    function convertDate2($d){
        if ($d){
            preg_match("/(.*)\T/", $d, $capturado);
            $array = explode(",", $capturado[1]);
            return date('Y-m-d', str_replace('/','-', strtotime($array[0])));
        }else{
            return ' - ';
        }

    }

    function convertTime($t){
        if ($t){
            preg_match("/\T(.*)/", $t, $time);
            $array = explode(",", $time[1]);
            return date('H:i:s', strtotime($array[0]));
        }else{
            return ' - ';
        }
    }

    $xml = '';
    if (isset($_POST['enviar_xml'])) {
        if (is_uploaded_file($_FILES['upl']['tmp_name'])) {
            $xml = simplexml_load_file($_FILES['upl']['tmp_name']); /* Lê o arquivo XML e recebe um objeto com as informações */
            //ler como string
            $xmlStr = file_get_contents($_FILES['upl']['tmp_name']);
            //$sql = "insert into nfe_nfe (xml) values ('$xmlStr')";
            //echo "$sql";
            //echo $r;
            //            echo $xmlStr;
//            print_r($xmlStr);
        
    //    exit();
        }
/* DADOS DA NF */

$chave = substr($xml->NFe->infNFe->attributes()->Id,3);
$serie = $xml->NFe->infNFe->ide->serie;
$nf = $xml->NFe->infNFe->ide->nNF;
$emissao = convertDate($xml->NFe->infNFe->ide->dhEmi)." ".convertTime($xml->NFe->infNFe->ide->dhEmi);
$emissaoReg = convertDate2($xml->NFe->infNFe->ide->dhEmi)." ".convertTime($xml->NFe->infNFe->ide->dhEmi);
$natOP = $xml->NFe->infNFe->ide->natOp;
$autorizacao = $xml->protNFe->infProt->nProt;
$dt_autorizacao = convertDate($xml->protNFe->infProt->dhRecbto). " " .convertTime($xml->protNFe->infProt->dhRecbto);
$dt_autorizacaoReg = convertDate2($xml->protNFe->infProt->dhRecbto). " " .convertTime($xml->protNFe->infProt->dhRecbto);
$fornecedor = $xml->NFe->infNFe->emit->CNPJ;

/* VALORES */
$vBC = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vBC, 2, ".", "");
$vICMS = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vICMS, 2, ".", "");
$vICMSDeson = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vICMSDeson, 2, ".", "");
$vFCPUFDest = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vFCPUFDest, 2, ".", "");
$vICMSUFDest = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vICMSUFDest, 2, ".", "");
$vICMSUFRemet = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vICMSUFRemet, 2, ".", "");
$vFCP = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vFCP, 2, ".", "");
$vBCST = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vBCST, 2, ".", "");
$vST = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vST, 2, ".", "");
$vFCPST = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vFCPST, 2, ".", "");
$vFCPSTRet = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vFCPSTRet, 2, ".", "");
$vProd = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vProd, 2, ".", "");
$vFrete = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vFrete, 2, ".", "");
$vSeg = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vSeg, 2, ".", "");
$vDesc = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vDesc, 2, ".", "");
$vII = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vII, 2, ".", "");
$vIPI = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vIPI, 2, ".", "");
$vIPIDevol = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vIPIDevol, 2, ".", "");
$vPIS = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vPIS, 2, ".", "");
$vCOFINS = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vCOFINS, 2, ".", "");
$vOutro = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vOutro, 2, ".", "");
$vNF = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vNF, 2, ".", "");
$vTotTrib = number_format((double) $xml->NFe->infNFe->total->ICMSTot->vTotTrib, 2, ".", "");


//grava dados da NF
$reg = new regNf($pdo, $chave, $serie, $nf, $emissaoReg, $fornecedor, $autorizacao, $dt_autorizacaoReg,  $vBC, $vICMS, $vICMSDeson, $vFCPUFDest, $vICMSUFDest, $vICMSUFRemet, $vFCP, $vBCST, $vST, $vFCPST, $vFCPSTRet, $vProd, $vFrete, $vSeg, $vDesc, $vII, $vIPI, $vIPIDevol, $vPIS, $vCOFINS, $vOutro, $vNF, $vTotTrib, $xmlStr);
$r = $reg->registraNota();
echo $r;

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

/* Dados dos itens para base de dados */

$seq = 0;
foreach($xml->NFe->infNFe->det as $item) {
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
    $vUnCom = number_format((double) $item->prod->vUnCom, 2, '.', '');
    $vProd = number_format((double) $item->prod->vProd, 2, '.', '');
    $cEANTrib = $item->prod->cEANTrib;
    $cBarraTrib = $item->prod->cBarraTrib;
    $uTrib = $item->prod->uTrib;
    $qTrib = $item->prod->qTrib;
    $vUnTrib = $item->prod->vUnTrib;
    $vFreteItem = number_format((double) $item->prod->vFrete, 2, ".", "");
    $vSeg = number_format((double) $item->prod->vSeg, 2, ".", "");
    $vDesc = number_format((double) $item->prod->vDesc, 2, ".", "");
    $vOutro = number_format((double) $item->prod->vOutro, 2, ".", "");
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
    
    if(!empty($ICMSST)){
        $bc_icms = "0.00";
        $pICMS = "0";
        $vlr_icms = "0.00";
    }
    if(!empty($ICMSSN102))
    {
        $bc_icms = "0.00";
        $pICMS = "0";
        $vlr_icms = "0.00";
    }

    if(!empty($ICMSSN101)){
        $bc_icms = "0.00";
        $pICMS = "0";
        $vlr_icms = "0.00";
    }

    if (!empty($icms00))
    {
        $bc_icms = $item->imposto->ICMS->ICMS00->vBC;
        $bc_icms = number_format((double) $bc_icms, 2, ".", "");
        $pICMS = $item->imposto->ICMS->ICMS00->pICMS;
        $pICMS = round($pICMS,0);
        $vlr_icms = $item->imposto->ICMS->ICMS00->vICMS;
        $vlr_icms = number_format((double) $vlr_icms, 2, ".", "");
    }
    if (!empty($icms20))
    {
        $bc_icms = $item->imposto->ICMS->ICMS20->vBC;
        $bc_icms = number_format((double) $bc_icms, 2, ".", "");
        $pICMS = $item->imposto->ICMS->ICMS20->pICMS;
        $pICMS = round($pICMS,0);
        $vlr_icms = $item->imposto->ICMS->ICMS20->vICMS;
        $vlr_icms = number_format((double) $vlr_icms, 2, ".", "");
    }
    if(!empty($icms30))
    {
        $bc_icms = "0.00";
        $pICMS = "0";
        $vlr_icms = "0.00";
    }
    if(!empty($icms40))
    {
        $bc_icms = "0.00";
        $pICMS = "0";
        $vlr_icms = "0.00";
    }
    if(!empty($icms50))
    {
        $bc_icms = "0.00";
        $pICMS = "0";
        $vlr_icms = "0.00";
    }
    if(!empty($icms51))
    {
        $bc_icms = $item->imposto->ICMS->ICMS51->vBC;
        $bc_icms = number_format((double) $bc_icms, 2, ".", "");
        $pICMS = $item->imposto->ICMS->ICMS51->pICMS;
        $pICMS = round($pICMS,0);
        $vlr_icms = $item->imposto->ICMS->ICMS51->vICMS;
        $vlr_icms = number_format((double) $vlr_icms, 2, ".", "");
    }
    if(!empty($icms60))
    {
        $bc_icms = "0.00";
        $pICMS = "0";
        $vlr_icms = "0.00";
    }
    $IPITrib = $item->imposto->IPI->IPITrib;
    if (!empty($IPITrib))
    {
        $bc_ipi =$item->imposto->IPI->IPITrib->vBC;
        $bc_ipi = number_format((double) $bc_ipi, 2, ".", "");
        $perc_ipi =  $item->imposto->IPI->IPITrib->pIPI;
        $perc_ipi = round($perc_ipi,0);
        $vlr_ipi = $item->imposto->IPI->IPITrib->vIPI;
        $vlr_ipi = number_format((double) $vlr_ipi, 2, ".", "");
    }
    $IPINT = $item->imposto->IPI->IPINT;
    {
        $bc_ipi = "0.00";
        $perc_ipi =  "0";
        $vlr_ipi = "0.00";
    }

    //cadastrar o item
    $cp = new regProduto($pdo, $chave, $linhaNf, $cProd, $cEAN, $cBarra, $xProd, $NCM, $CST, $cBenef, $CFOP, $uCom, $qCom, $vUnCom, $vProd, $cEANTrib, $cBarraTrib, $uTrib, $qTrib, $vUnTrib, $vFrete, $vSeg, $vDesc, $vOutro, $indTot, $xPed, $nItemPed, $bc_icms, $pICMS, $vlr_icms, $bc_ipi, $perc_ipi, $vlr_ipi);
    $rcp = $cp->registraProduto();
    echo $rcp;
}
?>

        <div class="container">
            <div class="section">
                <div class="row border">
                    <div class="col-md-4 center" style="height: 144px">
                        <h4><?= $emit_xNome ?></h4>
                        <p>CNPJ: <?= $emit_CNPJ ?></p>
                        <p><?= $emit_xLgr ?>
                            , <?= $emit_nro ?></p>
                        <p><?= $emit_xBairro ?></p>
                        <p><?= $emit_xMun ?>
                            - <?= $emit_xPais ?></p>
                        <p>Telefone / Fax: <?= $emit_fone ?></p>
                    </div>
                    <div class="col-md-2 center" style="height: 144px">
                        <br>
                        <h4>NF</h4>
                        <h4><?= $nf ?></h4>
                        <small>Série: <?= $serie ?></small>
                    </div>
                    <div class="col-md-6 center" style="height: 144px">
                        <small class="pull-left">Chave</small>
                        <br><br>
                        <h5m><?= $chave ?></h5m>
                        <br>
                        <small class="pull-left">Versão</small>
                        <br>
                        <?= $xml->NFe->infNFe->attributes()->versao ?>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row border">
                    <div class="col-md-7">
                        <small>Natureza: </small>
                        <?= $natOP ?>
                    </div>
                    <div class="col-md-5">
                        <small>Autorização: </small>
                        <?= $autorizacao ?> - <?= $dt_autorizacao ?>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row border">
                    <div class="col-md-3">
                        <small>IE: </small>
                        <?= $xml->NFe->infNFe->emit->IE ?>
                    </div>
                    <div class="col-md-4">
                        <small>IE ST: </small>
                        <?= $xml->NFe->infNFe->emit->IEst ?>
                    </div>
                    <div class="col-md-5">
                        <small>CNPJ: </small>
                        <?= $xml->NFe->infNFe->emit->CNPJ ?>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row">
                    <div class="col-md-12 title">
                        <b>DESTINATÁRIO / REMETENTE</b>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row border">
                    <div class="col-md-7">
                        <small>Nome: </small>
                        <?= $xml->NFe->infNFe->dest->xNome ?>
                    </div>
                    <div class="col-md-3"><small>CNPJ: </small>
                        <?= $xml->NFe->infNFe->dest->CNPJ ?></div>
                    <div class="col-md-2">
                        <small>Emissão: </small>
                        <?= $emissao ?>
                    </div>
                </div>
                <div class="row border">
                    <div class="col-md-5">
                        <small>Emissão: </small>
                        <?= $xml->NFe->infNFe->dest->enderDest->xLgr ?>, <?= $xml->NFe->infNFe->dest->enderDest->nro ?>
                    </div>
                    <div class="col-md-3">
                        <small>Bairro: </small>
                        <?= $xml->NFe->infNFe->dest->enderDest->xBairro ?>
                    </div>
                    <div class="col-md-2">
                        <small>Cep: </small>
                        <?= $xml->NFe->infNFe->dest->enderDest->CEP ?>
                    </div>
                    <div class="col-md-2">
                        <small>Saída: </small>
                        <?= convertDate($xml->NFe->infNFe->ide->dhSaiEnt) ?>
                    </div>
                </div>
                <div class="row border">
                    <div class="col-md-4">
                        <small>Municipio: </small>
                        <?= $xml->NFe->infNFe->dest->enderDest->xMun ?>
                    </div>
                    <div class="col-md-2">
                        <small>Telefone: </small>
                        <?= $xml->NFe->infNFe->dest->enderDest->fone ?>
                    </div>
                    <div class="col-md-1">
                        <small>UF: </small>
                        <?= $xml->NFe->infNFe->dest->enderDest->UF ?>
                    </div>
                    <div class="col-md-3">
                        <small>IE: </small>
                        <?= $xml->NFe->infNFe->dest->IE ?>
                    </div>
                    <div class="col-md-2">
                        <small>Hora: </small>
                        <?= convertTime($xml->NFe->infNFe->ide->dhSaiEnt) ?>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row">
                    <div class="col-md-12 title">
                        <b>FATURA / DUPLICATAS</b>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row border">
                    <?php
                    $id = 0;
                    if (!empty($xml->NFe->infNFe->cobr->dup))
                    {
                        foreach($xml->NFe->infNFe->cobr->dup as $dup)
                        {
                            $id++;
                            $titulo = $dup->nDup;
                            $vencimento = $dup->dVenc;
                            $vlr_parcela = number_format((double) $dup->vDup, 2, ",", ".");
                            echo "<div class='col-md-4'>Parcela {$titulo} - {$vencimento} R$ {$vlr_parcela}</div>";
                        }
                    }else{
                        echo "<div class='col-md-12'>NF sem duplicatas</div>";
                    }
                    ?>
                </div>
            </div>
            <div class="section">
                <div class="row">
                    <div class="col-md-12 title">
                        <b>CÁLCULO DE IMPOSTO</b>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row border">
                    <div class="col-md-3">
                        <small>Base calculo icms: </small>
                        <?= number_format((double) $xml->NFe->infNFe->total->ICMSTot->vBC, 2, ",", ".") ?>
                    </div>
                    <div class="col-md-2">
                        <small>Valor icms: </small>
                        <?= number_format((double) $xml->NFe->infNFe->total->ICMSTot->vICMS, 2, ",", ".") ?>
                    </div>
                    <div class="col-md-3">
                        <small>Base calculo icms st: </small>
                        <?= number_format((double) $xml->NFe->infNFe->total->ICMSTot->vBCST, 2, ",", ".") ?>
                    </div>
                    <div class="col-md-2">
                        <small>Valor calculo icms st: </small>
                        <?= number_format((double) $xml->NFe->infNFe->total->ICMSTot->vST, 2, ",", ".") ?>
                    </div>
                    <div class="col-md-2">
                        <small>Valor Total Produtos: </small>
                        <?= number_format((double) $xml->NFe->infNFe->total->ICMSTot->vProd, 2, ",", ".") ?>
                    </div>
                </div>
                <div class="row border">
                    <div class="col-md-2">
                        <small>Valor do Frete: </small>
                        <?= number_format((double) $xml->NFe->infNFe->total->ICMSTot->vFrete, 2, ",", "."); ?>
                    </div>
                    <div class="col-md-2">
                        <small>Valor do Seguro: </small>
                        <?= number_format((double)   $xml->NFe->infNFe->total->ICMSTot->vSeg, 2, ",", "."); ?>
                    </div>
                    <div class="col-md-2">
                        <small>Desconto: </small>
                        <?= number_format((double) $xml->NFe->infNFe->total->ICMSTot->vDesc, 2, ",", "."); ?>
                    </div>
                    <div class="col-md-2">
                        <small>Despesas: </small>
                        <?= number_format((double) $xml->NFe->infNFe->total->ICMSTot->vDesc, 2, ",", "."); ?>
                    </div>
                    <div class="col-md-2">
                        <small>Valor do IPI: </small>
                        <?= number_format((double) $xml->NFe->infNFe->total->ICMSTot->vIPI, 2, ",", "."); ?>
                    </div>
                    <div class="col-md-2">
                        <small>Valor Total NF: </small>
                        <?= number_format((double) $xml->NFe->infNFe->total->ICMSTot->vNF, 2, ",", "."); ?>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row">
                    <div class="col-md-12 title">
                        <b>TRASNSPORTADORA / VOLUMES TRANSPORTADOS</b>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row border">
                    <div class="col-md-5">
                        <small>Transportador: </small>
                        <?= $xml->NFe->infNFe->transp->transporta->xNome ?>
                    </div>
                    <div class="col-md-1">
                        <?= $xml->NFe->infNFe->transp->modFrete ? 1 .' - Emitente' : 2 .' - Destinatario' ?>
                    </div>
                    <div class="col-md-1">
                        <small>ANTT: </small>
                        <?= ' - ' ?>
                    </div>
                    <div class="col-md-2">
                        <small>Placa: </small>
                        <?= ' - ' ?>
                    </div>
                    <div class="col-md-1">
                        <small>UF: </small>
                        <?= $xml->NFe->infNFe->transp->transporta->UF ?>
                    </div>
                    <div class="col-md-2">
                        <small>CNPJ: </small>
                        <?= $xml->NFe->infNFe->transp->transporta->CNPJ ?>
                    </div>
                </div>
                <div class="row border">
                    <div class="col-md-5">
                        <small>Logradouro: </small>
                        <?= $xml->NFe->infNFe->transp->transporta->xEnder ?>
                    </div>
                    <div class="col-md-4">
                        <small>Municipio: </small>
                        <?= $xml->NFe->infNFe->transp->transporta->xMun ?>
                    </div>
                    <div class="col-md-1">
                        <small>UF: </small>
                        <?= $xml->NFe->infNFe->transp->transporta->UF ?>
                    </div>
                    <div class="col-md-2">
                        <small>IE: </small>
                        <?= $xml->NFe->infNFe->transp->transporta->IE ?>
                    </div>
                </div>
                <div class="row border">
                    <div class="col-md-2">
                        <small>Qtde: </small>
                        <?= $xml->NFe->infNFe->transp->vol->qVol ?>
                    </div>
                    <div class="col-md-2">
                        <small>Espécie: </small>
                        <?= $xml->NFe->infNFe->transp->vol->esp ?>
                    </div>
                    <div class="col-md-2">
                        <small>Marca: </small>
                        <?= $xml->NFe->infNFe->transp->vol->marca ?>
                    </div>
                    <div class="col-md-2">
                        <small>Numeração: </small>
                        <?= $xml->NFe->infNFe->transp->vol->nVol ?>
                    </div>
                    <div class="col-md-2">
                        <small>Peso B.: </small>
                        <?= $xml->NFe->infNFe->transp->vol->pesoB ?>
                    </div>
                    <div class="col-md-2">
                        <small>Peso L.: </small>
                        <?= $xml->NFe->infNFe->transp->vol->pesoL ?>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row">
                    <div class="col-md-12 title">
                        <b>DADOS DOS PRODUTOS / SERVIÇOS</b>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>CÓD. PRODUTO</th>
                                <th>DESCRICAO PRODUTOS / SERVICOS</th>
                                <th>NCM|SH</th>
                                <th>CST</th>
                                <th>CFOP</th>
                                <th>UN</th>
                                <th>QTDE</th>
                                <th>VALOR U.</th>
                                <th>VALOR T.</th>
                                <th>BC ICMS</th>
                                <th>V. ICMS</th>
                                <th>V. ST</th>
                                <th>IPI</th>
                                <th>% ICMS</th>
                                <th>% IPI</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $seq = 0;
                            foreach($xml->NFe->infNFe->det as $item) {
                                $seq++;
                                echo "<tr>";
                                echo "<td>{$item->prod->cProd}</td>";
                                echo "<td>{$item->prod->xProd}</td>";
                                echo "<td>{$item->prod->NCM}</td>";
                                echo "<td>{$item->imposto->ICMS->ICMS00->CST}</td>";
                                echo "<td>{$item->prod->CFOP}</td>";
                                echo "<td>{$item->prod->uCom}</td>";
                                echo "<td>{$item->prod->qCom}</td>";
                                $vuni = number_format((double) $item->prod->vUnCom, 2, ',', '.');
                                echo "<td>{$vuni}</td>";
                                $vtotal = number_format((double) $item->prod->vProd, 2, ',', '.');
                                echo "<td>{$vtotal}</td>";
                                $vBC_item = $item->imposto->ICMS->ICMS00->vBC;
                                $icms00 = $item->imposto->ICMS->ICMS00;
                                $icms10 = $item->imposto->ICMS->ICMS10;
                                $icms20 = $item->imposto->ICMS->ICMS20;
                                $icms30 = $item->imposto->ICMS->ICMS30;
                                $icms40 = $item->imposto->ICMS->ICMS40;
                                $icms50 = $item->imposto->ICMS->ICMS50;
                                $icms51 = $item->imposto->ICMS->ICMS51;
                                $icms60 = $item->imposto->ICMS->ICMS60;
                                $ICMSSN101 = $item->imposto->ICMS->ICMSSN101;
                                $ICMSSN102 = $item->imposto->ICMS->ICMSSN102;


                                if(!empty($ICMSSN102))
                                {
                                    $bc_icms = "0.00";
                                    $pICMS = "0";
                                    $vlr_icms = "0.00";
                                }

                                if(!empty($ICMSSN101)){
                                    $bc_icms = "0.00";
                                    $pICMS = "0";
                                    $vlr_icms = "0.00";
                                }

                                if (!empty($icms00))
                                {
                                    $bc_icms = $item->imposto->ICMS->ICMS00->vBC;
                                    $bc_icms = number_format((double) $bc_icms, 2, ",", ".");
                                    $pICMS = $item->imposto->ICMS->ICMS00->pICMS;
                                    $pICMS = round($pICMS,0);
                                    $vlr_icms = $item->imposto->ICMS->ICMS00->vICMS;
                                    $vlr_icms = number_format((double) $vlr_icms, 2, ",", ".");
                                }
                                if (!empty($icms20))
                                {
                                    $bc_icms = $item->imposto->ICMS->ICMS20->vBC;
                                    $bc_icms = number_format((double) $bc_icms, 2, ",", ".");
                                    $pICMS = $item->imposto->ICMS->ICMS20->pICMS;
                                    $pICMS = round($pICMS,0);
                                    $vlr_icms = $item->imposto->ICMS->ICMS20->vICMS;
                                    $vlr_icms = number_format((double) $vlr_icms, 2, ",", ".");
                                }
                                if(!empty($icms30))
                                {
                                    $bc_icms = "0.00";
                                    $pICMS = "0";
                                    $vlr_icms = "0.00";
                                }
                                if(!empty($icms40))
                                {
                                    $bc_icms = "0.00";
                                    $pICMS = "0";
                                    $vlr_icms = "0.00";
                                }
                                if(!empty($icms50))
                                {
                                    $bc_icms = "0.00";
                                    $pICMS = "0";
                                    $vlr_icms = "0.00";
                                }
                                if(!empty($icms51))
                                {
                                    $bc_icms = $item->imposto->ICMS->ICMS51->vBC;
                                    $pICMS = $item->imposto->ICMS->ICMS51->pICMS;
                                    $pICMS = round($pICMS,0);
                                    $vlr_icms = $item->imposto->ICMS->ICMS51->vICMS;
                                }
                                if(!empty($icms60))
                                {
                                    $bc_icms = "0,00";
                                    $pICMS = "0";
                                    $vlr_icms = "0,00";
                                }
                                $IPITrib = $item->imposto->IPI->IPITrib;
                                if (!empty($IPITrib))
                                {
                                    $bc_ipi =$item->imposto->IPI->IPITrib->vBC;
                                    $bc_ipi = number_format((double) $bc_ipi, 2, ",", ".");
                                    $perc_ipi =  $item->imposto->IPI->IPITrib->pIPI;
                                    $perc_ipi = round($perc_ipi,0);
                                    $vlr_ipi = $item->imposto->IPI->IPITrib->vIPI;
                                    $vlr_ipi = number_format((double) $vlr_ipi, 2, ",", ".");
                                }
                                $IPINT = $item->imposto->IPI->IPINT;
                                {
                                    $bc_ipi = "0,00";
                                    $perc_ipi =  "0";
                                    $vlr_ipi = "0,00";
                                }
                                echo "<td>{$bc_icms}</td>";
                                echo "<td>{$vlr_icms}</td>";
                                echo "<td>{$item->imposto->ICMS00->vCST}</td>";
                                echo "<td>{$item->imposto->IPI->IPITrib->vIPI}</td>";
                                echo "<td>{$pICMS}</td>";
                                echo "<td>{$item->imposto->IPI->IPITrib->pIPI}</td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row">
                    <div class="col-md-12 title">
                        <b>DADOS ADICIONAIS</b>
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="row border">
                    <div class="col-md-7" style="height:120px">
                        <?= $xml->NFe->infNFe->infAdic->infCpl ?>
                    </div>
                    <div class="col-md-5" style="height:120px"></div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<br><br><br>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <script>
        $(document).on('click', '.browse', function () {
            var file = $(this).parent().parent().parent().find('.file');
            file.trigger('click');
        });
        $(document).on('change', '.file', function () {
            $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
        });
    </script>