<?php
include "arquivos/fpdf/fpdf.php";

include "conexao.php"; // arquivo de conexão ao banco - isso você já deve saber fazer

// 6280 pimaco (3 colunoas)


// Variaveis de Tamanho

$mesq = "5"; // Margem Esquerda (mm)
$mdir = "5"; // Margem Direita (mm) 
$msup = "12"; // Margem Superior (mm)
$leti = "72"; // Largura da Etiqueta (mm)
$aeti = "27"; // Altura da Etiqueta (mm)
$ehet = "3,2"; // Espaço horizontal entre as Etiquetas (mm) 
$pdf=new FPDF('P','mm','Letter'); // Cria um arquivo novo tipo carta, na vertical.
$pdf->Open(); // inicia documento
$pdf->AddPage(); // adiciona a primeira pagina
$pdf->SetMargins('5','12,7'); // Define as margens do documento 
$pdf->SetAuthor("Wellington Santos"); // Define o autor
$pdf->SetFont('helvetica','',7); // Define a fonte
$pdf->SetDisplayMode($zoom,$layout='continuous');

$coluna = 0; 
$linha = 0;
//MONTA A ARRAY PARA ETIQUETAS
while($dados = mysql_fetch_array($busca)) {
$nome = $dados[0];
$ende = $dados[8];
$num = $dados[9];
$bairro = $dados[10];
$estado = $dados[12];
$cida = $dados[11]; 
$local = $bairro . ' - ' . $cida . ' - ' . $estado;
$cep = "CEP: " . $dados[13];
$res = $ende . " Nº " . $num;

if($linha == '10') {
$pdf->AddPage();
$linha = 0; 
}

if($coluna == '3') { // Se for a terceira coluna
$coluna = 0; // $coluna volta para o valor inicial
$linha = $linha +1; // $linha é igual ela mesma +1
}

if($linha == '10') { // Se for a última linha da página 
$pdf->AddPage(); // Adiciona uma nova página
$linha = 0; // $linha volta ao seu valor inicial
}

$posicaoV = $linha*$aeti;
$posicaoH = $coluna*$leti;

if($coluna == '0') { // Se a coluna for 0 
$somaH = $mesq; // Soma Horizontal é apenas a margem da esquerda inicial
} else { // Senão
$somaH = $mesq+$posicaoH; // Soma Horizontal é a margem inicial mais a posiçãoH
}

if($linha =='0') { // Se a linha for 0 
$somaV = $msup; // Soma Vertical é apenas a margem superior inicial
} else { // Senão
$somaV = $msup+$posicaoV; // Soma Vertical é a margem superior inicial mais a posiçãoV
}

$pdf->Text($somaH,$somaV,$nome); // Imprime o nome da pessoa de acordo com as coordenadas 
$pdf->Text($somaH,$somaV+4,$res); // Imprime o endereço da pessoa de acordo com as coordenadas
$pdf->Text($somaH,$somaV+8,$local); // Imprime a localidade da pessoa de acordo com as coordenadas
$pdf->Text($somaH,$somaV+12,$cep); // Imprime o cep da pessoa de acordo com as coordenadas 

$coluna = $coluna+1;
}

$pdf->Output();

?>
