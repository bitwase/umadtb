<?php

include "../seguranca.php";
require_once '../arquivos/mpdf/vendor/autoload.php';

$o = $_REQUEST['o']; //id da projeto

$sql = "select 
    o.garantia, o.ordemCliente, c.fantasia as 'nome', c.end, c.num, c.compl, c.bairro, c.cidade, c.uf, c.cep, so.status, o.pedido, o.orcamento,
    o.nomeProjeto, date_format(o.inicioPrevisto,'%d/%m/%Y') as 'inicioPrevisto', date_format(o.inicioEfetivo,'%d/%m/%Y') as 'inicioEfetivo', date_format(o.conclusaoPrevisto,'%d/%m/%Y') as 'conclusaoPrevisto', date_format(o.conclusaoReal,'%d/%m/%Y') as 'conclusaoReal', o.st, o.valorOrcado
    from tb_projetos o 
    inner join tb_clientes c on o.cliente = c.id 
    inner join tb_statusprojetos so on o.st = so.id 
    where o.id = $o
    order by o.inicioPrevisto";
$do = $pdo->query($sql)->fetch();
$vorcadoCalculo = number_format($do['valorOrcado'], 2, ".", "");
$valorOrcado = "R$".number_format($do['valorOrcado'], 2, ",", ".");

$endereco = "$do[end], $do[num], ";
if ($do['compl']) {
    $endereco .= "$do[compl],";
}
$endereco .= "$do[bairro], $do[cidade] - $do[uf]";

$tituloDoc = "$do[ordemCliente] - $do[nomeProjeto]";

$custoMO = "";
$custoNF = "";
$custoME = "";
$custoGA = "";
$custoGeral = 0;

$cima = "
    <h2 style='text-align:center;'>$do[ordemCliente] - $do[nomeProjeto]</h2>
    <h2 style='text-align:center;'>$do[nome]</h2>
    <h4 style='text-align:center;'>$endereco</h4>
    <hr>
    ";

$mpdf = new \Mpdf\Mpdf();

ob_start(); //inicia buffer
echo "<style>
table { 
    border-collapse: collapse;
}

.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
    font-size: 12px;
}

.table td,
.table th {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}

.table tbody+tbody {
    border-top: 2px solid #dee2e6;
}

.table .table {
    background-color: #fff;
}

.table-sm td,
.table-sm th {
    padding: 0.3rem;
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered td,
.table-bordered th {
    border: 1px solid #dee2e6;
}

.table-bordered thead td,
.table-bordered thead th {
    border-bottom-width: 2px;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}

.table-primary,
.table-primary>td,
.table-primary>th {
    background-color: #b8daff;
}

.table-secondary,
.table-secondary>td,
.table-secondary>th {
    background-color: #d6d8db;
}

.table-success,
.table-success>td,
.table-success>th {
    background-color: #c3e6cb;
}

.table-info,
.table-info>td,
.table-info>th {
    background-color: #bee5eb;
}

.table-warning,
.table-warning>td,
.table-warning>th {
    background-color: #ffeeba;
}

.table-danger,
.table-danger>td,
.table-danger>th {
    background-color: #f5c6cb;
}


.table-light,
.table-light>td,
.table-light>th {
    background-color: #fdfdfe;
}

.table-dark,
.table-dark>td,
.table-dark>th {
    background-color: #c6c8ca;
}

.table-active,
.table-active>td,
.table-active>th {
    background-color: rgba(0, 0, 0, 0.075);
}

.table .thead-dark th {
    color: #fff;
    background-color: #212529;
    border-color: #32383e;
}

.table .thead-light th {
    color: #495057;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.table-dark {
    color: #fff;
    background-color: #212529;
}

.table-dark td,
.table-dark th,
.table-dark thead th {
    border-color: #32383e;
}

.table-dark.table-bordered {
    border: 0;
}

.table-dark.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(255, 255, 255, 0.05);
}
</style>
";

echo "<h3>Mão de Obra</h3>";

echo "<table class='display table' width='100%'>";
echo "<thead class='thead-dark'>
<tr>
    <th>Emissão</th>
    <th>Descrição</th>
    <th>Início</th>
    <th>Fim</th>
    <th>QTD</th>
    <th>UN</th>
    <th>Vl. Unit.</th>
    <th>Total</th>
</tr>
</thead>";
echo "<tbody>";
$sql2 = "select c.id, date_format(c.data, '%d/%m/%Y') as 'emissao', c.inicio, c.fim, c.descricao, c.qt, c.cod, round((c.valor*c.tipoHora),2) as 'valor', round((c.qt*(c.valor*c.tipoHora)),2) as 'total', c.tipoHora
    from tb_custo_centrocusto c
    where referencia = 'MO' and projeto = '$o' and (garantia = 0 or garantia is null)
    order by c.data";
$rd = $pdo->query($sql2);
$dataSet = array();
$aux = 0;
$total = 0;
while ($r = $rd->fetch()) {

    //		$lk = "<i class='fa fa-pencil' onclick='editaCentro($r[id], $r[qt], $r[cat_id], $r[centro_id])' title='Editar Dados'></i>";

    switch ($r['tipoHora']) {
        case "1":
            $tipo = "Normal";
            break;
        case "1.5":
            $tipo = "Ext. 50%";
            break;
        case "2":
            $tipo = "Ext. 100%";
            break;
    }

    $qt = number_format($r['qt'], 2, ",", ".");
    $valor = "R$" . number_format($r['valor'], 2, ",", ".");
    $totalLinha = "R$" . number_format($r['total'], 2, ",", ".");
    $total += $r['total'];
    echo "
        <tr>
    <th>$r[emissao]</th>
    <th>$r[descricao]</th>
    <th>$r[inicio]</th>
    <th>$r[fim]</th>
    <th>$qt</th>
    <th>$tipo</th>
    <th>$valor</th>
    <th>$totalLinha</th>
</tr>
        ";
}

$custoGeral += $total;
$total = "R$" . number_format($total, 2, ",", ".");
$custoMO = $total;
echo "
            <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><b>Total</b></th>
                    <th>$total</th>
                </tr>
                ";

echo "</tbody>";
echo "</table>";

## materiais comprados ##

echo "<h3>Materiais Comprados</h3>";

echo "<table class='display table' width='100%'>";
echo "<thead class='thead-dark'>
<tr>
    <th>Emissão</th>
    <th>Descrição</th>
    <th>QTD</th>
    <th>UN</th>
    <th>Vl. Unit.</th>
    <th>Total</th>
</tr>
</thead>";
echo "<tbody>";
$sql2 = "select c.id, date_format(c.data, '%d/%m/%Y') as 'emissao', c.descricao, c.qt, round(c.valor,2) as 'valor', round((c.qt*c.valor),2) as 'total', c.cod, c.un, c.nf
    from tb_custo_centrocusto c
    where referencia = 'NF' and projeto = '$o' and (garantia = 0 or garantia is null)
    order by c.data, c.nf";
$rd = $pdo->query($sql2);
$dataSet = array();
$aux = 0;
$total = 0;
while ($r = $rd->fetch()) {

    $qt = number_format($r['qt'], 2, ",", ".");
    $valor = "R$" . number_format($r['valor'], 2, ",", ".");
    $totalLinha = "R$" . number_format($r['total'], 2, ",", ".");
    $total += $r['total'];
    echo "
        <tr>
    <th>$r[emissao]</th>
    <th>$r[descricao]</th>
    <th>$qt</th>
    <th>$r[un]</th>
    <th>$valor</th>
    <th>$totalLinha</th>
</tr>
        ";
}
$custoGeral += $total;
$total = "R$" . number_format($total, 2, ",", ".");
$custoNF = $total;
echo "
            <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><b>Total</b></th>
                    <th>$total</th>
                </tr>
                ";

echo "</tbody>";
echo "</table>";


## materiais de estoque ##

echo "<h3>Materiais de Estoque</h3>";

echo "<table class='display table' width='100%'>";
echo "<thead class='thead-dark'>
<tr>
    <th>Emissão</th>
    <th>Descrição</th>
    <th>QTD</th>
    <th>UN</th>
    <th>Vl. Unit.</th>
    <th>Total</th>
</tr>
</thead>";
echo "<tbody>";
$sql2 = "select c.id, date_format(c.data, '%d/%m/%Y') as 'emissao', c.descricao, c.qt, round(c.valor,2) as 'valor', round((c.qt*c.valor),2) as 'total', c.cod, c.un, c.nf
    from tb_custo_centrocusto c
    where referencia = 'ME' and projeto = '$o' and (garantia = 0 or garantia is null)
    order by c.data, c.nf";
$rd = $pdo->query($sql2);
$dataSet = array();
$aux = 0;
$total = 0;
while ($r = $rd->fetch()) {

    $qt = number_format($r['qt'], 2, ",", ".");
    $valor = "R$" . number_format($r['valor'], 2, ",", ".");
    $totalLinha = "R$" . number_format($r['total'], 2, ",", ".");
    $total += $r['total'];
    echo "
        <tr>
    <th>$r[emissao]</th>
    <th>$r[descricao]</th>
    <th>$qt</th>
    <th>$r[un]</th>
    <th>$valor</th>
    <th>$totalLinha</th>
</tr>
        ";
}
$custoGeral += $total;
$total = "R$" . number_format($total, 2, ",", ".");
$custoME = $total;
echo "
            <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><b>Total</b></th>
                    <th>$total</th>
                </tr>
                ";

echo "</tbody>";
echo "</table>";


## materiais de estoque ##

echo "<h3>Custos Diversos</h3>";

echo "<table class='display table' width='100%'>";
echo "<thead class='thead-dark'>
<tr>
    <th>Emissão</th>
    <th>Descrição</th>
    <th>QTD</th>
    <th>Vl. Unit.</th>
    <th>Total</th>
</tr>
</thead>";
echo "<tbody>";
$sql2 = "select c.id, date_format(c.data, '%d/%m/%Y') as 'emissao', c.descricao, c.qt, round(c.valor,2) as 'valor', round((c.qt*c.valor),2) as 'total', c.cod, c.un, c.nf
    from tb_custo_centrocusto c
    where referencia = 'CD' and projeto = '$o' and (garantia = 0 or garantia is null)
    order by c.data, c.nf";
$rd = $pdo->query($sql2);
$dataSet = array();
$aux = 0;
$total = 0;

while ($r = $rd->fetch()) {
    $qt = number_format($r['qt'], 2, ",", ".");
    $valor = "R$" . number_format($r['valor'], 2, ",", ".");
    $totalLinha = "R$" . number_format($r['total'], 2, ",", ".");
    $total += $r['total'];
    echo "
        <tr>
    <th>$r[emissao]</th>
    <th>$r[descricao]</th>
    <th>$qt</th>
    <th>$valor</th>
    <th>$totalLinha</th>
</tr>
        ";
}
$custoGeral += $total;
$total = "R$" . number_format($total, 2, ",", ".");
$custoCD = $total;
echo "
            <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><b>Total</b></th>
                    <th>$total</th>
                </tr>
                ";

echo "</tbody>";
echo "</table>";

//se houver garantia

if ($do['garantia'] == 1) {
    ## materiais de estoque ##
    $total = 0;

    echo "<h3>Garantia</h3>";

    echo "<table class='display table' width='100%'>";
    echo "<thead class='thead-dark'>
<tr>
    <th>Emissão</th>
    <th>Tipo</th>
    <th>Descrição</th>
    <th>Início</th>
    <th>Fim</th>
    <th>QTD</th>
    <th>UN</th>
    <th>Vl. Unit.</th>
    <th>Total</th>
</tr>
</thead>";
    echo "<tbody>";
    $sql2 = "select c.id, date_format(c.data, '%d/%m/%Y') as 'emissao', c.inicio, c.fim, c.descricao, c.qt, c.cod, round((c.valor*c.tipoHora),2) as 'valor', round((c.qt*(c.valor*c.tipoHora)),2) as 'total', c.tipoHora, c.referencia
    from tb_custo_centrocusto c
    where referencia = 'MO' and projeto = '$o' and (garantia = 1)
    order by c.data";
    $rd = $pdo->query($sql2);
    $dataSet = array();
    $aux = 0;
    while ($r = $rd->fetch()) {

        switch ($r['tipoHora']) {
            case "1":
                $tipo = "Normal";
                break;
            case "1.5":
                $tipo = "Ext. 50%";
                break;
            case "2":
                $tipo = "Ext. 100%";
                break;
        }

        $qt = number_format($r['qt'], 2, ",", ".");
        $valor = "R$" . number_format($r['valor'], 2, ",", ".");
        $totalLinha = "R$" . number_format($r['total'], 2, ",", ".");
        $total += $r['total'];
        echo "
        <tr>
    <th>$r[emissao]</th>
    <th>Mão de Obra</th>
    <th>$r[descricao]</th>
    <th>$r[inicio]</th>
    <th>$r[fim]</th>
    <th>$qt</th>
    <th>$tipo</th>
    <th>$valor</th>
    <th>$totalLinha</th>
</tr>
        ";
    }

    $sql2 = "select c.id, date_format(c.data, '%d/%m/%Y') as 'emissao', c.descricao, c.qt, c.nf, c.cod, c.un, round(c.valor,2) as 'valor', round((c.qt*c.valor),2) as 'total', c.referencia
    from tb_custo_centrocusto c
    where referencia = 'NF' and projeto = '$o' and (garantia = 1)
    order by c.data";
    $rd = $pdo->query($sql2);
    $dataSet = array();
    $aux = 0;
    while ($r = $rd->fetch()) {

        $qt = number_format($r['qt'], 2, ",", ".");
        $valor = "R$" . number_format($r['valor'], 2, ",", ".");
        $totalLinha = "R$" . number_format($r['total'], 2, ",", ".");
        $total += $r['total'];
        echo "
        <tr>
    <th>$r[emissao]</th>
    <th>Material Comprado</th>
    <th>$r[descricao]</th>
    <th></th>
    <th></th>
    <th>$qt</th>
    <th>$r[un]</th>
    <th>$valor</th>
    <th>$totalLinha</th>
</tr>
        ";
    }

    $sql2 = "select c.id, date_format(c.data, '%d/%m/%Y') as 'emissao', c.descricao, c.qt, c.nf, c.cod, c.un, round(c.valor,2) as 'valor', round((c.qt*c.valor),2) as 'total', c.referencia
    from tb_custo_centrocusto c
    where referencia = 'ME' and projeto = '$o' and (garantia = 1)
    order by c.data";
    $rd = $pdo->query($sql2);
    $dataSet = array();
    $aux = 0;
    while ($r = $rd->fetch()) {

        $qt = number_format($r['qt'], 2, ",", ".");
        $valor = "R$" . number_format($r['valor'], 2, ",", ".");
        $totalLinha = "R$" . number_format($r['total'], 2, ",", ".");
        $total += $r['total'];
        echo "
        <tr>
    <th>$r[emissao]</th>
    <th>Material de Estoque</th>
    <th>$r[descricao]</th>
    <th></th>
    <th></th>
    <th>$qt</th>
    <th>$r[un]</th>
    <th>$valor</th>
    <th>$totalLinha</th>
</tr>
        ";
    }
    $custoGeral += $total;
    $total = "R$" . number_format($total, 2, ",", ".");
    $custoGA = $total;
    echo "
            <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><b>Total</b></th>
                    <th>$total</th>
                </tr>
                ";

    echo "</tbody>";
    echo "</table>";
}

$perCons = ($custoGeral/$vorcadoCalculo)*100;
$perCons = number_format($perCons ?? 0,2,",",".")."%";
$custoGeral = "R$" . number_format($custoGeral, 2, ",", ".");

$valores = "
<table class='display table' style='border-collapse: collapse; width: 100%; max-width: 100%; margin-bottom: 1rem; background-color: transparent;' width='100%'>
<thead class='thead-dark'>
<tr>
<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>Início Previsto</th>
<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>Início Efetivo</th>
<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>Conclusão Prevista</th>
<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>Conclusão Efetiva</th>
<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>Status</th>
</tr>
</thead>
<tbody>
<tr>
<th>$do[inicioPrevisto]</th>
<th>$do[inicioEfetivo]</th>
<th>$do[conclusaoPrevisto]</th>
<th>$do[conclusaoReal]</th>
<th>$do[status]</th>
</tr>
</tbody>
</table>

<table class='display table' style='border-collapse: collapse; width: 100%; max-width: 100%; margin-bottom: 1rem; background-color: transparent;' width='100%'>
<thead class='thead-dark'>
<tr>
<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>Mão de Obra</th>
<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>Material Comprado</th>
<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>Material Estoque</th>
<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>Custos Diversos</th>";

if ($do['garantia'] == 1) {
    $valores .= "<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>Garantia</th>";
}

$valores .= "
<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>Total</th>
</tr>
</thead>
<tbody>
<tr>
<th>$custoMO</th>
<th>$custoNF</th>
<th>$custoME</th>
<th>$custoCD</th>";

if ($do['garantia'] == 1) {
    $valores .= "<th>$custoGA</th>";
}

$valores .= "<th>$custoGeral</th>
</tr>
</tbody>
</table>

<table class='display table' style='border-collapse: collapse; width: 100%; max-width: 100%; margin-bottom: 1rem; background-color: transparent;' width='100%'>
<thead class='thead-dark'>
<tr>
<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>Valor Orçado</th>
<th style='vertical-align: bottom; border-bottom: 2px solid #dee2e6;color: #fff; background-color: #212529; border-color: #32383e;'>% Consumido</th>
</tr>
</thead>
<tbody>
<tr>
<th>$valorOrcado</th>
<th>$perCons</th>
</tr>
</tbody>
</table>
<hr>
";

$html = ob_get_contents();

ob_end_clean();


$mpdf->SetHTMLFooter('
<table width="100%" style="vertical-align: bottom; font-family: serif; 
    font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
    <tr>
        <td width="33%">{DATE j-m-Y}</td>
        <td width="33%" align="center">{PAGENO}/{nbpg}</td>
        <td width="33%" style="text-align: right;">' . $tituloDoc . '</td>
    </tr>
</table>');
$mpdf->WriteHTML($cima);
$mpdf->WriteHTML($valores);
$mpdf->WriteHTML($html);
$mpdf->Output('projeto_'.$tituloDoc.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
