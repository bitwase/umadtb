<?php

include "../seguranca.php";
require_once '../arquivos/mpdf/vendor/autoload.php';

$filtra = $_REQUEST['ft'];

if ($filtra) {
    $dataInicio = $_REQUEST['i'];
    $dataFinal = $_REQUEST['f'];

    $filtro = "";

    if ($dataInicio) {
        $filtro .= " and c.data >= '$dataInicio 00:00:00'";
    }

    if ($dataFinal) {
        $filtro .= " and c.data <= '$dataFinal 23:59:59'";
    }
    //0123-56-89
    $i = $dataInicio[8] . $dataInicio[9] . "/" . $dataInicio[5] . $dataInicio[6] . "/" . $dataInicio[0] . $dataInicio[1] . $dataInicio[2] . $dataInicio[3];

    $f = $dataFinal[8] . $dataFinal[9] . "/" . $dataFinal[5] . $dataFinal[6] . "/" . $dataFinal[0] . $dataFinal[1] . $dataFinal[2] . $dataFinal[3];

    $periodo = "$i a $f";
    $tituloDoc = "Custos Engerede - $periodo";
    $totalGeral = 0;
}
$mpdf = new \Mpdf\Mpdf();
echo "<input type='hidden' id='dataInicial'><input type='hidden' id='dataFinal'>";
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
    font-size:12px;
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
## AQUI SERIA O TÍTULO MAS CHAMANDO NA INCLUSÃO ##
/**
 * procurar quantas categorias/subcategorias tem diferentes...
 * para cada uma, criar uma apresentação com resumo
 * id da div pode ser "res_x_y onde x = categoria e y subcategoria
 */
if (isset($filtro)) {
    $sql = "SELECT DISTINCT 
        c.catFin, c.subCatFin, cat.categoria as 't_catFin', sub.sub
        from tb_custo_centrocusto c 
        inner join fin_catfin cat on c.catFin = cat.id
        inner join fin_subcatfin sub on c.subCatFin = sub.id
        where c.categoria = $config[centroEngerede] $filtro
        order by cat.categoria, sub.sub";
    //echo $sql;
    $ld = $pdo->query($sql);

    while ($l = $ld->fetch()) {
        //enquanto houver...
        $titulo = "$l[t_catFin] - $l[sub]";
        $id_geral = "ger_$l[catFin]_$l[subCatFin]";
        $id_tab = "tab_$l[catFin]_$l[subCatFin]";

        echo "<div id='$id_geral'>

        <h3>$titulo</h3>

        <table id='$id_tab' class='display table' width='100%'>
            <thead class='thead-dark'>
                <tr>
                    <th>Emissão</th>
                    <th>Descrição</th>
                    <th>QTD</th>
                    <th>UN</th>
                    <th>Vl. Unit.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>";
        $sql2 = "select c.id, date_format(c.data, '%d/%m/%Y') as 'emissao', c.descricao, c.qt, round(c.valor,2) as 'valor', round((c.qt*c.valor),2) as 'total', c.cod, c.un, c.nf
    from tb_custo_centrocusto c
    where c.categoria = '$config[centroEngerede]' and c.catFin = '$l[catFin]' and c.subCatFin = '$l[subCatFin]' $filtro
    order by c.data, c.nf";
        $rd = $pdo->query($sql2);
        $dataSet = array();
        $aux = 0;
        $totalG = 0;
        while ($r = $rd->fetch()) {
            $totalG += $r['total'];
            $qt = number_format($r['qt'] ?? 0, 2, ",", ".");
            $valor = "R$" . number_format($r['valor'], 2, ",", ".");
            $total = "R$" . number_format($r['total'], 2, ",", ".");
            echo "
            <tr>
                    <th>$r[emissao]</th>
                    <th>$r[descricao]</th>
                    <th>$qt</th>
                    <th>$r[un]</th>
                    <th>$valor</th>
                    <th>$total</th>
                </tr>
                ";
        }
        $totalGeral += $totalG;
        $totalG = "R$" . number_format($totalG ?? 0, 2, ",", ".");
        echo "
            <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><b>Total</b></th>
                    <th>$totalG</th>
                </tr>
                ";
        echo "</tbody>

        </table>
    </div><br>";
    }
}

$totalGeral = "R$" . number_format($totalGeral ?? 0, 2, ",", ".");
$header = "
<h1 style='text-align:center'>Custos Engerede</h1>
<h3 style='text-align:center'>$periodo : $totalGeral</h3>
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
$mpdf->WriteHTML($header);
$mpdf->WriteHTML($html);
$mpdf->Output('custos_engerede.pdf', \Mpdf\Output\Destination::DOWNLOAD);