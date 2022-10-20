<?php

include "../seguranca.php";
require '../arquivos/xlsx/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

$sql = "SELECT
date_format(c.data, '%d/%m/%Y') as 'emissao',
c.descricao,
c.qt,
round(c.valor, 2) as 'valor',
round((c.qt * c.valor), 2) as 'total',
c.cod,
c.un,
c.nf,
cat.categoria as 't_catFin',
sub.sub
from
tb_custo_centrocusto c
inner join fin_catfin cat on c.catFin = cat.id
inner join fin_subcatfin sub on c.subCatFin = sub.id
where
c.categoria = '$config[centroEngerede]'
$filtro
order by
t_catFin,
sub.sub,
c.data,
c.nf";
//echo $sql;
$ld = $pdo->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Categoria');
$sheet->setCellValue('B1', 'Subcategoria');
$sheet->setCellValue('C1', 'Data');
$sheet->setCellValue('D1', 'NF');
$sheet->setCellValue('E1', 'Código');
$sheet->setCellValue('F1', 'Descrição');
$sheet->setCellValue('G1', 'Quantidade');
$sheet->setCellValue('H1', 'UN');
$sheet->setCellValue('I1', 'Vl. Unitário');
$sheet->setCellValue('J1', 'Vl. Total');

$linha = 2;
while($l = $ld->fetch()){
    $sheet->setCellValue('A'.$linha, $l['t_catFin']);
    $sheet->setCellValue('B'.$linha, $l['sub']);
    $sheet->setCellValue('C'.$linha, $l['emissao']);
    $sheet->setCellValue('D'.$linha, $l['nf']);
    $sheet->setCellValue('E'.$linha, $l['cod']);
    $sheet->setCellValue('F'.$linha, $l['descricao']);
    $sheet->setCellValue('G'.$linha, $l['qt']);
    $sheet->setCellValue('H'.$linha, $l['un']);
    $sheet->setCellValue('I'.$linha, $l['valor']);
    $sheet->setCellValue('J'.$linha, $l['total']);

    $linha++;
}
$filename = "custos_engerede.xlsx";
$writer = new Xlsx($spreadsheet);
$writer->save($filename);
$content = file_get_contents($filename);

header("Content-Disposition: attachment; filename=".$filename);

unlink($filename);
exit($content);