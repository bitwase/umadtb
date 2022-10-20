<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- Latest compiled and minified CSS -->
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
    <title>Vizualizador de DANFE 4.0</title>
</head>
<body>

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

    function convertTime($t){
        if ($t){
            preg_match("/\T(.*)/", $t, $time);
            $array = explode(",", $time[1]);
            return date('H:i', strtotime($array[0]));
        }else{
            return ' - ';
        }
    }

    $xml = '';
    if (isset($_POST['enviar_xml'])) {
        if (is_uploaded_file($_FILES['upl']['tmp_name'])) {
            $xml = simplexml_load_file($_FILES['upl']['tmp_name']); /* Lê o arquivo XML e recebe um objeto com as informações */
        }

        ?>

        <div class="container">
            <div class="section">
                <div class="row border">
                    <div class="col-md-4 center" style="height: 144px">
                        <h4><?= $xml->NFe->infNFe->emit->xNome ?></h4>
                        <p>CNPJ: <?= $xml->NFe->infNFe->emit->CNPJ ?></p>
                        <p><?= $xml->NFe->infNFe->emit->enderEmit->xLgr ?>
                            , <?= $xml->NFe->infNFe->emit->enderEmit->nro ?></p>
                        <p><?= $xml->NFe->infNFe->emit->enderEmit->xBairro ?></p>
                        <p><?= $xml->NFe->infNFe->emit->enderEmit->xMun ?>
                            - <?= $xml->NFe->infNFe->emit->enderEmit->xPais ?></p>
                        <p>Telefone / Fax: <?= $xml->NFe->infNFe->emit->enderEmit->fone ?></p>
                    </div>
                    <div class="col-md-2 center" style="height: 144px">
                        <br>
                        <h4>NF</h4>
                        <h4><?= $xml->NFe->infNFe->ide->nNF ?></h4>
                        <small>Série: <?= $xml->NFe->infNFe->ide->serie ?></small>
                    </div>
                    <div class="col-md-6 center" style="height: 144px">
                        <small class="pull-left">Chave</small>
                        <br><br>
                        <h5m><?= $xml->NFe->infNFe->attributes()->Id ?></h5m>
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
                        <?= $xml->NFe->infNFe->ide->natOp ?>
                    </div>
                    <div class="col-md-5">
                        <small>Autorização: </small>
                        <?= $xml->protNFe->infProt->nProt ?> - <?= $xml->protNFe->infProt->dhRecbto ?>
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
                        <?= convertDate($xml->NFe->infNFe->ide->dhEmi) ?>
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
                        <b>DADOS DOS PRODUTOS / SERVIĆOS</b>
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
                                    $pICMS = "0	";
                                    $vlr_icms = "0.00";
                                }

                                if(!empty($ICMSSN101)){
                                    $bc_icms = "0.00";
                                    $pICMS = "0	";
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
                                    $pICMS = "0	";
                                    $vlr_icms = "0.00";
                                }
                                if(!empty($icms40))
                                {
                                    $bc_icms = "0.00";
                                    $pICMS = "0	";
                                    $vlr_icms = "0.00";
                                }
                                if(!empty($icms50))
                                {
                                    $bc_icms = "0.00";
                                    $pICMS = "0	";
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
                                    $pICMS = "0	";
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
</body>
</html>