<link href="arquivos/css/mpdf.css" type="text/css" rel="stylesheet" media="mpdf" />
<?php
include 'seguranca.php';
require_once __DIR__ . '/arquivos/mpdf/vendor/autoload.php';

#$url = urldecode($_REQUEST['url']);
$url = "http://localhost/engerede/engerede.php?tk=123&filtra=1&dataInicial=2022-08-01&dataFinal=2022-09-15";
/*
// To prevent anyone else using your script to create their PDF files
if (!preg_match('@^https?://www\.mydomain\.com/@', $url)) {
    die("Access denied");
}
*/
// For $_POST i.e. forms with fields
if (count($_POST) > 0) {
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1 );
    
    foreach($_POST as $name => $post) {
        $formvars = array($name => $post . " \n");
    }
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $formvars);
    $html = curl_exec($ch);
    curl_close($ch);
    
} else if (!ini_get('allow_url_fopen')) {
    echo "31";
    $html = file_get_contents($url);
    
} else {
    echo "35";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt ( $ch , CURLOPT_RETURNTRANSFER , 1 );
    $html = curl_exec($ch);
    curl_close($ch);
}

$mpdf = new \Mpdf\Mpdf();

$mpdf->useSubstitutions = true; // optional - just as an example
$mpdf->SetHeader($url . "\n\n" . 'Page {PAGENO}');  // optional - just as an example
$mpdf->CSSselectMedia='mpdf'; // assuming you used this in the document header
$mpdf->setBasePath($url);
$mpdf->WriteHTML($html);

$mpdf->Output();
